<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Auth;
use BackOffice\Core\Csrf;
use BackOffice\Models\User;

final class UserController extends BaseController
{
    /**
     * @param array<string,mixed> $params
     */
    public function index(array $params = []): void
    {
        $items = (new User())->all();
        $this->render('users/index', [
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
        $this->render('users/form', [
            'csrf' => Csrf::token(),
            'mode' => 'create',
            'user' => ['email' => '', 'role' => 'admin'],
            'errors' => [],
        ]);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function store(array $params = []): void
    {
        $this->requirePostCsrf();
        $email = trim((string)($_POST['email'] ?? ''));
        $role = trim((string)($_POST['role'] ?? 'admin')) ?: 'admin';
        $password = (string)($_POST['password'] ?? '');

        $errors = $this->validate($email, $role, $password, null);
        if ($errors) {
            $this->render('users/form', [
                'csrf' => Csrf::token(),
                'mode' => 'create',
                'user' => ['email' => $email, 'role' => $role],
                'errors' => $errors,
            ]);
            return;
        }

        $id = (new User())->create($email, password_hash($password, PASSWORD_BCRYPT), $role);
        $this->flash('success', "Utilisateur créé (#{$id}).");
        $this->redirect('/users');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function edit(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $user = (new User())->find($id);
        if (!$user) {
            http_response_code(404);
            echo 'Utilisateur introuvable.';
            return;
        }

        $this->render('users/form', [
            'csrf' => Csrf::token(),
            'mode' => 'edit',
            'user' => $user,
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

        $model = new User();
        $existing = $model->find($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Utilisateur introuvable.';
            return;
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $role = trim((string)($_POST['role'] ?? 'admin')) ?: 'admin';
        $password = (string)($_POST['password'] ?? '');

        $errors = $this->validate($email, $role, $password !== '' ? $password : null, $id);
        if ($errors) {
            $this->render('users/form', [
                'csrf' => Csrf::token(),
                'mode' => 'edit',
                'user' => ['id' => $id, 'email' => $email, 'role' => $role],
                'errors' => $errors,
            ]);
            return;
        }

        $model->update($id, $email, $role);
        if ($password !== '') {
            $model->updatePassword($id, password_hash($password, PASSWORD_BCRYPT));
        }

        // Si l'utilisateur modifie son propre email/role, on resync la session
        if (Auth::id() === $id) {
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['role'] = $role;
        }

        $this->flash('success', 'Utilisateur mis à jour.');
        $this->redirect('/users');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function destroy(array $params): void
    {
        $this->requirePostCsrf();
        $id = (int)($params['id'] ?? 0);

        if (Auth::id() === $id) {
            $this->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            $this->redirect('/users');
        }

        (new User())->delete($id);
        $this->flash('success', 'Utilisateur supprimé.');
        $this->redirect('/users');
    }

    /**
     * @param string|null $password If null: no password validation (edit without change)
     * @return array<string,string>
     */
    private function validate(string $email, string $role, ?string $password, ?int $id): array
    {
        $errors = [];

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        } elseif ((new User())->emailExists($email, $id)) {
            $errors['email'] = 'Email déjà utilisé.';
        }

        $allowedRoles = ['admin', 'editor'];
        if (!in_array($role, $allowedRoles, true)) {
            $errors['role'] = 'Rôle invalide.';
        }

        if ($password !== null) {
            if ($password === '' || strlen($password) < 8) {
                $errors['password'] = 'Mot de passe: 8 caractères minimum.';
            }
        }

        return $errors;
    }
}

