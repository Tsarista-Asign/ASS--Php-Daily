CREATE DATABASE IF NOT EXISTS LongDatabase;
USE LongDatabase;

-- Bảng departments
CREATE TABLE IF NOT EXISTS departments (
    department_id INT PRIMARY KEY,
    department_name VARCHAR(100)
);

-- Bảng employeeroles
CREATE TABLE IF NOT EXISTS employeeroles (
    role_id INT PRIMARY KEY,
    role_name VARCHAR(100)
);

-- Bảng employees
CREATE TABLE IF NOT EXISTS employees (
    employee_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    department_id INT,
    role_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (role_id) REFERENCES employeeroles(role_id)
);

-- Dữ liệu departments
INSERT INTO IF NOT EXISTS departments VALUES
(1, 'HR'),
(2, 'Marketing'),
(3, 'IT'),
(4, 'Finance'),
(5, 'Operations');

-- Dữ liệu employeeroles
INSERT INTO IF NOT EXISTS employeeroles VALUES
(1, 'Manager'),
(2, 'Employee'),
(3, 'Intern'),
(4, 'Analyst'),
(5, 'Director');

-- Dữ liệu employees
INSERT INTO IF NOT EXISTS employees VALUES
(1, 'John', 'Doe', 1, 1),
(2, 'Jane', 'Smith', 2, 2),
(3, 'Michael', 'Johnson', 3, 3),
(4, 'Emily', 'Williams', 4, 2),
(5, 'Davis', 'Brown', 1, 4);

