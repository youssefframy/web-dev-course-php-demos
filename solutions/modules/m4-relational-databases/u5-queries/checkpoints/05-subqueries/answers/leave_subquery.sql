-- Unit 5, checkpoint 05, leave-subquery. Write one SELECT statement below.
SELECT Employee.name, Employee.surname, T.dateFrom, T.dateTo
FROM Employee
    LEFT JOIN (
        SELECT LeaveBooking.dateFrom, LeaveBooking.dateTo, LeaveBooking.employeeId
        FROM LeaveBooking
        WHERE LeaveBooking.dateFrom > '2024-01-01'
    ) T ON T.employeeId = Employee.id;
