-- No undo. In MariaDB/MySQL, DDL (ALTER, TRUNCATE, DROP) auto-commits, so ROLLBACK cannot undo it.

USE IceAdmin;

CREATE TABLE ScratchDemo
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    age INT,
    note VARCHAR(10)
);

ALTER TABLE ScratchDemo CHANGE COLUMN age age INT NOT NULL;

ALTER TABLE ScratchDemo DROP note;

INSERT INTO ScratchDemo (age)
VALUES
    (20),
    (35);

TRUNCATE TABLE ScratchDemo;

DROP TABLE ScratchDemo;
