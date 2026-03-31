<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$title = 'Articles';

// Récupération des filtres depuis l'URL
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
?>
<div class="card">
    <div class="card-header">
        <div>
            <h1>Articles</h1>
            <p class="subtitle">Gérez vos contenus éditoriaux</p>
        </div>
        <a class="btn" href="/articles/create">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
            Créer un article
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

    <form method="get" action="/articles" class="filter-bar" role="search">
        <div class="filter-group" style="flex:1;">
            <label for="search">Rechercher</label>
            <input type="search" id="search" name="search" class="search-input" placeholder="Titre, slug..." value="<?= Helpers::e($search) ?>">
        </div>
        <div class="filter-group">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="">Tous</option>
                <option value="draft" <?= $statusFilter === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                <option value="published" <?= $statusFilter === 'published' ? 'selected' : '' ?>>Publié</option>
                <option value="archived" <?= $statusFilter === 'archived' ? 'selected' : '' ?>>Archivé</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="category">Catégorie</label>
            <select id="category" name="category">
                <option value="">Toutes</option>
                <?php foreach (($categories ?? []) as $cat): ?>
                    <option value="<?= (int)$cat['id'] ?>" <?= $categoryFilter == $cat['id'] ? 'selected' : '' ?>>
                        <?= Helpers::e((string)$cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Filtrer
            </button>
            <?php if ($search || $statusFilter || $categoryFilter): ?>
                <a href="/articles" class="btn outline">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>

    <?php 
    $totalItems = count($items ?? []);
    if ($search || $statusFilter || $categoryFilter): 
    ?>
        <p class="results-info"><?= $totalItems ?> résultat(s) trouvé(s)</p>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th scope="col">Titre</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Auteur</th>
                <th scope="col">Statut</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($items ?? []) as $it): ?>
                <tr>
                    <td>
                        <strong><?= Helpers::e((string)$it['title']) ?></strong>
                        <div class="muted" style="font-size:12px;margin-top:2px;">/<?= Helpers::e((string)$it['slug']) ?></div>
                    </td>
                    <td><?= Helpers::e((string)$it['category_name']) ?></td>
                    <td><?= Helpers::e((string)$it['author_name']) ?></td>
                    <td>
                        <span class="badge <?= Helpers::e((string)$it['status']) ?>">
                            <?= Helpers::e(ucfirst((string)$it['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a class="btn secondary sm" href="/articles/<?= (int)$it['id'] ?>/edit">Éditer</a>
                            <form method="post" action="/articles/<?= (int)$it['id'] ?>/delete">
                                <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">
                                <button class="btn danger sm" type="submit" onclick="return confirm('Supprimer cet article ?');">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="5" class="muted" style="text-align:center;padding:32px;">Aucun article trouvé.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (($pages ?? 1) > 1): ?>
        <nav class="pagination" aria-label="Pagination">
            <span class="pagination-info">Page <?= (int)$page ?> sur <?= (int)$pages ?></span>
            <?php 
            $queryParams = array_filter(['search' => $search, 'status' => $statusFilter, 'category' => $categoryFilter]);
            $queryString = $queryParams ? '&' . http_build_query($queryParams) : '';
            ?>
            <?php if ($page > 1): ?>
                <a class="btn outline sm" href="/articles?page=<?= (int)($page - 1) ?><?= $queryString ?>" aria-label="Page précédente">← Précédent</a>
            <?php endif; ?>
            <?php if ($page < $pages): ?>
                <a class="btn outline sm" href="/articles?page=<?= (int)($page + 1) ?><?= $queryString ?>" aria-label="Page suivante">Suivant →</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>

