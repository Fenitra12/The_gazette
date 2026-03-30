<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Catégories';
?>
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h1 style="margin:0 0 6px;">Catégories</h1>
            <p class="muted" style="margin:0;">Utilisées pour classer les articles.</p>
        </div>
        <a class="btn" href="/categories/create">Créer une catégorie</a>
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
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Nom</th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Slug</th>
                <th style="text-align:right;padding:10px;border-bottom:1px solid #e5e7eb;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($items ?? []) as $it): ?>
                <tr>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?= Helpers::e((string)$it['name']) ?></td>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?= Helpers::e((string)$it['slug']) ?></td>
                    <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;white-space:nowrap;">
                        <a class="btn secondary" href="/categories/<?= (int)$it['id'] ?>/edit">Éditer</a>
                        <form method="post" action="/categories/<?= (int)$it['id'] ?>/delete" style="display:inline;margin:0;">
                            <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">
                            <button class="btn danger" type="submit" onclick="return confirm('Supprimer cette catégorie ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="3" class="muted" style="padding:12px;">Aucune catégorie.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

