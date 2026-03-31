<?php
declare(strict_types=1);

use BackOffice\Core\Helpers;

$isEdit = ($mode ?? 'create') === 'edit';
$title = $isEdit ? 'Éditer un article' : 'Créer un article';
$a = $article ?? [];
$errors = $errors ?? [];
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1><?= Helpers::e($title) ?></h1>
            <p class="subtitle">Les champs marqués d'un * sont obligatoires</p>
        </div>
        <a class="btn outline" href="/articles">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Retour
        </a>
    </div>

    <form method="post" action="<?= $isEdit ? '/articles/' . (int)($a['id'] ?? 0) : '/articles' ?>" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="_csrf" value="<?= Helpers::e((string)$csrf) ?>">

        <div class="grid cols-2">
            <div>
                <label for="title">Titre *</label>
                <input id="title" name="title" value="<?= Helpers::e((string)($a['title'] ?? '')) ?>" required aria-required="true" autocomplete="off">
                <?php if (!empty($errors['title'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['title']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="slug">Slug *</label>
                <input id="slug" name="slug" value="<?= Helpers::e((string)($a['slug'] ?? '')) ?>" placeholder="ex: mon-article" required aria-required="true" pattern="[a-z0-9]+(?:-[a-z0-9]+)*">
                <?php if (!empty($errors['slug'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['slug']) ?></div><?php endif; ?>
            </div>
        </div>

        <label for="excerpt">Extrait</label>
        <textarea id="excerpt" name="excerpt" rows="3" placeholder="Résumé court de l'article..."><?= Helpers::e((string)($a['excerpt'] ?? '')) ?></textarea>

        <label for="content">Contenu *</label>
        <textarea id="content" name="content" rows="10" required aria-required="true"><?= Helpers::e((string)($a['content'] ?? '')) ?></textarea>
        <?php if (!empty($errors['content'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['content']) ?></div><?php endif; ?>

        <div class="grid cols-2">
            <div>
                <label for="category_id">Catégorie *</label>
                <select id="category_id" name="category_id" required aria-required="true">
                    <?php foreach (($categories ?? []) as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ((int)($a['category_id'] ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                            <?= Helpers::e((string)$c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['category_id'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['category_id']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="author_id">Auteur *</label>
                <select id="author_id" name="author_id" required aria-required="true">
                    <?php foreach (($authors ?? []) as $au): ?>
                        <option value="<?= (int)$au['id'] ?>" <?= ((int)($a['author_id'] ?? 0) === (int)$au['id']) ? 'selected' : '' ?>>
                            <?= Helpers::e((string)$au['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['author_id'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['author_id']) ?></div><?php endif; ?>
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
                <?php if (!empty($errors['status'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['status']) ?></div><?php endif; ?>
            </div>
            <div>
                <label for="published_at">Date de publication</label>
                <input id="published_at" name="published_at" type="datetime-local" value="<?= Helpers::e((string)($a['published_at'] ?? '')) ?>">
                <?php if (!empty($errors['published_at'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['published_at']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-2">
            <div>
                <label for="meta_title">Meta title (SEO)</label>
                <input id="meta_title" name="meta_title" value="<?= Helpers::e((string)($a['meta_title'] ?? '')) ?>" maxlength="60" placeholder="Titre pour les moteurs de recherche">
            </div>
            <div>
                <label for="meta_description">Meta description (SEO)</label>
                <input id="meta_description" name="meta_description" value="<?= Helpers::e((string)($a['meta_description'] ?? '')) ?>" maxlength="160" placeholder="Description pour les moteurs de recherche">
                <?php if (!empty($errors['meta_description'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['meta_description']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-3">
            <div>
                <label for="featured_image">Image (nom de fichier)</label>
                <input id="featured_image" name="featured_image" value="<?= Helpers::e((string)($a['featured_image'] ?? '')) ?>" placeholder="ex: hero.jpg">
            </div>
            <div>
                <label for="featured_image_upload">Uploader une image</label>
                <input id="featured_image_upload" name="featured_image_upload" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                <?php if (!empty($errors['featured_image_upload'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['featured_image_upload']) ?></div><?php endif; ?>
                <?php if (!empty($a['featured_image'])): ?>
                    <p class="muted" style="margin:8px 0 0;font-size:13px;">📷 Image actuelle: <?= Helpers::e((string)$a['featured_image']) ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label for="views">Nombre de vues</label>
                <input id="views" name="views" type="number" min="0" value="<?= Helpers::e((string)($a['views'] ?? 0)) ?>">
                <?php if (!empty($errors['views'])): ?><div class="error" style="margin-top:8px;" role="alert"><?= Helpers::e((string)$errors['views']) ?></div><?php endif; ?>
            </div>
        </div>

        <label class="checkbox-label">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($a['is_featured']) ? 'checked' : '' ?>>
            <span>Mettre en avant cet article</span>
        </label>

        <div class="form-actions">
            <button class="btn" type="submit">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
                <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l\'article' ?>
            </button>
            <a class="btn outline" href="/articles">Annuler</a>
        </div>
    </form>
</div>

<script src="/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media | code fullscreen | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
    branding: false,
    promotion: false,
    license_key: 'gpl'
});

// Auto-génération du slug à partir du titre
function slugify(text) {
    return text.toString().toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}

const titleInput = document.getElementById('title');
const slugInput = document.getElementById('slug');

// Stocker le slug initial pour détecter les modifications manuelles
let lastAutoSlug = slugify(titleInput.value);
let slugManuallyEdited = slugInput.value.trim() !== '' && slugInput.value !== lastAutoSlug;

titleInput.addEventListener('input', function() {
    const newSlug = slugify(this.value);
    if (!slugManuallyEdited) {
        slugInput.value = newSlug;
    }
    lastAutoSlug = newSlug;
});

slugInput.addEventListener('input', function() {
    // Si le slug correspond à l'auto-généré, ne pas considérer comme édition manuelle
    slugManuallyEdited = this.value.trim() !== '' && this.value !== lastAutoSlug;
});

slugInput.addEventListener('blur', function() {
    if (this.value.trim() === '') {
        slugManuallyEdited = false;
        slugInput.value = lastAutoSlug;
    }
});
</script>

