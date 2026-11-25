<?php
/**
 * Модель работы с пользователями системы
 */

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Все пользователи */
    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM users ORDER BY username')->fetchAll();
    }

    /** Один пользователь по id */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /** Поиск по логину (для проверки уникальности) */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /** Создание пользователя: пароль хэшируется, в базу в открытом виде не попадает */
    public function create(string $username, string $password, string $fullName, string $role): bool
    {
                $stmt = $this->pdo->prepare(
            'INSERT INTO users (username, password_hash, full_name, role)
             VALUES (:username, :password_hash, :full_name, :role)'
        );
        return $stmt->execute([
            ':username'      => $username,
            ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ':full_name'     => $fullName,
            ':role'          => $role === 'admin' ? 'admin' : 'librarian',
        ]);

    }

    /** Смена пароля */
    public function updatePassword(int $id, string $password): void
    {
                $stmt = $this->pdo->prepare('UPDATE users SET password_hash = :p WHERE id = :id');

        $stmt->execute([':p' => password_hash($password, PASSWORD_DEFAULT), ':id' => $id]);
    }

    /** Удаление пользователя (кроме себя) */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return true;
    }
}
