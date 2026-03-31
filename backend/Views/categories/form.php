<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer une catégorie' : 'Créer une catégorie';
$c = $category ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1><?= Helpers::e($title) ?></h1>
            <p class="subtitle">Le nom et le slug sont obligatoires</p>
        </div>
        <a class="btn outline" href="/categories">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Retour
        </a>
    </div>

    <form method="post" action="<?= $isEdit ? '/categories/' . (int)($c['id'] ?? 0) : '/categories' ?>" novalidate>
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="name">Nom *</label>
                <input id="name" name="name" value="<?= Helpers::e((string)($c['name'] ?? '')) ?>" required aria-required="true" placeholder="Ex: Politique">
                <?php if (!empty($errors['name'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['name']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="slug">Slug *</label>
                <input id="slug" name="slug" value="<?= Helpers::e((string)($c['slug'] ?? '')) ?>" placeholder="ex: politique" required aria-required="true" pattern="[a-z0-9]+(?:-[a-z0-9]+)*">
                <?php if (!empty($errors['slug'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['slug']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn" type="submit">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
                <?= $isEdit ? 'Enregistrer' : 'Créer la catégorie' ?>
            </button>
            <a class="btn outline" href="/categories">Annuler</a>
        </div>
    </form>
</div>

