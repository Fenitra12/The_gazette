<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer une catégorie' : 'Créer une catégorie';
$c = $category ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div class="page-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div>
            <h1 style="margin:0 0 6px;"><?= Helpers::e($title) ?></h1>
            <p class="muted" style="margin:0;">Nom + slug (unique).</p>
        </div>
        <a class="btn secondary" href="/categories">Retour</a>
    </div>

    <form method="post" action="<?= $isEdit ? '/categories/' . (int)($c['id'] ?? 0) : '/categories' ?>" style="margin-top:16px;">
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="name">Nom</label>
                <input id="name" name="name" value="<?= Helpers::e((string)($c['name'] ?? '')) ?>" required>
                <?php if (!empty($errors['name'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['name']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="slug">Slug</label>
                <input id="slug" name="slug" value="<?= Helpers::e((string)($c['slug'] ?? '')) ?>" placeholder="ex: politique" required>
                <?php if (!empty($errors['slug'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['slug']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-actions" style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
            <a class="btn secondary" href="/categories">Annuler</a>
        </div>
    </form>
</div>

