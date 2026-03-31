<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer un utilisateur' : 'Créer un utilisateur';
$u = $user ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div class="page-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div>
            <h1 style="margin:0 0 6px;"><?= Helpers::e($title) ?></h1>
            <p class="muted" style="margin:0;"><?= $isEdit ? 'Laisser le mot de passe vide pour ne pas le changer.' : 'Mot de passe: 8 caractères minimum.' ?></p>
        </div>
        <a class="btn secondary" href="/users">Retour</a>
    </div>

    <form method="post" action="<?= $isEdit ? '/users/' . (int)($u['id'] ?? 0) : '/users' ?>" style="margin-top:16px;">
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?= Helpers::e((string)($u['email'] ?? '')) ?>" required>
                <?php if (!empty($errors['email'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['email']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="role">Rôle</label>
                <select id="role" name="role">
                    <?php foreach (['admin' => 'Admin', 'editor' => 'Éditeur'] as $k => $lbl): ?>
                        <option value="<?= Helpers::e((string)$k) ?>" <?= ((string)($u['role'] ?? 'admin') === (string)$k) ? 'selected' : '' ?>><?= Helpers::e((string)$lbl) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['role'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['role']) ?></div><?php endif; ?>
            </div>
        </div>

        <label for="password">Mot de passe <?= $isEdit ? '(optionnel)' : '' ?></label>
        <input id="password" name="password" type="password" <?= $isEdit ? '' : 'required' ?>>
        <?php if (!empty($errors['password'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['password']) ?></div><?php endif; ?>

        <div class="form-actions" style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
            <a class="btn secondary" href="/users">Annuler</a>
        </div>
    </form>
</div>

