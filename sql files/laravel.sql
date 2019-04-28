-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2019 at 10:22 AM
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

select SUM(pr.product_price*p.product_qntity) as total from cart c , p_include_cart p , products pr where p.cart_id = c.cart_id and p.product_id = pr.product_id and c.user_id = uid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `money_transfer` (IN `admin_id` INT, IN `amount_tk` INT)  NO SQL
BEGIN

insert into money_transfer (transfer_date , transfered_by , amount , status) VALUES (SYSDATE() , admin_id , amount_tk , 0);

UPDATE account set balance_available = balance_available - amount_tk where user_id = 0;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `order_t` (IN `uid` INT, IN `p_method` VARCHAR(20))  BEGIN
DECLARE o_no, p_id , qntity, total INT;
DECLARE status VARCHAR(20);
DECLARE b INT DEFAULT 0;
DECLARE cur_1 CURSOR FOR 
SELECT product_id , quantity FROM CART WHERE user_id = uid;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;

SELECT MAX(order_id) INTO o_no FROM ORDER_T;

select SUM(pr.product_price*p.product_qntity) into total from cart c , p_include_cart p , products pr where p.cart_id = c.cart_id and p.product_id = pr.product_id and c.user_id = uid;

INSERT INTO `order_t`(`order_id`, `order_date`, `payment_method`,  `user_id` , `total_amount`) VALUES (o_no+1 , sysdate() , p_method , uid , total );

OPEN cur_1;
read_loop: LOOP
FETCH cur_1 INTO p_id , qntity ;


IF b  = 1 THEN
LEAVE read_loop;
ELSE
INSERT INTO `order_includ_product`(`order_id`, `product_id`, `qntity`) VALUES (o_no+1 , p_id ,qntity);
SELECT p_id , qntity;

