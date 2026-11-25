<?php
/**
 * Страница входа в систему
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :u');
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    // Проверяем пароль против хэша из колонки password_hash
    $hash = $user['password_hash'] ?? null;
    if ($user && $hash && password_verify($password, $hash)) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'        => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role'      => $user['role'],
        ];
        header('Location: index.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — Библиотека</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width: 420px; margin-top: 12vh">
    <div class="card shadow">
        <div class="card-body p-4">
            <h1 class="h4 text-center mb-4">📚 Вход в систему</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Логин</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Войти</button>
            </form>
        </div>
    </div>
    <p class="text-center text-muted mt-3">
        <small>Тестовые учётные данные: admin / admin123, librarian / librarian123</small>
    </p>
</div>
</body>
</html>
