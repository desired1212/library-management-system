<?php
// models/Book.php — модель работы с книгами

class Book
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Возвращает все книги (сортировка по названию) */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books ORDER BY title');
        return $stmt->fetchAll();
    }

    /** Поиск по названию или автору (частичное совпадение) */
    public function search(string $field, string $query): array
    {
        // Разрешаем искать только по разрешённым полям
        if (!in_array($field, ['title', 'author'], true)) {
            $field = 'title';
        }
        // Подготовленное выражение: значение передаётся отдельно от SQL
        $stmt = $this->pdo->prepare(
            "SELECT * FROM books WHERE $field LIKE :q ORDER BY title"
        );
        $stmt->execute([':q' => '%' . trim($query) . '%']);
        return $stmt->fetchAll();
    }

    /** Возвращает одну книгу по id или null */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $book = $stmt->fetch();
        return $book ?: null;
    }

    /** Добавляет книгу, возвращает id новой записи */
    public function create(string $title, string $author, ?int $year, ?string $isbn): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (title, author, year, isbn, status)
             VALUES (:title, :author, :year, :isbn, :status)'
        );
        $stmt->execute([
            ':title'  => $title,
            ':author' => $author,
            ':year'   => $year,
            ':isbn'   => $isbn,
            ':status' => 'в наличии', // новая книга всегда в наличии
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /** Обновляет данные книги */
    public function update(int $id, string $title, string $author, ?int $year, ?string $isbn, string $status): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE books
             SET title = :title, author = :author, year = :year,
                 isbn = :isbn, status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':title' => $title, ':author' => $author, ':year' => $year,
            ':isbn' => $isbn, ':status' => $status, ':id' => $id,
        ]);
    }

    /** Удаляет книгу, если по ней нет незакрытых выдач */
    public function delete(int $id): bool
    {
        // Проверяем, нет ли активной выдачи
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM loans WHERE book_id = :id AND returned_at IS NULL'
        );
        $stmt->execute([':id' => $id]);
        if ((int)$stmt->fetchColumn() > 0) {
            return false; // удалять нельзя — книга на руках
        }
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return true;
    }
}
