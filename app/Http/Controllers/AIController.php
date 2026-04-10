<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:4096'
        ]);

        try {

            $file = $request->file('file');

            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post('https://api.ocr.space/parse/image', [
                'apikey' => env('OCR_SPACE_API_KEY'),
                'language' => 'eng'
            ]);

            $result = $response->json();

            if(isset($result['IsErroredOnProcessing']) && $result['IsErroredOnProcessing']) {
                throw new \Exception($result['ErrorMessage'][0] ?? 'OCR Failed');
            }

            $text = $result['ParsedResults'][0]['ParsedText'] ?? 'No Text Found';

            return back()->with('data', [
                'full_text' => $text
            ]);

        } catch (\Exception $e) {

            return back()->withErrors([
                'Error: ' . $e->getMessage()
            ]);
        }
    }
}