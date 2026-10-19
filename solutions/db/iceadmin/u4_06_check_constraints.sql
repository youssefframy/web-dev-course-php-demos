-- Unit 4, checkpoint 06, step 1. Add c_leavebooking_dates and c_salary_amount.
ALTER TABLE LeaveBooking
    ADD CONSTRAINT c_leavebooking_dates CHECK (dateTo >= dateFrom);

ALTER TABLE Salary
    ADD CONSTRAINT c_salary_amount CHECK (amount > 0);
