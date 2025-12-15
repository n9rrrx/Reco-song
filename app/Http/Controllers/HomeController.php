<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function recognize(Request $request)
    {
        // 1. Validate Audio
        $request->validate([
            'audio' => 'required|file|max:10240', // 10MB limit
        ]);

        try {
            // 2. Get the uploaded file content
            $file = $request->file('audio');
            $fileContent = file_get_contents($file->getRealPath());

            // 3. Prepare ACRCloud Config
            $host = env('ACR_HOST');
            $accessKey = env('ACR_ACCESS_KEY');
            $accessSecret = env('ACR_ACCESS_SECRET');

            // 4. Generate Signature (Crucial Security Step)
            $httpMethod = "POST";
            $httpUri = "/v1/identify";
            $dataType = "audio";
            $signatureVersion = "1";
            $timestamp = time();

            $stringToSign = $httpMethod . "\n" .
                $httpUri . "\n" .
                $accessKey . "\n" .
                $dataType . "\n" .
                $signatureVersion . "\n" .
                $timestamp;

            $signature = base64_encode(hash_hmac("sha1", $stringToSign, $accessSecret, true));

            // 5. Send Request to ACRCloud
            // We use Laravel's HTTP Client to post the file and keys
            $response = Http::withoutVerifying()
                ->asMultipart()
                ->post("https://" . $host . $httpUri, [
                    'access_key' => $accessKey,
                    'data_type' => $dataType,
                    'signature_version' => $signatureVersion,
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                    'sample' => $fileContent,
                ]);

            $result = $response->json();

            // 6. Handle Response
            if (isset($result['status']['code']) && $result['status']['code'] == 0) {

                // Success! Extract the best match
                $music = $result['metadata']['music'][0];

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title'  => $music['title'],
                        'artist' => $music['artists'][0]['name'],
                        'album'  => $music['album']['name'] ?? 'Single',
                        // ACRCloud doesn't always send art, so we use a safe fallback or their external metadata
                        'album_art' => '/assets/images/misc/plan.png',
                        'link_spotify' => $music['external_metadata']['spotify']['track']['id'] ?? '#'
                    ]
                ]);
            } else {
                Log::error("ACRCloud Error: " . json_encode($result));
                return response()->json(['status' => 'error', 'message' => 'Song not recognized.'], 404);
            }

        } catch (\Exception $e) {
            Log::error("Server Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}