END IF;
END LOOP;

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
(1, 0, 9616, 1424, 1324),
(6, 14, 800, 100, 0),
(7, 18, 2157, 500, 0),
(8, 15, 5653, 812, 0),
(9, 16, 470, 12, 0),
(10, 35, 536, 0, 0);

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
-- Stand-in structure for view `all_sales`
-- (See below for the actual view)
--
CREATE TABLE `all_sales` (
`order_id` int(5)
,`order_date` date
,`payment_method` varchar(50)
,`payment_status` varchar(50)
,`return_id` int(5)
,`user_id` int(8)
,`counter` int(8)
,`total_amount` int(11)
,`paid` int(10)
,`sales_point` varchar(50)
,`admin_id` int(8)
,`due` bigint(12)
,`last_name` varchar(50)
);

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
(27, 'cart', 36, 'user', 0, 4, 3),
(28, 'cart', 36, 'user', 0, 12, 5),
(29, 'cart', 36, 'user', 0, 19, 5);

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
-- Stand-in structure for view `daily_sales`
-- (See below for the actual view)
--
CREATE TABLE `daily_sales` (
`order_id` int(5)
,`order_date` date
,`payment_method` varchar(50)
,`payment_status` varchar(50)
,`return_id` int(5)
,`user_id` int(8)
,`counter` int(8)
,`total_amount` int(11)
,`paid` int(10)
,`sales_point` varchar(50)
,`admin_id` int(8)
,`due` bigint(12)
,`last_name` varchar(50)
);

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
(1, 'Ranpur', 'Rangpur'),
(2, 'Kurigram', 'Kurigram'),
(3, 'Dhaka', 'Dhaka'),
(4, 'Bogra', 'Bogra');

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
(8, 2, 1, 20);

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
(1, '2019-04-22', '2019-04-22', 2, 4, 1, 100);

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
(1, 3, 2, 69),
(1, 10, 2, 70),
(1, 14, 2, 71),
(2, 15, 7, 72),
(2, 5, 3, 73),
(2, 24, 4, 74),
(3, 3, 1, 75),
(3, 6, 3, 76),
(3, 14, 5, 77),
(4, 3, 3, 78),
(4, 6, 3, 79),
(4, 14, 5, 80),
(4, 25, 5, 81),
(4, 15, 2, 82),
(4, 15, 2, 83),
(5, 15, 7, 84),
(5, 5, 3, 85),
(5, 24, 4, 86),
(5, 28, 4, 87),
(5, 29, 4, 88),
(5, 9, 3, 89),
(5, 9, 3, 90),
(6, 3, 3, 91),
(6, 14, 3, 92),
(6, 18, 3, 93),
(6, 29, 6, 94),
(6, 29, 6, 95),
(7, 2, 2, 96),
(7, 2, 2, 97),
(8, 1, 7, 98),
(8, 2, 2, 99),
(8, 2, 2, 100),
(9, 11, 3, 101),
(9, 11, 3, 102),
(10, 3, 2, 103),
(10, 12, 2, 104),
(10, 12, 2, 105),
(11, 8, 3, 106),
(11, 8, 3, 107),
(12, 7, 2, 108),
(12, 10, 1, 109),
(12, 11, 1, 110),
(12, 12, 1, 111),
(12, 12, 1, 112),
(13, 7, 3, 113),
(13, 1, 1, 114),
(13, 1, 1, 115),
(14, 8, 1, 116),
(14, 1, 1, 117),
(14, 12, 3, 118),
(14, 12, 3, 119),
(15, 1, 3, 120),
(15, 7, 4, 121),
(15, 7, 4, 122),
(16, 2, 2, 123),
(16, 2, 2, 124),
(17, 10, 1, 125),
(17, 1, 1, 126),
(17, 1, 1, 127),
(18, 1, 4, 128),
(18, 1, 4, 129),
(19, 1, 4, 130),
(19, 1, 4, 131),
(20, 11, 2, 132),
(20, 11, 2, 133),
(21, 9, 1, 134),
(22, 2, 2, 135),
(22, 10, 2, 136),
(22, 14, 2, 137),
(22, 14, 2, 138),
(23, 12, 2, 139),
(24, 11, 4, 140);

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
(1, '2019-04-22', 'default', '', 0, 14, 38, 800, 100, 'Agrabad', 2),
(2, '2019-04-22', 'default', '', 0, 18, 39, 726, 200, 'Agrabad', 2),
(3, '2019-04-22', 'default', '', 0, 15, 40, 1472, 100, 'Agrabad', 2),
(4, '2019-04-22', 'default', '', 0, 15, 41, 1604, 200, 'New Delhi', 2),
(5, '2019-04-22', 'default', '', 0, 18, 42, 1431, 300, 'Agrabad', 2),
(6, '2019-04-22', 'default', '', 0, 15, 43, 1101, 400, 'New Delhi', 2),
(7, '2019-04-22', 'default', '', 0, 16, 44, 470, 12, 'Choose...', 2),
(8, '2019-04-27', 'cash', '', 0, 2, 45, 0, 0, 'default', 0),
(9, '2019-04-27', 'cash', '', 0, 12, 46, 0, 0, 'default', 0),
(10, '2019-04-27', 'default', '', 0, 35, 47, 536, 0, 'Kurigram', 12),
(11, '2019-04-28', 'bkash', '', 0, 12, 48, 528, 0, 'default', 0),
(12, '2019-04-28', 'cash', '', 0, 12, 49, 8054, 0, 'default', 0),
(13, '2019-04-28', 'cash', '', 0, 12, 50, 4492, 0, 'default', 0),
(14, '2019-04-28', 'cash', '', 0, 12, 51, 1095, 0, 'default', 0),
(15, '2019-04-28', 'cash', '', 0, 12, 52, 1471, 0, 'default', 0),
(16, '2019-04-28', 'default', '', 0, 15, 53, 470, 12, 'Kurigram', 12),
(17, '2019-04-28', 'bkash', '', 0, 12, 54, 66, 0, 'default', 0),
(18, '2019-04-28', 'nexus', '', 0, 12, 55, 132, 0, 'default', 0),
(19, '2019-04-28', 'nexus', '', 0, 12, 56, 132, 0, 'default', 0),
(20, '2019-04-28', 'ggffre', '', 0, 12, 57, 66, 0, 'default', 0),
(21, '2019-04-28', 'cash', '', 0, 12, 58, 343, 0, 'default', 0),
(22, '2019-04-28', 'default', '', 0, 15, 59, 1006, 100, 'Kurigram', 12),
(23, '2019-04-28', 'bkash', '', 0, 12, 60, 686, 0, 'default', 0),
(24, '2019-04-28', 'nexus', '', 0, 12, 61, 132, 0, 'default', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(5) NOT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `product_price` int(5) DEFAULT NULL,
  `product_avlble` int(5) DEFAULT NULL,
  `product_sell_price` int(5) DEFAULT NULL,
  `product_original_price` int(5) DEFAULT NULL,
  `category_id` int(5) DEFAULT NULL,
  `descriptions` varchar(100) DEFAULT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `image` varchar(100) NOT NULL DEFAULT 'img/cat1.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_avlble`, `product_sell_price`, `product_original_price`, `category_id`, `descriptions`, `category_name`, `sub_category`, `image`) VALUES
(1, 'Monitor LG', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg', 'img/cat1.jpg'),
(2, 'Monitor Samsung ', 343, 51, 235, 2356, 346, 'useful for home', 'monitor', 'samsung', 'img/cat1.jpg'),
(3, 'Monitor Walton', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'walton', 'img/cat1.jpg'),
(4, 'hard disk 1TB', 343, 34, 235, 2356, 346, 'useful for home', 'hdd', 'toshiba', 'img/cat1.jpg'),
(5, 'hard disk 2TB', 33, 75, 33, 34, 34, 'useful for home', 'hdd', 'western_digital', 'img/cat1.jpg'),
(6, 'hard disk 4TB', 33, 37, 33, 34, 34, 'useful for home', 'hdd', 'adata', 'img/cat1.jpg'),
(7, 'Printer Canon', 343, 34, 235, 2356, 346, 'useful for home', 'printer', 'canon', 'img/cat1.jpg'),
(8, 'Printer HP', 33, 33, 33, 34, 34, 'useful for home', 'printer', 'hp', 'img/cat1.jpg'),
(9, 'Ram Transcend', 343, 34, 235, 2356, 346, 'useful for home', 'ram', 'transcend', 'img/cat1.jpg'),
(10, 'Ram Adata', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'adata', 'img/cat1.jpg'),
(11, 'Ram Razor', 33, 39, 33, 34, 34, 'useful for home', 'ram', 'razor', 'img/cat1.jpg'),
(12, 'Motherboard GigaByte', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'gigabyte', 'img/cat1.jpg'),
(13, 'Motherboard Asus', 33, 33, 33, 34, 34, 'useful for home', 'motherboard', 'asus', 'img/cat1.jpg'),
(14, 'Motherboard Intel', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'intel', 'img/cat1.jpg'),
(15, 'Processor Intel', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg'),
(16, 'Processor AMD', 33, 34, 235, 34, 34, 'Computer processor , high quality', 'processor', 'amd', 'img/cat1.jpg'),
(17, 'Monitor LG Full HD', 33, 39, 33, 34, 34, 'useful for home', 'monitor', 'lg', 'img/cat1.jpg'),
(18, 'Monitor LG Full HD', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg', 'img/cat1.jpg'),
(19, 'Monitor Samsung 4K', 343, 34, 235, 2356, 346, 'useful for home', 'monitor', 'samsung', 'img/cat1.jpg'),
(20, 'Monitor Walton Plasma', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'walton', 'img/cat1.jpg'),
(21, 'hard disk 1TB 5400rpm', 343, 34, 235, 2356, 346, 'useful for home', 'hdd', 'toshiba', 'img/cat1.jpg'),
(22, 'hard disk 4TB 7200rpm', 33, 33, 33, 34, 34, 'useful for home', 'hdd', 'adata', 'img/cat1.jpg'),
(23, 'Printer Canon For Photos', 343, 34, 235, 2356, 346, 'useful for home', 'printer', 'canon', 'img/cat1.jpg'),
(24, 'Ram Adata 8GB', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'adata', 'img/cat1.jpg'),
(25, 'Ram Razor 16GB', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'razor', 'img/cat1.jpg'),
(26, 'Printer HP 1080P', 33, 33, 33, 34, 34, 'useful for home', 'printer', 'hp', 'img/cat1.jpg'),
(27, 'Motherboard Intel Gaming', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'intel', 'img/cat1.jpg'),
(28, 'Processor Intel Core i3', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg'),
(29, 'Processor Intel Core i5', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg'),
(30, 'Processor Intel Core i9', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg'),
(31, 'arfeaf', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C:\\xampp\\tmp\\phpFE53.tmp'),
(32, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/girl.png'),
(33, 'Afsana Moon', 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/boy32png'),
(34, 'Afsana Moon', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/girl33png'),
(35, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/.girl34png'),
(36, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/.girl35png'),
(37, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/girl36.png'),
(38, 'faerf', 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/boy37.png'),
(39, 'faerf', 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/boy38.png');

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
(22, 8, 1, 159),
(23, 1, 1, 160),
(24, 12, 3, 161),
(25, 1, 3, 162),
(26, 7, 4, 163),
(27, 4, 3, 164),
(28, 12, 5, 165),
(29, 19, 5, 166),
(30, 2, 2, 167),
(31, 10, 1, 168),
(32, 1, 1, 169),
(33, 1, 4, 170),
(34, 1, 4, 171),
(35, 11, 2, 172),
(36, 9, 1, 173),
(37, 2, 2, 174),
(38, 10, 2, 175),
(39, 14, 2, 176),
(40, 12, 2, 177),
(41, 11, 4, 178),
(42, 4, 3, 179),
(43, 11, 3, 180),
(44, 16, 3, 181),
(45, 28, 3, 182);

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
(16, 'afafearfaerf', '', '2019-04-27', 1, 2),
(17, 'really nice product', '', '2019-03-29', 9, 2),
(18, 'good products', '', '2019-04-02', 2, 2),
(19, 'hellow', '', '2019-04-27', 11, 12);

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
(1, '2019-04-22', '2019-04-22', 1, 4, 2),
(2, '2019-04-22', '2019-04-22', 2, 4, 2),
(3, '2019-04-22', '2019-04-22', 1, 4, 2),
(4, '2019-04-22', NULL, 0, 4, NULL),
(5, '2019-04-22', NULL, 0, 4, NULL),
(6, '2019-04-28', NULL, 0, 12, NULL),
(7, '2019-04-28', NULL, 0, 12, NULL);

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
(226, 1, 2, 2),
(227, 1, 8, 2),
(228, 1, 15, 2),
(229, 2, 17, 6),
(230, 2, 4, 8),
(231, 2, 18, 3),
(232, 2, 28, 5),
(233, 2, 10, 5),
(234, 3, 16, 3),
(235, 3, 4, 3),
(236, 3, 28, 3),
(237, 3, 30, 3),
(238, 4, 5, 4),
(239, 4, 17, 4),
(240, 5, 4, 3),
(241, 5, 12, 3),
(242, 5, 17, 3),
(243, 5, 19, 3),
(244, 6, 3, 5),
(245, 6, 9, 4),
(246, 6, 14, 7),
(247, 6, 16, 3),
(248, 6, 28, 3),
(249, 7, 10, 3),
(250, 7, 17, 3),
(251, 7, 19, 3);

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
  `last_name` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'both'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_password`, `u_address`, `u_email`, `u_mobile`, `dob`, `u_status`, `u_type`, `first_name`, `last_name`, `country`) VALUES
(2, '123456', '', 'riyad298@gmail.com', 1919448787, '2007-02-17', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad', 'india'),
(4, '123456', '', 'riyad298@yahoo.com', 666, '2007-02-14', 'valid', 'admin', 'Muhammad Ahsan', 'Ahsan', 'bangladesh'),
(12, '123456', '', 'riyad298@hotmail.com', 1919448787, '2007-03-15', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad', 'both'),
(14, 'arefa', '', 'riyadhellow298@gmail.com', 1919448787, '0000-00-00', 'valid', 'user', 'Muhammad Ahsan', 'Riyad', NULL),
(15, '448787', '', 'riyad298@outlook.com', 1919448787, '0000-00-00', 'valid', 'user', 'Ahsan', 'Riyad', NULL),
(19, '111', '', 'riyad28877722@gmail.com', 1919448787, '0000-00-00', 'valid', 'admin', 'sde', 'edf', NULL),
(20, 'ffaf', NULL, 'riyad298faerfaer', 1919448787, '2007-02-14', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad', NULL),
(29, '11', NULL, 'riyadmail@gmail.com', 1919448787, '2008-01-09', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad', NULL),
(39, 'fff', NULL, 'frafeaf', 0, '0000-00-00', 'valid', 'aferfea', NULL, 'fff', 'ffaf'),
(44, 'rrrrrrr', NULL, 'riyadajofaeorfeaofj@gmail.com', 1919448787, '2006-02-13', 'valid', 'bangladesh', NULL, 'Riyad', 'admin'),
(45, 'aerreafreafer', NULL, 'riyaarferferdffrea298@gmail.com', 1919448787, '2006-03-16', 'valid', 'admin', NULL, 'Riyad', 'bangladesh'),
(46, 'aaaaaa', NULL, 'riz@gmail.com', 1919448787, '2007-01-16', 'valid', 'admin', NULL, 'Riyad', 'bangladesh'),
(47, '1111111111', NULL, 'rarfaiyaerfaeadafe298@gmail.com', 0, NULL, NULL, 'user', NULL, 'faerferfearfae', NULL),
(48, '1111111111', NULL, 'faerfaer@gmail.com', 0, NULL, NULL, 'user', NULL, 'rfearfea', NULL),
(49, '123456', NULL, 'ariyfadfff298@gmail.com', 1919448787, NULL, 'valid', NULL, NULL, 'Riyad', 'bangladesh'),
(51, '123456', NULL, 'rifyafd29f8f@gmail.com', 1919448787, NULL, 'valid', 'admin', NULL, 'Riyad', 'both');

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
(9, 0, '127.0.0.1', 0, 36),
(37, 0, '127.0.0.1', 0, 37),
(32, 0, '127.0.0.1', 0, 38);

-- --------------------------------------------------------

--
-- Table structure for table `visitcounter`
--

CREATE TABLE `visitcounter` (
  `total` int(8) NOT NULL DEFAULT '0',
  `id` int(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visitcounter`
--

INSERT INTO `visitcounter` (`total`, `id`) VALUES
(1599, 0);

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

-- --------------------------------------------------------

--
-- Structure for view `all_sales`
--
DROP TABLE IF EXISTS `all_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_sales`  AS  select `o`.`order_id` AS `order_id`,`o`.`order_date` AS `order_date`,`o`.`payment_method` AS `payment_method`,`o`.`payment_status` AS `payment_status`,`o`.`return_id` AS `return_id`,`o`.`user_id` AS `user_id`,`o`.`counter` AS `counter`,`o`.`total_amount` AS `total_amount`,`o`.`paid` AS `paid`,`o`.`sales_point` AS `sales_point`,`o`.`admin_id` AS `admin_id`,(`o`.`total_amount` - `o`.`paid`) AS `due`,`u`.`last_name` AS `last_name` from (`order_t` `o` join `user` `u`) where (`u`.`u_id` = `o`.`user_id`) order by `o`.`order_id` desc ;

-- --------------------------------------------------------

--
-- Structure for view `daily_sales`
--
DROP TABLE IF EXISTS `daily_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `daily_sales`  AS  select `o`.`order_id` AS `order_id`,`o`.`order_date` AS `order_date`,`o`.`payment_method` AS `payment_method`,`o`.`payment_status` AS `payment_status`,`o`.`return_id` AS `return_id`,`o`.`user_id` AS `user_id`,`o`.`counter` AS `counter`,`o`.`total_amount` AS `total_amount`,`o`.`paid` AS `paid`,`o`.`sales_point` AS `sales_point`,`o`.`admin_id` AS `admin_id`,(`o`.`total_amount` - `o`.`paid`) AS `due`,`u`.`last_name` AS `last_name` from (`order_t` `o` join `user` `u`) where ((`o`.`order_date` = curdate()) and (`u`.`u_id` = `o`.`user_id`)) order by `o`.`order_id` desc ;

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
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factory`
--
ALTER TABLE `factory`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `factory_materials`
--
ALTER TABLE `factory_materials`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `msg`
--
ALTER TABLE `msg`
  MODIFY `msg_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_includ_product`
--
ALTER TABLE `order_includ_product`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `order_t`
--
ALTER TABLE `order_t`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `promo_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `p_include_cart`
--
ALTER TABLE `p_include_cart`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

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
  MODIFY `review_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `s_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipment_product`
--
ALTER TABLE `shipment_product`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `shipment_temp`
--
ALTER TABLE `shipment_temp`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supply_order`
--
ALTER TABLE `supply_order`
  MODIFY `supply_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_name`
--
ALTER TABLE `user_name`
  MODIFY `counter` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
