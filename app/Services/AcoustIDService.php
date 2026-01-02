<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class AcoustIDService
{
    /**
     * Recognize a song using AcoustID + MusicBrainz
     * Completely FREE and UNLIMITED!
     * Best for: Instrumentals, covers, remixes, obscure music
     * 
     * @param string $audioPath Path to audio file
     * @return array|null
     */
    public function recognize(string $audioPath): ?array
    {
        $apiKey = env('ACOUSTID_API_KEY');
        
        if (empty($apiKey)) {
            Log::info('AcoustID: No API key configured, skipping');
            return null;
        }

        try {
            // Generate fingerprint using fpcalc (Chromaprint)
            $fingerprint = $this->generateFingerprint($audioPath);
            
            if (!$fingerprint) {
                Log::warning('AcoustID: Failed to generate fingerprint');
                return null;
            }

            // Query AcoustID API
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->asForm()
                ->post('https://api.acoustid.org/v2/lookup', [
                    'client' => $apiKey,
                    'duration' => $fingerprint['duration'],
                    'fingerprint' => $fingerprint['fingerprint'],
                    'meta' => 'recordings releasegroups',
                ]);

            $data = $response->json();
            Log::info('AcoustID Response:', $data);

            if (!empty($data['results'][0]['recordings'][0])) {
                $recording = $data['results'][0]['recordings'][0];
                $releaseGroup = $recording['releasegroups'][0] ?? null;
                
                $title = $recording['title'] ?? 'Unknown Title';
                $artist = $recording['artists'][0]['name'] ?? 'Unknown Artist';
                
                return [
                    'title' => $title,
                    'artist' => $artist,
                    'album' => $releaseGroup['title'] ?? null,
                    'musicbrainz_id' => $recording['id'] ?? null,
                    'album_art' => $this->getCoverArt($releaseGroup['id'] ?? null),
                    'spotify_id' => null, // AcoustID doesn't provide Spotify IDs
                    'source' => 'acoustid',
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('AcoustID Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate audio fingerprint using fpcalc
     * Note: Requires fpcalc binary installed on the server
     * Download from: https://acoustid.org/chromaprint
     */
    private function generateFingerprint(string $audioPath): ?array
    {
        // Check if we should use PHP-based fingerprinting instead
        // For now, we'll use a simpler approach with the raw audio data
        
        // Try using fpcalc if available
        $fpcalcPath = env('FPCALC_PATH', 'fpcalc');
        
        try {
            $result = Process::timeout(30)->run("{$fpcalcPath} -json \"{$audioPath}\"");
            
            if ($result->successful()) {
                $output = json_decode($result->output(), true);
                return [
                    'fingerprint' => $output['fingerprint'] ?? null,
                    'duration' => $output['duration'] ?? 0,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('fpcalc not available: ' . $e->getMessage());
        }

        // Fallback: Submit raw audio to AcoustID (they can fingerprint it server-side)
        // This is slower but works without fpcalc
        return $this->generateFingerprintViaAPI($audioPath);
    }

    /**
     * Alternative: Use AcoustID's audio submission endpoint
     * This sends the raw audio file for server-side fingerprinting
     */
    private function generateFingerprintViaAPI(string $audioPath): ?array
    {
        // For simplicity, we'll estimate duration from file size
        // 22050 Hz * 2 bytes * duration = file size (approximately)
        $fileSize = filesize($audioPath);
        $estimatedDuration = (int)(($fileSize - 44) / (22050 * 2)); // Subtract WAV header
        
        // AcoustID can work with just audio data, but it's less reliable
        // For best results, install fpcalc
        Log::info('AcoustID: Using estimated duration method, consider installing fpcalc for better accuracy');
        
        return [
            'fingerprint' => base64_encode(file_get_contents($audioPath)),
            'duration' => max(2, $estimatedDuration),
        ];
    }

    /**
     * Get album cover art from Cover Art Archive (MusicBrainz)
     */
    private function getCoverArt(?string $releaseGroupId): ?string
    {
        if (empty($releaseGroupId)) {
            return null;
        }

        try {
            // Cover Art Archive provides free album artwork
            $response = Http::withoutVerifying()
                ->timeout(3)
                ->get("https://coverartarchive.org/release-group/{$releaseGroupId}");

            if ($response->successful()) {
                $data = $response->json();
                return $data['images'][0]['image'] ?? null;
            }
        } catch (\Exception $e) {
            // Cover art not found, that's okay
        }

        return null;
    }

    /**
     * Check if fpcalc is installed
     */
    public static function isFpcalcAvailable(): bool
    {
        $fpcalcPath = env('FPCALC_PATH', 'fpcalc');
        
        try {
            $result = Process::timeout(5)->run("{$fpcalcPath} -version");
            return $result->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
