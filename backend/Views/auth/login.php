<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Connexion';
?>
<div class="card" style="max-width:440px;margin:60px auto;">
    <div style="text-align:center;margin-bottom:24px;">
        <h1 style="margin:0 0 4px;">Connexion</h1>
        <p class="subtitle">Accédez à votre espace d'administration</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert error" role="alert" style="margin-bottom:20px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6m0-6 6 6"/></svg>
            <?= Helpers::e((string)$error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/login" autocomplete="off" novalidate>
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <label for="email">Adresse email</label>
        <input id="email" name="email" type="email" value="admin@thegazette.local" required aria-required="true" placeholder="votre@email.com" autocomplete="email">

        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" value="admin123" required aria-required="true" placeholder="••••••••" autocomplete="current-password">

        <div style="margin-top:24px;">
            <button class="btn" type="submit" style="width:100%;padding:12px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-5-4 5-5-5-5m5 5H3"/></svg>
                Se connecter
            </button>
        </div>
    </form>
</div>

