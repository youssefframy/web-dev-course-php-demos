-- The POS database used in Unit 5, vendored from
-- https://gist.github.com/icemaltacode/c119c1576088d2217ad13d5823a130f0
-- with CREATE DATABASE and USE removed, so ./sql can load it into any database.
-- Everything else, including the typos in the data, is as published, so results match the slides.
-- Load it with: ./sql reset pos

-- Create Structure --

CREATE TABLE PaymentType(
	id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    typeName	VARCHAR(20) NOT NULL
);

CREATE TABLE Cashier(
	id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name		VARCHAR(20) NOT NULL,
    surname		VARCHAR(20) NOT NULL
);

CREATE TABLE Client(
	id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name		VARCHAR(20) NOT NULL,
    surname		VARCHAR(20) NOT NULL
);

CREATE TABLE Category(
	id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    catCode		VARCHAR(9) UNIQUE NOT NULL,
    catName		VARCHAR(20) UNIQUE NOT NULL,
    description VARCHAR(200)
);

CREATE TABLE Invoice(
	id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    total		DECIMAL(10,2) NOT NULL,
    cashierId	INT NOT NULL,
    clientId	INT NOT NULL,
    issueTime	DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT c_invoice_cashier
		FOREIGN KEY(cashierId) REFERENCES Cashier(id)
        ON UPDATE CASCADE,
	CONSTRAINT c_invoice_client
		FOREIGN KEY(clientId) REFERENCES Client(id)
        ON UPDATE CASCADE
);

CREATE TABLE Payment(
	id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    issueTime		DATETIME NOT NULL DEFAULT NOW(),
    cashierId		INT NOT NULL,
    clientId		INT NOT NULL,
    paymentTypeId	INT NOT NULL,
    total			DECIMAL(10,2) NOT NULL,
    CONSTRAINT c_payment_cashier
		FOREIGN KEY(cashierId) REFERENCES Cashier(id)
        ON UPDATE CASCADE,
	CONSTRAINT c_payment_client
		FOREIGN KEY(clientId) REFERENCES Client(id)
        ON UPDATE CASCADE,
	CONSTRAINT c_payment_paymenttype
		FOREIGN KEY(paymentTypeId) REFERENCES PaymentType(id)
        ON UPDATE CASCADE
);

CREATE TABLE Item(
	id				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    stockCode		VARCHAR(15) UNIQUE NOT NULL,
    name			VARCHAR(60) NOT NULL,
    description		VARCHAR(200) NOT NULL,
    price			DECIMAL(10,2) NOT NULL,
    categoryId		INT NOT NULL,
    CONSTRAINT c_item_category
		FOREIGN KEY(categoryId) REFERENCES Category(id)
        ON UPDATE CASCADE
);

CREATE TABLE InvoiceItem(
	id				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    invoiceId		INT NOT NULL,
    unitPrice		DECIMAL(10,2) NOT NULL,
    qty				INT NOT NULL DEFAULT 1,
    itemId			INT NOT NULL,
    CONSTRAINT c_invoiceitem_invoice
		FOREIGN KEY(invoiceId) REFERENCES Invoice(id),
	CONSTRAINT c_invoiceitem_item
		FOREIGN KEY(itemId) REFERENCES Item(id)
);

CREATE TABLE PaymentAllocation(
	id				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    paymentId		INT NOT NULL,
    invoiceItemId	INT NOT NULL,
    amount			DECIMAL(10,2) NOT NULL,
    CONSTRAINT c_paymentallocation_payment
		FOREIGN KEY(paymentId) REFERENCES Payment(id),
	CONSTRAINT c_paymentallocation_invoiceitem
		FOREIGN KEY(invoiceItemId) REFERENCES InvoiceItem(id)
);

-- INSERT DATA --
INSERT INTO Cashier(name, surname) VALUES
	('Kristina', 'Grech'),
    ('Roger', 'Vassallo');
    
INSERT INTO PaymentType(typeName) VALUES
	('Cash'), ('Cheque'), ('Credit'), ('Online');
    
INSERT INTO Client(name, surname) VALUES 
	('Lucy', 'Pace'),
    ('Lorraine', 'Pulis'),
    ('Trevor', 'Musu'),
    ('Rachel', 'Bonavia'),
    ('Francesco', 'Rossi'),
    ('Brandon', 'Pace');
    
INSERT INTO Category(catCode, catName, description) VALUES
	('CRK', 'Crockery', 'Plates, dishes, cups, and other similar items'),
    ('CUT', 'Cutlery', 'Forkes, knives, spoons, teaspoons and similar items'),
    ('APP', 'Appliances', 'Electrical and electronic appliances'),
    ('ENT', 'Entertainment', 'TVs, sound systems and toys');

