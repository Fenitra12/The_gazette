<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'The Gazette') ?></title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 0; }
        header { background-color: #1a1a2e; color: #fff; padding: 1rem 2rem; }
        .container { max-width: 800px; margin: 2rem auto; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #e94560; }
    </style>
</head>
<body>
    <header>
        <h2>The Gazette (MVC Natif)</h2>
    </header>
    <div class="container">
        <?= $content ?>
    </div>
</body>
</html>
