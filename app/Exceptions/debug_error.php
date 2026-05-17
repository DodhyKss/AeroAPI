<?php
/** @var \Throwable $exception */
/** @var string $message */
/** @var string $file */
/** @var int $line */
/** @var int|string $code */
/** @var string $trace */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aero Debug - Terjadi Kesalahan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Fira+Code:wght@400;500&display=swap');
        
        :root {
            --bg: #0b0f17;
            --surface: #121824;
            --border: #ef4444;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --code-bg: #0d1117;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 40px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            max-width: 900px;
            width: 100%;
            background: var(--surface);
            border-left: 4px solid var(--border);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .exception-type {
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            color: #ef4444;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            margin-bottom: 8px;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 24px;
            line-height: 1.4;
            color: #f1f5f9;
        }

        .meta-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .meta-label {
            color: var(--text-muted);
            margin-bottom: 4px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .meta-value {
            font-family: 'Fira Code', monospace;
            color: #38bdf8;
            word-break: break-all;
        }

        h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-main);
        }

        .stack-trace {
            background: var(--code-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            padding: 20px;
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            line-height: 1.6;
            color: #cbd5e1;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .stack-line {
            margin-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            padding-bottom: 8px;
        }

        .stack-line:last-child {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }

        .stack-number {
            color: #64748b;
            margin-right: 8px;
        }

        .home-link {
            display: inline-block;
            margin-top: 30px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .home-link:hover {
            color: var(--text-main);
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="exception-type"><?= get_class($exception) ?></div>
        <h1><?= htmlspecialchars($message) ?></h1>

        <div class="meta-info">
            <div>
                <div class="meta-label">File</div>
                <div class="meta-value"><?= htmlspecialchars($file) ?></div>
            </div>
            <div>
                <div class="meta-label">Baris</div>
                <div class="meta-value"><?= htmlspecialchars($line) ?></div>
            </div>
            <div>
                <div class="meta-label">Kode Error</div>
                <div class="meta-value"><?= htmlspecialchars($code) ?></div>
            </div>
        </div>

        <h2>Stack Trace</h2>
        <div class="stack-trace"><?php 
            $lines = explode("\n", $trace);
            foreach ($lines as $i => $stackLine) {
                if (trim($stackLine) === '') continue;
                echo '<div class="stack-line"><span class="stack-number">#' . $i . '</span>' . htmlspecialchars($stackLine) . '</div>';
            }
        ?></div>
    </div>

</body>
</html>
