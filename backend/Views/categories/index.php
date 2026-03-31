<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Catégories';
?>
<div class="card">
    <div class="card-header">
        <div>
            <h1>Catégories</h1>
            <p class="subtitle">Organisez vos articles par thématique</p>
        </div>
        <a class="btn" href="/categories/create">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
            Créer une catégorie
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert success" role="alert">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            <?= Helpers::e((string)$success) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert error" role="alert"><?= Helpers::e((string)$error) ?></div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Slug</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($items ?? []) as $it): ?>
                <tr>
                    <td><strong><?= Helpers::e((string)$it['name']) ?></strong></td>
                    <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:13px;"><?= Helpers::e((string)$it['slug']) ?></code></td>
                    <td>
                        <div class="table-actions">
                            <a class="btn secondary sm" href="/categories/<?= (int)$it['id'] ?>/edit">Éditer</a>
                            <form method="post" action="/categories/<?= (int)$it['id'] ?>/delete">
                                <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">
                                <button class="btn danger sm" type="submit" onclick="return confirm('Supprimer cette catégorie ?');">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="3" class="muted" style="text-align:center;padding:32px;">Aucune catégorie trouvée.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

