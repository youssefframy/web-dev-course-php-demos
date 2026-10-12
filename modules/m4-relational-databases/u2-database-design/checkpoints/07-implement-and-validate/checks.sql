-- Queries that show the ICEAdmin relationships working.
-- Run with: scripts/sql.sh -t iceadmin < modules/m4-relational-databases/u2-database-design/checkpoints/07-implement-and-validate/checks.sql

-- 1. Each employee with every department they work in.
--    Expected: Joe in Admissions Team; Lucy in Academic Team and QA Team.
SELECT e.name, e.surname, GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ', ') AS departments
FROM Employee e
JOIN EmployeeDepartment ed ON ed.employeeId = e.id
JOIN Department d ON d.id = ed.departmentId
GROUP BY e.id, e.name, e.surname
ORDER BY e.id;

-- 2. Each department with its number of employees.
--    Expected: 1 in each.
SELECT d.name, COUNT(ed.employeeId) AS employees
FROM Department d
LEFT JOIN EmployeeDepartment ed ON ed.departmentId = d.id
GROUP BY d.id, d.name
ORDER BY d.id;

-- 3. Current salaries (toDate is empty).
--    Expected: Joe 30000.00, Lucy 32000.00.
SELECT e.name, s.amount, s.fromDate
FROM Employee e
JOIN Salary s ON s.employeeId = e.id
WHERE s.toDate IS NULL
ORDER BY e.id;

-- 4. Joe's salary history, oldest first.
--    Expected: 28000.00 then 30000.00.
SELECT s.fromDate, s.toDate, s.amount
FROM Salary s
WHERE s.employeeId = 1
ORDER BY s.fromDate;

-- 5. The default for basis was applied.
--    Expected: FT for both.
SELECT name, basis FROM Employee ORDER BY id;

-- 6. The database protects itself. Remove the leading "-- " from one line at a time,
--    run the file again, and read the error MariaDB gives you.
-- INSERT INTO EmployeeDepartment (employeeId, departmentId) VALUES (2, 1);   -- duplicate pair: rejected by the unique index
-- INSERT INTO Salary (fromDate, amount, employeeId) VALUES ('2024-01-01', 1000.00, 99);   -- no employee 99: rejected by the foreign key
-- INSERT INTO Employee (name, jobTitle, basis) VALUES ('Ann', 'Tutor', 'XX');   -- not in the ENUM list: rejected
