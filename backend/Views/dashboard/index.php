<?php
declare(strict_types=1);

$title = 'Dashboard';
?>
<div class="card">
    <h1 style="margin:0 0 8px;">Dashboard</h1>
    <p class="muted" style="margin:0 0 16px;">Bienvenue dans le BackOffice.</p>

    <div class="grid cols-2">
        <div class="card">
            <h2 style="margin:0 0 8px;">Contenu</h2>
            <p class="muted" style="margin:0 0 12px;">Gérer les articles.</p>
            <a class="btn" href="/articles">Voir les articles</a>
        </div>

        <div class="card">
            <h2 style="margin:0 0 8px;">Sécurité</h2>
            <p class="muted" style="margin:0;">CSRF actif, sessions sécurisées, routes protégées.</p>
        </div>
    </div>
</div>

