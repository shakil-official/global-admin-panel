{{-- resources/views/errors/404.blade.php --}}
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 | Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .error-container {
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #dc3545;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 20px;
            color: #6c757d;
        }
        .btn-home {
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="error-container">
    <div class="error-code">404</div>
    <div class="error-message">Oops! The page you’re looking for doesn’t exist.</div>
    <a href="{{ url('/') }}" class="btn btn-primary btn-home">Back to Home</a>
</div>
</body>
</html>
