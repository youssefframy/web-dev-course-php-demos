-- Unit 5, checkpoint 04, lucy. Write one SELECT statement below.
SELECT LeaveBooking.dateFrom, LeaveBooking.dateTo, Employee.name, Employee.surname
FROM LeaveBooking
    LEFT JOIN Employee ON Employee.id = LeaveBooking.employeeId;
