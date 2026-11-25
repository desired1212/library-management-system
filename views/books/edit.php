<?php 
/**
 * @var array $errors
 * @var array $book
 */
// views/books/edit.php — форма редактирования ?>
<h1 class="mb-3">Редактирование книги</h1>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="index.php?action=edit&id=<?= $book['id'] ?>" class="card p-4 shadow-sm" style="max-width: 560px">
    <div class="mb-3">
        <label class="form-label">Название *</label>
        <input type="text" name="title" class="form-control" required maxlength="255"
               value="<?= htmlspecialchars($book['title']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Автор *</label>
        <input type="text" name="author" class="form-control" required maxlength="255"
               value="<?= htmlspecialchars($book['author']) ?>">
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Год издания</label>
            <input type="number" name="year" class="form-control" min="1400" max="<?= date('Y') + 1 ?>"
                   value="<?= htmlspecialchars((string)$book['year']) ?>">
        </div>
        <div class="col mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" maxlength="20"
                   value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Статус</label>
        <select name="status" class="form-select">
            <option value="в наличии" <?= $book['status'] === 'в наличии' ? 'selected' : '' ?>>В наличии</option>
            <option value="на руках"  <?= $book['status'] === 'на руках' ? 'selected' : '' ?>>На руках</option>
        </select>
    </div>
    <button class="btn btn-primary">Сохранить</button>
    <a href="index.php" class="btn btn-link mt-2">Отмена</a>
</form>
