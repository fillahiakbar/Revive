<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TorrentService;
use Illuminate\Support\Facades\Log;

class TorrentAnalysisController extends Controller
{
    protected $torrentService;

    public function __construct(TorrentService $torrentService)
    {
        $this->torrentService = $torrentService;
    }

    /**
     * Analyze a torrent file to extract fingerprint information
     * Use this when you find your torrents leaked elsewhere
     */
    public function analyzeTorrent(Request $request)
    {
        $request->validate([
            'torrent_file' => 'required|file|mimes:torrent',
        ]);

        try {
            $torrentContent = file_get_contents($request->file('torrent_file')->path());

            // Decode torrent to extract comment
            $torrentData = $this->decodeTorrent($torrentContent);

            if (!$torrentData || !isset($torrentData['comment'])) {
                return response()->json(['error' => 'No comment field found in torrent']);
            }

            $comment = $torrentData['comment'];

            // Extract fingerprint from comment
            if (strpos($comment, 'RR:') !== false) {
                $parts = explode('RR:', $comment);
                $fingerprint = end($parts);

                // Decode fingerprint
                $userData = $this->torrentService->decodeFingerprint($fingerprint);

                if ($userData) {
                    Log::warning('Torrent leak detected', [
                        'user_data' => $userData,
                        'comment' => $comment,
                        'analyzer_ip' => $request->ip(),
                    ]);

                    return response()->json([
                        'leak_detected' => true,
                        'user_data' => $userData,
                        'download_time' => date('Y-m-d H:i:s', $userData['timestamp']),
                    ]);
                }
            }

            return response()->json(['leak_detected' => false, 'comment' => $comment]);

        } catch (\Exception $e) {
            Log::error('Torrent analysis failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Analysis failed']);
        }
    }

    /**
     * Simple torrent decoder for analysis
     */
    private function decodeTorrent(string $data): ?array
    {
        try {
            $position = 0;
            return $this->bencodeDecode($data, $position);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function bencodeDecode(string $data, int &$position)
    {
        if ($position >= strlen($data)) {
            throw new \Exception('Unexpected end of data');
        }

        $char = $data[$position];

        if ($char === 'd') {
            $position++;
            $dict = [];
            while ($position < strlen($data) && $data[$position] !== 'e') {
                $key = $this->bencodeDecode($data, $position);
                $value = $this->bencodeDecode($data, $position);
                $dict[$key] = $value;
            }
            $position++;
            return $dict;
        } elseif ($char === 'l') {
            $position++;
            $list = [];
            while ($position < strlen($data) && $data[$position] !== 'e') {
                $list[] = $this->bencodeDecode($data, $position);
            }
            $position++;
            return $list;
        } elseif ($char === 'i') {
            $position++;
            $end = strpos($data, 'e', $position);
            $number = substr($data, $position, $end - $position);
            $position = $end + 1;
            return (int)$number;
        } elseif (ctype_digit($char)) {
            $colonPos = strpos($data, ':', $position);
            $length = (int)substr($data, $position, $colonPos - $position);
            $position = $colonPos + 1;
            $string = substr($data, $position, $length);
            $position += $length;
            return $string;
        }

        throw new \Exception('Invalid bencode data');
    }
}