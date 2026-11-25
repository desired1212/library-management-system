<?php
/**
 * @package controllers
 */
// controllers/ReaderController.php

class ReaderController
{
    private PDO $pdo;
    private Reader $reader;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->reader = new Reader($pdo);
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
        $readers = $this->reader->all();
        $this->render('index', ['readers' => $readers]);
    }

    private function create(): void
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $phone    = trim($_POST['phone'] ?? '') ?: null;

            if (mb_strlen($fullName) < 3) {
                $errors[] = 'Введите ФИО читателя';
            }

            if (!$errors) {
                $this->reader->create($fullName, $phone);
                header('Location: index.php?page=readers');
                exit;
            }
        }
        $this->render('create', ['errors' => $errors]);
    }
        /** Удаление читателя */
    private function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $deleted = $this->reader->delete($id);

        // Сообщение для пользователя передаём через URL-параметр
        if ($deleted) {
            header('Location: index.php?page=readers&msg=deleted');
        } else {
            header('Location: index.php?page=readers&msg=blocked');
        }
        exit;
    }


    private function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/readers/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout/header.php';
        echo $content;
        require __DIR__ . '/../views/layout/footer.php';
    }
}
