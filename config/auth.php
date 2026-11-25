<?php
// config/auth.php — функции авторизации и проверки доступа

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // сессию стартуем ровно один раз
}

/** Текущий пользователь или null, если не авторизован */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/** Требует авторизации: не вошедших отправляет на страницу входа */
function requireLogin(): void
{
    if (!currentUser()) {
        header('Location: login.php');
        exit;
    }
}

/** Требует определённую роль (например, admin) */
function requireRole(string $role): void
{
    requireLogin();
    if (currentUser()['role'] !== $role) {
        http_response_code(403);
        die('Доступ запрещён: недостаточно прав');
    }
}
