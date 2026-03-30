<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Csrf;
use BackOffice\Models\Author;

final class AuthorController extends BaseController
{
    public function index(): void
    {
        $items = (new Author())->all();
        $this->render('authors/index', [
            'csrf' => Csrf::token(),
            'items' => $items,
            'success' => $this->flash('success'),
            'error' => $this->flash('error'),
        ]);
    }

    public function create(): void
    {
        $this->render('authors/form', [
            'csrf' => Csrf::token(),
            'mode' => 'create',
            'author' => ['name' => '', 'email' => ''],
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        $this->requirePostCsrf();
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $email = $email !== '' ? $email : null;

        $errors = $this->validate($name, $email);
        if ($errors) {
            $this->render('authors/form', [
                'csrf' => Csrf::token(),
                'mode' => 'create',
                'author' => ['name' => $name, 'email' => (string)($email ?? '')],
                'errors' => $errors,
            ]);
            return;
        }

        (new Author())->create($name, $email);
        $this->flash('success', 'Auteur créé.');
        $this->redirect('/authors');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function edit(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $author = (new Author())->find($id);
        if (!$author) {
            http_response_code(404);
            echo 'Auteur introuvable.';
            return;
        }

        $this->render('authors/form', [
            'csrf' => Csrf::token(),
            'mode' => 'edit',
            'author' => $author,
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

        $model = new Author();
        $existing = $model->find($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Auteur introuvable.';
            return;
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $email = $email !== '' ? $email : null;

        $errors = $this->validate($name, $email);
        if ($errors) {
            $this->render('authors/form', [
                'csrf' => Csrf::token(),
                'mode' => 'edit',
                'author' => ['id' => $id, 'name' => $name, 'email' => (string)($email ?? '')],
                'errors' => $errors,
            ]);
            return;
        }

        $model->update($id, $name, $email);
        $this->flash('success', 'Auteur mis à jour.');
        $this->redirect('/authors');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function destroy(array $params): void
    {
        $this->requirePostCsrf();
        $id = (int)($params['id'] ?? 0);

        try {
            (new Author())->delete($id);
            $this->flash('success', 'Auteur supprimé.');
        } catch (\Throwable) {
            $this->flash('error', 'Impossible de supprimer (auteur utilisé par des articles).');
        }
        $this->redirect('/authors');
    }

    /**
     * @return array<string,string>
     */
    private function validate(string $name, ?string $email): array
    {
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Le nom est obligatoire.';
        }
        if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        }
        return $errors;
    }
}

