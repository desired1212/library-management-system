<?php
/**
 * @var array $errors
 */
?>
<h1 class="mb-3">Добавление читателя</h1>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0">
        <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul></div>
<?php endif; ?>

<form method="post" action="index.php?page=readers&action=create" class="card p-4 shadow-sm" style="max-width: 480px">
    <div class="mb-3">
        <label class="form-label">ФИО *</label>
        <input type="text" name="full_name" class="form-control" required maxlength="255">
    </div>
    <div class="mb-3">
        <label class="form-label">Телефон</label>
        <input type="tel" name="phone" class="form-control" maxlength="20" placeholder="+7 (900) 000-00-00">
    </div>
    <button class="btn btn-primary">Сохранить</button>
    <a href="index.php?page=readers" class="btn btn-link mt-2">Отмена</a>
</form>
