<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class TorrentService
{
    /**
     * Inject user fingerprint into torrent file comment field
     */
    public function injectFingerprint(string $torrentContent, $request): string
    {
        try {
            // Generate encrypted fingerprint
            $fingerprint = $this->generateFingerprint($request);

            // Decode the torrent file (bencode format)
            $torrentData = $this->bencodeDecodeString($torrentContent);

            if (!$torrentData) {
                Log::error('Failed to decode torrent file');
                return $torrentContent; // Return original on failure
            }

            // Inject fingerprint into comment field
            $torrentData['comment'] = $this->buildComment($torrentData['comment'] ?? '', $fingerprint);

            // Re-encode the torrent file
            $modifiedContent = $this->bencodeEncodeString($torrentData);

            Log::info('Torrent fingerprint injected', [
                'user_id' => Auth::id(),
                'fingerprint_length' => strlen($fingerprint),
                'original_size' => strlen($torrentContent),
                'modified_size' => strlen($modifiedContent),
            ]);

            return $modifiedContent;

        } catch (\Exception $e) {
            Log::error('Failed to inject torrent fingerprint', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            // Return original torrent on any error
            return $torrentContent;
        }
    }

    /**
     * Generate encrypted user fingerprint
     */
    private function generateFingerprint($request): string
    {
        $data = [
            'user_id' => Auth::id(),
            'username' => Auth::user()->name ?? 'unknown',
            'email' => Auth::user()->email ?? 'unknown',
            'timestamp' => now()->timestamp,
            'ip' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 100),
        ];

        return base64_encode(Crypt::encrypt($data));
    }

    /**
     * Build comment with fingerprint
     */
    private function buildComment(string $originalComment, string $fingerprint): string
    {
        $separator = $originalComment ? ' | ' : '';
        return $originalComment . $separator . 'RR:' . $fingerprint;
    }

    /**
     * Decode fingerprint back to user data (for leak analysis)
     */
    public function decodeFingerprint(string $fingerprint): ?array
    {
        try {
            // Remove 'RR:' prefix if present
            $cleanFingerprint = str_replace('RR:', '', $fingerprint);

            $decrypted = Crypt::decrypt(base64_decode($cleanFingerprint));
            return $decrypted;

        } catch (\Exception $e) {
            Log::error('Failed to decode fingerprint', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Simple bencode decoder for torrent files
     */
    private function bencodeDecodeString(string $data): ?array
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
            // Dictionary
            $position++;
            $dict = [];
            while ($position < strlen($data) && $data[$position] !== 'e') {
                $key = $this->bencodeDecode($data, $position);
                $value = $this->bencodeDecode($data, $position);
                $dict[$key] = $value;
            }
            $position++; // Skip 'e'
            return $dict;
        } elseif ($char === 'l') {
            // List
            $position++;
            $list = [];
            while ($position < strlen($data) && $data[$position] !== 'e') {
                $list[] = $this->bencodeDecode($data, $position);
            }
            $position++; // Skip 'e'
            return $list;
        } elseif ($char === 'i') {
            // Integer
            $position++;
            $end = strpos($data, 'e', $position);
            $number = substr($data, $position, $end - $position);
            $position = $end + 1;
            return (int)$number;
        } elseif (ctype_digit($char)) {
            // String
            $colonPos = strpos($data, ':', $position);
            $length = (int)substr($data, $position, $colonPos - $position);
            $position = $colonPos + 1;
            $string = substr($data, $position, $length);
            $position += $length;
            return $string;
        }

        throw new \Exception('Invalid bencode data');
    }

    /**
     * Simple bencode encoder
     */
    private function bencodeEncodeString($data): string
    {
        return $this->bencodeEncode($data);
    }

    private function bencodeEncode($data): string
    {
        if (is_int($data)) {
            return "i{$data}e";
        } elseif (is_string($data)) {
            return strlen($data) . ':' . $data;
        } elseif (is_array($data)) {
            if ($this->isAssociative($data)) {
                // Dictionary
                $encoded = 'd';
                ksort($data); // Bencode requires sorted keys
                foreach ($data as $key => $value) {
                    $encoded .= $this->bencodeEncode($key) . $this->bencodeEncode($value);
                }
                $encoded .= 'e';
                return $encoded;
            } else {
                // List
                $encoded = 'l';
                foreach ($data as $item) {
                    $encoded .= $this->bencodeEncode($item);
                }
                $encoded .= 'e';
                return $encoded;
            }
        }

        throw new \Exception('Cannot encode data type: ' . gettype($data));
    }

    private function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}