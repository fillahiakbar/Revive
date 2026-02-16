<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TorrentDownload;
use App\Services\TorrentService;

class TorrentDownloadController extends Controller
{
    protected $torrentService;

    public function __construct(TorrentService $torrentService)
    {
        $this->torrentService = $torrentService;
    }

    public function download(Request $request, $filename)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            abort(401, 'Authentication required');
        }

        // Sanitize filename - prevent directory traversal but allow subdirectories
        if (empty($filename) || strpos($filename, '..') !== false) {
            abort(400, 'Invalid filename');
        }

        // Remove leading/trailing slashes and normalize path separators
        $filename = trim(str_replace('\\', '/', $filename), '/');

        // Build Nextcloud WebDAV URL
        $baseUrl = config('services.nextcloud.webdav_url');
        $baseFolder = config('services.nextcloud.base_folder');
        $username = config('services.nextcloud.username');
        $password = config('services.nextcloud.password');

        if (empty($baseUrl) || empty($username) || empty($password)) {
            abort(500, 'Server configuration error');
        }

        // Construct full path
        // old code may make // if the baseFolder is empty
        // $webdavUrl = rtrim($baseUrl, '/') . '/remote.php/dav/files/' . $username . '/' . trim($baseFolder, '/') . '/' . $filename;
        
        $basePath = rtrim($baseUrl, '/') . '/remote.php/dav/files/' . $username . '/';

        // Add base folder only if it's not empty
        if (!empty($baseFolder)) {
            $basePath .= trim($baseFolder, '/') . '/';
        }

        $webdavUrl = $basePath . $filename;

        // Extract just the filename (without path) for the download
        $downloadFilename = basename($filename);

        try {
            // Fetch file from Nextcloud via WebDAV
            $response = Http::withBasicAuth($username, $password)
                ->timeout(30)
                ->get($webdavUrl);

            if (!$response->successful()) {
                if ($response->status() === 404) {
                    abort(404, 'Torrent file not found');
                }
                abort(555, 'Error retrieving file');
            }

            // Inject user fingerprint into torrent file
            $fingerprintedTorrent = $this->torrentService->injectFingerprint($response->body(), $request);

            // Log download
            TorrentDownload::create([
                'user_id' => Auth::id(),
                'filename' => $filename,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Stream fingerprinted file to user with original filename
            return response($fingerprintedTorrent)
                ->header('Content-Type', 'application/x-bittorrent')
                ->header('Content-Disposition', 'attachment; filename="' . $downloadFilename . '"')
                ->header('Content-Length', strlen($fingerprintedTorrent));

        } catch (\Exception $e) {
            abort(500, 'Download failed');
        }
    }
}