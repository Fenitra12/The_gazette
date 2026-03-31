<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Csrf;
use BackOffice\Models\Article;
use BackOffice\Models\Author;
use BackOffice\Models\Category;

final class ArticleController extends BaseController
{
    /**
     * @param array<string,mixed> $params
     */
    public function index(array $params = []): void
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Filtres
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'category' => trim($_GET['category'] ?? ''),
        ];

        $model = new Article();
        $items = $model->paginate($limit, $offset, $filters);
        $total = $model->countAll($filters);
        $pages = (int)max(1, (int)ceil($total / $limit));

        $this->render('articles/index', [
            'csrf' => Csrf::token(),
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
            'categories' => (new Category())->all(),
            'success' => $this->flash('success'),
            'error' => $this->flash('error'),
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function show(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $model = new Article();
        $article = $model->find($id);
        if (!$article) {
            http_response_code(404);
            echo 'Article introuvable.';
            return;
        }

        $this->render('articles/show', [
            'csrf' => Csrf::token(),
            'article' => $article,
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function create(array $params = []): void
    {
        $this->render('articles/form', [
            'csrf' => Csrf::token(),
            'mode' => 'create',
            'article' => $this->emptyArticle(),
            'categories' => (new Category())->all(),
            'authors' => (new Author())->all(),
            'errors' => [],
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function store(array $params = []): void
    {
        $this->requirePostCsrf();
        $payload = $this->payloadFromPost();
        $uploadError = null;

        $uploadedImage = $this->handleImageUpload();
        if ($uploadedImage !== null) {
            if (str_starts_with($uploadedImage, '__ERROR__:')) {
                $uploadError = substr($uploadedImage, 10);
            } else {
                $payload['featured_image'] = $uploadedImage;
            }
        }

        $errors = $this->validate($payload, null);
        if ($uploadError !== null) {
            $errors['featured_image_upload'] = $uploadError;
        }
        if ($errors) {
            $this->render('articles/form', [
                'csrf' => Csrf::token(),
                'mode' => 'create',
                'article' => $payload,
                'categories' => (new Category())->all(),
                'authors' => (new Author())->all(),
                'errors' => $errors,
            ]);
            return;
        }

        $id = (new Article())->create($payload);
        $this->flash('success', "Article créé (#{$id}).");
        $this->redirect('/articles');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function edit(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $model = new Article();
        $article = $model->find($id);
        if (!$article) {
            http_response_code(404);
            echo 'Article introuvable.';
            return;
        }

        $this->render('articles/form', [
            'csrf' => Csrf::token(),
            'mode' => 'edit',
            'article' => $article,
            'categories' => (new Category())->all(),
            'authors' => (new Author())->all(),
            'errors' => [],
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function update(array $params): void
    {
        $this->requirePostCsrf();
        $id = (int)($params['id'] ?? 0);

        $model = new Article();
        $existing = $model->find($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Article introuvable.';
            return;
        }

        $payload = $this->payloadFromPost();
        $uploadError = null;

        $uploadedImage = $this->handleImageUpload();
        if ($uploadedImage !== null) {
            if (str_starts_with($uploadedImage, '__ERROR__:')) {
                $uploadError = substr($uploadedImage, 10);
            } else {
                $payload['featured_image'] = $uploadedImage;
            }
        }
        $errors = $this->validate($payload, $id);
        if ($uploadError !== null) {
            $errors['featured_image_upload'] = $uploadError;
        }
        if ($errors) {
            $payload['id'] = $id;
            $this->render('articles/form', [
                'csrf' => Csrf::token(),
                'mode' => 'edit',
                'article' => $payload,
                'categories' => (new Category())->all(),
                'authors' => (new Author())->all(),
                'errors' => $errors,
            ]);
            return;
        }

        $model->update($id, $payload);
        $this->flash('success', 'Article mis à jour.');
        $this->redirect('/articles');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function destroy(array $params): void
    {
        $this->requirePostCsrf();
        $id = (int)($params['id'] ?? 0);

        (new Article())->delete($id);
        $this->flash('success', 'Article supprimé.');
        $this->redirect('/articles');
    }

    /**
     * @return array<string,mixed>
     */
    private function emptyArticle(): array
    {
        return [
            'title' => '',
            'slug' => '',
            'excerpt' => '',
            'content' => '',
            'featured_image' => '',
            'category_id' => 1,
            'author_id' => 1,
            'is_featured' => false,
            'status' => 'draft',
            'meta_title' => '',
            'meta_description' => '',
            'views' => 0,
            'published_at' => '',
        ];
    }

    /**
     * @return array<string,mixed>
     */
    private function payloadFromPost(): array
    {
        $status = isset($_POST['status']) ? (string)$_POST['status'] : 'draft';

        return [
            'title' => trim((string)($_POST['title'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'excerpt' => trim((string)($_POST['excerpt'] ?? '')),
            'content' => trim((string)($_POST['content'] ?? '')),
            'featured_image' => trim((string)($_POST['featured_image'] ?? '')),
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'author_id' => (int)($_POST['author_id'] ?? 0),
            'is_featured' => isset($_POST['is_featured']) && (string)$_POST['is_featured'] === '1',
            'status' => $status,
            'meta_title' => trim((string)($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string)($_POST['meta_description'] ?? '')),
            'views' => (int)($_POST['views'] ?? 0),
            'published_at' => trim((string)($_POST['published_at'] ?? '')),
        ];
    }

    /**
     * @return string|null Filename, null if no upload, "__ERROR__:<message>" if invalid
     */
    private function handleImageUpload(): ?string
    {
        if (!isset($_FILES['featured_image_upload']) || !is_array($_FILES['featured_image_upload'])) {
            return null;
        }

        $file = $_FILES['featured_image_upload'];
        $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($error !== UPLOAD_ERR_OK) {
            return '__ERROR__:Erreur upload image.';
        }

        $tmpName = (string)($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return '__ERROR__:Fichier upload invalide.';
        }

        $size = (int)($file['size'] ?? 0);
        if ($size <= 0 || $size > (2 * 1024 * 1024)) {
            return '__ERROR__:Image trop volumineuse (max 2 Mo).';
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($tmpName);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        if (!isset($allowed[$mime])) {
            return '__ERROR__:Format image non autorisé.';
        }

        $uploadDir = __DIR__ . '/../public/uploads';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return '__ERROR__:Impossible de créer le dossier upload.';
        }

        $filename = sprintf('%s_%s.%s', date('Ymd_His'), bin2hex(random_bytes(8)), $allowed[$mime]);
        $destination = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($tmpName, $destination)) {
            return '__ERROR__:Impossible d\'enregistrer l\'image.';
        }

        return $filename;
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,string>
     */
    private function validate(array $data, ?int $id): array
    {
        $errors = [];

        if ($data['title'] === '') {
            $errors['title'] = 'Le titre est obligatoire.';
        } elseif (strlen((string)$data['title']) > 255) {
            $errors['title'] = 'Le titre est trop long.';
        }

        if ($data['slug'] === '') {
            $errors['slug'] = 'Le slug est obligatoire.';
        } elseif (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', (string)$data['slug'])) {
            $errors['slug'] = 'Slug invalide (minuscules, chiffres, tirets).';
        } elseif ((new Article())->slugExists((string)$data['slug'], $id)) {
            $errors['slug'] = 'Slug déjà utilisé.';
        }

        if ($data['content'] === '') {
            $errors['content'] = 'Le contenu est obligatoire.';
        }

        if ((int)$data['category_id'] <= 0) {
            $errors['category_id'] = 'Catégorie invalide.';
        }
        if ((int)$data['author_id'] <= 0) {
            $errors['author_id'] = 'Auteur invalide.';
        }

        $allowedStatus = ['draft', 'published', 'archived'];
        if (!in_array((string)$data['status'], $allowedStatus, true)) {
            $errors['status'] = 'Statut invalide.';
        }

        if ((int)$data['views'] < 0) {
            $errors['views'] = 'Views invalide.';
        }

        if ($data['published_at'] !== '' && strtotime((string)$data['published_at']) === false) {
            $errors['published_at'] = 'Date de publication invalide.';
        }

        if ($data['meta_description'] !== '' && strlen((string)$data['meta_description']) > 255) {
            $errors['meta_description'] = 'Meta description trop longue.';
        }

        return $errors;
    }
}

