<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use OpenAI;

class AIController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function upload(Request $request)
    {
        // ✅ API KEY
        $apiKey = config('services.openai.key') ?: env('OPENAI_API_KEY');
       
        if (!$apiKey) {
            return back()->withErrors(['API Key missing']);
        }

        $client = OpenAI::client($apiKey);
        
        // ✅ File check
        if (!$request->hasFile('file')) {
            return back()->withErrors(['Please upload a file']);
        }

        $file = $request->file('file');
        dd($file);
        // ✅ Convert to base64
        $fileData = base64_encode(file_get_contents($file));

        // ✅ Prompt
        $prompt = "Extract Name and Mobile number from this form. Return only JSON like {\"name\":\"\",\"mobile\":\"\"}";

        try {

            // 🔥 Image + PDF दोनों same तरीके से भेजेंगे
            $response = $client->responses()->create([
                'model' => 'gpt-4.1',
                'input' => [[
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $prompt
                        ],
                        [
                            'type' => 'input_image',
                            'image_url' => 'data:' . $file->getMimeType() . ';base64,' . $fileData
                        ]
                    ]
                ]]
            ]);

            // ✅ Output extract
            $output = $response->output[0]->content[0]->text ?? '';

            // ✅ JSON निकालना (safe)
            preg_match('/\{.*\}/s', $output, $match);
            $json = $match[0] ?? '{}';

            $data = json_decode($json, true);

            $name = $data['name'] ?? 'Not Found';
            $mobile = $data['mobile'] ?? 'Not Found';

            // ✅ Save DB
            Student::create([
                'name' => $name,
                'mobile' => $mobile,
                'raw_text' => $output
            ]);

            return back()->with('data', [
                'name' => $name,
                'mobile' => $mobile
            ]);

        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }
}