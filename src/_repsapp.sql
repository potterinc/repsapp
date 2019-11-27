-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 20, 2019 at 08:53 AM
-- Server version: 10.1.43-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `celteckw_repsapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `allitems`
--

CREATE TABLE `allitems` (
  `Id` int(5) UNSIGNED NOT NULL,
  `serialCode` varchar(20) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `Model` varchar(20) NOT NULL,
  `Spec` varchar(1000) NOT NULL,
  `Quantity` int(5) NOT NULL,
  `Price` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='for View All Items';

--
-- Dumping data for table `allitems`
--

INSERT INTO `allitems` (`Id`, `serialCode`, `Category`, `Model`, `Spec`, `Quantity`, `Price`) VALUES
(57, 'CNU8735787098', 'Laptop', 'Hp-Compaq 8510p', 'Hp notebook', 199, 30000),
(68, 'Infinix Hot', 'Smart Phone', 'INFINITI89', 'Awesome Smartphone', 26, 9000),
(64, 'SG0033', 'Hard Drive', 'SATA', '500GB Hard disk drive Samsung', 40, 400),
(65, 'USB0054', 'USB Hub', 'IEEE004', '4 muli-port USB device.', 40, 200),
(67, '67866565676767', 'Taser', 'TSR-S8', 'High Voltage 1200VDC Mobile Tasers', 9, 500),
(69, '4xMD3312', 'Wireless Hotspot', 'MF774', 'Portable Mobile WiFi Hotspot device.', 23, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Id` int(5) UNSIGNED NOT NULL,
  `Name` varchar(40) NOT NULL,
  `contactAddress` varchar(50) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `additionDate` date NOT NULL,
  `allPurchase` int(20) DEFAULT NULL,
  `allReturns` int(20) DEFAULT NULL,
  `sucPurchase` int(20) DEFAULT NULL,
  `cashPurchase` int(20) DEFAULT NULL,
  `tranPurchase` int(20) DEFAULT NULL,
  `creditRem` int(20) DEFAULT NULL,
  `amountReturned` int(20) DEFAULT NULL,
  `lastDate` date DEFAULT NULL,
  `lastReturnD` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`Id`, `Name`, `contactAddress`, `phoneNumber`, `additionDate`, `allPurchase`, `allReturns`, `sucPurchase`, `cashPurchase`, `tranPurchase`, `creditRem`, `amountReturned`, `lastDate`, `lastReturnD`) VALUES
