<!DOCTYPE html>
<html>

<head>
    <title>AI OCR</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        .result {
            margin-top: 20px;
            text-align: left;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            max-height: 200px;
            overflow-y: auto;
        }

        .result p {
            margin: 5px 0;
            color: #333;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Upload Form Image / PDF</h2>

        {{-- Error Message --}}
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf

            <input type="file" id="fileInput" name="file" accept=".jpg,.jpeg,.png,.pdf" required>

            <button type="submit">Scan with AI</button>

        </form>

        {{-- Result Show --}}
        @if(session('data'))
            <div class="result">

                <h3>OCR Result:</h3>

                <p>
                    <strong>Name:</strong>
                    {{ session('data')['name'] }}
                </p>

                <p>
                    <strong>Mobile:</strong>
                    {{ session('data')['mobile'] }}
                </p>

                <hr style="margin:10px 0;">

                <p>
                    <strong>Full Text:</strong>
                </p>

                <p>
                    {{ session('data')['full_text'] }}
                </p>

            </div>
        @endif

    </div>

    <script>
        function validateForm() {

            let file = document.getElementById("fileInput").value;

            if (!file) {
                alert("Please select a file first!");
                return false;
            }

            return true;
        }
    </script>

</body>

</html>