<?php
/**
 * @package models
 */
// models/Reader.php — модель работы с читателями

class Reader
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Все читатели по алфавиту */
    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM readers ORDER BY full_name')->fetchAll();
    }

    /** Один читатель по id или null */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM readers WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $reader = $stmt->fetch();
        return $reader ?: null;
    }

    /** Добавление читателя */
    public function create(string $fullName, ?string $phone): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO readers (full_name, phone) VALUES (:name, :phone)'
        );
        $stmt->execute([':name' => $fullName, ':phone' => $phone]);
        return (int)$this->pdo->lastInsertId();
    }
        /** Удаляет читателя, если у него нет незакрытых выдач */
        /** Удаляет читателя, если у него нет незакрытых выдач (история закрытых удаляется вместе с ним) */
    public function delete(int $id): bool
    {
        // 1. Активные выдачи блокируют удаление
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM loans WHERE reader_id = :id AND returned_at IS NULL'
        );
        $stmt->execute([':id' => $id]);
        if ((int)$stmt->fetchColumn() > 0) {
            return false; // у читателя книги на руках
        }

        $this->pdo->beginTransaction();
        try {
            // 2. Удаляем закрытую историю выдач, иначе внешний ключ не даст удалить читателя
            $stmt = $this->pdo->prepare('DELETE FROM loans WHERE reader_id = :id');
            $stmt->execute([':id' => $id]);

            // 3. Теперь удаляем самого читателя
            $stmt = $this->pdo->prepare('DELETE FROM readers WHERE id = :id');
            $stmt->execute([':id' => $id]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

}
