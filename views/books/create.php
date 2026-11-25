<?php // views/books/create.php — форма добавления книги ?>
<h1 class="mb-3">Добавление книги</h1>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="index.php?action=create" class="card p-4 shadow-sm" style="max-width: 560px">
    <div class="mb-3">
        <label class="form-label">Название *</label>
        <input type="text" name="title" class="form-control" required maxlength="255">
    </div>
    <div class="mb-3">
        <label class="form-label">Автор *</label>
        <input type="text" name="author" class="form-control" required maxlength="255">
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Год издания</label>
            <input type="number" name="year" class="form-control" min="1400" max="<?= date('Y') + 1 ?>">
        </div>
        <div class="col mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" maxlength="20">
        </div>
    </div>
    <button class="btn btn-primary">Сохранить</button>
    <a href="index.php" class="btn btn-link mt-2">Отмена</a>
</form>
