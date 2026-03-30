<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Csrf;
use BackOffice\Models\Category;

final class CategoryController extends BaseController
{
    /**
     * @param array<string,mixed> $params
     */
    public function index(array $params = []): void
    {
        $items = (new Category())->all();
        $this->render('categories/index', [
            'csrf' => Csrf::token(),
            'items' => $items,
            'success' => $this->flash('success'),
            'error' => $this->flash('error'),
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function create(array $params = []): void
    {
        $this->render('categories/form', [
            'csrf' => Csrf::token(),
            'mode' => 'create',
            'category' => ['name' => '', 'slug' => ''],
            'errors' => [],
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function store(array $params = []): void
    {
        $this->requirePostCsrf();
        $name = trim((string)($_POST['name'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));

        $errors = $this->validate($name, $slug, null);
        if ($errors) {
            $this->render('categories/form', [
                'csrf' => Csrf::token(),
                'mode' => 'create',
                'category' => ['name' => $name, 'slug' => $slug],
                'errors' => $errors,
            ]);
            return;
        }

        try {
            (new Category())->create($name, $slug);
        } catch (\Throwable) {
            $this->render('categories/form', [
                'csrf' => Csrf::token(),
                'mode' => 'create',
                'category' => ['name' => $name, 'slug' => $slug],
                'errors' => ['slug' => 'Impossible de créer la catégorie (slug déjà utilisé ou erreur DB).'],
            ]);
            return;
        }
        $this->flash('success', 'Catégorie créée.');
        $this->redirect('/categories');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function edit(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $category = (new Category())->find($id);
        if (!$category) {
            http_response_code(404);
            echo 'Catégorie introuvable.';
            return;
        }

        $this->render('categories/form', [
            'csrf' => Csrf::token(),
            'mode' => 'edit',
            'category' => $category,
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

        $model = new Category();
        $existing = $model->find($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Catégorie introuvable.';
            return;
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));

        $errors = $this->validate($name, $slug, $id);
        if ($errors) {
            $this->render('categories/form', [
                'csrf' => Csrf::token(),
                'mode' => 'edit',
                'category' => ['id' => $id, 'name' => $name, 'slug' => $slug],
                'errors' => $errors,
            ]);
            return;
        }

        $model->update($id, $name, $slug);
        $this->flash('success', 'Catégorie mise à jour.');
        $this->redirect('/categories');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function destroy(array $params): void
    {
        $this->requirePostCsrf();
        $id = (int)($params['id'] ?? 0);

        try {
            (new Category())->delete($id);
            $this->flash('success', 'Catégorie supprimée.');
        } catch (\Throwable) {
            $this->flash('error', 'Impossible de supprimer (catégorie utilisée par des articles).');
        }
        $this->redirect('/categories');
    }

    /**
     * @return array<string,string>
     */
    private function validate(string $name, string $slug, ?int $id): array
    {
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Le nom est obligatoire.';
        }
        if ($slug === '') {
            $errors['slug'] = 'Le slug est obligatoire.';
        } elseif (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $errors['slug'] = 'Slug invalide.';
        } elseif ((new Category())->slugExists($slug, $id)) {
            $errors['slug'] = 'Slug déjà utilisé.';
        }
        return $errors;
    }
}

