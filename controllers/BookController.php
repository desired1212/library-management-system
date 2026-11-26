<?php
// controllers/BookController.php — контроллер книг
require_once __DIR__ . '/../models/Loan.php';

class BookController
{
    private PDO $pdo;
    private Book $book;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->book = new Book($pdo);
    }

    /** Точка входа: выбирает действие по параметру ?action= */
    public function run(string $action): void
    {
        switch ($action) {
            case 'create':  $this->create();  break;
            case 'edit':    $this->edit();    break;
            case 'delete':  $this->delete();  break;
            case 'show':    $this->show();    break;
            default:        $this->index();
        }
    }

    /** Список книг + поиск */
    private function index(): void
    {
        $field = $_GET['field'] ?? '';
        $query = $_GET['q'] ?? '';

        if ($query !== '') {
            $books = $this->book->search($field, $query);
        } else {
            $books = $this->book->all();
        }
        $this->render('index', ['books' => $books, 'q' => $query, 'field' => $field]);
    }

    /** Карточка книги */
        /** Карточка книги */
    private function show(): void
    {
        $book = $this->book->find((int)($_GET['id'] ?? 0));
        if (!$book) {
            http_response_code(404);
            die('Книга не найдена');
        }
        // История выдач этой книги: кто брал, когда и когда вернул
        $loan = new Loan($this->pdo);
        $history = $loan->byBook((int)$book['id']);

        $this->render('show', ['book' => $book, 'history' => $history]);
    }


    /** Добавление книги */
    private function create(): void
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Серверная валидация
            $title  = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $year   = $_POST['year'] !== '' ? (int)$_POST['year'] : null;
            $isbn   = trim($_POST['isbn'] ?? '') ?: null;

            if ($title === '')  $errors[] = 'Введите название книги';
            if ($author === '') $errors[] = 'Введите автора книги';
            if ($year !== null && ($year < 1400 || $year > (int)date('Y') + 1)) {
                $errors[] = 'Год издания указан некорректно';
            }

            if (!$errors) {
                $this->book->create($title, $author, $year, $isbn);
                header('Location: index.php'); // после добавления — на список
                exit;
            }
        }
        $this->render('create', ['errors' => $errors]);
    }

    /** Редактирование книги */
    private function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $book = $this->book->find($id);
        if (!$book) {
            http_response_code(404);
            die('Книга не найдена');
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title  = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $year   = $_POST['year'] !== '' ? (int)$_POST['year'] : null;
            $isbn   = trim($_POST['isbn'] ?? '') ?: null;
            $status = ($_POST['status'] ?? '') === 'на руках' ? 'на руках' : 'в наличии';

            if ($title === '')  $errors[] = 'Введите название книги';
            if ($author === '') $errors[] = 'Введите автора книги';

            if (!$errors) {
                $this->book->update($id, $title, $author, $year, $isbn, $status);
                header('Location: index.php');
                exit;
            }
        }
        $this->render('edit', ['book' => $book, 'errors' => $errors]);
    }

    public function delete(): void
{
    $id = (int)($_GET['id'] ?? 0);

    try {
        $this->book->delete($id);
        header('Location: index.php?page=books&msg=deleted');
    } catch (PDOException $e) {
        // Нарушение внешнего ключа: по книге есть выдачи
        header('Location: index.php?page=books&error=book_in_use');
    }
    exit;
}


    /** Подключает шаблон внутри общей разметки */
    private function render(string $view, array $data = []): void
    {
        // Переменные из $data становятся доступны в шаблоне
        extract($data);
        ob_start();
        require __DIR__ . '/../views/books/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout/header.php';
        echo $content;
        require __DIR__ . '/../views/layout/footer.php';
    }
}
