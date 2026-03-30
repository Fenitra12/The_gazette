<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer un auteur' : 'Créer un auteur';
$a = $author ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h1 style="margin:0 0 6px;"><?= Helpers::e($title) ?></h1>
            <p class="muted" style="margin:0;">Nom obligatoire, email optionnel.</p>
        </div>
        <a class="btn secondary" href="/authors">Retour</a>
    </div>

    <form method="post" action="<?= $isEdit ? '/authors/' . (int)($a['id'] ?? 0) : '/authors' ?>" style="margin-top:16px;">
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="name">Nom</label>
                <input id="name" name="name" value="<?= Helpers::e((string)($a['name'] ?? '')) ?>" required>
                <?php if (!empty($errors['name'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['name']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="email">Email (optionnel)</label>
                <input id="email" name="email" type="email" value="<?= Helpers::e((string)($a['email'] ?? '')) ?>">
                <?php if (!empty($errors['email'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['email']) ?></div><?php endif; ?>
            </div>
        </div>

        <div style="margin-top:16px;display:flex;gap:10px;">
            <button class="btn" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
            <a class="btn secondary" href="/authors">Annuler</a>
        </div>
    </form>
</div>

