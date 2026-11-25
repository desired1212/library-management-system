<?php // views/layout/header.php — шапка страницы ?>
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
            <a class="nav-link active" href="index.php">Книги</a>
            <a class="nav-link" href="index.php?action=show_readers">Читатели</a>
        </div>
    </div>
</nav>
<main class="container">
