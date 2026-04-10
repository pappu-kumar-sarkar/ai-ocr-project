<!DOCTYPE html>
<html>

<head>
    <title>AI OCR Tool</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .main-container {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .box {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            width: 420px;
            min-height: 450px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #222;
            font-size: 22px;
        }

        .sub-text {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        .upload-area {
            border: 2px dashed #bbb;
            padding: 18px;
            border-radius: 12px;
            background: #fafafa;
            margin-bottom: 20px;
        }

        input[type="file"] {
            width: 100%;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        textarea {
            width: 100%;
            height: 320px;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #ddd;
            resize: none;
            font-size: 15px;
            background: #fafafa;
        }

        .copy-btn {
            margin-top: 15px;
            background: #28a745;
        }

        .error {
            background: #ffe5e5;
            color: red;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        /* LEFT BOX INFO */
        .feature-box {
            margin-top: 25px;
        }

        .feature-item {
            background: #f8f8f8;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #444;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

    </style>
</head>

<body>

<div class="main-container">

    <!-- Upload Box -->
    <div class="box">

        <h2>📤 Upload PDF / Image</h2>

        <p class="sub-text">Upload your scanned document here</p>

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="upload-area">
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>

            <button type="submit">🚀 Convert To Text</button>
        </form>

        <!-- New Left Bottom Design -->
        <div class="feature-box">

            <div class="feature-item">⚡ Fast OCR Processing</div>

            <div class="feature-item">📄 PDF & Image Supported</div>

            <div class="feature-item">🔒 100% Secure Upload</div>

        </div>

    </div>


    <!-- Result Box -->
    <div class="box">

        <h2>📄 Extracted Text</h2>

        <p class="sub-text">Your OCR result will appear below</p>

        <textarea id="outputText" readonly>
@if(session('data'))
{{ session('data')['full_text'] }}
@endif
        </textarea>

        <button class="copy-btn" onclick="copyText()">📋 Copy Text</button>

    </div>

</div>

<script>
    function copyText() {
        let text = document.getElementById("outputText");
        text.select();
        document.execCommand("copy");
        alert("Copied Successfully!");
    }
</script>

</body>

</html>