(1, 'Micheal Smith', '', '', '0000-00-00', 56, NULL, 56, 645500, 0, -341500, NULL, '2019-02-08', NULL),
(2, 'Samuel Terry', '', '', '0000-00-00', 9, NULL, 9, 58300, 0, 1200, NULL, '2019-02-08', NULL),
(3, 'James Parker', '', '', '0000-00-00', 35, NULL, 35, 873500, 0, 5000, NULL, '2019-02-09', NULL),
(4, 'Terry Mackson', '', '', '0000-00-00', 19, NULL, 19, 416000, 0, -54000, NULL, '2019-02-09', NULL),
(5, 'Terry Mackson Jrn', '', '', '0000-00-00', 12, NULL, 12, 462000, 0, -176000, NULL, '2019-02-09', NULL),
(6, 'Harry Potter', '', '', '0000-00-00', 33, NULL, 33, 616540, 0, 113000, NULL, '2019-02-11', NULL),
(7, 'Celestine', '', '', '0000-00-00', 2, NULL, 2, 900, 0, 0, NULL, '2019-02-10', NULL),
(8, 'Jenifer Aniston', '', '', '0000-00-00', 12, NULL, 12, 800, 0, 0, NULL, '2019-02-14', NULL),
(9, 'Gen. Chang', '', '', '0000-00-00', 3, NULL, 3, 900, 0, 20, NULL, '2019-02-14', NULL),
(10, 'Chandler Bing', '', '', '0000-00-00', 3, NULL, 3, 48000, 0, 0, NULL, '2019-02-18', NULL),
(11, 'Sarah Alfred', '', '', '0000-00-00', 2, NULL, 2, 0, 0, 4000, NULL, '2019-10-07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `Id` int(5) UNSIGNED NOT NULL,
  `purchaseType` varchar(40) NOT NULL,
  `purchaseDesc` varchar(80) NOT NULL,
  `purchaseDate` date NOT NULL,
  `authorizedBy` varchar(40) NOT NULL,
  `purchaseBy` varchar(40) NOT NULL,
  `Supplier` varchar(40) NOT NULL,
  `cashPaid` int(10) NOT NULL,
  `transferPaid` int(10) NOT NULL,
  `creditRemaining` int(10) NOT NULL,
  `lastDate` date NOT NULL,
  `staffNote` varchar(150) NOT NULL,
  `serialCode` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `Id` int(5) UNSIGNED NOT NULL,
  `Customer` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `salesType` varchar(40) NOT NULL,
  `salesDesc` varchar(1000) NOT NULL,
  `salesDate` date NOT NULL,
  `salesBy` varchar(40) NOT NULL,
  `paidTo` varchar(50) NOT NULL,
  `totalCost` int(50) NOT NULL,
  `cashPaid` int(10) NOT NULL,
  `transferPaid` int(10) NOT NULL,
  `creditRemaining` int(10) NOT NULL,
  `creditPaid` int(20) NOT NULL,
  `lastDate` date NOT NULL,
  `staffNote` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `regusers`
--

CREATE TABLE `regusers` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(20) COLLATE utf8_bin NOT NULL,
  `LastName` varchar(20) COLLATE utf8_bin NOT NULL,
  `Usrname` varchar(200) COLLATE utf8_bin NOT NULL,
  `PWord` text COLLATE utf8_bin NOT NULL,
  `SecQuest` varchar(100) COLLATE utf8_bin NOT NULL,
  `Ans` varchar(1000) COLLATE utf8_bin NOT NULL,
  `DateReg` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `regusers`
--

INSERT INTO `regusers` (`Id`, `FirstName`, `LastName`, `Usrname`, `PWord`, `SecQuest`, `Ans`, `DateReg`) VALUES
(9, 'Harry', 'Potter', 'potterinc', 'potterinc', 'Where did you meet your spouce?', 'here', '16-Feb-2019'),
(10, 'John', 'Doe', 'johndoe34', 'Johnny', 'Who is your favourite music Artist?', 'Passenger', '18-Feb-2019'),
(11, 'John', 'Doe', 'johndoe123', '123456789', 'Where did you meet your spouce?', '123', '08-Oct-2019'),
(12, 'Stella', 'Nnamani', 'StellaN', '113355', 'What is your Mothers Maiden name?', 'Ebere', '10-Oct-2019');

-- --------------------------------------------------------

--
-- Table structure for table `repairs`
--

CREATE TABLE `repairs` (
  `GadgetId` int(11) NOT NULL,
  `Gadget` varchar(50) COLLATE utf8_bin NOT NULL,
  `Customer` varchar(50) COLLATE utf8_bin NOT NULL,
  `Telephone` varchar(50) COLLATE utf8_bin NOT NULL,
  `Qty` int(11) NOT NULL,
  `Fault` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8_bin NOT NULL,
  `Price` int(11) NOT NULL,
  `AmtPaid` int(11) NOT NULL,
  `Balance` int(11) NOT NULL,
  `OrderDate` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `repairs`
--

INSERT INTO `repairs` (`GadgetId`, `Gadget`, `Customer`, `Telephone`, `Qty`, `Fault`, `Status`, `Price`, `AmtPaid`, `Balance`, `OrderDate`) VALUES
(1, 'Samsung Galaxy J6', 'James Parker', '01234567', 1, 'power', 'Collected', 200, 200, 0, '10-Feb-2019'),
(2, 'Tecno Spark', 'James Parker', '01234567', 2, 'keypad', 'Collected', 200, 200, 0, '10-Feb-2019'),
(3, 'Samsung', '', '', 1, 'Charging Port', 'Collected', 200, 200, 0, '11-Feb-2019'),
(4, 'Apple', 'James Parker', '03847575', 2, 'Touch Pad', 'Collected', 200, 200, 0, '12-Feb-2019'),
(5, 'hp', 'Harry Potter', '07068687200', 1, 'Keyboard', 'Collected', 298, 298, 0, '13-Feb-2019'),
(6, 'Iphone 5', 'James', '0973675445', 1, 'Keypad', 'Collected', 300, 300, 0, '14-Feb-2019'),
(7, 'Infinix Hot 5', 'Jack Reacher', '019958485', 1, 'Screen, Touchpad and charging port', 'Processing', 800, 800, 0, '15-Feb-2019'),
(8, 'Microphone', 'Chandler Bing', '0965858', 1, 'No Power', 'Collected', 30, 30, 0, '18-Feb-2019');

-- --------------------------------------------------------

--
-- Table structure for table `sellitems`
--

CREATE TABLE `sellitems` (
  `Id` int(10) UNSIGNED NOT NULL,
  `serialCode` varchar(20) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `Model` varchar(20) NOT NULL,
  `quantitySold` int(20) DEFAULT NULL,
  `sellingPrice` int(20) DEFAULT NULL,
  `totalPrice` int(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `solditems`
--

CREATE TABLE `solditems` (
  `Id` int(20) UNSIGNED NOT NULL,
  `serialCode` varchar(20) NOT NULL,
  `ItemCategory` varchar(20) NOT NULL,
  `Qty` int(20) DEFAULT NULL,
  `AmtPaid` int(50) NOT NULL,
  `Balance` int(20) DEFAULT NULL,
  `totalCost` int(11) NOT NULL,
  `salesDate` varchar(20) NOT NULL,
  `ClientName` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `solditems`
--

INSERT INTO `solditems` (`Id`, `serialCode`, `ItemCategory`, `Qty`, `AmtPaid`, `Balance`, `totalCost`, `salesDate`, `ClientName`) VALUES
(1, 'CNU787886', 'Keyboard', 1, 2500, 0, 2500, '09-Feb-2019', 'James Parker'),
(2, '67866565676767SG0033', 'Hard Drive', 11, 900, 0, 900, '10-Feb-2019', 'Celestine'),
(3, 'BL-5667866565676767', 'Taser', 23, 1540, 0, 1540, '11-Feb-2019', 'Harry Potter'),
(4, 'BL-56', 'Battery', 10, 800, 0, 800, '14-Feb-2019', 'Jenifer Aniston'),
(5, 'SG0033USB00546786656', 'Taser', 111, 900, 0, 920, '14-Feb-2019', 'Gen. Chang'),
(6, 'Infinix Hot', 'Smart Phone', 2, 18000, 0, 18000, '18-Feb-2019', 'Chandler Bing'),
(7, 'CNU8735787098', 'Laptop', 1, 30000, 0, 30000, '18-Feb-2019', 'Chandler Bing'),
(8, '4xMD3312', 'Wireless Hotspot', 2, 0, 4000, 4000, '07-Oct-2019', 'Sarah Alfred');

-- --------------------------------------------------------

--
-- Table structure for table `usersactivity`
--

CREATE TABLE `usersactivity` (
  `id` int(5) UNSIGNED NOT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `activity` varchar(800) DEFAULT NULL,
  `dates` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usersactivity`
--

INSERT INTO `usersactivity` (`id`, `firstname`, `lastname`, `activity`, `dates`) VALUES
(1, 'Samuel', 'L Jackson', ' Sold 12 Item(s) to Jenifer Aniston.', '2019-02-14'),
(2, 'Samuel', 'L Jackson', ' Sold 3 Item(s) to Gen. Chang.', '2019-02-14'),
(3, 'John', 'Doe', ' Sold 2 Item(s) to Chandler Bing.', '2019-02-18'),
(4, 'John', 'Doe', ' Sold 1 Item(s) to Chandler Bing.', '2019-02-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allitems`
--
ALTER TABLE `allitems`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `serialCode` (`serialCode`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `regusers`
--
ALTER TABLE `regusers`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Usrname` (`Usrname`);

--
-- Indexes for table `repairs`
--
ALTER TABLE `repairs`
  ADD PRIMARY KEY (`GadgetId`);

--
-- Indexes for table `sellitems`
--
ALTER TABLE `sellitems`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `solditems`
--
ALTER TABLE `solditems`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `usersactivity`
--
ALTER TABLE `usersactivity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allitems`
--
ALTER TABLE `allitems`
  MODIFY `Id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `Id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `Id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `Id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regusers`
--
ALTER TABLE `regusers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `repairs`
--
ALTER TABLE `repairs`
  MODIFY `GadgetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sellitems`
--
ALTER TABLE `sellitems`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `solditems`
--
ALTER TABLE `solditems`
  MODIFY `Id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usersactivity`
--
ALTER TABLE `usersactivity`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
