<?php
declare(strict_types=1);

$title = 'Dashboard';
?>
<div style="margin-bottom:24px;">
    <h1 style="margin:0 0 4px;">Bienvenue dans le BackOffice</h1>
    <p class="subtitle">Gérez votre contenu éditorial en toute simplicité</p>
</div>

<section aria-labelledby="navigation-rapide">
    <h2 id="navigation-rapide" class="visually-hidden">Navigation rapide</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Articles</h3>
            <div class="value">
                <a href="/articles">Gérer →</a>
            </div>
        </div>
        <div class="stat-card">
            <h3>Catégories</h3>
            <div class="value">
                <a href="/categories">Gérer →</a>
            </div>
        </div>
        <div class="stat-card">
            <h3>Auteurs</h3>
            <div class="value">
                <a href="/authors">Gérer →</a>
            </div>
        </div>
        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <div class="value">
                <a href="/users">Gérer →</a>
            </div>
        </div>
    </div>
</section>

<div class="grid cols-2">
    <section class="card" aria-labelledby="actions-rapides">
        <h2 id="actions-rapides" style="margin:0 0 12px;display:flex;align-items:center;gap:10px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Actions rapides
        </h2>
        <p class="muted" style="margin:0 0 16px;">Accédez rapidement aux fonctions principales</p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <a class="btn" href="/articles/create">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14m-7-7h14"/></svg>
                Nouvel article
            </a>
            <a class="btn secondary" href="/categories/create">Nouvelle catégorie</a>
            <a class="btn secondary" href="/authors/create">Nouvel auteur</a>
        </div>
    </section>
</div>

