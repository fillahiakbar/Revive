<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TorrentService;
use Illuminate\Support\Facades\Log;

class AnalyzeTorrentLeak extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'torrent:analyze {file_path : Path to the torrent file to analyze}';

    /**
     * The console command description.
     */
    protected $description = 'Analyze a torrent file to identify leak source from fingerprint';

    protected $torrentService;

    public function __construct(TorrentService $torrentService)
    {
        parent::__construct();
        $this->torrentService = $torrentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file_path');

        // Check if file exists
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        // Check if it's a torrent file
        if (!str_ends_with(strtolower($filePath), '.torrent')) {
            $this->warn('File does not have .torrent extension. Proceeding anyway...');
        }

        $this->info("Analyzing torrent file: {$filePath}");
        $this->newLine();

        try {
            $torrentContent = file_get_contents($filePath);

            // Decode torrent to extract comment
            $torrentData = $this->decodeTorrent($torrentContent);

            if (!$torrentData) {
                $this->error('Failed to decode torrent file. Invalid format.');
                return 1;
            }

            // Display basic torrent info
            $this->displayTorrentInfo($torrentData);

            if (!isset($torrentData['comment'])) {
                $this->warn('No comment field found in torrent.');
                return 0;
            }

            $comment = $torrentData['comment'];
            $this->info("Comment: {$comment}");
            $this->newLine();

            // Check for fingerprint
            if (strpos($comment, 'RR:') === false) {
                $this->warn('No RR fingerprint found in torrent comment.');
                $this->info('This torrent was not downloaded through your system.');
                return 0;
            }

            // Extract and decode fingerprint
            $parts = explode('RR:', $comment);
            $fingerprint = trim(end($parts));

            $this->info('RR Fingerprint found! Decoding...');
            $this->newLine();

            $userData = $this->torrentService->decodeFingerprint($fingerprint);

            if (!$userData) {
                $this->error('Failed to decode fingerprint. Data may be corrupted or from different system.');
                return 1;
            }

            // Display leak information
            $this->displayLeakInfo($userData);

            // Log the leak detection
            Log::critical('Torrent leak detected via CLI analysis', [
                'file_path' => $filePath,
                'user_data' => $userData,
                'analyzed_by' => get_current_user(),
                'analyzed_at' => now(),
            ]);

            $this->newLine();
            $this->info('✅ Leak analysis completed and logged.');

            return 0;

        } catch (\Exception $e) {
            $this->error("Analysis failed: {$e->getMessage()}");
            Log::error('Torrent analysis command failed', [
                'file_path' => $filePath,
                'error' => $e->getMessage(),
            ]);
            return 1;
        }
    }

    private function displayTorrentInfo(array $torrentData): void
    {
        $this->info('=== TORRENT INFO ===');

        if (isset($torrentData['info']['name'])) {
            $this->line("Name: {$torrentData['info']['name']}");
        }

        if (isset($torrentData['announce'])) {
            $this->line("Tracker: {$torrentData['announce']}");
        }

        if (isset($torrentData['created by'])) {
            $this->line("Created by: {$torrentData['created by']}");
        }

        if (isset($torrentData['creation date'])) {
            $date = date('Y-m-d H:i:s', $torrentData['creation date']);
            $this->line("Creation date: {$date}");
        }

        $this->newLine();
    }

    private function displayLeakInfo(array $userData): void
    {
        $this->error('🚨 LEAK DETECTED! 🚨');
        $this->newLine();

        $this->info('=== LEAK SOURCE INFORMATION ===');

        $this->line("<fg=red>User ID:</> {$userData['user_id']}");
        $this->line("<fg=red>Username:</> {$userData['username']}");
        $this->line("<fg=red>Email:</> {$userData['email']}");

        $downloadTime = date('Y-m-d H:i:s', $userData['timestamp']);
        $this->line("<fg=red>Downloaded:</> {$downloadTime}");

        $this->line("<fg=red>IP Address:</> {$userData['ip']}");

        if (isset($userData['user_agent'])) {
            $this->line("<fg=red>User Agent:</> {$userData['user_agent']}");
        }

        $this->newLine();
        $this->warn('⚠️  This user leaked your torrent file!');
    }

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