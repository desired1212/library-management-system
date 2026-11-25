<?php
/**
 * Контроллер главной страницы (дашборд) и профиля
 */

class DashboardController
{
    private PDO $pdo;
    private Stats $stats;

    public function __construct(PDO $pdo)
    {
        $this->pdo   = $pdo;
        $this->stats = new Stats($pdo);
    }

    public function run(string $action): void
    {
        switch ($action) {
            case 'profile':
                $this->profile();
                break;
            case 'changePassword':
                $this->changePassword();
                break;
            default:
                $this->index();
        }
    }

    private function index(): void
    {
        $data = $this->stats->dashboard();
        $recent = $this->stats->recentLoans();
        $this->render('index', array_merge($data, ['recent' => $recent]));
    }

    private function profile(): void
    {
        $this->render('profile', ['user' => currentUser()]);
    }

    private function changePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=dashboard&action=profile');
            exit;
        }

        $current = $_POST['current'] ?? '';
        $new     = $_POST['new']     ?? '';
        $repeat  = $_POST['repeat']  ?? '';

        // Проверки: длина, совпадение, верность текущего пароля
        $errors = [];
        if (strlen($new) < 6)                 $errors[] = 'Новый пароль — не менее 6 символов';
        if ($new !== $repeat)                 $errors[] = 'Новый пароль и повтор не совпадают';
        if (!preg_match('/[A-Za-zА-Яа-я]/u', $new) || !preg_match('/\d/', $new)) {
            $errors[] = 'Новый пароль должен содержать и буквы, и цифры';
        }

        if (!$errors) {
            $stmt = $this->pdo->prepare('SELECT password_hash FROM users WHERE id = :id');
            $stmt->execute([':id' => (int)currentUser()['id']]);
            $row = $stmt->fetch();

            if (!$row || !password_verify($current, $row['password_hash'])) {
                $errors[] = 'Текущий пароль указан неверно';
            }
        }

        if (!$errors) {
            $stmt = $this->pdo->prepare('UPDATE users SET password_hash = :h WHERE id = :id');
            $stmt->execute([':h' => password_hash($new, PASSWORD_DEFAULT), ':id' => (int)currentUser()['id']]);
            $success = true;
        }

        $this->render('profile', [
            'user'    => currentUser(),
            'errors'  => $errors ?? [],
            'success' => !empty($success),
        ]);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/dashboard/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout/header.php';
        echo $content;
        require __DIR__ . '/../views/layout/footer.php';
    }
}
