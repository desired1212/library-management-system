<?php
/**
 * @package controllers
 */
// controllers/LoanController.php

class LoanController
{
    private PDO $pdo;
    private Loan $loan;
    private Book $book;
    private Reader $reader;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->loan = new Loan($pdo);
        $this->book = new Book($pdo);
        $this->reader = new Reader($pdo);
    }

    public function run(string $action): void
    {
        switch ($action) {
            case 'issue':   $this->issue();   break;
            case 'return':  $this->doReturn(); break;
            default:        $this->index();
        }
    }

    /** Список выдач с переключателем: активные / просроченные / история */
    private function index(): void
    {
        $tab = $_GET['tab'] ?? 'active';
        $loans = match ($tab) {
            'overdue' => $this->loan->overdue(),
            'history' => $this->loan->history(),
            default   => $this->loan->active(),
        };
        $this->render('index', ['loans' => $loans, 'tab' => $tab]);
    }

    /** Форма выдачи книги */
    private function issue(): void
    {
        $bookId = (int)($_GET['book_id'] ?? 0);
        $book = $this->book->find($bookId);

        if (!$book) {
            http_response_code(404);
            die('Книга не найдена');
        }
        if ($book['status'] !== 'в наличии') {
            die('Книга уже находится на руках, выдача невозможна');
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $readerId = (int)($_POST['reader_id'] ?? 0);
            $dueDate  = $_POST['due_date'] ?? '';

            if (!$readerId)          $errors[] = 'Выберите читателя';
            if ($dueDate === '')     $errors[] = 'Укажите срок возврата';
            if ($dueDate !== '' && $dueDate < date('Y-m-d')) {
                $errors[] = 'Срок возврата не может быть в прошлом';
            }

            if (!$errors) {
                // issued_by пока 1 — заглушка, на этапе авторизации подставим реального пользователя
                $ok = $this->loan->issue($bookId, $readerId, 1, $dueDate);
                if ($ok) {
                    header('Location: index.php?page=loans');
                    exit;
                }
                $errors[] = 'Не удалось оформить выдачу';
            }
        }

        $readers = $this->reader->all();
        $this->render('issue', ['book' => $book, 'readers' => $readers, 'errors' => $errors]);
    }

    /** Приём возврата книги */
    private function doReturn(): void
    {
        $loanId = (int)($_GET['id'] ?? 0);
        $this->loan->returnBook($loanId);
        header('Location: index.php?page=loans');
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/loans/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout/header.php';
        echo $content;
        require __DIR__ . '/../views/layout/footer.php';
    }
}
