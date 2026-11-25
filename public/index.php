<?php
// public/index.php — проверка подключения к БД
require_once __DIR__ . '/../config/db.php';

echo "<h1>Проверка подключения</h1>";

// Считаем количество книг в базе
$stmt = $pdo->query('SELECT COUNT(*) AS cnt FROM books');
$row = $stmt->fetch();
echo "<p>Книг в базе: " . $row['cnt'] . "</p>";

$stmt = $pdo->query('SELECT COUNT(*) AS cnt FROM readers');
$row = $stmt->fetch();
echo "<p>Читателей в базе: " . $row['cnt'] . "</p>";
