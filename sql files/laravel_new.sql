-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2019 at 07:25 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

DELIMITER $$
--
-- Procedures
--
CREATE OR REPLACE DEFINER=`root`@`localhost`  PROCEDURE `a_order_t` (IN `uid` INT, IN `total` INT, IN `paid` INT, IN `sales_point` VARCHAR(50), IN `admin_id` INT)  BEGIN
DECLARE o_no, p_id , acc_count , qntity INT;
DECLARE status VARCHAR(20);
DECLARE b INT DEFAULT 0;
DECLARE cur_1 CURSOR FOR 
SELECT product_id , quantity FROM CART WHERE user_id = uid;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;

SELECT MAX(order_id) INTO o_no FROM ORDER_T;

select count(*) into acc_count from account where user_id = uid;

IF acc_count = 0 then
INSERT into account (user_id , total_tk , paid_tk ) VALUES (uid , total  , paid ) ;
ELSE
UPDATE account set total_tk = total_tk + total , paid_tk = paid_tk + paid where user_id = uid;
END IF;


UPDATE account SET total_tk = total_tk + total , balance_available = balance_available+ paid , paid_tk = paid_tk + paid where user_id = 0;

if o_no IS NULL 
then
set o_no =  0;
end if;

INSERT INTO `order_t`(`order_id`, `order_date`,   `user_id` , `admin_id` ,  `total_amount` , `paid` , `sales_point`) VALUES (o_no+1 , SYSDATE() ,  uid , admin_id ,total_amount+total , paid , sales_point );

OPEN cur_1;
REPEAT FETCH cur_1 INTO p_id , qntity ;

if p_id is NOT NULL
then
INSERT INTO `order_includ_product`(`order_id`, `product_id`, `qntity`) VALUES (o_no+1 , p_id ,qntity);
end if;


SELECT p_id , qntity;

UNTIL b = 1
END REPEAT;
CLOSE cur_1;
SET status = 'done' ;
DELETE FROM `cart` WHERE user_id = uid;
SELECT status;
END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `cart` (IN `pid` INT, IN `uid` INT, IN `qnt` INT)  BEGIN
DECLARE p_id , c_id , cart_count int; 
DECLARE status VARCHAR(20);

