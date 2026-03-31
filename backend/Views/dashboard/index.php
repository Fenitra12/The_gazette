<?php
declare(strict_types=1);

$title = 'Dashboard';
?>
<div style="margin-bottom:24px;">
    <h1 style="margin:0 0 4px;">Bienvenue dans le BackOffice</h1>
    <p class="subtitle">Gérez votre contenu éditorial en toute simplicité</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Articles</h3>
        <div class="value" style="color:var(--primary);">
            <a href="/articles" style="color:inherit;text-decoration:none;">Gérer →</a>
        </div>
    </div>
    <div class="stat-card">
        <h3>Catégories</h3>
        <div class="value" style="color:var(--secondary);">
            <a href="/categories" style="color:inherit;text-decoration:none;">Gérer →</a>
        </div>
    </div>
    <div class="stat-card">
        <h3>Auteurs</h3>
        <div class="value" style="color:var(--success);">
            <a href="/authors" style="color:inherit;text-decoration:none;">Gérer →</a>
        </div>
    </div>
    <div class="stat-card">
        <h3>Utilisateurs</h3>
        <div class="value" style="color:var(--warning);">
            <a href="/users" style="color:inherit;text-decoration:none;">Gérer →</a>
        </div>
    </div>
</div>

<div class="grid cols-2">
    <div class="card">
        <h2 style="margin:0 0 12px;display:flex;align-items:center;gap:10px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Actions rapides
        </h2>
        <p class="muted" style="margin:0 0 16px;">Accédez rapidement aux fonctions principales</p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <a class="btn" href="/articles/create">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
                Nouvel article
            </a>
            <a class="btn secondary" href="/categories/create">Nouvelle catégorie</a>
            <a class="btn secondary" href="/authors/create">Nouvel auteur</a>
        </div>
    </div>

</div>

