<?php
declare(strict_types=1);

use BackOffice\Core\Auth;
use BackOffice\Core\Csrf;
use BackOffice\Core\Helpers;

$title = $title ?? 'BackOffice';
$pageTitle = $title . ' | The Gazette Admin';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Helpers::e($pageTitle) ?></title>
    <meta name="description" content="Administration du site The Gazette - Gestion des articles, catégories et utilisateurs">
    <meta name="theme-color" content="#1e40af">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        :root {
            --primary: #1d4ed8;
            --primary-hover: #1e40af;
            --primary-light: #dbeafe;
            --secondary: #374151;
            --secondary-hover: #1f2937;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --success: #15803d;
            --success-light: #dcfce7;
            --success-border: #86efac;
            --warning: #b45309;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-header: #0f172a;
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --text-muted: #6b7280;
            --border: #d1d5db;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius: 8px;
            --radius-lg: 12px;
            --transition: 150ms ease;
            color-scheme: light;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            background: var(--bg-body);
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
        }

        /* Skip link for accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--primary);
            color: #fff;
            padding: 8px 16px;
            z-index: 1000;
            text-decoration: none;
            font-weight: 600;
        }

        .skip-link:focus {
            top: 0;
        }

        /* Header - Site Navigation */
        .site-header {
            background: var(--bg-header);
            color: #fff;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            gap: 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-md);
        }

        .logo {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        nav {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        nav a {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 14px;
            border-radius: var(--radius);
            transition: all var(--transition);
        }

        nav a:hover, nav a:focus {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }

        nav a.active {
            color: #fff;
            background: var(--primary);
        }

        .topbar {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            font-size: 14px;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        /* Typography */
        h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 4px;
            color: var(--text-primary);
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 4px;
            color: var(--text-primary);
        }

        h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 4px;
            color: var(--text-primary);
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 15px;
            margin: 0;
        }

        .muted {
            color: var(--text-muted);
        }

        /* Links - ensure good contrast */
        a {
            color: var(--primary);
        }

        a:hover, a:focus {
            color: var(--primary-hover);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            padding: 10px 18px;
            border-radius: var(--radius);
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .btn:hover, .btn:focus {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .btn.secondary {
            background: var(--secondary);
        }

        .btn.secondary:hover, .btn.secondary:focus {
            background: var(--secondary-hover);
        }

        .btn.danger {
            background: var(--danger);
        }

        .btn.danger:hover, .btn.danger:focus {
            background: var(--danger-hover);
        }

        .btn.outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-primary);
        }

        .btn.outline:hover {
            background: var(--bg-body);
            border-color: var(--text-secondary);
        }

        .btn.sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Forms */
        label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin: 16px 0 6px;
            color: var(--text-primary);
        }

        input, textarea, select {
            width: 100%;
            max-width: 100%;
            padding: 10px 14px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            font-size: 15px;
            font-family: inherit;
            background: var(--bg-card);
            color: var(--text-primary);
            transition: all var(--transition);
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        input::placeholder, textarea::placeholder {
            color: var(--text-muted);
        }

        input[type="file"] {
            padding: 8px;
            cursor: pointer;
        }

        input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 20px;
        }

        .grid.cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid.cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        /* Tables */
        .table-wrapper {
            overflow-x: auto;
            margin: 0 -24px;
            padding: 0 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--bg-body);
        }

        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border);
        }

        th:last-child {
            text-align: right;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        tr:hover td {
            background: #fafbfc;
        }

        td:last-child {
            text-align: right;
        }

        .table-actions {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        .table-actions form {
            display: inline;
            margin: 0;
        }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.draft {
            background: #fef3c7;
            color: #92400e;
        }

        .badge.published {
            background: var(--success-light);
            color: #166534;
        }

        .badge.archived {
            background: #f1f5f9;
            color: #475569;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: var(--success-light);
            border: 1px solid var(--success-border);
            color: #166534;
        }

        .alert.error, .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 14px;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }

        .pagination-info {
            font-size: 14px;
            color: var(--text-secondary);
            margin-right: 8px;
        }

        /* Visually hidden (for screen readers) */
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Stats cards for dashboard */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px;
        }

        .stat-card .value {
            font-size: 18px;
            font-weight: 600;
        }

        .stat-card .value a {
            color: var(--primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .stat-card .value a:hover {
            text-decoration: underline;
        }

        /* Checkbox label */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .grid.cols-2, .grid.cols-3 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .site-header {
                padding: 0 16px;
                height: auto;
                min-height: 64px;
                flex-wrap: wrap;
                gap: 12px;
                padding-top: 12px;
                padding-bottom: 12px;
            }

            nav {
                order: 3;
                width: 100%;
                overflow-x: auto;
                padding-bottom: 4px;
                -webkit-overflow-scrolling: touch;
            }

            nav a {
                padding: 6px 10px;
                font-size: 13px;
            }

            .topbar {
                order: 2;
            }

            .user-info span {
                display: none;
            }

            .container {
                padding: 16px;
            }

            .card {
                padding: 16px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-header .btn {
                width: 100%;
            }

            h1 {
                font-size: 20px;
            }

            .table-wrapper {
                margin: 0 -16px;
                padding: 0 16px;
            }

            th, td {
                padding: 10px 12px;
                font-size: 13px;
            }

            .table-actions {
                flex-direction: column;
                gap: 6px;
            }

            .table-actions .btn {
                width: 100%;
                padding: 8px 12px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .logo::before {
                width: 28px;
                height: 28px;
            }

            .logo {
                font-size: 16px;
            }

            .btn {
                padding: 8px 14px;
                font-size: 13px;
            }
        }

        /* Print styles */
        @media print {
            .site-header, .btn, .form-actions, .pagination { display: none !important; }
            .card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>
<a href="#main-content" class="skip-link">Aller au contenu principal</a>

<header class="site-header" role="banner">
    <a href="/" class="logo" aria-label="Accueil BackOffice">BackOffice</a>
    <?php if (Auth::check()): ?>
        <nav role="navigation" aria-label="Navigation principale">
            <a href="/articles" <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/articles') ? 'class="active" aria-current="page"' : '' ?>>Articles</a>
            <a href="/categories" <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/categories') ? 'class="active" aria-current="page"' : '' ?>>Catégories</a>
            <a href="/authors" <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/authors') ? 'class="active" aria-current="page"' : '' ?>>Auteurs</a>
            <a href="/users" <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/users') ? 'class="active" aria-current="page"' : '' ?>>Utilisateurs</a>
        </nav>
        <div class="topbar">
            <div class="user-info">
                <span><?= Helpers::e((string)($_SESSION['user']['email'] ?? '')) ?></span>
            </div>
            <form method="post" action="/logout" style="margin:0;">
                <input type="hidden" name="_csrf" value="<?= Helpers::e(Csrf::token()) ?>">
                <button class="btn secondary sm" type="submit">Déconnexion</button>
            </form>
        </div>
    <?php endif; ?>
</header>

<main id="main-content" class="container" role="main">
    <?= $content ?? '' ?>
</main>
</body>
</html>