SELECT cart_id  into c_id  FROM `CART` WHERE product_id = pid AND user_id = uid ;
select c_id;
if c_id IS NOT NULL
then
UPDATE `p_include_cart` SET `product_qntity`= product_qntity + qnt WHERE cart_id = c_id;
UPDATE `CART` SET `quantity`= quantity + qnt WHERE cart_id = c_id;
SET status = 'updated' ; 
select status;
SELECT COUNT(*) into cart_count FROM `cart` WHERE user_id = uid;
select cart_count;
else
INSERT INTO `cart`( `cart_status`, `user_id`,  `product_id` , `quantity` ) VALUES ('cart' , uid , pid , qnt); 
select max(cart_id) into c_id from cart;
INSERT INTO `p_include_cart`(`cart_id`, `product_id`, `product_qntity`) VALUES (c_id , pid , qnt);
SET status = 'added' ; 
select status;
SELECT COUNT(*) into cart_count FROM `cart` WHERE user_id = uid;
select cart_count;
end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cartPage` (IN `uid` INT)  BEGIN
select p.product_id , pr.product_name , pr.product_price , pr.descriptions , p.product_qntity from cart c , p_include_cart p , products pr where p.cart_id = c.cart_id and p.product_id = pr.product_id and c.user_id = uid;

select SUM(pr.product_price) as total from cart c , p_include_cart p , products pr where p.cart_id = c.cart_id and p.product_id = pr.product_id and c.user_id = uid;

END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `money_transfer` (IN `admin_id` INT, IN `amount_tk` INT)  NO SQL
BEGIN

insert into money_transfer (transfer_date , transfered_by , amount , status) VALUES (SYSDATE() , admin_id , amount_tk , 0);

UPDATE account set balance_available = balance_available - amount_tk where user_id = 0;

END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `order_t` (IN `uid` INT, IN `o_date` DATE, IN `p_method` VARCHAR(20))  BEGIN
DECLARE o_no, p_id , qntity INT;
DECLARE status VARCHAR(20);
DECLARE b INT DEFAULT 0;
DECLARE cur_1 CURSOR FOR 
SELECT product_id , quantity FROM CART WHERE user_id = uid;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;

SELECT MAX(order_id) INTO o_no FROM ORDER_T;
INSERT INTO `order_t`(`order_id`, `order_date`, `payment_method`,  `user_id`) VALUES (o_no+1 , o_date , p_method , uid );

OPEN cur_1;
REPEAT FETCH cur_1 INTO p_id , qntity ;

INSERT INTO `order_includ_product`(`order_id`, `product_id`, `qntity`) VALUES (o_no+1 , p_id ,qntity);


SELECT p_id , qntity;

UNTIL b = 1
END REPEAT;
CLOSE cur_1;
SET status = 'done' ;
DELETE FROM `cart` WHERE user_id = uid;
SELECT status;
END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `review` (IN `uid` INT, IN `pid` INT, IN `rev_text` VARCHAR(50), IN `rev_date` DATE)  BEGIN

DECLARE rev_id INT;
DECLARE status VARCHAR(20);
SET status = 'DONE';

SELECT review_id INTO rev_id FROM `review` WHERE product_id = pid and user_id = uid;

IF rev_id IS NOT NULL
THEN

UPDATE `review` SET `review_text`= rev_text ,`review_date`= rev_date WHERE product_id= pid AND user_id= uid;

SELECT status;


ELSE

INSERT INTO `review`( `review_text`, `review_date`, `product_id`, `user_id`) VALUES (rev_text , rev_date , pid , uid ) ; 

SELECT status;

END IF;

END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `shipment_cart` (IN `a_id` INT(10), IN `p_id` INT(10), IN `p_qntity` INT(10))  NO SQL
BEGIN
DECLARE counter INT ;

SELECT  COUNT(*) INTO counter FROM `shipment_temp` WHERE product_id = p_id and admin_id =a_id;

IF counter > 0 THEN
UPDATE shipment_temp set product_quantity = product_quantity + p_qntity WHERE admin_id = a_id and product_id = p_id;
ELSE
INSERT INTO shipment_temp (product_id , admin_id , product_quantity) VALUES (p_id , a_id , p_qntity);
END IF;

END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `shipment_req` (IN `a_id` INT)  NO SQL
BEGIN
DECLARE o_no, p_id , qntity INT;
DECLARE status VARCHAR(20);
DECLARE b INT DEFAULT 0;
DECLARE cur_1 CURSOR FOR 
SELECT product_id , product_quantity FROM shipment_temp WHERE admin_id = a_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;

SELECT MAX(id) INTO o_no FROM shipment;

if o_no IS NULL 
then
set o_no =  0;
end if;

INSERT INTO `shipment`(`id`, `req_date`,   `admin_id_req`  ,  `status` ) VALUES (o_no+1 , SYSDATE() ,  a_id  , 0 );

OPEN cur_1;
REPEAT FETCH cur_1 INTO p_id , qntity ;

if p_id is NOT NULL
then
INSERT INTO `shipment_product`(`shipment_id`, `product_id`, `product_quantity`) VALUES (o_no+1 , p_id ,qntity);
end if;


SELECT p_id , qntity;

UNTIL b = 1
END REPEAT;
CLOSE cur_1;
SET status = 'done' ;
DELETE FROM `shipment_temp` WHERE admin_id = a_id;
SELECT status;
END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `shipment_to_products` (IN `id` INT)  NO SQL
BEGIN
DECLARE b INT DEFAULT 0;
DECLARE o_no, p_id , acc_count , qntity INT;
DECLARE cur_1 CURSOR FOR 
select product_id , product_quantity FROM shipment_product where shipment_id = id ; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;


OPEN cur_1;
REPEAT FETCH cur_1 INTO p_id , qntity ;

SELECT p_id , qntity;
select product_id , product_avlble from products;

if p_id is NOT NULL
then

UPDATE products SET product_avlble = product_avlble + qntity WHERE product_id = p_id;



end if;


select product_id , product_avlble from products;

UNTIL b = 1
END REPEAT;
CLOSE cur_1;

END$$

CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `test1` ()  BEGIN
SELECT * FROM USER;
SELECT * FROM products;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `userTest` ()  NO SQL
SELECT * FROM user$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(8) NOT NULL,
  `user_id` int(8) DEFAULT '0',
  `total_tk` int(8) NOT NULL DEFAULT '0',
  `paid_tk` int(8) NOT NULL DEFAULT '0',
  `balance_available` int(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `user_id`, `total_tk`, `paid_tk`, `balance_available`) VALUES
(1, 0, 635, 450, 273),
(2, 12, 132, 100, 0),
(3, 4, 268, 250, 0),
(4, 15, 235, 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(5) NOT NULL,
  `cart_status` varchar(50) NOT NULL,
  `user_id` int(5) NOT NULL,
  `g_u_type` varchar(20) NOT NULL DEFAULT 'user',
  `order_id` int(5) NOT NULL,
  `product_id` int(5) NOT NULL,
  `quantity` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `cart_status`, `user_id`, `g_u_type`, `order_id`, `product_id`, `quantity`) VALUES
(83, 'cart', 4, 'user', 0, 1, 3),
(84, 'cart', 4, 'user', 0, 2, 1),
(89, 'cart', 12, 'user', 0, 7, 1),
(90, 'cart', 12, 'user', 0, 11, 2),
(91, 'cart', 12, 'user', 0, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `factory`
--

CREATE TABLE `factory` (
  `id` int(8) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `factory`
--

INSERT INTO `factory` (`id`, `name`, `location`) VALUES
(1, 'afear', 'arefear');

-- --------------------------------------------------------

--
-- Table structure for table `factory_materials`
--

CREATE TABLE `factory_materials` (
  `id` int(8) NOT NULL,
  `factory_id` int(8) DEFAULT NULL,
  `materials_id` int(8) DEFAULT NULL,
  `qntity` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `factory_materials`
--

INSERT INTO `factory_materials` (`id`, `factory_id`, `materials_id`, `qntity`) VALUES
(1, 1, 2, 20),
(2, 1, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `money_transfer`
--

CREATE TABLE `money_transfer` (
  `id` int(8) NOT NULL,
  `transfer_date` date DEFAULT NULL,
  `receive_date` date DEFAULT NULL,
  `transfered_by` int(8) DEFAULT NULL,
  `received_by` int(8) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `money_transfer`
--

INSERT INTO `money_transfer` (`id`, `transfer_date`, `receive_date`, `transfered_by`, `received_by`, `status`, `amount`) VALUES
(1, '2019-04-19', '2019-04-19', 2, 2, 1, 10),
(2, '2019-04-19', '2019-04-19', 2, 2, 1, 10),
(3, '2019-04-19', '2019-04-19', 2, 2, 1, 5),
(4, '2019-04-19', '2019-04-19', 2, 2, 1, 5),
(5, '2019-04-19', '2019-04-19', 2, 2, 1, 7),
(6, '2019-04-19', '2019-04-19', 2, 2, 1, 20),
(7, '2019-04-19', '2019-04-20', 2, 2, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `order_includ_product`
--

CREATE TABLE `order_includ_product` (
  `order_id` int(8) NOT NULL,
  `product_id` int(8) NOT NULL,
  `qntity` int(8) NOT NULL,
  `counter` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_includ_product`
