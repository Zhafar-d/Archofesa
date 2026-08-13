<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            font-size: 72px;
            color: #667eea;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .error-details {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
            margin-top: 20px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .diagnostic-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .diagnostic-links a {
            display: inline-block;
            color: #667eea;
            text-decoration: none;
            margin-right: 15px;
            font-size: 14px;
            margin-top: 5px;
        }
        .diagnostic-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>500</h1>
        <h2>Server Error</h2>
        <p>Maaf, terjadi kesalahan pada server saat memproses permintaan Anda.</p>
        
        @if(isset($message))
            <div class="error-details">
                <strong>Detail Error:</strong><br>
                {{ $message }}
            </div>
        @endif

        @if(config('app.debug'))
            <p style="color: #dc3545; font-weight: bold;">⚠️ Debug mode aktif - nonaktifkan di production!</p>
        @endif

        <a href="{{ route('admin.dashboard') }}" class="btn">← Kembali ke Dashboard</a>

        <div class="diagnostic-links">
            <strong style="display: block; margin-bottom: 10px; color: #333;">🔧 Diagnostic Tools:</strong>
            <a href="/diagnostic/basic" target="_blank">Basic Info</a>
            <a href="/diagnostic/db" target="_blank">Database Check</a>
            <a href="/diagnostic/room/1" target="_blank">Room Test</a>
            <a href="/diagnostic/view-test" target="_blank">View Test</a>
            <a href="/diagnostic/auth-test" target="_blank">Auth Test</a>
        </div>
    </div>
</body>
</html>
