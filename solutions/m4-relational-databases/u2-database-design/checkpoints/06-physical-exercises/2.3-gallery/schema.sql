-- Exercise 2.3: Gallery.

CREATE TABLE ArtStyle
(
  id   INT         NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Artist
(
  id          INT          NOT NULL AUTO_INCREMENT,
  name        VARCHAR(50)  NOT NULL,
  surname     VARCHAR(50)  NULL,
  birthPlace  VARCHAR(100) NOT NULL,
  dateOfBirth DATE         NULL,
  artStyleId  INT          NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ArtType
(
  id   INT         NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Artwork
(
  id          INT           NOT NULL AUTO_INCREMENT,
  title       VARCHAR(150)  NOT NULL,
  yearCreated SMALLINT      NULL,
  price       DECIMAL(10,2) NOT NULL,
  artTypeId   INT           NOT NULL,
  artistId    INT           NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ArtGroup
(
  id   INT          NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ArtworkGroup
(
  id         INT NOT NULL AUTO_INCREMENT,
  artworkId  INT NOT NULL,
  artGroupId INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Customer
(
  id           INT           NOT NULL AUTO_INCREMENT,
  name         VARCHAR(50)   NOT NULL,
  surname      VARCHAR(50)   NOT NULL,
  addressLine1 VARCHAR(100)  NOT NULL,
  addressLine2 VARCHAR(100)  NULL,
  city         VARCHAR(50)   NOT NULL,
  country      VARCHAR(50)   NOT NULL,
  totalSpent   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (id)
);

CREATE TABLE CustomerArtist
(
  id         INT NOT NULL AUTO_INCREMENT,
  customerId INT NOT NULL,
  artistId   INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE CustomerGroup
(
  id         INT NOT NULL AUTO_INCREMENT,
  customerId INT NOT NULL,
  artGroupId INT NOT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE Artist
  ADD CONSTRAINT FK_ArtStyle_TO_Artist
    FOREIGN KEY (artStyleId) REFERENCES ArtStyle (id);

ALTER TABLE Artwork
  ADD CONSTRAINT FK_ArtType_TO_Artwork
    FOREIGN KEY (artTypeId) REFERENCES ArtType (id);

ALTER TABLE Artwork
  ADD CONSTRAINT FK_Artist_TO_Artwork
    FOREIGN KEY (artistId) REFERENCES Artist (id);

ALTER TABLE ArtworkGroup
  ADD CONSTRAINT FK_Artwork_TO_ArtworkGroup
    FOREIGN KEY (artworkId) REFERENCES Artwork (id);

ALTER TABLE ArtworkGroup
  ADD CONSTRAINT FK_ArtGroup_TO_ArtworkGroup
    FOREIGN KEY (artGroupId) REFERENCES ArtGroup (id);

ALTER TABLE CustomerArtist
  ADD CONSTRAINT FK_Customer_TO_CustomerArtist
    FOREIGN KEY (customerId) REFERENCES Customer (id);

ALTER TABLE CustomerArtist
  ADD CONSTRAINT FK_Artist_TO_CustomerArtist
    FOREIGN KEY (artistId) REFERENCES Artist (id);

ALTER TABLE CustomerGroup
  ADD CONSTRAINT FK_Customer_TO_CustomerGroup
    FOREIGN KEY (customerId) REFERENCES Customer (id);

ALTER TABLE CustomerGroup
  ADD CONSTRAINT FK_ArtGroup_TO_CustomerGroup
    FOREIGN KEY (artGroupId) REFERENCES ArtGroup (id);

CREATE UNIQUE INDEX UQ_ArtStyle_name ON ArtStyle (name);
CREATE UNIQUE INDEX UQ_ArtType_name ON ArtType (name);
CREATE UNIQUE INDEX UQ_ArtGroup_name ON ArtGroup (name);
CREATE UNIQUE INDEX UQ_ArtworkGroup ON ArtworkGroup (artworkId, artGroupId);
CREATE UNIQUE INDEX UQ_CustomerArtist ON CustomerArtist (customerId, artistId);
CREATE UNIQUE INDEX UQ_CustomerGroup ON CustomerGroup (customerId, artGroupId);
