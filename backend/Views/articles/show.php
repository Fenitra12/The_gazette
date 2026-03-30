<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Article #' . (int)($article['id'] ?? 0);
?>
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h1 style="margin:0 0 6px;"><?= Helpers::e((string)($article['title'] ?? '')) ?></h1>
            <div class="muted">Slug: /<?= Helpers::e((string)($article['slug'] ?? '')) ?></div>
        </div>
        <a class="btn secondary" href="/articles">Retour</a>
    </div>

    <div style="margin-top:16px;" class="card">
        <h2 style="margin:0 0 8px;">Contenu</h2>
        <div style="white-space:pre-wrap;line-height:1.5;"><?= Helpers::e((string)($article['content'] ?? '')) ?></div>
    </div>
</div>

