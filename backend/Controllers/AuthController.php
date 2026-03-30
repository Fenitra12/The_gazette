<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Auth;
use BackOffice\Core\Csrf;
use BackOffice\Models\User;

final class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('auth/login', [
            'csrf' => Csrf::token(),
            'error' => $_SESSION['flash']['error'] ?? null,
        ]);
        unset($_SESSION['flash']['error']);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function login(array $params = []): void
    {
        $this->requirePostCsrf();

        $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
        $password = isset($_POST['password']) ? (string)$_POST['password'] : '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);
        if (!$user || !password_verify($password, (string)$user['password_hash'])) {
            $_SESSION['flash']['error'] = 'Identifiants invalides.';
            $this->redirect('/login');
        }

        Auth::login([
            'id' => (int)$user['id'],
            'email' => (string)$user['email'],
            'role' => (string)$user['role'],
        ]);

        $this->redirect('/');
    }

    /**
     * @param array<string,mixed> $params
     */
    public function logout(array $params = []): void
    {
        $this->requirePostCsrf();
        Auth::logout();
        $this->redirect('/login');
    }
}

