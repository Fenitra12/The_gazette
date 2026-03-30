<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$id = (int)($article['id'] ?? 0);
$title = 'Article #' . $id;
?>
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h1 style="margin:0 0 6px;"><?= Helpers::e((string)($article['title'] ?? '')) ?></h1>
            <div class="muted">Slug: /<?= Helpers::e((string)($article['slug'] ?? '')) ?></div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <a class="btn secondary" href="/articles">Retour</a>
            <a class="btn" href="/articles/<?= $id ?>/edit">Éditer</a>
            <form method="post" action="/articles/<?= $id ?>/delete" style="display:inline;margin:0;">
                <input type="hidden" name="_csrf" value="<?= Helpers::e((string)($csrf ?? '')) ?>">
                <button class="btn danger" type="submit" onclick="return confirm('Supprimer cet article ?');">Supprimer</button>
            </form>
        </div>
    </div>

    <?php if (!empty($article['excerpt'])): ?>
        <div style="margin-top:16px;" class="card">
            <h2 style="margin:0 0 8px;">Extrait</h2>
            <div style="white-space:pre-wrap;line-height:1.5;"><?= Helpers::e((string)$article['excerpt']) ?></div>
        </div>
    <?php endif; ?>

    <div style="margin-top:16px;" class="card">
        <h2 style="margin:0 0 8px;">Contenu</h2>
        <div style="white-space:pre-wrap;line-height:1.5;"><?= Helpers::e((string)($article['content'] ?? '')) ?></div>
    </div>
</div>

