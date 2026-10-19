-- Exercise 2.1: Restaurant (physical form of the logical solution).

CREATE TABLE Category
(
  id               INT          NOT NULL AUTO_INCREMENT,
  name             VARCHAR(50)  NOT NULL,
  description      TEXT         NOT NULL,
  employeeInCharge VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE MenuItem
(
  id          INT           NOT NULL AUTO_INCREMENT,
  name        VARCHAR(100)  NOT NULL,
  description TEXT          NOT NULL,
  price       DECIMAL(10,2) NOT NULL,
  photoUrl    VARCHAR(255)  NULL,
  categoryId  INT           NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Ingredient
(
  id            INT           NOT NULL AUTO_INCREMENT,
  name          VARCHAR(100)  NOT NULL,
  amountInStock DECIMAL(10,3) NOT NULL DEFAULT 0,
  unit          VARCHAR(20)   NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE MenuItemIngredient
(
  id           INT           NOT NULL AUTO_INCREMENT,
  menuItemId   INT           NOT NULL,
  ingredientId INT           NOT NULL,
  amount       DECIMAL(10,3) NOT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE MenuItem
  ADD CONSTRAINT FK_Category_TO_MenuItem
    FOREIGN KEY (categoryId) REFERENCES Category (id);

ALTER TABLE MenuItemIngredient
  ADD CONSTRAINT FK_MenuItem_TO_MenuItemIngredient
    FOREIGN KEY (menuItemId) REFERENCES MenuItem (id);

ALTER TABLE MenuItemIngredient
  ADD CONSTRAINT FK_Ingredient_TO_MenuItemIngredient
    FOREIGN KEY (ingredientId) REFERENCES Ingredient (id);

CREATE UNIQUE INDEX UQ_Category_name ON Category (name);
CREATE UNIQUE INDEX UQ_Ingredient_name ON Ingredient (name);
CREATE UNIQUE INDEX UQ_MenuItemIngredient ON MenuItemIngredient (menuItemId, ingredientId);
