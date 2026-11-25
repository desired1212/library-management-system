<?php
/**
 * @package models
 */
// models/Loan.php — модель работы с выдачами

class Loan
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Все активные выдачи (книга ещё не возвращена) */
    public function active(): array
    {
        $sql = 'SELECT l.*, b.title AS book_title, r.full_name AS reader_name
                FROM loans l
                JOIN books b   ON b.id = l.book_id
                JOIN readers r ON r.id = l.reader_id
                WHERE l.returned_at IS NULL
                ORDER BY l.due_date';
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Просроченные выдачи: срок истёк, книга не возвращена */
    public function overdue(): array
    {
        $sql = 'SELECT l.*, b.title AS book_title, r.full_name AS reader_name,
                       DATEDIFF(CURRENT_DATE, l.due_date) AS days_late
                FROM loans l
                JOIN books b   ON b.id = l.book_id
                JOIN readers r ON r.id = l.reader_id
                WHERE l.returned_at IS NULL AND l.due_date < CURRENT_DATE
                ORDER BY days_late DESC';
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Полная история выдач */
    public function history(): array
    {
        $sql = 'SELECT l.*, b.title AS book_title, r.full_name AS reader_name
                FROM loans l
                JOIN books b   ON b.id = l.book_id
                JOIN readers r ON r.id = l.reader_id
                ORDER BY l.issued_at DESC';
        return $this->pdo->query($sql)->fetchAll();
    }

    /** История выдач одной книги */
    public function byBook(int $bookId): array
    {
        $sql = 'SELECT l.*, r.full_name AS reader_name
                FROM loans l
                JOIN readers r ON r.id = l.reader_id
                WHERE l.book_id = :id
                ORDER BY l.issued_at DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $bookId]);
        return $stmt->fetchAll();
    }

    /**
     * Выдача книги читателю. Выполняется в транзакции:
     * либо создаются обе записи (выдача + статус книги), либо ни одной.
     */
    public function issue(int $bookId, int $readerId, ?int $issuedBy, string $dueDate): bool
    {
        // Проверяем, что книга существует и в наличии
        $stmt = $this->pdo->prepare('SELECT status FROM books WHERE id = :id FOR UPDATE');
        $stmt->execute([':id' => $bookId]);
        $status = $stmt->fetchColumn();

        if ($status === false || $status !== 'в наличии') {
            return false; // книги нет или она уже на руках
        }

        $this->pdo->beginTransaction();
        try {
            // 1. Создаём запись о выдаче
            $stmt = $this->pdo->prepare(
                'INSERT INTO loans (book_id, reader_id, issued_by, issued_at, due_date)
                 VALUES (:book_id, :reader_id, :issued_by, CURRENT_DATE, :due_date)'
            );
            $stmt->execute([
                ':book_id'  => $bookId,
                ':reader_id' => $readerId,
                ':issued_by' => $issuedBy,
                ':due_date' => $dueDate,
            ]);

            // 2. Меняем статус книги
            $stmt = $this->pdo->prepare(
                "UPDATE books SET status = 'на руках' WHERE id = :id"
            );
            $stmt->execute([':id' => $bookId]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack(); // откатываем всё, если что-то пошло не так
            return false;
        }
    }

    /** Приём возврата книги: закрываем выдачу, возвращаем статус */
    public function returnBook(int $loanId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT book_id FROM loans WHERE id = :id AND returned_at IS NULL'
        );
        $stmt->execute([':id' => $loanId]);
        $bookId = $stmt->fetchColumn();

        if ($bookId === false) {
            return false; // выдача не найдена или книга уже возвращена
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE loans SET returned_at = CURRENT_DATE WHERE id = :id'
            );
            $stmt->execute([':id' => $loanId]);

            $stmt = $this->pdo->prepare(
                "UPDATE books SET status = 'в наличии' WHERE id = :id"
            );
            $stmt->execute([':id' => (int)$bookId]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
