<?php
/**
 * @var array $book
 * @var array $readers
 * @var array $errors
 */
?>
<h1 class="mb-3">Выдача книги: <?= htmlspecialchars($book['title']) ?></h1>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0">
        <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul></div>
<?php endif; ?>

<form method="post" class="card p-4 shadow-sm" style="max-width: 520px">
    <div class="mb-3">
        <label class="form-label">Читатель *</label>
        <select name="reader_id" class="form-select" required>
            <option value="">— выберите из списка —</option>
            <?php foreach ($readers as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['full_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Срок возврата *</label>
        <input type="date" name="due_date" class="form-control" required
               min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+14 days')) ?>">
    </div>
    <button class="btn btn-primary">Выдать</button>
    <a href="index.php" class="btn btn-link mt-2">Отмена</a>
</form>