--

INSERT INTO `order_includ_product` (`order_id`, `product_id`, `qntity`, `counter`) VALUES
(1, 4, 1, 16),
(1, 2, 2, 17),
(1, 2, 2, 18),
(2, 3, 3, 19),
(2, 3, 3, 20),
(5, 3, 3, 21),
(5, 8, 3, 22),
(5, 14, 3, 23),
(5, 18, 3, 24),
(5, 18, 3, 25),
(6, 3, 3, 26),
(6, 4, 4, 27),
(6, 4, 4, 28),
(7, 1, 1, 29),
(7, 1, 1, 30),
(11, 3, 2, 31),
(11, 2, 1, 32),
(11, 2, 1, 33),
(12, 3, 2, 34),
(12, 3, 2, 35),
(14, 2, 9, 36),
(14, 1, 3, 37),
(14, 3, 1, 38),
(15, 2, 9, 39),
(15, 1, 3, 40),
(15, 3, 1, 41),
(16, 1, 4, 42),
(16, 2, 4, 43),
(16, 2, 4, 44),
(17, 2, 5, 45),
(17, 1, 2, 46),
(17, 1, 2, 47),
(18, 2, 7, 48),
(18, 1, 2, 49),
(18, 1, 2, 50),
(19, 3, 2, 51),
(19, 3, 2, 52),
(20, 2, 1, 53),
(20, 3, 4, 54),
(20, 3, 4, 55),
(21, 3, 5, 56),
(21, 8, 2, 57),
(21, 10, 3, 58),
(22, 1, 3, 59),
(22, 2, 1, 60),
(22, 2, 1, 61),
(23, 2, 4, 62),
(23, 2, 4, 63);

-- --------------------------------------------------------

--
-- Table structure for table `order_t`
--

