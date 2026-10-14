-- Read-only checks. Safe to run at any point.

SHOW DATABASES;

USE IceAdminDbml;

SHOW TABLES;

SHOW CREATE TABLE Employee;

SELECT 'Employee' AS tableName, COUNT(*) AS totalRows FROM Employee
UNION ALL
SELECT 'Department', COUNT(*) FROM Department
UNION ALL
SELECT 'Salary', COUNT(*) FROM Salary
UNION ALL
SELECT 'EmployeeDepartment', COUNT(*) FROM EmployeeDepartment;
