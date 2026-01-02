USE LongDatabase;

-- 1. Lấy danh sách thông tin tất cả cuốn sách
SELECT * FROM Books;

-- 2. Lấy danh sách thông tin tất cả tác giả
SELECT * FROM Authors;

-- 3. Lấy thông tin cuốn sách 1984
SELECT * FROM Books WHERE title = '1984';

-- 4. Lấy danh sách cuốn sách của tác giả Harper Lee
SELECT b.title, b.year_published
FROM Books b
JOIN Authors a ON b.author_id = a.author_id
WHERE a.author_name = 'Harper Lee';

-- 5. Lấy danh sách cuốn sách của nhà xuất bản D
SELECT b.title, b.year_published
FROM Books b
JOIN Publishers p ON b.publisher_id = p.publisher_id
WHERE p.publisher_name = 'Publisher D';

-- 6. Lấy tên tác giả của cuốn sách Pride and Prejudice
SELECT a.author_name
FROM Books b
JOIN Authors a ON b.author_id = a.author_id
WHERE b.title = 'Pride and Prejudice';

-- 7. Lấy tên cuốn sách và năm xuất bản của cuốn sách có nhà xuất bản là "Publisher A"
SELECT b.title, b.year_published
FROM Books b
JOIN Publishers p ON b.publisher_id = p.publisher_id
WHERE p.publisher_name = 'Publisher A';

-- 8. Lấy sách xuất bản sau 1950)
SELECT * FROM Books
WHERE year_published > 1950;

-- 9. Lấy số lượng cuốn sách thuộc mỗi nhà xuất bản
SELECT p.publisher_name, COUNT(b.book_id) AS num_books
FROM Books b
JOIN Publishers p ON b.publisher_id = p.publisher_id
GROUP BY p.publisher_name;

-- 10. Lấy số lượng cuốn sách của mỗi tác giả và sắp xếp theo số lượng giảm dần
SELECT a.author_name, COUNT(b.book_id) AS num_books
FROM Books b
JOIN Authors a ON b.author_id = a.author_id
GROUP BY a.author_name
ORDER BY num_books DESC;

-- 11. Lấy tên tác giả và tổng số cuốn sách của mỗi tác giả có năm xuất bản sau 1900
SELECT a.author_name, COUNT(b.book_id) AS num_books
FROM Books b
JOIN Authors a ON b.author_id = a.author_id
WHERE b.year_published > 1900
GROUP BY a.author_name;

-- 12. Lấy danh sách cuốn sách và tên nhà xuất bản của cuốn sách có tên bắt đầu bằng "The Great"
SELECT b.title, p.publisher_name
FROM Books b
JOIN Publishers p ON b.publisher_id = p.publisher_id
WHERE b.title LIKE 'The Great%';

-- 13. Lấy tên cuốn sách và tên tác giả của cuốn sách có năm xuất bản sau 1950
SELECT b.title, a.author_name
FROM Books b
JOIN Authors a ON b.author_id = a.author_id
WHERE b.year_published > 1950;

-- 14. Lấy tên cuốn sách và tên nhà xuất bản của cuốn sách có tên kết thúc bằng "Mockingbird"
SELECT b.title, p.publisher_name
FROM Books b
JOIN Publishers p ON b.publisher_id = p.publisher_id
WHERE b.title LIKE '%Mockingbird';

-- 15. Lấy danh sách cuốn sách và tên tác giả của cuốn sách có năm xuất bản sau 2000
SELECT b.title, a.author_name
FROM Books b
JOIN Authors a ON b.author_id = a.author_id
WHERE b.year_published > 2000;
