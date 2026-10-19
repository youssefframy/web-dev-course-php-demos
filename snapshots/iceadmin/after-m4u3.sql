-- iceadmin as Unit 4 expects to find it: the Unit 3 physical design with its data.
-- Load it with: ./sql reset iceadmin --at m4u3
--
-- TODO: build this from the Unit 3 checkpoint files once Unit 3 has checkpoints.
-- Until then it is written by hand from the Unit 3 demo (sql/01_ddl.sql and sql/02_dml.sql),
-- with two changes the exercises use throughout: money is DECIMAL(10,2), not FLOAT, and
-- EmployeeDepartment has UNIQUE (employeeId, departmentId).

CREATE TABLE Employee
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    jobTitle VARCHAR(15) NOT NULL,
    basis ENUM('PT', 'FT') DEFAULT 'FT'
);

CREATE TABLE Department
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL
);

CREATE TABLE Salary
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fromDate DATE NOT NULL,
    toDate DATE,
    amount DECIMAL(10,2) NOT NULL,
    employeeId INT NOT NULL,
    CONSTRAINT c_salary_employee FOREIGN KEY (employeeId) REFERENCES Employee (id)
);

CREATE TABLE EmployeeDepartment
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    employeeId INT NOT NULL,
    departmentId INT NOT NULL,
    CONSTRAINT c_employeedepartment_employee FOREIGN KEY (employeeId) REFERENCES Employee (id),
    CONSTRAINT c_employeedepartment_department FOREIGN KEY (departmentId) REFERENCES Department (id),
    CONSTRAINT uq_employeedepartment UNIQUE (employeeId, departmentId)
);

INSERT INTO Employee (name, surname, jobTitle, basis)
VALUES
    ('Joe', 'Mifsud', 'Educator', 'PT'),
    ('Lucy', 'Marconi', 'Admissions Team', 'FT'),
    ('Fred', 'Wake', 'Academic Team', 'FT');

INSERT INTO Department (name)
VALUES
    ('Academic'),
    ('Admissions'),
    ('Marketing');

INSERT INTO EmployeeDepartment (employeeId, departmentId)
VALUES
    (1, 1),
    (2, 2),
    (3, 1),
    (3, 2);

INSERT INTO Salary (employeeId, fromDate, toDate, amount)
VALUES
    (1, '2023-01-01', NULL, 35000.00),
    (2, '2019-11-01', '2021-10-31', 30000.00),
    (2, '2021-11-01', NULL, 37800.00),
    (3, '2022-05-01', NULL, 40000.00);
