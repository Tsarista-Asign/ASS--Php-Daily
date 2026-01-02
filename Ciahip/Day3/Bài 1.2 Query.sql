USE LongDatabase;

-- 1. (Lấy danh sách tất cả nhân viên (bao gồm họ tên, tên phòng ban, tên chức vụ)?
SELECT e.first_name, e.last_name, d.department_name, r.role_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
JOIN employeeroles r ON e.role_id = r.role_id;

-- 2. Lấy danh sách tên tất cả phòng ban 
SELECT department_name FROM departments;

-- 3. Lấy thông tin nhân viên (họ tên, phòng ban, chức vụ) có ID là 3
SELECT e.first_name, e.last_name, d.department_name, r.role_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
JOIN employeeroles r ON e.role_id = r.role_id
WHERE e.employee_id = 3;

-- 4. Lấy danh sách nhân viên (họ tên, chức vụ, phòng ban) làm việc trong phòng ban "HR":
SELECT e.first_name, e.last_name, r.role_name, d.department_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
JOIN employeeroles r ON e.role_id = r.role_id
WHERE d.department_name = 'HR';

-- 5. Lấy danh sách nhân viên (họ tên, phòng ban) có vai trò là "Manager"
SELECT e.first_name, e.last_name, d.department_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
JOIN employeeroles r ON e.role_id = r.role_id
WHERE r.role_name = 'Manager';

-- 6. Lấy tên phòng ban và số lượng nhân viên trong mỗi phòng ban 
SELECT d.department_name, COUNT(e.employee_id) AS num_employees
FROM employees e
JOIN departments d ON e.department_id = d.department_id
GROUP BY d.department_name;

-- 7. Lấy thông tin chức vụ của nhân viên có ID là 2 
SELECT r.role_name
FROM employees e
JOIN employeeroles r ON e.role_id = r.role_id
WHERE e.employee_id = 2;

-- 8. Lấy danh sách nhân viên có tên bắt đầu bằng "J"
SELECT first_name, last_name
FROM employees
WHERE first_name LIKE 'J%';

-- 9. Lấy danh sách các phòng ban và tên của nhân viên có chức vụ "Manager"
SELECT d.department_name, e.first_name, e.last_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
JOIN employeeroles r ON e.role_id = r.role_id
WHERE r.role_name = 'Manager';

-- 10. Lấy số lượng nhân viên trong mỗi phòng ban và sắp xếp theo số lượng giảm dần:
SELECT d.department_name, COUNT(e.employee_id) AS num_employees
FROM employees e
JOIN departments d ON e.department_id = d.department_id
GROUP BY d.department_name
ORDER BY num_employees DESC;

-- 11. Lấy thông tin vai trò của nhân viên có tên là "Emily Williams"
SELECT r.role_name
FROM employees e
JOIN employeeroles r ON e.role_id = r.role_id
WHERE e.first_name = 'Emily' AND e.last_name = 'Williams';

-- 12.  Lấy danh sách nhân viên làm việc trong phòng ban có tên bắt đầu bằng "M": 
SELECT e.first_name, e.last_name, d.department_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
WHERE d.department_name LIKE 'M%';

-- 13. Lấy thông tin nhân viên và tên phòng ban của nhân viên có chức vụ "Director" 
SELECT e.first_name, e.last_name, d.department_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
JOIN employeeroles r ON e.role_id = r.role_id
WHERE r.role_name = 'Director';

-- 14.  Lấy danh sách nhân viên làm việc trong phòng ban "IT" hoặc "Finance" 
SELECT e.first_name, e.last_name, d.department_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
WHERE d.department_name IN ('IT','Finance');

-- 15. Lấy danh sách nhân viên và số lượng nhân viên của phòng ban có nhiều nhân viên nhất. 
SELECT d.department_name, e.first_name, e.last_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id
WHERE d.department_id = (
    SELECT e2.department_id
    FROM employees e2
    GROUP BY e2.department_id
    ORDER BY COUNT(e2.employee_id) DESC
    LIMIT 1
);
