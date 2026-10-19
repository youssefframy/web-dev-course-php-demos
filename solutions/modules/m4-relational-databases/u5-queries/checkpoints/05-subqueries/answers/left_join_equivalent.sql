-- Unit 5, checkpoint 05, left-join-equivalent. Write one SELECT statement below.
SELECT Employee.name, Employee.surname, Department.name AS dept
FROM Employee
    LEFT JOIN Department ON Department.id = Employee.deptId;
