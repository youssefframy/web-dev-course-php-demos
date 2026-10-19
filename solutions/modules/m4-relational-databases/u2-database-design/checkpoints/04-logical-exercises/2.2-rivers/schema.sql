-- Exercise 2.2: Rivers (physical form of the logical solution).

CREATE TABLE Mouth
(
  id   INT                  NOT NULL AUTO_INCREMENT,
  name VARCHAR(100)         NOT NULL,
  type ENUM('sea', 'lake')  NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE River
(
  id          INT          NOT NULL AUTO_INCREMENT,
  name        VARCHAR(100) NOT NULL,
  totalLength DECIMAL(8,1) NOT NULL,
  mouthId     INT          NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE State
(
  id          INT          NOT NULL AUTO_INCREMENT,
  name        VARCHAR(50)  NOT NULL,
  capitalCity VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE RiverState
(
  id      INT          NOT NULL AUTO_INCREMENT,
  riverId INT          NOT NULL,
  stateId INT          NOT NULL,
  length  DECIMAL(8,1) NOT NULL,
  PRIMARY KEY (id)
);

-- Each neighbouring pair is stored once, with the smaller id first.
CREATE TABLE StateNeighbour
(
  id               INT NOT NULL AUTO_INCREMENT,
  stateId          INT NOT NULL,
  neighbourStateId INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT CK_StateNeighbour_order CHECK (stateId < neighbourStateId)
);

ALTER TABLE River
  ADD CONSTRAINT FK_Mouth_TO_River
    FOREIGN KEY (mouthId) REFERENCES Mouth (id);

ALTER TABLE RiverState
  ADD CONSTRAINT FK_River_TO_RiverState
    FOREIGN KEY (riverId) REFERENCES River (id);

ALTER TABLE RiverState
  ADD CONSTRAINT FK_State_TO_RiverState
    FOREIGN KEY (stateId) REFERENCES State (id);

ALTER TABLE StateNeighbour
  ADD CONSTRAINT FK_State_TO_StateNeighbour
    FOREIGN KEY (stateId) REFERENCES State (id);

ALTER TABLE StateNeighbour
  ADD CONSTRAINT FK_State_TO_StateNeighbour_neighbour
    FOREIGN KEY (neighbourStateId) REFERENCES State (id);

CREATE UNIQUE INDEX UQ_State_name ON State (name);
CREATE UNIQUE INDEX UQ_RiverState ON RiverState (riverId, stateId);
CREATE UNIQUE INDEX UQ_StateNeighbour ON StateNeighbour (stateId, neighbourStateId);
