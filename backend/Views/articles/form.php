<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer un article' : 'Créer un article';
$a = $article ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h1 style="margin:0 0 6px;"><?= Helpers::e($title) ?></h1>
            <p class="muted" style="margin:0;">Champs obligatoires: titre, slug, contenu.</p>
        </div>
        <a class="btn secondary" href="/articles">Retour</a>
    </div>

    <form method="post" action="<?= $isEdit ? '/articles/' . (int)($a['id'] ?? 0) : '/articles' ?>" style="margin-top:16px;">
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="title">Titre</label>
                <input id="title" name="title" value="<?= Helpers::e((string)($a['title'] ?? '')) ?>" required>
                <?php if (!empty($errors['title'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['title']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="slug">Slug</label>
                <input id="slug" name="slug" value="<?= Helpers::e((string)($a['slug'] ?? '')) ?>" placeholder="ex: mon-article" required>
                <?php if (!empty($errors['slug'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['slug']) ?></div><?php endif; ?>
            </div>
        </div>

        <label for="excerpt">Extrait</label>
        <textarea id="excerpt" name="excerpt" rows="3"><?= Helpers::e((string)($a['excerpt'] ?? '')) ?></textarea>

        <label for="content">Contenu</label>
        <textarea id="content" name="content" rows="10" required><?= Helpers::e((string)($a['content'] ?? '')) ?></textarea>
        <?php if (!empty($errors['content'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['content']) ?></div><?php endif; ?>

        <div class="grid cols-2">
            <div>
                <label for="category_id">Catégorie</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach (($categories ?? []) as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ((int)($a['category_id'] ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                            <?= Helpers::e((string)$c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['category_id'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['category_id']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="author_id">Auteur</label>
                <select id="author_id" name="author_id" required>
                    <?php foreach (($authors ?? []) as $au): ?>
                        <option value="<?= (int)$au['id'] ?>" <?= ((int)($a['author_id'] ?? 0) === (int)$au['id']) ? 'selected' : '' ?>>
                            <?= Helpers::e((string)$au['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['author_id'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['author_id']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-2">
            <div>
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <?php foreach (['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'] as $k => $lbl): ?>
                        <option value="<?= Helpers::e((string)$k) ?>" <?= ((string)($a['status'] ?? 'draft') === (string)$k) ? 'selected' : '' ?>>
                            <?= Helpers::e((string)$lbl) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['status'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['status']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="published_at">Publié le (optionnel)</label>
                <input id="published_at" name="published_at" value="<?= Helpers::e((string)($a['published_at'] ?? '')) ?>" placeholder="YYYY-MM-DD HH:MM:SS">
                <?php if (!empty($errors['published_at'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['published_at']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-2">
            <div>
                <label for="meta_title">Meta title</label>
                <input id="meta_title" name="meta_title" value="<?= Helpers::e((string)($a['meta_title'] ?? '')) ?>">
            </div>
            <div>
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="<?= Helpers::e((string)($a['meta_description'] ?? '')) ?>">
                <?php if (!empty($errors['meta_description'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['meta_description']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-2">
            <div>
                <label for="featured_image">Image (nom de fichier)</label>
                <input id="featured_image" name="featured_image" value="<?= Helpers::e((string)($a['featured_image'] ?? '')) ?>" placeholder="ex: hero.jpg">
            </div>
            <div>
                <label for="views">Vues</label>
                <input id="views" name="views" type="number" min="0" value="<?= Helpers::e((string)($a['views'] ?? 0)) ?>">
                <?php if (!empty($errors['views'])): ?><div class="error" style="margin-top:8px;"><?= Helpers::e((string)$errors['views']) ?></div><?php endif; ?>
            </div>
        </div>

        <label style="display:flex;align-items:center;gap:10px;margin-top:14px;">
            <input type="checkbox" name="is_featured" value="1" style="width:auto;" <?= !empty($a['is_featured']) ? 'checked' : '' ?>>
            Mettre en avant
        </label>

        <div style="margin-top:16px;display:flex;gap:10px;">
            <button class="btn" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
            <a class="btn secondary" href="/articles">Annuler</a>
        </div>
    </form>
</div>

