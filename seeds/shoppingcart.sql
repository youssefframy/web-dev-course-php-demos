-- The shopping cart database used in Unit 8 of Module 5 (PHP).
-- Written for this course: the slides point at a gist, and this file has the same four products
-- and the tables the project needs. There is no CREATE DATABASE or USE, so ./sql can load it into any database.
-- Load it with: ./sql reset shoppingcart

CREATE TABLE Product (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    price           DECIMAL(10,2) NOT NULL,
    description     VARCHAR(255) NOT NULL,
    featuredImage   VARCHAR(100) NULL,
    requiresDeposit BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE User (
    id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstName   VARCHAR(50) NULL,
    lastName    VARCHAR(50) NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    accessLevel VARCHAR(20) NOT NULL DEFAULT 'standard'
);

CREATE TABLE Cart (
    id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId  INT NOT NULL,
    status  ENUM('PENDING', 'CHECKOUT') NOT NULL DEFAULT 'PENDING',
    FOREIGN KEY (userId) REFERENCES User(id)
);

CREATE TABLE CartItem (
    id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cartId      INT NOT NULL,
    productId   INT NOT NULL,
    price       DECIMAL(10,2) NOT NULL,
    qty         INT NOT NULL,
    FOREIGN KEY (cartId) REFERENCES Cart(id) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES Product(id),
    UNIQUE (cartId, productId)
);

INSERT INTO Product (name, price, description, featuredImage, requiresDeposit) VALUES
('Getting Started with React', 90, 'Get quickly up and running with React and create a profile website in just 3 hours!', 'masterclass_react.png', FALSE),
('AI with TensorFlow & Python', 90, 'Create a machine learning model using Python and TensorFlow, focusing on image recognition and classification.', 'masterclass_ai.png', FALSE),
('Game Development with Unity', 360, 'Create a clone of the popular 2D platformer featuring an Italian plumber, starting from scratch.', 'masterclass_game.png', TRUE),
('Introduction to CSS FlexBox', 90, 'FlexBox can revolutionise how you create responsive websites. Learn how in just 3 hours!', 'masterclass_css.png', FALSE);
