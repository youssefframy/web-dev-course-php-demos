-- joinlab: the Employee and Department tables from the Unit 5 join slides, for practising joins.
-- It is a toy database. Nothing else builds on it.
-- Load it with: ./sql reset joinlab

CREATE TABLE Department
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL
);

CREATE TABLE Employee
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    surname VARCHAR(20) NOT NULL,
    deptId INT NULL,
    CONSTRAINT c_employee_department FOREIGN KEY (deptId) REFERENCES Department (id)
);

INSERT INTO Department (name)
VALUES
    ('Sales'),
    ('R&D'),
    ('Development'),
    ('Marketing');

-- Roger has no department, and nobody works in Marketing.
INSERT INTO Employee (name, surname, deptId)
VALUES
    ('Alice', 'White', 3),
    ('Lucy', 'Pace', 2),
    ('Grace', 'Green', 2),
    ('Joe', 'Barker', 1),
    ('Roger', 'Smith', NULL);
