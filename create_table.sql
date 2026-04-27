CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  emp_name VARCHAR(100) NOT NULL,
  job_name VARCHAR(100) NOT NULL,
  salary DECIMAL(10, 2) NOT NULL,
  hire_date DATE NOT NULL,
  department_id INT NOT NULL,
  department_name VARCHAR(100) NOT NULL
);
