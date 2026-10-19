-- Unit 4, checkpoint 03, step 3. This runs on a scratch copy, never on your iceadmin.
-- Create Vendor with name VARCHAR(20), then insert the four vendors in one INSERT.
CREATE TABLE Vendor
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    website VARCHAR(50) NOT NULL,
    country VARCHAR(30) NOT NULL,
    brandManager INT NOT NULL,
    CONSTRAINT c_vendor_employee FOREIGN KEY (brandManager) REFERENCES Employee (id)
);

-- Fails with 1406: "Linux Professional Institute" is 28 characters.
INSERT INTO Vendor (name, website, country, brandManager)
VALUES
    ('Microsoft', 'https://microsoft.com', 'USA', 3),
    ('Linux Professional Institute', 'https://lpi.org', 'USA', 3),
    ('Digital Marketing Institute', 'https://digitalmarketinginstitute.com', 'Ireland', 2),
    ('Adobe', 'https://adobe.com', 'USA', 1);
