-- Unit 5, checkpoint 05, column-subquery. Write one SELECT statement below.
SELECT
    Employee.name,
    Employee.surname,
    (SELECT Department.name FROM Department WHERE Department.id = Employee.deptId) AS dept
FROM Employee;
