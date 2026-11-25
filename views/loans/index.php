<?php
/**
 * @var array $loans
 * @var string $tab
 */
?>
<h1 class="mb-3">Выдачи книг</h1>
<?php if ($tab === 'overdue'): ?>
    <div class="alert alert-warning">
        Всего просроченных выдач: <strong><?= count($loans) ?></strong>.
        <a class="btn btn-sm btn-outline-success ms-2"
           href="index.php?page=loans&tab=overdue&action=export">Экспорт в CSV</a>
    </div>
<?php endif; ?>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'active' ? 'active' : '' ?>" href="index.php?page=loans&tab=active">Активные</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'overdue' ? 'active' : '' ?>" href="index.php?page=loans&tab=overdue">Просроченные</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'history' ? 'active' : '' ?>" href="index.php?page=loans&tab=history">История</a>
    </li>
</ul>

<table class="table table-striped table-hover align-middle bg-white">
    <thead class="table-dark">
    <tr>
        <th>Книга</th><th>Читатель</th><th>Выдана</th><th>Срок возврата</th><th>Возвращена</th>
        <?php if ($tab === 'overdue'): ?><th>Просрочка</th><?php endif; ?>
        <?php if ($tab === 'active'): ?><th>Действие</th><?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php if (!$loans): ?>
        <tr><td colspan="7" class="text-center">Записей нет</td></tr>
    <?php endif; ?>
    <?php foreach ($loans as $l): ?>
        <tr>
            <td><?= htmlspecialchars($l['book_title']) ?></td>
            <td><?= htmlspecialchars($l['reader_name']) ?></td>
            <td><?= date('d.m.Y', strtotime($l['issued_at'])) ?></td>
            <td class="<?= $tab !== 'history' && $l['due_date'] < date('Y-m-d') ? 'text-danger fw-bold' : '' ?>">
                <?= date('d.m.Y', strtotime($l['due_date'])) ?>
            </td>
            <td><?= $l['returned_at'] ? date('d.m.Y', strtotime($l['returned_at'])) : '—' ?></td>
            <?php if ($tab === 'overdue'): ?>
                <td><span class="badge bg-danger"><?= $l['days_late'] ?> дн.</span></td>
            <?php endif; ?>
            <?php if ($tab === 'active'): ?>
                <td>
                    <a class="btn btn-sm btn-outline-success"
                       href="index.php?page=loans&action=return&id=<?= $l['id'] ?>">Принять возврат</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
