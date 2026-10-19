-- Unit 4, checkpoint 02, step 2. This runs on a scratch copy, never on your iceadmin.
-- Create LeaveBooking, insert the rows including the one for employee 4, then add the foreign key.
CREATE TABLE LeaveBooking
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dateFrom DATE NOT NULL,
    dateTo DATE NOT NULL,
    employeeId INT NOT NULL
);

INSERT INTO LeaveBooking (dateFrom, dateTo, employeeId)
VALUES
    ('2024-01-01', '2024-01-02', 2),
    ('2024-08-12', '2024-08-16', 2),
    ('2024-02-01', '2024-02-03', 1),
    ('2024-03-01', '2024-03-15', 3),
    ('2023-12-27', '2023-12-29', 3),
    ('2024-01-10', '2024-01-10', 4);

-- Fails with 1452: employee 4 does not exist.
ALTER TABLE LeaveBooking
    ADD CONSTRAINT c_leavebooking_employee
    FOREIGN KEY (employeeId) REFERENCES Employee (id);
