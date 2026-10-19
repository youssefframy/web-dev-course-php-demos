-- tvstudio as Unit 4 expects to find it: the Exercise 2.4 physical design with data.
-- Load it with: ./sql reset tvstudio --at m4u3
--
-- TODO: build this from the Unit 3 checkpoint files once Unit 3 has checkpoints.
-- Until then the structure is the Exercise 2.4 reference design, and the data is representative.
-- Table and column names here are the ones every later check expects.

CREATE TABLE Series
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Actor
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    dateOfBirth DATE NULL,
    PRIMARY KEY (id)
);

CREATE TABLE SeriesActor
(
    id INT NOT NULL AUTO_INCREMENT,
    seriesId INT NOT NULL,
    actorId INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Director
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    dateOfBirth DATE NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Episode
(
    id INT NOT NULL AUTO_INCREMENT,
    season SMALLINT NOT NULL DEFAULT 1,
    number SMALLINT NOT NULL,
    title VARCHAR(100) NOT NULL,
    seriesId INT NOT NULL,
    directorId INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Station
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Transmission
(
    id INT NOT NULL AUTO_INCREMENT,
    airDateTime DATETIME NOT NULL,
    episodeId INT NOT NULL,
    stationId INT NOT NULL,
    PRIMARY KEY (id)
);

ALTER TABLE SeriesActor
    ADD CONSTRAINT FK_Series_TO_SeriesActor FOREIGN KEY (seriesId) REFERENCES Series (id);

ALTER TABLE SeriesActor
    ADD CONSTRAINT FK_Actor_TO_SeriesActor FOREIGN KEY (actorId) REFERENCES Actor (id);

ALTER TABLE Episode
    ADD CONSTRAINT FK_Series_TO_Episode FOREIGN KEY (seriesId) REFERENCES Series (id);

ALTER TABLE Episode
    ADD CONSTRAINT FK_Director_TO_Episode FOREIGN KEY (directorId) REFERENCES Director (id);

ALTER TABLE Transmission
    ADD CONSTRAINT FK_Episode_TO_Transmission FOREIGN KEY (episodeId) REFERENCES Episode (id);

ALTER TABLE Transmission
    ADD CONSTRAINT FK_Station_TO_Transmission FOREIGN KEY (stationId) REFERENCES Station (id);

CREATE UNIQUE INDEX UQ_SeriesActor ON SeriesActor (seriesId, actorId);
CREATE UNIQUE INDEX UQ_Episode_number ON Episode (seriesId, season, number);
CREATE UNIQUE INDEX UQ_Station_name ON Station (name);
CREATE UNIQUE INDEX UQ_Transmission_slot ON Transmission (stationId, airDateTime);

INSERT INTO Series (name)
VALUES
    ('Harbour Lights'),
    ('The Salt Pans'),
    ('Kitchen Wars');

INSERT INTO Actor (name, surname, dateOfBirth)
VALUES
    ('Maria', 'Borg', '1985-03-14'),
    ('Daniel', 'Camilleri', '1979-11-02'),
    ('Sarah', 'Vella', '1992-06-21'),
    ('Mark', 'Zammit', NULL),
    ('Anna', 'Farrugia', '1988-01-30');

-- Maria Borg appears in two series.
INSERT INTO SeriesActor (seriesId, actorId)
VALUES
    (1, 1),
    (1, 2),
    (2, 1),
    (2, 3),
    (3, 4),
    (3, 5);

INSERT INTO Director (name, surname, dateOfBirth)
VALUES
    ('Paul', 'Grech', '1970-08-09'),
    ('Claire', 'Debono', '1983-12-01'),
    ('Ian', 'Spiteri', NULL);

INSERT INTO Episode (season, number, title, seriesId, directorId)
VALUES
    (1, 1, 'The Breakwater', 1, 1),
    (1, 2, 'Low Tide', 1, 1),
    (1, 3, 'Fog Bank', 1, 2),
    (1, 1, 'First Harvest', 2, 2),
    (1, 2, 'Brine', 2, 2),
    (1, 1, 'Heat', 3, 3),
    (1, 2, 'Service', 3, 3),
    (2, 1, 'New Menu', 3, 3);

INSERT INTO Station (name)
VALUES
    ('TVM'),
    ('ONE'),
    ('F Living');

INSERT INTO Transmission (airDateTime, episodeId, stationId)
VALUES
    ('2024-01-08 20:30:00', 1, 1),
    ('2024-01-15 20:30:00', 2, 1),
    ('2024-01-22 20:30:00', 3, 1),
    ('2024-02-03 21:00:00', 1, 2),
    ('2024-02-05 19:00:00', 4, 2),
    ('2024-02-12 19:00:00', 5, 2),
    ('2024-02-20 18:00:00', 6, 3),
    ('2024-03-05 18:00:00', 7, 3);
