-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2019 at 11:48 PM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `a_order_t` (IN `uid` INT, IN `total` INT, IN `paid` INT, IN `sales_point` VARCHAR(50), IN `admin_id` INT)  BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `cart` (IN `pid` INT, IN `uid` INT, IN `qnt` INT)  BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `money_transfer` (IN `admin_id` INT, IN `amount_tk` INT)  NO SQL
BEGIN

insert into money_transfer (transfer_date , transfered_by , amount , status) VALUES (SYSDATE() , admin_id , amount_tk , 0);

UPDATE account set balance_available = balance_available - amount_tk where user_id = 0;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `order_t` (IN `uid` INT, IN `o_date` DATE, IN `p_method` VARCHAR(20))  BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `review` (IN `uid` INT, IN `pid` INT, IN `rev_text` VARCHAR(50), IN `rev_date` DATE)  BEGIN

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `shipment_cart` (IN `a_id` INT(10), IN `p_id` INT(10), IN `p_qntity` INT(10))  NO SQL
BEGIN
DECLARE counter INT ;

SELECT  COUNT(*) INTO counter FROM `shipment_temp` WHERE product_id = p_id and admin_id =a_id;

IF counter > 0 THEN
UPDATE shipment_temp set product_quantity = product_quantity + p_qntity WHERE admin_id = a_id and product_id = p_id;
ELSE
INSERT INTO shipment_temp (product_id , admin_id , product_quantity) VALUES (p_id , a_id , p_qntity);
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `shipment_req` (IN `a_id` INT)  NO SQL
BEGIN
DECLARE o_no, p_id , qntity INT;
declare k INT DEFAULT 0 ; 
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
read_loop: LOOP
FETCH cur_1 INTO p_id , qntity ;

IF b  = 1 THEN
LEAVE read_loop;
ELSE
INSERT INTO `shipment_product`(`shipment_id`, `product_id`, `product_quantity`) VALUES (o_no+1 , p_id ,qntity);
set k = k+1;
SELECT k ;
END IF;
END LOOP;


