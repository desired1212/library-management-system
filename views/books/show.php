<?php
/**
 * @var array $book
 * @var array $history
 */
?>
<h1 class="mb-3"><?= htmlspecialchars($book['title']) ?></h1>

<table class="table bg-white shadow-sm">
    <tr><th width="200">Автор</th><td><?= htmlspecialchars($book['author']) ?></td></tr>
    <tr><th>Год издания</th><td><?= htmlspecialchars((string)$book['year']) ?></td></tr>
    <tr><th>ISBN</th><td><?= htmlspecialchars($book['isbn'] ?? '—') ?></td></tr>
    <tr>
        <th>Статус</th>
        <td><?= $book['status'] === 'в наличии'
            ? '<span class="badge bg-success">В наличии</span>'
            : '<span class="badge bg-warning text-dark">На руках</span>' ?></td>
    </tr>
    <tr><th>Добавлена</th><td><?= date('d.m.Y H:i', strtotime($book['created_at'])) ?></td></tr>
</table>

<h2 class="h4 mt-4 mb-3">История выдач</h2>
<table class="table table-striped bg-white shadow-sm">
    <thead class="table-dark">
    <tr><th>№</th><th>Читатель</th><th>Выдана</th><th>Срок возврата</th><th>Возвращена</th></tr>
    </thead>
    <tbody>
    <?php if (!$history): ?>
        <tr><td colspan="5" class="text-center">Книга пока не выдавалась</td></tr>
    <?php endif; ?>
    <?php foreach ($history as $i => $l): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($l['reader_name']) ?></td>
            <td><?= date('d.m.Y', strtotime($l['issued_at'])) ?></td>
            <td><?= date('d.m.Y', strtotime($l['due_date'])) ?></td>
            <td><?= $l['returned_at'] ? date('d.m.Y', strtotime($l['returned_at'])) : '—' ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php" class="btn btn-secondary">← К списку</a>
<a href="index.php?action=edit&id=<?= $book['id'] ?>" class="btn btn-outline-primary">Редактировать</a>
