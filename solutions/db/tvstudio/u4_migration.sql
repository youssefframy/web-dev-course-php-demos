-- Unit 4, checkpoint 07. Your migration: ALTER TABLE statements only, applied on top of tvstudio after Unit 3.

-- 1. Running time and age rating. Existing episodes have neither yet.
ALTER TABLE Episode
    ADD runtimeMinutes SMALLINT UNSIGNED NULL,
    ADD ageRating ENUM('U', 'PG', '12', '15', '18') NULL;

-- 2. Longer station names. Still required, and the unique index stays.
ALTER TABLE Station
    CHANGE COLUMN name
    name VARCHAR(100) NOT NULL;

-- 3. A deleted director leaves their episodes with no director.
ALTER TABLE Episode
    CHANGE COLUMN directorId
    directorId INT NULL;

ALTER TABLE Episode DROP CONSTRAINT FK_Director_TO_Episode;

ALTER TABLE Episode
    ADD CONSTRAINT FK_Director_TO_Episode
    FOREIGN KEY (directorId) REFERENCES Director (id)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

-- 4. A deleted series takes its episodes, cast links and transmissions with it.
ALTER TABLE Episode DROP CONSTRAINT FK_Series_TO_Episode;

ALTER TABLE Episode
    ADD CONSTRAINT FK_Series_TO_Episode
    FOREIGN KEY (seriesId) REFERENCES Series (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE SeriesActor DROP CONSTRAINT FK_Series_TO_SeriesActor;

ALTER TABLE SeriesActor
    ADD CONSTRAINT FK_Series_TO_SeriesActor
    FOREIGN KEY (seriesId) REFERENCES Series (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE Transmission DROP CONSTRAINT FK_Episode_TO_Transmission;

ALTER TABLE Transmission
    ADD CONSTRAINT FK_Episode_TO_Transmission
    FOREIGN KEY (episodeId) REFERENCES Episode (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
