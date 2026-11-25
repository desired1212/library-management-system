<?php
/**
 * Главная страница — сводная статистика
 * @var array $recent
 */
?>
<h1 class="mb-1">📚 Система учёта библиотечных книг</h1>
<p class="text-muted mb-4">Сводка на <?= date('d.m.Y') ?></p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-bg-primary h-100"><div class="card-body">
            <div class="fs-1"><?= $books_total ?></div>
            <div>Книг в фонде (<?= $books_available ?> в наличии)</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success h-100"><div class="card-body">
            <div class="fs-1"><?= $readers_total ?></div>
            <div>Читателей зарегистрировано</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-info h-100"><div class="card-body">
            <div class="fs-1"><?= $loans_active ?></div>
            <div>Книг на руках (<?= $loans_returned ?> уже возвращено)</div>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card <?= $loans_overdue ? 'text-bg-danger' : 'text-bg-secondary' ?> h-100">
            <div class="card-body">
                <div class="fs-1"><?= $loans_overdue ?></div>
                <div>Просроченных выдач</div>
            </div>
        </div>
        <a href="index.php?page=loans&action=overdue" class="btn btn-outline-danger mt-2 w-100">
            Открыть отчёт по просрочкам
        </a>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-white">Недавние операции</div>
            <ul class="list-group list-group-flush">
                <?php if (!$recent): ?>
                    <li class="list-group-item text-muted">Выдач пока нет</li>
                <?php endif; ?>
                <?php foreach ($recent as $l): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong><?= htmlspecialchars($l['book_title']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($l['reader_name']) ?></small>
                        </span>
                        <span class="text-end">
                            <small class="text-muted d-block"><?= date('d.m.Y', strtotime($l['issued_at'])) ?></small>
                            <?php if ($l['is_overdue']): ?>
                                <span class="badge bg-danger">просрочено</span>
                            <?php elseif ($l['due_date']): ?>
                                <small>до <?= date('d.m.Y', strtotime($l['due_date'])) ?></small>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
