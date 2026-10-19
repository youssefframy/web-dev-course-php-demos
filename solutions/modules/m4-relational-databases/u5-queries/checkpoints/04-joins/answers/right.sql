-- Unit 5, checkpoint 04, right. Write one SELECT statement below.
SELECT Employee.name, Employee.surname, Department.name AS dept
FROM Employee
    RIGHT JOIN Department ON Department.id = Employee.deptId;
