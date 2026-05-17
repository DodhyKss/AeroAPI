<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; height: 100vh; text-align: center; }
        h1 { font-size: 4rem; color: #ef4444; margin-bottom: 0; }
        p { color: #94a3b8; font-size: 1.2rem; }
        a { color: #6366f1; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div>
        <h1>Oops!</h1>
        <p><?= htmlspecialchars($message ?? 'Terjadi kesalahan') ?></p>
    </div>
</body>
</html>
