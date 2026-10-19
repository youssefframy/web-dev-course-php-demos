-- Unit 5, checkpoint 04, bridge-left. Write one SELECT statement below.
SELECT Department.name AS dept, Employee.name, Employee.surname
FROM Department
    LEFT JOIN EmployeeDepartment ON EmployeeDepartment.departmentId = Department.id
    LEFT JOIN Employee ON Employee.id = EmployeeDepartment.employeeId;
