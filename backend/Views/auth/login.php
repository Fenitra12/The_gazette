<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Connexion';
?>
<div class="card" style="max-width:520px;margin:0 auto;">
    <h1 style="margin:0 0 8px;">Connexion</h1>
    <p class="muted" style="margin:0 0 16px;">Accès sécurisé au BackOffice.</p>

    <?php if (!empty($error)): ?>
        <div class="error" role="alert"><?= Helpers::e((string)$error) ?></div>
    <?php endif; ?>

    <form method="post" action="/login" autocomplete="off">
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" required>

        <div style="margin-top:16px;">
            <button class="btn" type="submit">Se connecter</button>
        </div>
    </form>

    <p class="muted" style="margin-top:16px;">
        Compte par défaut: <strong>admin@thegazette.local</strong> / <strong>admin123</strong>
    </p>
</div>

