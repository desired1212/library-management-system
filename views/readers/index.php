<?php
/**
 * @var array $readers
 */
// views/readers/index.php — список читателей

// Служебные сообщения после операций
$messages = [
    'deleted' => 'Читатель удалён',
    'blocked' => 'Нельзя удалить читателя: у него есть книги на руках',
];
$msg = $messages[$_GET['msg'] ?? ''] ?? null;
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Читатели</h1>
    <a href="index.php?page=readers&action=create" class="btn btn-success">+ Добавить читателя</a>
</div>

<?php if ($msg): ?>
    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<table class="table table-striped table-hover align-middle bg-white">
    <thead class="table-dark">
    <tr><th>№</th><th>ФИО</th><th>Телефон</th><th>Дата регистрации</th><th>Действия</th></tr>
    </thead>
    <tbody>
    <?php foreach ($readers as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= htmlspecialchars($r['phone'] ?? '—') ?></td>
            <td><?= date('d.m.Y', strtotime($r['created_at'])) ?></td>
            <td>
                <a class="btn btn-sm btn-outline-danger"
                   href="index.php?page=readers&action=delete&id=<?= $r['id'] ?>"
                   onclick="return confirm('Удалить читателя «<?= htmlspecialchars($r['full_name']) ?>»?')">
                    Удалить
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

