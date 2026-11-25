<?php
// config/db.php — подключение к базе данных через PDO

// Параметры соединения (стандартные для XAMPP)
$host = 'localhost';
$dbname = 'library_db';
$user = 'root';       // стандартный пользователь XAMPP
$pass = '';           // у root в XAMPP пароль пустой
$charset = 'utf8mb4';

// DSN — строка описания источника данных
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Опции PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // ошибки выбрасывают исключения
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // результат — ассоциативный массив
    PDO::ATTR_EMULATE_PREPARES => false,                  // настоящие подготовленные выражения
];

try {
    // Создаём объект PDO — единое соединение для всех модулей
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // При ошибке соединения останавливаем работу с понятным сообщением
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}
