USE IceAdmin;

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
    (1, '2023-01-01', NULL, 35000),
    (2, '2019-11-01', '2021-10-31', 30000),
    (2, '2021-11-01', NULL, 37800),
    (3, '2022-05-01', NULL, 40000);

SELECT * FROM Employee;
