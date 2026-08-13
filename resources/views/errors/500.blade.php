<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | DICT SDN ILCDB</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: white; border-radius: 1.5rem; padding: 3rem; text-align: center; max-width: 480px; width: 90%; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .code { font-size: 6rem; font-weight: 900; color: #CE1126; line-height: 1; }
        .flag { display: inline-block; font-size: 2rem; margin-bottom: 1rem; }
        h1 { font-size: 1.25rem; color: #1e293b; margin-bottom: 0.5rem; }
        p { color: #64748b; font-size: 0.875rem; margin-bottom: 1.5rem; line-height: 1.6; }
        a { display: inline-flex; align-items: center; gap: 0.5rem; background: #003366; color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: background 0.2s; }
        a:hover { background: #0055a5; }
    </style>
</head>
<body>
    <div class="card">
        <div class="flag">🇵🇭</div>
        <div class="code">500</div>
        <h1>Server Error</h1>
        <p>Something went wrong on our end.<br>Please try again later or contact the system administrator.</p>
        <a href="{{ url('/dashboard') }}"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>
