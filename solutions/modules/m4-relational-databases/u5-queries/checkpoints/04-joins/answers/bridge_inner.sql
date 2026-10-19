-- Unit 5, checkpoint 04, bridge-inner. Write one SELECT statement below.
SELECT Employee.name, Employee.surname, Department.name AS dept
FROM Employee
    JOIN EmployeeDepartment ON EmployeeDepartment.employeeId = Employee.id
    JOIN Department ON Department.id = EmployeeDepartment.departmentId;
