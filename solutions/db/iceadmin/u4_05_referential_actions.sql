-- Unit 4, checkpoint 05, step 3. Allow NULL in Vendor.brandManager, re-create the four constraints, then delete Lucy.
ALTER TABLE Salary DROP CONSTRAINT c_salary_employee;

ALTER TABLE Salary
    ADD CONSTRAINT c_salary_employee
    FOREIGN KEY (employeeId) REFERENCES Employee (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE EmployeeDepartment DROP CONSTRAINT c_employeedepartment_employee;

ALTER TABLE EmployeeDepartment
    ADD CONSTRAINT c_employeedepartment_employee
    FOREIGN KEY (employeeId) REFERENCES Employee (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE Vendor
    CHANGE COLUMN brandManager
    brandManager INT NULL;

ALTER TABLE Vendor DROP CONSTRAINT c_vendor_employee;

ALTER TABLE Vendor
    ADD CONSTRAINT c_vendor_employee
    FOREIGN KEY (brandManager) REFERENCES Employee (id)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE LeaveBooking DROP CONSTRAINT c_leavebooking_employee;

ALTER TABLE LeaveBooking
    ADD CONSTRAINT c_leavebooking_employee
    FOREIGN KEY (employeeId) REFERENCES Employee (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

DELETE FROM Employee WHERE id = 2;
