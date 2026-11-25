<?php
// views/layout/header.php — шапка страницы
$page = $_GET['page'] ?? 'books';
/**
 * @var string $page
 */
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Библиотека — система учёта книг</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">📚 Библиотека</a>
        <div class="navbar-nav">
    <a class="nav-link <?= $page === 'books' ? 'active' : '' ?>" href="index.php">Книги</a>
    <a class="nav-link <?= $page === 'readers' ? 'active' : '' ?>" href="index.php?page=readers">Читатели</a>
    <a class="nav-link <?= $page === 'loans' ? 'active' : '' ?>" href="index.php?page=loans">Выдачи</a>
</div>

    </div>
</nav>
<main class="container">
