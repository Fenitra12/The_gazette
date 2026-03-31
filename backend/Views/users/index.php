<?php
declare(strict_types=1);

use BackOffice\Core\Auth;
use BackOffice\Core\Helpers;

$title = 'Utilisateurs';

// Récupération des filtres depuis l'URL
$search = $_GET['search'] ?? '';
$roleFilter = $_GET['role'] ?? '';
?>
<div class="card">
    <div class="card-header">
        <div>
            <h1>Utilisateurs</h1>
            <p class="subtitle">Gérez les comptes d'accès au BackOffice</p>
        </div>
        <a class="btn" href="/users/create">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Créer un utilisateur
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert success" role="alert">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            <?= Helpers::e((string)$success) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert error" role="alert"><?= Helpers::e((string)$error) ?></div>
    <?php endif; ?>

    <form method="get" action="/users" class="filter-bar" role="search">
        <div class="filter-group" style="flex:1;">
            <label for="search">Rechercher</label>
            <input type="search" id="search" name="search" class="search-input" placeholder="Email..." value="<?= Helpers::e($search) ?>">
        </div>
        <div class="filter-group">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="">Tous</option>
                <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="editor" <?= $roleFilter === 'editor' ? 'selected' : '' ?>>Éditeur</option>
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Filtrer
            </button>
            <?php if ($search || $roleFilter): ?>
                <a href="/users" class="btn outline">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>

    <?php 
    $totalItems = count($items ?? []);
    if ($search || $roleFilter): 
    ?>
        <p class="results-info"><?= $totalItems ?> résultat(s) trouvé(s)</p>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th scope="col">Utilisateur</th>
                <th scope="col">Rôle</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($items ?? []) as $it): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div>
                                <strong><?= Helpers::e((string)$it['email']) ?></strong>
                                <?php if (Auth::id() === (int)$it['id']): ?>
                                    <span class="badge published" style="margin-left:8px;">Vous</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?= $it['role'] === 'admin' ? 'published' : 'draft' ?>">
                            <?= Helpers::e(ucfirst((string)$it['role'])) ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a class="btn secondary sm" href="/users/<?= (int)$it['id'] ?>/edit">Éditer</a>
                            <form method="post" action="/users/<?= (int)$it['id'] ?>/delete">
                                <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">
                                <button class="btn danger sm" type="submit" <?= (Auth::id() === (int)$it['id']) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?> onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="3" class="muted" style="text-align:center;padding:32px;">Aucun utilisateur trouvé.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

