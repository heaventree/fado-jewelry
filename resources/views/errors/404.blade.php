<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found — FADÓ Jewellery</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #F8F9F5;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            text-align: center;
        }
        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #044705;
            margin-bottom: 48px;
        }
        .brand span { color: #766D42; }
        .code {
            font-family: Georgia, serif;
            font-size: 6rem;
            font-weight: 400;
            color: #044705;
            line-height: 1;
            margin-bottom: 8px;
        }
        h1 {
            font-family: Georgia, serif;
            font-size: 1.75rem;
            font-weight: 400;
            color: #044705;
            margin-bottom: 16px;
        }
        p {
            font-size: 1rem;
            color: #666;
            max-width: 460px;
            line-height: 1.7;
            margin-bottom: 40px;
        }
        .actions { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 2px;
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-primary { background: #044705; color: #fff; }
        .btn-primary:hover { background: #0AAC45; }
        .btn-secondary { background: transparent; color: #044705; border: 1.5px solid #044705; }
        .btn-secondary:hover { background: #044705; color: #fff; }
        .divider { width: 48px; height: 2px; background: #766D42; margin: 24px auto; }
    </style>
</head>
<body>
    <div class="brand">FAD<span>Ó</span> Jewellery</div>
    <div class="code">404</div>
    <div class="divider"></div>
    <h1>Page Not Found</h1>
    <p>The page you were looking for doesn't exist or may have been moved. Let us help you find something beautiful instead.</p>
    <div class="actions">
        <a href="/" class="btn btn-primary">Back to Home</a>
        <a href="/jewellery" class="btn btn-secondary">Browse Jewellery</a>
    </div>
</body>
</html>
