-- Unit 4, checkpoint 04, steps 2 and 4. This runs on a scratch copy, never on your iceadmin.
-- TRUNCATE is safe on a table that no foreign key references, when you really mean to remove every row.
TRUNCATE TABLE LeaveBooking;

-- The counter was reset, so this booking gets id 1. After DELETE FROM LeaveBooking it would get id 6.
INSERT INTO LeaveBooking (dateFrom, dateTo, employeeId)
VALUES ('2024-06-03', '2024-06-04', 1);
