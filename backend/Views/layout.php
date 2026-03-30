<?php
declare(strict_types=1);

use BackOffice\Core\Auth;
use BackOffice\Core\Csrf;
use BackOffice\Core\Helpers;

$title = $title ?? 'BackOffice';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Helpers::e((string)$title) ?></title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        :root { color-scheme: light; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin:0; background:#f6f7fb; color:#111; }
        header { background:#111827; color:#fff; padding:12px 16px; display:flex; align-items:center; gap:16px; }
        header a { color:#fff; text-decoration:none; font-weight:600; }
        nav { display:flex; gap:12px; align-items:center; }
        .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; }
        .btn { display:inline-block; background:#2563eb; color:#fff; padding:10px 12px; border-radius:10px; border:0; cursor:pointer; text-decoration:none; font-weight:600; }
        .btn.secondary { background:#374151; }
        .btn.danger { background:#dc2626; }
        input, textarea, select { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #d1d5db; }
        label { display:block; font-weight:600; margin:12px 0 6px; }
        .grid { display:grid; gap:16px; }
        .grid.cols-2 { grid-template-columns: 1fr 1fr; }
        .error { background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:12px; border-radius:10px; }
        .topbar { margin-left:auto; display:flex; align-items:center; gap:12px; }
        .muted { color:#6b7280; }
    </style>
</head>
<body>
<header>
    <a href="/">BackOffice</a>
    <?php if (Auth::check()): ?>
        <nav>
            <a href="/articles">Articles</a>
            <a href="/categories">Catégories</a>
            <a href="/authors">Auteurs</a>
            <a href="/users">Utilisateurs</a>
        </nav>
        <div class="topbar">
            <span class="muted"><?= Helpers::e((string)($_SESSION['user']['email'] ?? '')) ?></span>
            <form method="post" action="/logout" style="margin:0;">
                <input type="hidden" name="_csrf" value="<?= Helpers::e(Csrf::token()) ?>">
                <button class="btn secondary" type="submit">Déconnexion</button>
            </form>
        </div>
    <?php endif; ?>
</header>

<main class="container">
    <?= $content ?? '' ?>
</main>
</body>
</html>

