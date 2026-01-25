CREATE DATABASE Shop;
USE Shop;

-- 1. ตาราง Products [cite: 30]
CREATE TABLE Products (
    ProductID int AUTO_INCREMENT PRIMARY KEY,
    ProductName varchar(50) NOT NULL,
    Picture varchar(100) NOT NULL,
    Category varchar(50) NOT NULL,
    ProductDescription varchar(250),
    Price int(4) NOT NULL,
    QuantityStock int(3) NOT NULL
);

-- 2. ตาราง Customers 
CREATE TABLE Customers (
    CustomerID int AUTO_INCREMENT PRIMARY KEY,
    CustomerName varchar(50) NOT NULL,
    AddressLine1 varchar(50) NOT NULL,
    City varchar(50),
    Country varchar(50) NOT NULL,
    PostalCode varchar(5) NOT NULL,
    MobilePhone varchar(12) NOT NULL -- Pattern: 0xx-xxx-xxxx
);

-- 3. ตาราง ShippingCompany [cite: 36]
CREATE TABLE ShippingCompany (
    ShippingCompanyID int AUTO_INCREMENT PRIMARY KEY,
    CompanyName varchar(50) NOT NULL,
    Address varchar(50) NOT NULL,
    City varchar(50),
    Country varchar(50) NOT NULL
);

-- 4. ตาราง Order [cite: 38]
CREATE TABLE `Order` ( -- Order เป็นคำสงวน ให้ใช้ Backtick ครอบ
    OrderID int AUTO_INCREMENT PRIMARY KEY,
    CustomerID int NOT NULL,
    ShippingCompanyID int NOT NULL,
    OrderDateTime DATETIME NOT NULL
);

-- 5. ตาราง OrderLine [cite: 40]
CREATE TABLE OrderLine (
    OrderID int NOT NULL,
    ProductID int NOT NULL,
    Quantity int(3) NOT NULL,
    PRIMARY KEY (OrderID, ProductID)
);