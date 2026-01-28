-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS shop;
USE shop;

-- ตาราง Products
CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(50) NOT NULL,
    Picture VARCHAR(100) NOT NULL,
    Category VARCHAR(50) NOT NULL,
    ProductDescription VARCHAR(250),
    Price INT(4) NOT NULL,
    QuantityStock INT(3) NOT NULL
);

-- ตาราง Customers
CREATE TABLE Customers (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerName VARCHAR(50) NOT NULL,
    AddressLine1 VARCHAR(50) NOT NULL,
    City VARCHAR(50),
    Country VARCHAR(50) NOT NULL,
    PostalCode VARCHAR(5) NOT NULL,
    MobilePhone VARCHAR(12) NOT NULL
);

-- ตาราง ShippingCompany
CREATE TABLE ShippingCompany (
    ShippingCompanyID INT AUTO_INCREMENT PRIMARY KEY,
    CompanyName VARCHAR(50) NOT NULL,
    Address VARCHAR(50) NOT NULL,
    City VARCHAR(50),
    Country VARCHAR(50) NOT NULL
);

-- ตาราง Orders
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    ShippingCompanyID INT NOT NULL,
    OrderDateTime DATETIME NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
    FOREIGN KEY (ShippingCompanyID) REFERENCES ShippingCompany(ShippingCompanyID)
);

-- ตาราง OrderLine
CREATE TABLE OrderLine (
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT(3) NOT NULL,
    PRIMARY KEY (OrderID, ProductID),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

-- เพิ่มข้อมูลตัวอย่าง Products
INSERT INTO Products (ProductName, Picture, Category, ProductDescription, Price, QuantityStock) VALUES
('LIFX', 'https://www.muzzley.com/uploads/devices/main_56001ea6215708f9755113ed.png', 'Lighting', 'The brightest, most efficient Wi-Fi LED light bulb.', 150, 48),
('Amazon Echo', 'https://www.muzzley.com/uploads/devices/main_56f44bc87570537d86229100.png', 'Voice', 'Use your voice to control your Muzzley devices.', 151, 102),
('Nest Learning Thermostat', 'https://www.muzzley.com/uploads/devices/main_56001d0b215708f9755113e4.jpg', 'Thermostats', 'Set the perfect temperature and save money while you are away.', 152, 77),
('Nest Protect: Smoke + Carbon Monoxide', 'https://www.muzzley.com/uploads/devices/main_56097e0b6cab5e733821638e.jpg', 'Detectors & Sensors', 'It speaks up to tell you if there is smoke or CO and tells you where the problem is so you know what to do.', 168, 145),
('ecobee3', 'https://www.muzzley.com/uploads/devices/main_5605eade6cab5e733821637b.png', 'Thermostats', 'ecobee3 is an Apple HomeKit enabled smart thermostat with wireless remote sensors.', 169, 80);

-- เพิ่มข้อมูลตัวอย่าง Customers
INSERT INTO Customers (CustomerName, AddressLine1, City, Country, PostalCode, MobilePhone) VALUES
('Consectetuer Limited', 'Ap #956-8282 Bibendum Rd.', 'Kabul', 'Afghanistan', '68684', '076-241-2315'),
('Vitae Diam Inc.', '9313 Ac Rd.', 'Tirana', 'Albania', '45310', '081-785-6157'),
('Enim Industries', 'Ap #752-2131 Lorem Street', 'Pago', 'American', '25452', '085-313-0230'),
('Mi Corp.', 'P.O. Box 748, Sodales. Rd.', 'Andorra la', 'Andorra', '74782', '076-799-3738'),
('Cras Eget Industries', '250-2844 Commodo Rd.', 'St. Johns', 'Antigua', '53029', '085-585-9934');

-- เพิ่มข้อมูลตัวอย่าง ShippingCompany
INSERT INTO ShippingCompany (CompanyName, Address, City, Country) VALUES
('Vitae Risus LLP', '1447 Sit Rd.', 'Broken Arrow', 'Svalbard and Jan Mayen Islands'),
('Magna Praesent PC', 'Ap #165-2329 Lectus Rd.', 'Halifax', 'Holy See (Vatican City State)'),
('Volutpat Ornare Incorporated', 'P.O. Box 867, 4523 Felis Av.', 'Fochabers', 'Burundi'),
('Etiam Vestibulum Massa Limited', '810-6031 Lacus. Road', 'Bear', 'Saint Lucia'),
('Dictum Eu Eleifend PC', '606 Tellus Rd.', 'Pike Creek', 'Bangladesh');
