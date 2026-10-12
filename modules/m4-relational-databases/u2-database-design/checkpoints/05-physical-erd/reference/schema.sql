-- ICEAdmin physical design (reference).
-- Written in the same shape as an ERD Editor export with the MariaDB dialect:
-- tables first, then foreign keys, then unique indexes.

CREATE TABLE Employee
(
  id       INT                NOT NULL AUTO_INCREMENT,
  name     VARCHAR(30)        NOT NULL,
  surname  VARCHAR(50)        NULL,
  jobTitle VARCHAR(30)        NOT NULL,
  basis    ENUM('FT', 'PT')   NOT NULL DEFAULT 'FT',
  PRIMARY KEY (id)
);

CREATE TABLE Department
(
  id   INT         NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Salary
(
  id         INT           NOT NULL AUTO_INCREMENT,
  fromDate   DATE          NOT NULL,
  toDate     DATE          NULL,
  amount     DECIMAL(10,2) NOT NULL,
  employeeId INT           NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE EmployeeDepartment
(
  id           INT NOT NULL AUTO_INCREMENT,
  employeeId   INT NOT NULL,
  departmentId INT NOT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE Salary
  ADD CONSTRAINT FK_Employee_TO_Salary
    FOREIGN KEY (employeeId) REFERENCES Employee (id);

ALTER TABLE EmployeeDepartment
  ADD CONSTRAINT FK_Employee_TO_EmployeeDepartment
    FOREIGN KEY (employeeId) REFERENCES Employee (id);

ALTER TABLE EmployeeDepartment
  ADD CONSTRAINT FK_Department_TO_EmployeeDepartment
    FOREIGN KEY (departmentId) REFERENCES Department (id);

CREATE UNIQUE INDEX UQ_EmployeeDepartment
  ON EmployeeDepartment (employeeId, departmentId);
