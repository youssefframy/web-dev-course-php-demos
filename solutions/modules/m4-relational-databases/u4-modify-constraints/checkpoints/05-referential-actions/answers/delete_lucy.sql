-- Unit 4, checkpoint 05, step 1. This runs on a scratch copy, never on your iceadmin.
-- Fails with 1451: Salary, EmployeeDepartment, LeaveBooking and Vendor rows refer to Lucy.
DELETE FROM Employee WHERE id = 2;
