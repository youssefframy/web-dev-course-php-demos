-- Unit 4, checkpoint 06, step 3. This runs on a scratch copy, never on your iceadmin.
-- Fails with 4025: the booking ends before it starts.
INSERT INTO LeaveBooking (dateFrom, dateTo, employeeId)
VALUES ('2024-05-02', '2024-05-01', 1);
