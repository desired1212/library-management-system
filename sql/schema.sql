-- sql/schema.sql
-- Создание базы данных системы учёта библиотечных книг

CREATE DATABASE IF NOT EXISTS library_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE library_db;

-- Таблица книг
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Название книги',
    author VARCHAR(255) NOT NULL COMMENT 'Автор',
    year INT NULL COMMENT 'Год издания',
    isbn VARCHAR(20) NULL COMMENT 'Международный стандартный номер',
    status ENUM('в наличии', 'на руках') NOT NULL DEFAULT 'в наличии' COMMENT 'Текущий статус',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица читателей
CREATE TABLE readers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL COMMENT 'ФИО читателя',
    phone VARCHAR(20) NULL COMMENT 'Телефон',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата регистрации'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица пользователей системы (библиотекари)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role ENUM('admin','librarian') NOT NULL DEFAULT 'librarian',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);



-- Таблица выдач (связующая между книгами и читателями)
CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL COMMENT 'Книга',
    reader_id INT NOT NULL COMMENT 'Читатель',
        issued_by INT NULL COMMENT 'Кем выдана (id пользователя)',

    issued_at DATE NOT NULL COMMENT 'Дата выдачи',
    due_date DATE NOT NULL COMMENT 'Срок возврата',
    returned_at DATE NULL COMMENT 'Дата возврата (NULL = не возвращена)',
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (reader_id) REFERENCES readers(id),
    FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
