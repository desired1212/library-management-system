<?php

/**
 * @var array $book
 */
// views/books/show.php — карточка книги

// views/books/show.php — карточка книги ?>
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
<a href="index.php" class="btn btn-secondary">← К списку</a>
<a href="index.php?action=edit&id=<?= $book['id'] ?>" class="btn btn-outline-primary">Редактировать</a>
