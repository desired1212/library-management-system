<?php
// public/index.php — единая точка входа приложения
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../controllers/BookController.php';

$action = $_GET['action'] ?? 'index';
$controller = new BookController($pdo);
$controller->run($action);
