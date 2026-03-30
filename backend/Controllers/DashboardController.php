<?php
declare(strict_types=1);

namespace BackOffice\Controllers;

use BackOffice\Core\Csrf;

final class DashboardController extends BaseController
{
    /**
     * @param array<string,mixed> $params
     */
    public function index(array $params = []): void
    {
        $this->render('dashboard/index', [
            'csrf' => Csrf::token(),
        ]);
    }
}

