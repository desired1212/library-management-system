<?php
/**
 * Контроллер управления пользователями (только для админа)
 */

class UserController
{
    private PDO $pdo;
    private User $user;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->user = new User($pdo);
    }

    public function run(string $action): void
    {
        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
        }
    }

    private function index(): void
    {
        $users = $this->user->all();
        $this->render('index', ['users' => $users]);
    }

    private function create(): void
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $fullName = trim($_POST['full_name'] ?? '');
            $role     = $_POST['role'] ?? 'librarian';

            if (mb_strlen($username) < 3)            $errors[] = 'Логин должен быть не короче 3 символов';
            if (strlen($password) < 6)               $errors[] = 'Пароль должен быть не короче 6 символов';
            if (mb_strlen($fullName) < 3)            $errors[] = 'Введите ФИО пользователя';
            if (!in_array($role, ['admin', 'librarian'], true)) $role = 'librarian';

            // Логин должен быть уникальным
            if (!$errors && $this->user->findByUsername($username)) {
                $errors[] = 'Пользователь с таким логином уже существует';
            }

            if (!$errors) {
                $this->user->create($username, $password, $fullName, $role);
                header('Location: index.php?page=users');
                exit;
            }
        }
        $this->render('create', ['errors' => $errors]);
    }

    private function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        // Себя удалить нельзя, иначе админ останется без доступа
        if ($id !== (int)currentUser()['id']) {
            $this->user->delete($id);
        }
        header('Location: index.php?page=users');
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/users/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout/header.php';
        echo $content;
        require __DIR__ . '/../views/layout/footer.php';
    }
}
