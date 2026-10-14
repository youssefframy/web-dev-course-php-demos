-- SQL dump generated using DBML (dbml.dbdiagram.io)
-- Database: MySQL
-- Generated at: 2026-10-14T16:07:48.010Z

CREATE TABLE `Employee` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL,
  `surname` VARCHAR(30) NOT NULL,
  `jobTitle` VARCHAR(15) NOT NULL,
  `basis` ENUM ('PT', 'FT') DEFAULT 'FT'
);

CREATE TABLE `Department` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL
);

CREATE TABLE `Salary` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `fromDate` DATE NOT NULL,
  `toDate` DATE COMMENT 'NULL means this is the current salary',
  `amount` FLOAT NOT NULL,
  `employeeId` INT NOT NULL
);

CREATE TABLE `EmployeeDepartment` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `employeeId` INT NOT NULL,
  `departmentId` INT NOT NULL
);

ALTER TABLE `Salary` ADD CONSTRAINT `c_salary_employee` FOREIGN KEY (`employeeId`) REFERENCES `Employee` (`id`);

ALTER TABLE `EmployeeDepartment` ADD CONSTRAINT `c_employeedepartment_employee` FOREIGN KEY (`employeeId`) REFERENCES `Employee` (`id`);

ALTER TABLE `EmployeeDepartment` ADD CONSTRAINT `c_employeedepartment_department` FOREIGN KEY (`departmentId`) REFERENCES `Department` (`id`);
