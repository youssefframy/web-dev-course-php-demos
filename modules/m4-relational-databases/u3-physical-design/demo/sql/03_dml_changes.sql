USE IceAdmin;

-- Give Fred a raise. Salary lives in Salary, not Employee, and WHERE limits it to one row.
UPDATE Salary
SET amount = 41000
WHERE id = 4 AND employeeId = 3;

-- Start a transaction so the delete can be undone.
START TRANSACTION;

-- Remove Fred from Admissions.
DELETE FROM EmployeeDepartment
WHERE employeeId = 3 AND departmentId = 2;

-- The row is gone.
SELECT * FROM EmployeeDepartment WHERE employeeId = 3;

-- Undo the delete.
ROLLBACK;

-- The row is back.
SELECT * FROM EmployeeDepartment WHERE employeeId = 3;
