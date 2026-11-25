<?php
/**
 * @var array $errors
 */
?>
<h1 class="mb-3">Добавление пользователя</h1>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0">
        <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul></div>
<?php endif; ?>

<form method="post" action="index.php?page=users&action=create" class="card p-4 shadow-sm" style="max-width: 520px">
    <div class="mb-3">
        <label class="form-label">Логин *</label>
        <input type="text" name="username" class="form-control" required minlength="3" maxlength="50">
    </div>
    <div class="mb-3">
        <label class="form-label">Пароль *</label>
        <input type="password" name="password" class="form-control" required minlength="6">
        <div class="form-text">Не менее 6 символов. В базе хранится только хэш.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">ФИО *</label>
        <input type="text" name="full_name" class="form-control" required maxlength="255">
    </div>
    <div class="mb-3">
        <label class="form-label">Роль</label>
        <select name="role" class="form-select">
            <option value="librarian">Библиотекарь</option>
            <option value="admin">Администратор</option>
        </select>
    </div>
    <button class="btn btn-primary">Создать</button>
    <a href="index.php?page=users" class="btn btn-link mt-2">Отмена</a>
</form>
