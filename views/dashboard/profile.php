<?php
/**
 * @var array $user
 * @var array $errors
 * @var bool  $success
 */
?>
<h1 class="mb-4">Мой профиль</h1>

<div class="card mb-4" style="max-width: 520px">
    <div class="card-body">
        <p class="mb-1"><strong>ФИО:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
        <p class="mb-1"><strong>Логин:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p class="mb-0"><strong>Роль:</strong> <?= $user['role'] === 'admin' ? 'Администратор' : 'Библиотекарь' ?></p>
    </div>
</div>

<div class="card" style="max-width: 520px">
    <div class="card-header bg-white">Смена пароля</div>
    <div class="card-body">
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">Пароль изменён.</div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul></div>
        <?php endif; ?>

        <form method="post" action="index.php?page=dashboard&action=changePassword">
            <div class="mb-3">
                <label class="form-label">Текущий пароль</label>
                <input type="password" name="current" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Новый пароль</label>
                <input type="password" name="new" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Повторите новый пароль</label>
                <input type="password" name="repeat" class="form-control" required minlength="6">
            </div>
            <button class="btn btn-primary">Сменить пароль</button>
        </form>
    </div>
</div>
