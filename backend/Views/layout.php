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
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin:0; background:#f6f7fb; color:#111; }
        header { background:#111827; color:#fff; padding:12px 16px; display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
        header a { color:#fff; text-decoration:none; font-weight:600; }
        nav { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
        .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; overflow-x:auto; }
        .btn { display:inline-block; background:#2563eb; color:#fff; padding:10px 12px; border-radius:10px; border:0; cursor:pointer; text-decoration:none; font-weight:600; }
        .btn.secondary { background:#374151; }
        .btn.danger { background:#dc2626; }
        input, textarea, select { width:100%; max-width:100%; padding:10px 12px; border-radius:10px; border:1px solid #d1d5db; }
        label { display:block; font-weight:600; margin:12px 0 6px; }
        .grid { display:grid; gap:16px; }
        .grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .error { background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:12px; border-radius:10px; }
        .topbar { margin-left:auto; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        .muted { color:#6b7280; }
        @media (max-width: 900px) {
            .grid.cols-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            header { padding: 10px 12px; gap: 10px; }
            nav { gap: 8px; }
            .container { margin: 16px auto; padding: 0 12px; }
            .card { padding: 12px; }
            .page-header { flex-direction: column; align-items: flex-start !important; gap: 12px !important; }
            .page-header .btn { width: 100%; text-align: center; }
            .action-buttons { display: flex; flex-direction: column; gap: 6px; }
            .action-buttons form { display: block !important; }
            .action-buttons .btn { width: 100%; text-align: center; display: block; }
            table th, table td { padding: 8px 6px !important; font-size: 14px; }
            .btn { padding: 8px 10px; font-size: 14px; }
            .form-actions { flex-direction: column !important; }
            .form-actions .btn { width: 100%; text-align: center; }
        }
        @media (max-width: 480px) {
            header { flex-direction: column; align-items: flex-start; }
            nav { width: 100%; justify-content: flex-start; }
            .topbar { width: 100%; justify-content: space-between; margin-left: 0; }
            .hide-mobile { display: none !important; }
            table { font-size: 13px; }
            table th, table td { padding: 6px 4px !important; }
            h1 { font-size: 1.4rem; }
            h2 { font-size: 1.2rem; }
        }
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

