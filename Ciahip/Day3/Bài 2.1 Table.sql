CREATE DATABASE IF NOT EXISTS LongDatabase;
USE LongDatabase;

-- Bảng authors
CREATE TABLE IF NOT EXISTS Authors (
    author_id INT PRIMARY KEY,
    author_name VARCHAR(100)
);

-- Bảng publishers
CREATE TABLE IF NOT EXISTS Publishers (
    publisher_id INT PRIMARY KEY,
    publisher_name VARCHAR(100)
);

-- Bảng Books
CREATE TABLE IF NOT EXISTS Books (
    book_id INT PRIMARY KEY,
    title VARCHAR(150),
    year_published INT,
    author_id INT,
    publisher_id INT,
    FOREIGN KEY (author_id) REFERENCES Authors(author_id),
    FOREIGN KEY (publisher_id) REFERENCES Publishers(publisher_id)
);

-- Dữ liệu bảng authors
INSERT INTO Authors VALUES
(1, 'J.K. Rowling'),
(2, 'Harper Lee'),
(3, 'George Orwell'),
(4, 'Jane Austen'),
(5, 'F. Scott Fitzgerald');

-- Dữ liệu về Publishers
INSERT INTO Publishers VALUES
(1, 'Publisher A'),
(2, 'Publisher B'),
(3, 'Publisher C'),
(4, 'Publisher D'),
(5, 'Publisher E');

-- Dữ liệu về books 
INSERT INTO Books VALUES
(1, 'Harry Potter and the Sorcerer''s Store', 1997authors, 1, 1),
(2, 'To Kill a Mockingbird', 1960, 2, 2),
(3, '1984', 1949, 3, 3),
(4, 'Pride and Prejudice', 1813, 4, 4),
(5, 'The Great Gatsby', 1925, 5, 5);

