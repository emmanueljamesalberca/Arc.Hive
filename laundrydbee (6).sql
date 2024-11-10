-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 02:34 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundrydbee`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, 'Fabcon'),
(3, 'Liquid Detergent'),
(4, 'Solane Gas');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Contact` int(11) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Gender` varchar(255) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `Name`, `Email`, `Contact`, `Address`, `Gender`, `Age`) VALUES
(1, 'Not Available', '', 0, '', 'Male', 0),
(2, 'Melissa', 'MelissaF@gmail.com', 218230, 'Somewhere Over the Rainbow', 'Female', 22),
(3, 'Donald Duck', 'donaldd@gmail.com', 10, 'Up in the sky!', 'Male', 20),
(4, 'Mav', 'mav@gmail.com', 192, 'Idk', 'Male', 22);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `ItemQuantity` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Price` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`ItemID`, `ItemName`, `ItemQuantity`, `CategoryID`, `Description`, `Price`) VALUES
(6, 'Surf', 100030, 3, 'A Cool Soap , TEST', 50.00),
(9, 'SuperSolane', 9, 1, 'DSDSDD', 1000.00),
(10, 'Tide', 29, 4, 'New item', 30.00),
(11, 'Downy', 152, 4, 'Hello', 25.00),
(12, 'Testnew', 10, 4, 'New', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `job-order`
--

CREATE TABLE `job-order` (
  `JobOrderID` int(11) NOT NULL,
  `ServiceID` int(11) NOT NULL,
  `Date` varchar(100) NOT NULL,
  `CustomerID` int(11) DEFAULT '0',
  `Weight` int(11) NOT NULL,
  `Total` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job-order`
--

INSERT INTO `job-order` (`JobOrderID`, `ServiceID`, `Date`, `CustomerID`, `Weight`, `Total`) VALUES
(42, 1, '2024-11-04 15:00:28', 3, 7, 4300.00),
(43, 1, '2024-11-04 15:09:44', NULL, 5, 1100.00),
(44, 1, '2024-11-04 15:28:33', 2, 6, 500.00),
(46, 4, '2024-11-05 03:33:10', 3, 8, 215.00),
(47, 1, '2024-11-05 09:06:21', 1, 8, 10355.00),
(48, 1, '2024-11-05 11:40:00', 2, 8, 1055.00),
(49, 1, '2024-11-05 14:36:11', 1, 8, 1555.00);

-- --------------------------------------------------------

--
-- Table structure for table `job-order-items`
--

CREATE TABLE `job-order-items` (
  `JobOrderID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `ItemQuantity` int(11) NOT NULL,
  `Subtotal` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job-order-items`
--

INSERT INTO `job-order-items` (`JobOrderID`, `ItemID`, `ItemQuantity`, `Subtotal`) VALUES
(42, 6, 4, 200.00),
(42, 9, 4, 4000.00),
(43, 9, 1, 1000.00),
(44, 6, 8, 250.00),
(44, 10, 5, 150.00),
(46, 10, 1, 30.00),
(46, 11, 1, 25.00),
(47, 6, 6, 300.00),
(47, 9, 10, 10000.00),
(48, 9, 1, 1000.00),
(49, 6, 30, 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `ServiceID` int(11) NOT NULL,
  `ServiceType` varchar(255) NOT NULL,
  `Price` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`ServiceID`, `ServiceType`, `Price`) VALUES
(1, 'Wash ONLY', 55.00),
(2, 'Dry ONLY', 65.00),
(3, 'Wash and Dry', 110.00),
(4, 'Wash, Dry and Fold', 160.00),
(5, 'Dry and Fold', 110.00);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(255) NOT NULL,
  `Contact` int(11) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SupplierID`, `SupplierName`, `Contact`, `Address`) VALUES
(1, 'SM', 919191, 'Hll jnahahewqe'),
(2, 'NCCC ', 218230, 'PROCTORIO'),
(3, 'Wings', 192929, 'Idk');

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

CREATE TABLE `supply` (
  `SupplierID` int(11) NOT NULL,
  `InvoiceID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `UserID` int(11) NOT NULL,
  `Total` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supply`
--

INSERT INTO `supply` (`SupplierID`, `InvoiceID`, `Date`, `UserID`, `Total`) VALUES
(2, 222, '2024-11-04', 1, 109.00),
(1, 9999, '2024-11-04', 1, 500.00),
(1, 88888, '2024-11-05', 1, 4700.00),
(2, 8989, '2024-11-05', 1, 100.00),
(2, 12222, '2024-11-05', 1, 450.00),
(2, 2444, '2024-11-05', 1, 100000.00),
(3, 34444, '2024-11-05', 1, 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `supply-details`
--

CREATE TABLE `supply-details` (
  `InvoiceID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Base-Price` float(10,2) NOT NULL,
  `Subtotal` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supply-details`
--

INSERT INTO `supply-details` (`InvoiceID`, `ItemID`, `Quantity`, `Base-Price`, `Subtotal`) VALUES
(222, 6, 3, 6.00, 0.00),
(222, 11, 13, 7.00, 18.00),
(9999, 6, 10, 10.00, 0.00),
(9999, 10, 20, 20.00, 100.00),
(88888, 9, 5, 900.00, 0.00),
(88888, 10, 10, 20.00, 4500.00),
(8989, 11, 10, 10.00, 0.00),
(12222, 6, 30, 15.00, 0.00),
(2444, 6, 100000, 1.00, 0.00),
(34444, 11, 100, 3.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Age` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Date`, `Status`, `Age`, `Email`) VALUES
(1, 'example', '$2y$10$sGqdRefPfGJjTUHXzL1vROi6.n.Ou9xwOfrzdoN2cxNmIqzN9Kdma', '0000-00-00', 'active', 69, 'example@gmail.com'),
(2, 'example2', '$2y$10$XadqHQ66pya/DuL6qfrwBOBZL7eZ9l4ekNdKKeu3LyFjPc02Jphse', '0000-00-00', 'active', 18, 'example2@gmail.com'),
(3, 'hello', '$2y$10$aZPDL6dxMmqmMjj3znwiheBEWrk9zlJqGlEWXZ.QYdAPUsmH814FC', '0000-00-00', 'active', 20, 'helloworld@gmail.com'),
(4, 'test5', '$2y$10$tBKC9LXITROncPksAwm1u.xo3Yx4uaAgd1T7vZnA53x3JWzIG/V.u', '0000-00-00', 'active', 23, 'test5@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `job-order`
--
ALTER TABLE `job-order`
  ADD PRIMARY KEY (`JobOrderID`),
  ADD KEY `ServiceID` (`ServiceID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `job-order-items`
--
ALTER TABLE `job-order-items`
  ADD KEY `JobOrderID` (`JobOrderID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`ServiceID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `supply`
--
ALTER TABLE `supply`
  ADD KEY `SupplierID` (`SupplierID`,`InvoiceID`),
  ADD KEY `InvoiceID` (`InvoiceID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `job-order`
--
ALTER TABLE `job-order`
  MODIFY `JobOrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `ServiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- Constraints for table `job-order`
--
ALTER TABLE `job-order`
  ADD CONSTRAINT `job-order_ibfk_1` FOREIGN KEY (`ServiceID`) REFERENCES `service` (`ServiceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `job-order_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
