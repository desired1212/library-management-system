<?php
// public/index.php — единая точка входа приложения
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Reader.php';
require_once __DIR__ . '/../models/Loan.php';
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/ReaderController.php';
require_once __DIR__ . '/../controllers/LoanController.php';

$page   = $_GET['page']   ?? 'books';
$action = $_GET['action'] ?? 'index';

switch ($page) {
    case 'readers':
        (new ReaderController($pdo))->run($action);
        break;
    case 'loans':
        (new LoanController($pdo))->run($action);
        break;
    default:
        (new BookController($pdo))->run($action);
}