CREATE TABLE `order_t` (
  `order_id` int(5) NOT NULL DEFAULT '0',
  `order_date` date NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'default',
  `payment_status` varchar(50) NOT NULL,
  `return_id` int(5) NOT NULL,
  `user_id` int(8) NOT NULL,
  `counter` int(8) NOT NULL,
  `total_amount` int(11) DEFAULT '0',
  `paid` int(10) NOT NULL DEFAULT '0',
  `sales_point` varchar(50) NOT NULL DEFAULT 'default',
  `admin_id` int(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_t`
--

INSERT INTO `order_t` (`order_id`, `order_date`, `payment_method`, `payment_status`, `return_id`, `user_id`, `counter`, `total_amount`, `paid`, `sales_point`, `admin_id`) VALUES
(1, '2019-04-16', 'default', '', 0, 14, 12, 0, 11, 'uuu', 0),
(2, '2019-04-16', 'default', '', 0, 12, 13, 0, 111, '111', 0),
(3, '2019-04-16', 'default', '', 0, 12, 14, 0, 111, '11', 0),
(4, '2019-04-16', 'default', '', 0, 12, 15, 0, 11, '111', 0),
(5, '2019-04-16', 'default', '', 0, 15, 16, 0, 33, '333', 0),
(6, '2019-04-16', 'default', '', 0, 12, 17, 0, 88, '12', 0),
(7, '2019-04-16', 'default', '', 0, 12, 18, 0, 10, 'Agrabad', 0),
(8, '2019-04-16', 'default', '', 0, 12, 19, 0, 10, 'Agrabad', 0),
(9, '2019-04-16', 'default', '', 0, 12, 20, 0, 10, 'Agrabad', 0),
(10, '2019-04-16', 'default', '', 0, 12, 21, 0, 10, 'Agrabad', 0),
(11, '2019-04-16', 'default', '', 0, 14, 22, 0, 11, 'Agrabad', 0),
(12, '2019-04-16', 'default', '', 0, 14, 23, 0, 12, 'New Delhi', 0),
(13, '2019-04-16', 'default', '', 0, 14, 24, 0, 12, 'New Delhi', 0),
(14, '2019-04-16', 'default', '', 0, 12, 25, 0, 12, 'New Delhi', 0),
(15, '2019-04-16', 'default', '', 0, 12, 26, 0, 12, 'New Delhi', 0),
(16, '2019-04-16', 'default', '', 0, 4, 27, 0, 0, 'New Delhi', 0),
(17, '2019-04-18', 'default', '', 0, 12, 28, 0, 100, 'New Delhi', 0),
(18, '2019-04-18', 'default', '', 0, 12, 29, 0, 100, 'New Delhi', 0),
(19, '2019-04-18', 'default', '', 0, 15, 30, 0, 12, 'New Delhi', 0),
(20, '2019-04-18', 'default', '', 0, 14, 31, 0, 14, 'New Delhi', 2),
(21, '2019-04-19', 'default', '', 0, 12, 32, 0, 100, 'New Delhi', 2),
(22, '2019-04-19', 'default', '', 0, 4, 33, 0, 250, 'New Delhi', 2),
(23, '2019-04-19', 'default', '', 0, 15, 34, 235, 100, 'Choose...', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(5) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_price` int(5) NOT NULL,
  `product_avlble` int(5) NOT NULL,
  `product_sell_price` int(5) NOT NULL,
  `product_original_price` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  `descriptions` varchar(100) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `sub_category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_avlble`, `product_sell_price`, `product_original_price`, `category_id`, `descriptions`, `category_name`, `sub_category`) VALUES
(1, 'Monitor LG', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg'),
(2, 'Monitor Samsung ', 343, 51, 235, 2356, 346, 'useful for home', 'monitor', 'samsung'),
(3, 'Monitor Walton', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'walton'),
(4, 'hard disk 1TB', 343, 34, 235, 2356, 346, 'useful for home', 'hdd', 'toshiba'),
(5, 'hard disk 2TB', 33, 75, 33, 34, 34, 'useful for home', 'hdd', 'western_digital'),
(6, 'hard disk 4TB', 33, 33, 33, 34, 34, 'useful for home', 'hdd', 'adata'),
(7, 'Printer Canon', 343, 34, 235, 2356, 346, 'useful for home', 'printer', 'canon'),
(8, 'Printer HP', 33, 33, 33, 34, 34, 'useful for home', 'printer', 'hp'),
(9, 'Ram Transcend', 343, 34, 235, 2356, 346, 'useful for home', 'ram', 'transcend'),
(10, 'Ram Adata', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'adata'),
(11, 'Ram Razor', 33, 39, 33, 34, 34, 'useful for home', 'ram', 'razor'),
(12, 'Motherboard GigaByte', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'gigabyte'),
(13, 'Motherboard Asus', 33, 33, 33, 34, 34, 'useful for home', 'motherboard', 'asus'),
(14, 'Motherboard Intel', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'intel'),
(15, 'Processor Intel', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel'),
(16, 'Processor AMD', 33, 34, 235, 34, 34, 'Computer processor , high quality', 'processor', 'amd'),
(17, 'Monitor LG Full HD', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg'),
(18, 'Monitor LG Full HD', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg'),
(19, 'Monitor Samsung 4K', 343, 34, 235, 2356, 346, 'useful for home', 'monitor', 'samsung'),
(20, 'Monitor Walton Plasma', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'walton'),
(21, 'hard disk 1TB 5400rpm', 343, 34, 235, 2356, 346, 'useful for home', 'hdd', 'toshiba'),
(22, 'hard disk 4TB 7200rpm', 33, 33, 33, 34, 34, 'useful for home', 'hdd', 'adata'),
(23, 'Printer Canon For Photos', 343, 34, 235, 2356, 346, 'useful for home', 'printer', 'canon'),
(24, 'Ram Adata 8GB', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'adata'),
(25, 'Ram Razor 16GB', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'razor'),
(26, 'Printer HP 1080P', 33, 33, 33, 34, 34, 'useful for home', 'printer', 'hp'),
(27, 'Motherboard Intel Gaming', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'intel'),
(28, 'Processor Intel Core i3', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel'),
(29, 'Processor Intel Core i5', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel'),
(30, 'Processor Intel Core i9', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel');

-- --------------------------------------------------------

--
-- Table structure for table `p_include_cart`
--

CREATE TABLE `p_include_cart` (
  `cart_id` int(5) NOT NULL,
  `product_id` int(5) NOT NULL,
  `product_qntity` int(5) NOT NULL,
  `counter` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `p_include_cart`
--

INSERT INTO `p_include_cart` (`cart_id`, `product_id`, `product_qntity`, `counter`) VALUES
(1, 8, 7, 1),
(2, 1, 16, 2),
(3, 7, 4, 3),
(4, 9, 2, 4),
(5, 3, 57, 5),
(6, 5, 13, 6),
(7, 4, 27, 7),
(8, 2, 27, 8),
(9, 7, 3, 9),
(10, 6, 3, 10),
(11, 1, 8, 11),
(12, 18, 5, 12),
(13, 4, 11, 13),
(14, 3, 11, 14),
(15, 2, 10, 15),
(16, 5, 3, 16),
(17, 4, 6, 17),
(18, 2, 3, 18),
(19, 2, 5, 19),
(20, 4, 3, 20),
(21, 1, 2, 21),
(22, 3, 1, 22),
(23, 4, 1, 23),
(24, 2, 2, 24),
(25, 3, 2, 25),
(26, 2, 4, 26),
(27, 3, 3, 27),
(21, 2, 1, 28),
(22, 4, 1, 29),
(23, 8, 1, 30),
(24, 12, 1, 31),
(25, 3, 2, 32),
(26, 3, 2, 33),
(27, 3, 1, 34),
(28, 3, 2, 35),
(29, 2, 4, 36),
(30, 3, 2, 37),
(31, 3, 6, 38),
(32, 3, 2, 39),
(33, 9, 2, 40),
(34, 7, 2, 41),
(35, 2, 1, 42),
(36, 3, 1, 43),
(37, 2, 4, 44),
(38, 5, 3, 45),
(39, 12, 1, 46),
(40, 3, 2, 47),
(41, 2, 3, 48),
(42, 3, 6, 49),
(43, 5, 3, 50),
(44, 2, 3, 51),
(45, 3, 2, 52),
(46, 6, 2, 53),
(47, 12, 2, 54),
(48, 2, 1, 55),
(49, 1, 1, 56),
(50, 2, 3, 57),
(51, 3, 3, 58),
(52, 4, 1, 59),
(53, 2, 2, 60),
(54, 3, 3, 61),
(55, 2, 2, 62),
(56, 3, 3, 63),
(57, 3, 3, 64),
(58, 8, 3, 65),
(59, 14, 3, 66),
(60, 18, 3, 67),
(61, 4, 4, 68),
(62, 1, 1, 69),
(63, 2, 11, 70),
(64, 3, 2, 71),
(65, 1, 4, 72),
(66, 1, 5, 73),
(67, 2, 6, 74),
(68, 3, 3, 75),
(69, 2, 1, 76),
(70, 3, 2, 77),
(71, 4, 2, 78),
(72, 2, 6, 79),
(73, 2, 2, 80),
(74, 2, 17, 81),
(75, 1, 2, 82),
(76, 3, 2, 83),
(77, 2, 4, 84),
(78, 1, 2, 85),
(79, 3, 9, 86),
(80, 4, 2, 87),
(81, 2, 2, 88),
(82, 2, 2, 89),
(83, 1, 3, 90),
(84, 2, 1, 91),
(85, 3, 7, 92),
(86, 8, 4, 93),
(87, 10, 3, 94),
(88, 15, 3, 95),
(89, 7, 2, 96),
(90, 11, 10, 97),
(91, 3, 2, 98);

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id` int(8) NOT NULL,
  `quantity` int(8) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `quantity`, `name`) VALUES
(1, 100, 'cement'),
(2, 100, 'rod');

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `id` int(8) NOT NULL,
  `req_date` date DEFAULT NULL,
  `acc_date` date DEFAULT NULL,
  `status` int(8) NOT NULL DEFAULT '0',
  `admin_id_req` int(8) DEFAULT NULL,
  `admin_id_acc` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`id`, `req_date`, `acc_date`, `status`, `admin_id_req`, `admin_id_acc`) VALUES
(1, '2019-04-19', '2019-04-19', 1, 2, 2),
(2, '2019-04-19', '2019-04-19', 1, 2, 2),
(3, '2019-04-19', '2019-04-19', 1, 2, 2),
(4, '2019-04-19', '2019-04-19', 1, 2, 2),
(5, '2019-04-19', '2019-04-19', 1, 2, 2),
(6, '2019-04-19', '2019-04-19', 1, 2, 2),
(7, '2019-04-19', '2019-04-19', 1, 2, 2),
(8, '2019-04-19', '2019-04-19', 1, 2, 2),
(9, '2019-04-19', '2019-04-19', 1, 2, 2),
(10, '2019-04-19', '2019-04-19', 1, 2, 2),
(11, '2019-04-19', '2019-04-19', 1, 2, 2),
(12, '2019-04-19', '2019-04-19', 1, 2, 2),
(13, '2019-04-19', '2019-04-19', 1, 2, 2),
(14, '2019-04-19', '2019-04-19', 1, 2, 2),
(15, '2019-04-19', '2019-04-19', 1, 2, 2),
(16, '2019-04-19', '2019-04-19', 1, 2, 2),
(17, '2019-04-19', '2019-04-19', 1, 2, 2),
(18, '2019-04-19', '2019-04-19', 1, 2, 2),
(19, '2019-04-19', '2019-04-19', 1, 2, 2),
(20, '2019-04-19', '2019-04-19', 1, 2, 2),
(21, '2019-04-19', '2019-04-19', 1, 2, 2),
(22, '2019-04-19', '2019-04-19', 1, 2, 2),
(23, '2019-04-19', '2019-04-19', 1, 2, 2),
(24, '2019-04-19', '2019-04-19', 1, 2, 2),
(25, '2019-04-19', '2019-04-19', 1, 2, 2),
(26, '2019-04-19', '2019-04-19', 1, 2, 2),
(27, '2019-04-19', '2019-04-19', 1, 2, 2),
(28, '2019-04-19', '2019-04-19', 1, 2, 2),
(29, '2019-04-19', '2019-04-19', 1, 2, 2),
(30, '2019-04-19', '2019-04-19', 1, 2, 2),
(31, '2019-04-19', '2019-04-19', 1, 2, 2),
(32, '2019-04-19', '2019-04-19', 1, 2, 2),
(33, '2019-04-19', '2019-04-19', 1, 2, 2),
(34, '2019-04-19', '2019-04-19', 1, 2, 2),
(35, '2019-04-19', '2019-04-19', 1, 2, 2),
(36, '2019-04-19', '2019-04-19', 1, 2, 2),
(37, '2019-04-19', '2019-04-19', 1, 2, 2),
(38, '2019-04-19', '2019-04-19', 1, 2, 2),
(39, '2019-04-19', '2019-04-19', 1, 2, 2),
(40, '2019-04-19', '2019-04-19', 1, 2, 2),
(41, '2019-04-19', '2019-04-19', 1, 2, 2),
(42, '2019-04-19', '2019-04-19', 2, 2, 2),
(43, '2019-04-19', '2019-04-20', 2, 2, 2),
(44, '2019-04-20', '2019-04-20', 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `shipment_product`
--

CREATE TABLE `shipment_product` (
  `id` int(8) NOT NULL,
  `shipment_id` int(8) NOT NULL DEFAULT '0',
  `product_id` int(8) DEFAULT NULL,
  `product_quantity` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipment_product`
--

INSERT INTO `shipment_product` (`id`, `shipment_id`, `product_id`, `product_quantity`) VALUES
(1, 1, 5, 3),
(2, 1, 2, 1),
(3, 1, 2, 1),
(4, 2, 5, 3),
(5, 2, 2, 1),
(6, 2, 11, 3),
(7, 3, 5, 4),
(8, 3, 14, 4),
(9, 3, 14, 4),
(10, 4, 5, 4),
(11, 4, 14, 4),
(12, 4, 2, 3),
(13, 5, 5, 4),
(14, 5, 14, 4),
(15, 5, 2, 3),
(16, 6, 5, 4),
(17, 6, 14, 4),
(18, 6, 2, 3),
(19, 7, 5, 4),
(20, 7, 14, 4),
(21, 7, 2, 3),
(22, 8, 5, 4),
(23, 8, 14, 4),
(24, 8, 2, 3),
(25, 9, 5, 4),
(26, 9, 14, 4),
(27, 9, 2, 3),
(28, 10, 5, 4),
(29, 10, 14, 4),
(30, 10, 2, 6),
(31, 11, 5, 4),
(32, 11, 14, 4),
(33, 11, 2, 6),
(34, 12, 5, 4),
(35, 12, 14, 4),
(36, 12, 2, 6),
(37, 13, 5, 4),
(38, 13, 14, 4),
(39, 13, 2, 6),
(40, 14, 5, 4),
(41, 14, 14, 4),
(42, 14, 2, 6),
(43, 15, 5, 4),
(44, 15, 14, 4),
(45, 15, 2, 6),
(46, 16, 5, 4),
(47, 16, 14, 4),
(48, 16, 2, 6),
(49, 17, 10, 2),
(50, 17, 3, 3),
(51, 17, 3, 3),
(52, 18, 10, 2),
(53, 18, 3, 3),
(54, 18, 8, 2),
(55, 19, 10, 2),
(56, 19, 3, 5),
(57, 19, 8, 2),
(58, 20, 10, 2),
(59, 20, 3, 5),
(60, 20, 8, 2),
(61, 21, 10, 2),
(62, 21, 3, 5),
(63, 21, 8, 2),
(64, 22, 10, 2),
(65, 22, 3, 7),
(66, 22, 8, 2),
(67, 23, 10, 2),
(68, 23, 3, 7),
(69, 23, 8, 2),
(70, 24, 10, 2),
(71, 24, 3, 7),
(72, 24, 8, 2),
(73, 25, 10, 2),
(74, 25, 3, 7),
(75, 25, 8, 2),
(76, 26, 10, 2),
(77, 26, 3, 9),
(78, 26, 8, 2),
(79, 27, 10, 2),
(80, 27, 3, 9),
(81, 27, 8, 2),
(82, 28, 10, 2),
(83, 28, 3, 11),
(84, 28, 8, 2),
(85, 29, 10, 2),
(86, 29, 3, 14),
(87, 29, 8, 2),
(88, 30, 10, 2),
(89, 30, 3, 14),
(90, 30, 8, 5),
(91, 31, 10, 2),
(92, 31, 3, 18),
(93, 31, 8, 5),
(94, 32, 10, 2),
(95, 32, 3, 21),
(96, 32, 8, 5),
(97, 33, 2, 1),
(98, 33, 3, 3),
(99, 33, 3, 3),
(100, 34, 2, 1),
(101, 34, 3, 7),
(102, 34, 10, 4),
(103, 35, 2, 1),
(104, 35, 3, 7),
(105, 35, 10, 4),
(106, 36, 2, 1),
(107, 36, 3, 7),
(108, 36, 10, 4),
(109, 37, 2, 1),
(110, 37, 3, 7),
(111, 37, 10, 4),
(112, 38, 2, 1),
(113, 38, 3, 10),
(114, 38, 10, 4),
(115, 39, 2, 1),
(116, 39, 3, 12),
(117, 39, 10, 4),
(118, 39, 14, 4),
(119, 39, 5, 20),
(120, 39, 4, 44),
(121, 39, 17, 5),
(122, 39, 19, 2),
(123, 39, 19, 2),
(124, 40, 4, 4),
(125, 40, 8, 1),
(126, 40, 8, 1),
(127, 41, 3, 2),
(128, 41, 3, 2),
(129, 42, 3, 3),
(130, 42, 3, 3),
(131, 43, 3, 2),
(132, 43, 8, 2),
(133, 43, 15, 2),
(134, 44, 3, 2),
(135, 44, 8, 2),
(136, 44, 15, 2);

-- --------------------------------------------------------

--
-- Table structure for table `shipment_temp`
--

CREATE TABLE `shipment_temp` (
  `id` int(8) NOT NULL,
  `admin_id` int(8) DEFAULT NULL,
  `product_id` int(8) DEFAULT NULL,
  `product_quantity` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipment_temp`
--

INSERT INTO `shipment_temp` (`id`, `admin_id`, `product_id`, `product_quantity`) VALUES
(53, 2, 3, 2),
(54, 2, 8, 2),
(55, 2, 15, 2),
(56, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(5) NOT NULL,
  `u_password` varchar(50) NOT NULL,
  `u_address` varchar(50) DEFAULT NULL,
  `u_email` varchar(50) NOT NULL,
  `u_mobile` int(5) NOT NULL DEFAULT '0',
  `dob` date DEFAULT NULL,
  `u_status` varchar(50) DEFAULT NULL,
  `u_type` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_password`, `u_address`, `u_email`, `u_mobile`, `dob`, `u_status`, `u_type`, `first_name`, `last_name`) VALUES
(2, '12', '', 'riyad298@gmail.com', 1919448787, '2007-02-17', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad'),
(4, 'fff', '', 'ffffaaa', 666, '2007-02-14', 'valid', 'user', 'Muhammad Ahsan', 'afa'),
(12, 'afrf', '', 'riyadarfr298@gmail.com', 1919448787, '2007-03-15', 'valid', 'user', 'Muhammad Ahsan', 'Riyad'),
(14, 'arefa', '', 'riyadhellow298@gmail.com', 1919448787, '0000-00-00', 'valid', 'user', 'Muhammad Ahsan', 'Riyad'),
(15, '448787', '', 'riyad298@outlook.com', 1919448787, '0000-00-00', 'valid', 'user', 'Ahsan', 'Riyad'),
(16, 'afaaf', '', 'afa343', 122, '0000-00-00', 'valid', 'user', '', 'afa'),
(18, '12', '', 'as', 111, '0000-00-00', 'seller', 'user', '', 'afa'),
(19, '111', '', 'riyad28877722@gmail.com', 1919448787, '0000-00-00', 'valid', 'admin', 'sde', 'edf'),
(20, 'ffaf', NULL, 'riyad298faerfaer', 1919448787, '2007-02-14', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad'),
(21, '12', NULL, 'riyad298faerfaer222', 1919448787, '2007-02-14', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad'),
(29, '11', NULL, 'riyadmail@gmail.com', 1919448787, '2008-01-09', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad'),
(32, 'aerfer', NULL, 'aerfaer', 0, NULL, NULL, 'user', NULL, 'areferf'),
(33, 'erafaerf', NULL, 'aerfearfae', 0, NULL, NULL, 'user', NULL, 'arfeafearfaer'),
(34, '1212', NULL, 'riyad298@yahooo.com', 1919448787, '2006-02-12', 'valid', NULL, 'Ahsan', 'Riyad'),
(35, 'afrf', NULL, 'riyad298@gmfffail.com', 1919448787, '2007-02-04', 'valid', NULL, 'Ahsan', 'Riyad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD UNIQUE KEY `cart_id` (`cart_id`);

--
-- Indexes for table `factory`
--
ALTER TABLE `factory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factory_materials`
--
ALTER TABLE `factory_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_transfer`
--
ALTER TABLE `money_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_includ_product`
--
ALTER TABLE `order_includ_product`
  ADD PRIMARY KEY (`counter`);

--
-- Indexes for table `order_t`
--
ALTER TABLE `order_t`
  ADD PRIMARY KEY (`counter`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `p_include_cart`
--
ALTER TABLE `p_include_cart`
  ADD PRIMARY KEY (`counter`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipment_product`
--
ALTER TABLE `shipment_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipment_temp`
--
ALTER TABLE `shipment_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `u_email` (`u_email`),
  ADD UNIQUE KEY `u_email_2` (`u_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `factory`
--
ALTER TABLE `factory`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `factory_materials`
--
ALTER TABLE `factory_materials`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `money_transfer`
--
ALTER TABLE `money_transfer`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_includ_product`
--
ALTER TABLE `order_includ_product`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `order_t`
--
ALTER TABLE `order_t`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `p_include_cart`
--
ALTER TABLE `p_include_cart`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `shipment_product`
--
ALTER TABLE `shipment_product`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `shipment_temp`
--
ALTER TABLE `shipment_temp`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
