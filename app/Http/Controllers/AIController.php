<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Spatie\PdfToImage\Pdf;

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

            $tesseractPath = 'C:\Program Files\Tesseract-OCR\tesseract.exe';

            if ($file->getClientOriginalExtension() == 'pdf') {

                $pdf = new Pdf($file->getPathname());

                $imagePath = public_path('converted.jpg');

                $pdf->saveImage($imagePath);

                $text = (new TesseractOCR($imagePath))
                    ->executable($tesseractPath)
                    ->run();

            } else {

                $text = (new TesseractOCR($file->getPathname()))
                    ->executable($tesseractPath)
                    ->run();
            }

            preg_match('/Name[:\- ]*(.*)/i', $text, $nameMatch);
            preg_match('/[0-9]{10}/', $text, $mobileMatch);

            $name = $nameMatch[1] ?? 'Not Found';
            $mobile = $mobileMatch[0] ?? 'Not Found';

            Student::create([
                'name' => $name,
                'mobile' => $mobile,
                'raw_text' => $text
            ]);

            return back()->with('data', [
                'name' => $name,
                'mobile' => $mobile,
                'full_text' => $text
            ]);

        } catch (\Exception $e) {

            return back()->withErrors([
                'Error: ' . $e->getMessage()
            ]);
        }
    }
}