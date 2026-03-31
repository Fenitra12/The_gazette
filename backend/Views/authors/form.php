<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer un auteur' : 'Créer un auteur';
$a = $author ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1><?= Helpers::e($title) ?></h1>
            <p class="subtitle">Le nom est obligatoire, l'email est optionnel</p>
        </div>
        <a class="btn outline" href="/authors">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Retour
        </a>
    </div>

    <form method="post" action="<?= $isEdit ? '/authors/' . (int)($a['id'] ?? 0) : '/authors' ?>" novalidate>
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="name">Nom *</label>
                <input id="name" name="name" value="<?= Helpers::e((string)($a['name'] ?? '')) ?>" required aria-required="true" placeholder="Ex: Jean Dupont">
                <?php if (!empty($errors['name'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['name']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?= Helpers::e((string)($a['email'] ?? '')) ?>" placeholder="jean.dupont@exemple.com">
                <?php if (!empty($errors['email'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['email']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn" type="submit">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
                <?= $isEdit ? 'Enregistrer' : 'Créer l\'auteur' ?>
            </button>
            <a class="btn outline" href="/authors">Annuler</a>
        </div>
    </form>
</div>

