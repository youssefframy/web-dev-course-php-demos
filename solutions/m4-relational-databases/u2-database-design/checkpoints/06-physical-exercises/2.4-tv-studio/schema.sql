-- Exercise 2.4: TV studio.

CREATE TABLE Series
(
  id   INT          NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Actor
(
  id          INT         NOT NULL AUTO_INCREMENT,
  name        VARCHAR(50) NOT NULL,
  surname     VARCHAR(50) NOT NULL,
  dateOfBirth DATE        NULL,
  PRIMARY KEY (id)
);

CREATE TABLE SeriesActor
(
  id       INT NOT NULL AUTO_INCREMENT,
  seriesId INT NOT NULL,
  actorId  INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Director
(
  id          INT         NOT NULL AUTO_INCREMENT,
  name        VARCHAR(50) NOT NULL,
  surname     VARCHAR(50) NOT NULL,
  dateOfBirth DATE        NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Episode
(
  id         INT          NOT NULL AUTO_INCREMENT,
  season     SMALLINT     NOT NULL DEFAULT 1,
  number     SMALLINT     NOT NULL,
  title      VARCHAR(100) NOT NULL,
  seriesId   INT          NOT NULL,
  directorId INT          NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Station
(
  id   INT         NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Transmission
(
  id          INT      NOT NULL AUTO_INCREMENT,
  airDateTime DATETIME NOT NULL,
  episodeId   INT      NOT NULL,
  stationId   INT      NOT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE SeriesActor
  ADD CONSTRAINT FK_Series_TO_SeriesActor
    FOREIGN KEY (seriesId) REFERENCES Series (id);

ALTER TABLE SeriesActor
  ADD CONSTRAINT FK_Actor_TO_SeriesActor
    FOREIGN KEY (actorId) REFERENCES Actor (id);

ALTER TABLE Episode
  ADD CONSTRAINT FK_Series_TO_Episode
    FOREIGN KEY (seriesId) REFERENCES Series (id);

ALTER TABLE Episode
  ADD CONSTRAINT FK_Director_TO_Episode
    FOREIGN KEY (directorId) REFERENCES Director (id);

ALTER TABLE Transmission
  ADD CONSTRAINT FK_Episode_TO_Transmission
    FOREIGN KEY (episodeId) REFERENCES Episode (id);

ALTER TABLE Transmission
  ADD CONSTRAINT FK_Station_TO_Transmission
    FOREIGN KEY (stationId) REFERENCES Station (id);

CREATE UNIQUE INDEX UQ_SeriesActor ON SeriesActor (seriesId, actorId);
CREATE UNIQUE INDEX UQ_Episode_number ON Episode (seriesId, season, number);
CREATE UNIQUE INDEX UQ_Station_name ON Station (name);
CREATE UNIQUE INDEX UQ_Transmission_slot ON Transmission (stationId, airDateTime);
