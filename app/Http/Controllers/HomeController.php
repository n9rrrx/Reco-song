<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SongRecognitionService;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function recognize(Request $request)
    {
        $request->validate(['audio' => 'required|file|max:10240']);

        $file = $request->file('audio');
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();

        // Use the multi-service recognition chain
        $recognitionService = new SongRecognitionService();
        $result = $recognitionService->recognize($tempPath, $fileSize);

        return response()->json($result);
    }
}

