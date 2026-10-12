-- ICEAdmin simulated data. Dates are written YYYY-MM-DD, the format MariaDB expects.
-- Insert order matters: parent rows must exist before the rows that refer to them.

INSERT INTO Department (id, name) VALUES
  (1, 'Academic Team'),
  (2, 'Admissions Team'),
  (3, 'QA Team');

-- basis is left out, so both employees take the default, 'FT'.
INSERT INTO Employee (id, name, surname, jobTitle) VALUES
  (1, 'Joe',  'Doe',  'Admissions Team'),
  (2, 'Lucy', 'Pace', 'Academic Team');

INSERT INTO Salary (id, fromDate, toDate, amount, employeeId) VALUES
  (1, '2019-01-01', '2022-12-31', 28000.00, 1),
  (2, '2023-01-01', NULL,         30000.00, 1),
  (3, '2022-02-27', NULL,         32000.00, 2);

INSERT INTO EmployeeDepartment (id, employeeId, departmentId) VALUES
  (1, 1, 2),
  (2, 2, 1),
  (3, 2, 3);
