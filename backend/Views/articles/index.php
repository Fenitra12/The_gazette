<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Articles';
?>
<div class="card">
    <div style="display:flex;align-items:center;gap:12px;justify-content:space-between;">
        <div>
            <h1 style="margin:0 0 6px;">Articles</h1>
            <p class="muted" style="margin:0;">Gestion des contenus.</p>
        </div>
        <a class="btn" href="/articles/create">Créer un article</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="card" style="margin-top:16px;border-color:#bbf7d0;background:#f0fdf4;">
            <?= Helpers::e((string)$success) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="error" style="margin-top:16px;"><?= Helpers::e((string)$error) ?></div>
    <?php endif; ?>

    <div style="overflow:auto;margin-top:16px;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
            <tr>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Titre</th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Catégorie</th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Auteur</th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Statut</th>
                <th style="text-align:right;padding:10px;border-bottom:1px solid #e5e7eb;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($items ?? []) as $it): ?>
                <tr>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;">
                        <div style="font-weight:700;"><?= Helpers::e((string)$it['title']) ?></div>
                        <div class="muted" style="font-size:12px;">/<?= Helpers::e((string)$it['slug']) ?></div>
                    </td>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?= Helpers::e((string)$it['category_name']) ?></td>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?= Helpers::e((string)$it['author_name']) ?></td>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?= Helpers::e((string)$it['status']) ?></td>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;white-space:nowrap;">
                        <a class="btn secondary" href="/articles/<?= (int)$it['id'] ?>/edit">Éditer</a>
                        <form method="post" action="/articles/<?= (int)$it['id'] ?>/delete" style="display:inline;margin:0;">
                            <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">
                            <button class="btn danger" type="submit" onclick="return confirm('Supprimer cet article ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="5" class="muted" style="padding:12px;">Aucun article.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (($pages ?? 1) > 1): ?>
        <div style="display:flex;gap:8px;align-items:center;justify-content:flex-end;margin-top:16px;">
            <span class="muted">Page <?= (int)$page ?> / <?= (int)$pages ?></span>
            <?php if ($page > 1): ?>
                <a class="btn secondary" href="/articles?page=<?= (int)($page - 1) ?>">Précédent</a>
            <?php endif; ?>
            <?php if ($page < $pages): ?>
                <a class="btn secondary" href="/articles?page=<?= (int)($page + 1) ?>">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

