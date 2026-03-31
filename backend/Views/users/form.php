<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer un utilisateur' : 'Créer un utilisateur';
$u = $user ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1><?= Helpers::e($title) ?></h1>
            <p class="subtitle"><?= $isEdit ? 'Laissez le mot de passe vide pour ne pas le modifier' : 'Le mot de passe doit contenir au moins 8 caractères' ?></p>
        </div>
        <a class="btn outline" href="/users">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Retour
        </a>
    </div>

    <form method="post" action="<?= $isEdit ? '/users/' . (int)($u['id'] ?? 0) : '/users' ?>" novalidate>
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="email">Email *</label>
                <input id="email" name="email" type="email" value="<?= Helpers::e((string)($u['email'] ?? '')) ?>" required aria-required="true" placeholder="utilisateur@exemple.com" autocomplete="email">
                <?php if (!empty($errors['email'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['email']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="role">Rôle *</label>
                <select id="role" name="role" required aria-required="true">
                    <?php foreach (['admin' => 'Administrateur', 'editor' => 'Éditeur'] as $k => $lbl): ?>
                        <option value="<?= Helpers::e((string)$k) ?>" <?= ((string)($u['role'] ?? 'admin') === (string)$k) ? 'selected' : '' ?>><?= Helpers::e((string)$lbl) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['role'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['role']) ?></div><?php endif; ?>
            </div>
        </div>

        <label for="password">Mot de passe <?= $isEdit ? '' : '*' ?></label>
        <input id="password" name="password" type="password" <?= $isEdit ? '' : 'required aria-required="true"' ?> minlength="8" placeholder="<?= $isEdit ? 'Laisser vide pour conserver l\'actuel' : 'Minimum 8 caractères' ?>" autocomplete="new-password">
        <?php if (!empty($errors['password'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['password']) ?></div><?php endif; ?>

        <div class="form-actions">
            <button class="btn" type="submit">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
                <?= $isEdit ? 'Enregistrer' : 'Créer l\'utilisateur' ?>
            </button>
            <a class="btn outline" href="/users">Annuler</a>
        </div>
    </form>
</div>

