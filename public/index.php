<?php
// public/index.php — единая точка входа приложения
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';


require_once __DIR__ . '/../models/Stats.php';
require_once __DIR__ . '/../controllers/DashboardController.php';


requireLogin(); // без входа дальше не пускаем

// Выход из системы
if (($_GET['page'] ?? '') === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Reader.php';
require_once __DIR__ . '/../models/Loan.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/ReaderController.php';
require_once __DIR__ . '/../controllers/LoanController.php';
require_once __DIR__ . '/../controllers/UserController.php';

$page   = $_GET['page']   ?? 'books';
$action = $_GET['action'] ?? 'index';

switch ($page) {
    case 'dashboard':
        (new DashboardController($pdo))->run($action);
        break;
   
        

    case 'readers':
        (new ReaderController($pdo))->run($action);
        break;
    case 'loans':
        (new LoanController($pdo))->run($action);
        break;
    case 'users':
        requireRole('admin'); // управление пользователями — только админу
        (new UserController($pdo))->run($action);
        break;
    default:
        (new BookController($pdo))->run($action);
}
