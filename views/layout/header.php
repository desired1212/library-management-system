<?php
// views/layout/header.php — шапка страниц

/**
 * Общий шаблон шапки
 *
 * @var string   $page         Текущий раздел (из index.php)
 * @var callable $isAdmin
 * @var callable $currentUser
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    ...



<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Библиотека — система учёта книг</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">📚 Библиотека</a>
        <div class="navbar-nav">
            <a class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">Главная</a>
            <a class="nav-link <?= $page === 'books'   ? 'active' : '' ?>" href="index.php?page=books">Книги</a>
            <a class="nav-link <?= $page === 'readers' ? 'active' : '' ?>" href="index.php?page=readers">Читатели</a>
            <a class="nav-link <?= $page === 'loans'   ? 'active' : '' ?>" href="index.php?page=loans">Выдачи</a>
            <?php if (isAdmin()): ?>
                <a class="nav-link <?= $page === 'users' ? 'active' : '' ?>" href="index.php?page=users">Пользователи</a>
            <?php endif; ?>
        </div>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php?page=dashboard&action=profile">
                <?= htmlspecialchars(currentUser()['full_name']) ?>
                (<?= currentUser()['role'] === 'admin' ? 'админ' : 'библиотекарь' ?>)
            </a>
            <a class="nav-link" href="index.php?page=logout">Выход</a>
        </div>
    </div>
</nav>

<main class="container">
