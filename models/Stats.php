<?php
/**
 * Модель агрегированной статистики для главной страницы
 */

class Stats
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Все показатели для дашборда одним вызовом */
    public function dashboard(): array
    {
        $out = [];

        // Книги: всего и в наличии
        $out['books_total']      = (int)$this->pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
        $out['books_available']  = (int)$this->pdo->query(
            "SELECT COUNT(*) FROM books WHERE status = 'в наличии'"
        )->fetchColumn();

        // Читатели
        $out['readers_total']    = (int)$this->pdo->query('SELECT COUNT(*) FROM readers')->fetchColumn();

        // Выдачи: активные (не возвращённые) и просроченные
        $out['loans_active']     = (int)$this->pdo->query(
            'SELECT COUNT(*) FROM loans WHERE returned_at IS NULL'
        )->fetchColumn();
        $out['loans_overdue']    = (int)$this->pdo->query(
            'SELECT COUNT(*) FROM loans WHERE returned_at IS NULL AND due_date < CURRENT_DATE'
        )->fetchColumn();

        // Возвращено за всё время
        $out['loans_returned']   = (int)$this->pdo->query(
            'SELECT COUNT(*) FROM loans WHERE returned_at IS NOT NULL'
        )->fetchColumn();

        return $out;
    }

    /** Последние 5 выдач — для блока «недавние операции» */
    public function recentLoans(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT l.id, b.title AS book_title, r.full_name AS reader_name,
                    l.issued_at, l.due_date,
                    (l.returned_at IS NULL AND l.due_date < CURRENT_DATE) AS is_overdue
             FROM loans l
             JOIN books b   ON b.id = l.book_id
             JOIN readers r ON r.id = l.reader_id
             ORDER BY l.id DESC
             LIMIT :lim'
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
