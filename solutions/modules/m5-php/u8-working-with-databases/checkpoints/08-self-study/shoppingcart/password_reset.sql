-- Extra credit for Unit 8: the table behind the forgot password flow.
-- Run it against the shopping cart database: ./sql shell shoppingcart < password_reset.sql
-- (or paste it into phpMyAdmin). Only a hash of each token is stored.

CREATE TABLE IF NOT EXISTS PasswordReset (
    id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId      INT NOT NULL,
    tokenHash   CHAR(64) NOT NULL UNIQUE,
    expiresAt   DATETIME NOT NULL,
    used        BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
);