INSERT INTO Item(stockCode, name, description, price, categoryId) VALUES
	('KNI-MAT-021', 'Matei Knife 21 Series', 'A sharp knife for meat trimming', 21.95, 2),
    ('SPN-MAT-X50', 'Matei X50 Spoon', 'Large spoon with rubber grip', 10.95, 2),
    ('PLT-HEL-92877', 'Helfing Bone China Plate', 'Bone china plate with flower and temple motif', 45.99, 1),
    ('MUG-NOV-5L', 'Novelty Inc. Maxi Mug 5L', 'Novelty 5 Litre Mug', 23.25, 1),
    ('TV-SON-X3456', 'Sony Bravia X3456', 'UHD 4K TV with HDR Support', 1099.99, 4),
    ('TST-RH-890', 'Russell Hobbs 890 Toaster', 'With 4-slice toasting', 100.00, 3),
    ('FRI-AR-B1', 'American Refridgeration B1', 'Large capacity fridge with ice maker', 1200.99, 3),
    ('MIC-HL-2', 'Microsoft Hololens 2', 'Virtual reality headset', 455.99, 4);
    
-- First Invoice --
-- Invoice is fully settled by one payment. Contains 1 item.
-- Invoice: id=1, total=1099.99, cashierId=Kristina Grech, clientId=Lorraine Pulis
-- InvoiceItem: id=1, invoiceId=1, unitPrice=1099.99, qty=1, itemid=TV-SON-X3456
-- Payment: id=1, cashierId=Kristina Grech, clientId=Lorraine Pulus, paymentTypeId=Credit, total=1099.99
-- PaymentAllocation: id=1, paymentId=1, invoiceItemId=1, amount=1099.99
INSERT INTO Invoice(total, cashierId, clientId, issueTime) VALUES 
	(1099.99, 1, 2, '2022-01-22 10:12:13');
INSERT INTO InvoiceItem(invoiceId, unitPrice, qty, itemId) VALUES 
	(1, 1099.99, 1, 5);
INSERT INTO Payment(issueTime, cashierId, clientId, paymentTypeId, total) VALUES 
	('2022-01-22 10:15:02', 1, 2, 3, 1099.99);
INSERT INTO PaymentAllocation(paymentId, invoiceItemId, amount) VALUES 
	(1, 1, 1099.99);

-- Second Invoice --
-- Invoice is pending (700.99). Contains 1 item.
-- Invoice: id=2, total=1200.99, cashierId=Roger Vassallo, clientId=Rachel Bonavia
-- InvoiceItem: id=2, invoiceId=2, unitPrice=1200.00, qty=1, itemId-FRI-AR-B1
-- Payment: id=2, cashierId=Roger Vassallo, clientId=Rachel Bonavia, paymenTypeId=Cash, total=500
-- PaymentAllocation: id=2, paymentId=2, invoiceItemId=2, amount=500
INSERT INTO Invoice(total, cashierId, clientId, issueTime) VALUES
	(1200.99, 2, 4, '2022-01-22 10:30:12');
INSERT INTO InvoiceItem(invoiceId, unitPrice, qty, itemId) VALUES 
	(2, 1200.99, 1, 7);
INSERT INTO Payment(issueTime, cashierId, clientId, paymentTypeId, total) VALUES
	('2022-01-22 10:42:12', 2, 4, 1, 500);
INSERT INTO PaymentAllocation(paymentId, invoiceItemId, amount) VALUES
	(2, 2, 500);
    
-- Third Invoice --
-- Invoice is pending (373.91). Contains two items (second item with qty 8).
-- Invoice: id=3, total=823.91, cashierId=Roger Vassallo, clientId=Lorraine Pulis
-- InvoiceItem: 
-- 		id=3, invoiceId=3, unitPrice=455.99, qty=1, itemId=MIC-HL-2
--      id=4, invoiceId=3, unitPrice=45.99, qty=8, itemId=PLT-HEL-92877
-- Payment: id=3, cashierId=Roger Vassallo, clientId=Lorraine Pulis, paymentTypeId=Cheque, total=450
-- PaymentAllocation:
--      id=3, paymentId=3, invoiceItemId=3, amount=250 (For MIC-HL-2)
--      id=4, paymentId=3, invoiceItemId=4, amount=200 (For PLT-HEL-92877 x8)
INSERT INTO Invoice(total, cashierId, clientId, issueTime) VALUES
	(823.91, 2, 2, '2022-01-22 13:59:22');
INSERT INTO InvoiceItem(invoiceId, unitPrice, qty, itemId) VALUES
	(3, 455.99, 1, 8),
    (3, 45.99, 8, 3);
INSERT INTO Payment(issueTime, cashierId, clientId, paymentTypeId, total) VALUES
	('2022-01-22 14:07:12', 2, 2, 2, 450);
INSERT INTO PaymentAllocation(paymentId, invoiceItemId, amount) VALUES
	(3, 3, 250),
    (3, 4, 200);