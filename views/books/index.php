<?php // views/books/index.php — список книг с поиском ?>
<h1 class="mb-3">Каталог книг</h1>

<!-- Форма поиска: клиентская валидация через required -->
<form class="row g-2 mb-3" method="get" action="index.php">
    <div class="col-md-5">
        <input type="text" name="q" class="form-control" placeholder="Что искать?"
               value="<?= htmlspecialchars($q) ?>" maxlength="100">
    </div>
    <div class="col-md-3">
        <select name="field" class="form-select">
            <option value="title"  <?= $field === 'title' ? 'selected' : '' ?>>По названию</option>
            <option value="author" <?= $field === 'author' ? 'selected' : '' ?>>По автору</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Найти</button>
    </div>
    <div class="col-md-2">
        <a href="index.php?action=create" class="btn btn-success w-100">+ Добавить</a>
    </div>
</form>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
    <tr>
        <th>№</th><th>Название</th><th>Автор</th><th>Год</th><th>ISBN</th><th>Статус</th><th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!$books): ?>
        <tr><td colspan="7" class="text-center">Книги не найдены</td></tr>
    <?php endif; ?>
    <?php foreach ($books as $i => $b): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= htmlspecialchars($b['author']) ?></td>
            <td><?= htmlspecialchars((string)$b['year']) ?></td>
            <td><?= htmlspecialchars($b['isbn'] ?? '') ?></td>
            <td>
                <?php if ($b['status'] === 'в наличии'): ?>
                    <span class="badge bg-success">В наличии</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">На руках</span>
                <?php endif; ?>
            </td>
            <td>
                <a class="btn btn-sm btn-outline-primary" href="index.php?action=show&id=<?= $b['id'] ?>">Открыть</a>
                <a class="btn btn-sm btn-outline-secondary" href="index.php?action=edit&id=<?= $b['id'] ?>">Изменить</a>
                <!-- Минимум JS: подтверждение удаления -->
                <a class="btn btn-sm btn-outline-danger"
                   href="index.php?action=delete&id=<?= $b['id'] ?>"
                   onclick="return confirm('Удалить книгу «<?= htmlspecialchars($b['title']) ?>»?')">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
