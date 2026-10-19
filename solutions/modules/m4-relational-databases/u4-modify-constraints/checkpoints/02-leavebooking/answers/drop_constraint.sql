-- Unit 4, checkpoint 02, step 4. This runs on a scratch copy, never on your iceadmin.
-- Drop c_leavebooking_employee from the table it belongs to.
ALTER TABLE LeaveBooking
    DROP CONSTRAINT c_leavebooking_employee;
