<?php
/**
 * @var array $users
 */
// Список пользователей системы
$me = currentUser();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Пользователи системы</h1>
    <a href="index.php?page=users&action=create" class="btn btn-success">+ Добавить пользователя</a>
</div>

<table class="table table-striped table-hover align-middle bg-white">
    <thead class="table-dark">
    <tr><th>№</th><th>Логин</th><th>ФИО</th><th>Роль</th><th>Создан</th><th>Действия</th></tr>
    </thead>
    <tbody>
    <?php foreach ($users as $i => $u): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td>
                <span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                    <?= $u['role'] === 'admin' ? 'Администратор' : 'Библиотекарь' ?>
                </span>
            </td>
            <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
            <td>
                <?php if ($u['id'] != $me['id']): ?>
                    <a class="btn btn-sm btn-outline-danger"
                       href="index.php?page=users&action=delete&id=<?= $u['id'] ?>"
                       onclick="return confirm('Удалить пользователя «<?= htmlspecialchars($u['username']) ?>»?')">
                        Удалить
                    </a>
                <?php else: ?>
                    <span class="text-muted small">это вы</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
