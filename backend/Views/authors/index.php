<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Auteurs';

// Récupération des filtres depuis l'URL
$search = $_GET['search'] ?? '';
?>
<div class="card">
    <div class="card-header">
        <div>
            <h1>Auteurs</h1>
            <p class="subtitle">Gérez les auteurs de vos articles</p>
        </div>
        <a class="btn" href="/authors/create">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
            Créer un auteur
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

    <form method="get" action="/authors" class="filter-bar" role="search">
        <div class="filter-group" style="flex:1;">
            <label for="search">Rechercher</label>
            <input type="search" id="search" name="search" class="search-input" placeholder="Nom de l'auteur..." value="<?= Helpers::e($search) ?>">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Rechercher
            </button>
            <?php if ($search): ?>
                <a href="/authors" class="btn outline">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>

    <?php 
    $totalItems = count($items ?? []);
    if ($search): 
    ?>
        <p class="results-info"><?= $totalItems ?> résultat(s) trouvé(s)</p>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($items ?? []) as $it): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <strong><?= Helpers::e((string)$it['name']) ?></strong>
                        </div>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a class="btn secondary sm" href="/authors/<?= (int)$it['id'] ?>/edit">Éditer</a>
                            <form method="post" action="/authors/<?= (int)$it['id'] ?>/delete">
                                <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">
                                <button class="btn danger sm" type="submit" onclick="return confirm('Supprimer cet auteur ?');">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="2" class="muted" style="text-align:center;padding:32px;">Aucun auteur trouvé.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