SELECT p_id , qntity;
CLOSE cur_1;
SET status = 'done' ;
DELETE FROM `shipment_temp` WHERE admin_id = a_id;
SELECT status;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `shipment_to_products` (IN `id` INT)  NO SQL
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `test1` ()  BEGIN
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
(1, 0, 969, 550, 363),
(2, 12, 132, 100, 0),
(3, 4, 268, 250, 0),
(4, 15, 235, 100, 0),
(5, 14, 334, 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(5) NOT NULL,
  `a_password` varchar(50) NOT NULL,
  `a_email` varchar(50) NOT NULL,
  `a_adress` varchar(50) NOT NULL,
  `a_mobile` int(50) NOT NULL,
  `u_status` varchar(50) NOT NULL,
  `u_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_name`
--

CREATE TABLE `admin_name` (
  `a_id` int(5) NOT NULL,
  `a_u_type` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(84, 'cart', 4, 'user', 0, 2, 3),
(89, 'cart', 12, 'user', 0, 7, 3),
(90, 'cart', 12, 'user', 0, 11, 2),
(91, 'cart', 4, 'user', 0, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(5) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(2, 1, 1, 20),
(3, 1, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `give_review`
--

CREATE TABLE `give_review` (
  `review_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `seller_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `g_user`
--

CREATE TABLE `g_user` (
  `g_u_id` int(5) NOT NULL,
  `g_u_password` varchar(50) NOT NULL,
  `g_u_address` varchar(50) NOT NULL,
  `g_u_email` varchar(50) NOT NULL,
  `g_u_mobile` int(50) NOT NULL,
  `u_status` varchar(50) NOT NULL,
  `u_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `g_user`
--

INSERT INTO `g_user` (`g_u_id`, `g_u_password`, `g_u_address`, `g_u_email`, `g_u_mobile`, `u_status`, `u_type`) VALUES
(1, '12', 'arfaf', 'riyad298@gmail.com', 1919448787, 'valid', 'g_user'),
(2, '12', 'arfaf', 'riyad@gmail.com', 1719246822, 'valid', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `g_user_name`
--

CREATE TABLE `g_user_name` (
  `g_u_id` int(5) NOT NULL,
  `u_type` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `counter` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `g_user_name`
--

INSERT INTO `g_user_name` (`g_u_id`, `u_type`, `first_name`, `last_name`, `counter`) VALUES
(1, 'user', 'Riyad', 'Ahsan', 1),
(2, 'user', 'Ahsan', 'Riyad', 2),
(115, 'AERF', 'AERF', 'AERFAE', 3),
(128, 'F', 'ff', 'ff', 4),
(129, 'F', 'ff', 'ff', 5),
(131, 'F', 'ff', 'ff', 6),
(133, 'F', 'ff', 'ff', 7),
(135, 'F', 'ff', 'ff', 8),
(137, 'F', 'ff', 'ff', 9),
(139, 'F', 'ff', 'ff', 10),
(141, 'F', 'ff', 'ff', 11),
(143, 'F', 'ff', 'ff', 12),
(145, 'F', 'ff', 'ff', 13),
(147, 'F', 'ff', 'ff', 14),
(149, 'F', 'ff', 'ff', 15),
(151, 'F', 'ff', 'ff', 16),
(153, 'F', 'ff', 'ff', 17),
(154, 'F', 'ff', 'ff', 18),
(1, 'user', 'ahsan', 'riyad', 19);

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
(7, '2019-04-19', '2019-04-20', 2, 2, 1, 20),
(8, '2019-04-21', '2019-04-21', 2, 2, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `msg`
--

CREATE TABLE `msg` (
  `msg_id` int(5) NOT NULL,
  `msg_text` varchar(50) NOT NULL,
  `msg_status` varchar(50) NOT NULL,
  `msg_reply` varchar(50) NOT NULL,
  `msg_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `msg-g_user-admin`
--

CREATE TABLE `msg-g_user-admin` (
  `a_id` int(5) NOT NULL,
  `a_type` varchar(50) NOT NULL,
  `g_u_id` int(5) NOT NULL,
  `g_type` varchar(50) NOT NULL,
  `msg_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `msg-seller-admin`
--

CREATE TABLE `msg-seller-admin` (
  `a_id` int(5) NOT NULL,
  `a_u_type` varchar(50) NOT NULL,
  `s_id` int(5) NOT NULL,
  `s_u_type` varchar(50) NOT NULL,
  `msg_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(23, 2, 4, 63),
(24, 3, 3, 64),
(24, 10, 3, 65),
(24, 12, 3, 66),
(26, 13, 0, 67),
(26, 13, 0, 68);

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
(23, '2019-04-19', 'default', '', 0, 15, 34, 235, 100, 'Choose...', 2),
(24, '2019-04-21', 'default', '', 0, 14, 35, 334, 100, 'Choose...', 2),
(25, '2019-04-21', 'default', '', 0, 14, 36, 0, 0, 'Choose...', 2),
(26, '2019-04-21', 'default', '', 0, 14, 37, 0, 0, 'Choose...', 2);

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
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `promo_id` int(5) NOT NULL,
  `promo_desc` varchar(50) NOT NULL,
  `Promo_expiry` date NOT NULL,
  `promo_percentage` int(50) NOT NULL,
  `promo_status` varchar(50) NOT NULL,
  `promo_limit` int(5) NOT NULL,
  `promo_use_count` int(5) NOT NULL,
  `a_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`promo_id`, `promo_desc`, `Promo_expiry`, `promo_percentage`, `promo_status`, `promo_limit`, `promo_use_count`, `a_id`) VALUES
(5, 'arf', '0000-00-00', 2, '3', 22, 22, 0),
(7, 'afre', '0000-00-00', 3, '3', 22, 222, 0),
(8, 'afre', '0000-00-00', 4, '3', 222, 22, 2),
(9, 'arfar', '0000-00-00', 3, '3', 23, 33, 2),
(10, 'afre', '0000-00-00', 4, '4', 12, 222, 2),
(11, 'afre', '0000-00-00', 4, '3', 345, 333, 2),
(12, 'afre', '0000-00-00', 2, '3', 122, 123, 2),
(13, 'afre', '0000-00-00', 2, '3', 122, 123, 2),
(16, 'afre', '2019-03-21', 3, '2', 22, 33, 2);

-- --------------------------------------------------------

--
-- Table structure for table `promo_use`
--

CREATE TABLE `promo_use` (
  `promo_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `g_u_type` varchar(50) NOT NULL,
  `user_use_date` date NOT NULL,
  `user_use_count` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(84, 2, 3, 91),
(85, 3, 7, 92),
(86, 8, 4, 93),
(87, 10, 3, 94),
(88, 15, 3, 95),
(89, 7, 2, 96),
(90, 11, 10, 97),
(91, 3, 4, 98),
(92, 4, 3, 99),
(93, 3, 3, 100),
(94, 10, 6, 101),
(95, 12, 3, 102),
(96, 15, 3, 103),
(97, 4, 3, 104),
(98, 8, 3, 105),
(99, 2, 3, 106),
(100, 10, 6, 107),
(101, 15, 3, 108),
(102, 15, 6, 109),
(103, 15, 11, 110),
(104, 4, 3, 111),
(105, 13, 5, 112),
(106, 13, 5, 113),
(107, 13, 4, 114);

-- --------------------------------------------------------

--
-- Table structure for table `p_include_wishlist`
--

CREATE TABLE `p_include_wishlist` (
  `wishlist_id` int(5) NOT NULL,
  `product_id` int(5) NOT NULL,
  `product_qntity` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `return_t`
--

CREATE TABLE `return_t` (
  `return_id` int(5) NOT NULL,
  `return_desc` varchar(50) NOT NULL,
  `return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(5) NOT NULL,
  `review_text` varchar(50) NOT NULL,
  `review_status` varchar(50) NOT NULL,
  `review_date` date NOT NULL,
  `product_id` int(5) NOT NULL,
  `user_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `review_text`, `review_status`, `review_date`, `product_id`, `user_id`) VALUES
(14, 'it is a good product', '', '2019-02-19', 8, 2),
(15, 'it is a good product', '', '2019-02-19', 7, 2),
(16, 'nope at least', '', '2019-03-29', 1, 2),
(17, 'really nice product', '', '2019-03-29', 9, 2),
(18, 'good products', '', '2019-04-02', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `s_id` int(5) NOT NULL,
  `s_password` varchar(50) NOT NULL,
  `s_address` varchar(50) NOT NULL,
  `s_email` varchar(50) NOT NULL,
  `s_mobile` int(50) NOT NULL,
  `u_status` varchar(50) NOT NULL,
  `u_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seller_name`
--

CREATE TABLE `seller_name` (
  `s_id` int(5) NOT NULL,
  `u_type` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, '2019-04-20', NULL, 0, 2, NULL),
(2, '2019-04-20', NULL, 0, 2, NULL),
(3, '2019-04-20', '2019-04-21', 2, 2, 2);

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
(207, 1, 3, 2),
(208, 1, 11, 2),
(209, 1, 16, 2),
(210, 1, 18, 2),
(211, 1, 19, 2),
(212, 1, 28, 2),
(213, 1, 29, 2),
(214, 2, 6, 4),
(215, 2, 17, 4),
(216, 2, 27, 4),
(217, 2, 29, 4),
(218, 2, 1, 4),
(219, 2, 10, 4),
(220, 3, 3, 4),
(221, 3, 10, 4),
(222, 3, 15, 4),
(223, 3, 19, 4);

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
(142, 2, 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `supply_contains`
--

CREATE TABLE `supply_contains` (
  `supply_id` int(5) NOT NULL,
  `product_id` int(5) NOT NULL,
  `product_qntity` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supply_order`
--

CREATE TABLE `supply_order` (
  `supply_id` int(5) NOT NULL,
  `supply_date` date NOT NULL,
  `supply_status` varchar(50) NOT NULL,
  `seller_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(34, '1212', NULL, 'riyad298@yahooo.com', 1919448787, '2006-02-12', 'valid', NULL, 'Ahsan', 'Riyad');

-- --------------------------------------------------------

--
-- Table structure for table `user_name`
--

CREATE TABLE `user_name` (
  `U_id` int(5) NOT NULL,
  `U_type` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `counter` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_name`
--

INSERT INTO `user_name` (`U_id`, `U_type`, `first_name`, `last_name`, `counter`) VALUES
(155, 'F', 'ff', 'ff', 1),
(156, 'F', 'ff', 'ff', 2),
(157, 'user', 'afrfa', 'rfa', 3),
(158, 'user', 'afre', 'afr', 4),
(1, 'user', 'afrfa', 'afr', 5),
(5, 'user', 'afrfa', 'afr', 6),
(7, 'user', 'afrfa', 'rfa', 7),
(9, 'arf', 'refa', 'raefa', 8),
(10, 'arfea', 'afer', 'arfa', 9),
(11, 'afra', 'arfa', 'rfaf', 10),
(12, 'user', 'Muhammad Ahsan', 'Riyad', 11),
(14, 'user', 'afrfa', 'rfa', 12),
(16, 'user', 'Muhammad Ahsan', 'Riyad', 13),
(18, 'user', 'Muhammad Ahsan', 'Riyad', 14),
(20, 'user', 'Muhammad Ahsan', 'Riyad', 15);

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `product_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL DEFAULT '0',
  `user_ip` varchar(50) NOT NULL,
  `hit_count` int(5) NOT NULL DEFAULT '0',
  `counter` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`product_id`, `user_id`, `user_ip`, `hit_count`, `counter`) VALUES
(3, 0, '127.0.0.1', 0, 27),
(11, 0, '127.0.0.1', 0, 28),
(10, 0, '127.0.0.1', 0, 29),
(2, 0, '127.0.0.1', 0, 30),
(8, 0, '127.0.0.1', 0, 31),
(4, 0, '127.0.0.1', 0, 32),
(12, 0, '127.0.0.1', 0, 33),
(1, 0, '127.0.0.1', 0, 34),
(7, 0, '127.0.0.1', 0, 35),
(9, 0, '127.0.0.1', 0, 36);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(5) NOT NULL,
  `wishlist_status` varchar(50) NOT NULL,
  `user_id` int(5) NOT NULL,
  `g_u_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD UNIQUE KEY `cart_id` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

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
-- Indexes for table `g_user`
--
ALTER TABLE `g_user`
  ADD PRIMARY KEY (`g_u_id`);

--
-- Indexes for table `g_user_name`
--
ALTER TABLE `g_user_name`
  ADD PRIMARY KEY (`counter`);

--
-- Indexes for table `money_transfer`
--
ALTER TABLE `money_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `msg`
--
ALTER TABLE `msg`
  ADD PRIMARY KEY (`msg_id`);

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
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD UNIQUE KEY `promo_id` (`promo_id`);

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
-- Indexes for table `return_t`
--
ALTER TABLE `return_t`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`s_id`);

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
-- Indexes for table `supply_order`
--
ALTER TABLE `supply_order`
  ADD PRIMARY KEY (`supply_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `u_email` (`u_email`),
  ADD UNIQUE KEY `u_email_2` (`u_email`);

--
-- Indexes for table `user_name`
--
ALTER TABLE `user_name`
  ADD PRIMARY KEY (`counter`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`counter`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factory`
--
ALTER TABLE `factory`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `factory_materials`
--
ALTER TABLE `factory_materials`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `g_user`
--
ALTER TABLE `g_user`
  MODIFY `g_u_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `g_user_name`
--
ALTER TABLE `g_user_name`
  MODIFY `counter` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `money_transfer`
--
ALTER TABLE `money_transfer`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `msg`
--
ALTER TABLE `msg`
  MODIFY `msg_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_includ_product`
--
ALTER TABLE `order_includ_product`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `order_t`
--
ALTER TABLE `order_t`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `promo_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `p_include_cart`
--
ALTER TABLE `p_include_cart`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `return_t`
--
ALTER TABLE `return_t`
  MODIFY `return_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `s_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shipment_product`
--
ALTER TABLE `shipment_product`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `shipment_temp`
--
ALTER TABLE `shipment_temp`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `supply_order`
--
ALTER TABLE `supply_order`
  MODIFY `supply_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user_name`
--
ALTER TABLE `user_name`
  MODIFY `counter` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
