-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2019 at 12:35 AM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `avgRating` (IN `p_id` INT, OUT `rat` INT)  BEGIN
select avg(rating) into rat from review where product_id = p_id;

if(rat IS NULL)
then
set rat = 0;
end if;
END$$

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
read_loop: LOOP
FETCH cur_1 INTO p_id , qntity ;


IF b  = 1 THEN
LEAVE read_loop;
ELSE
if p_id is NOT NULL
then
INSERT INTO `order_includ_product`(`order_id`, `product_id`, `qntity`) VALUES (o_no+1 , p_id ,qntity);
end if;


SELECT p_id , qntity;


END IF;
END LOOP;
CLOSE cur_1;


SET status = 'done' ;
DELETE FROM `cart` WHERE user_id = uid;
SELECT status;
end$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `order_invoice` (IN `uid` INT, OUT `order_id_max` INT, OUT `total` INT, OUT `date` DATE)  begin
select max(order_id) into order_id_max from order_t; 
select total_amount into total from order_t where order_id = order_id_max;
select order_date into date from order_t where order_id = order_id_max;
end$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `review` (IN `uid` INT, IN `pid` INT, IN `rev_text` VARCHAR(50), IN `rev_date` DATE, IN `rat` INT)  BEGIN

DECLARE rev_id INT;
DECLARE status VARCHAR(20);
SET status = 'DONE';

SELECT review_id INTO rev_id FROM `review` WHERE product_id = pid and user_id = uid;

IF rev_id IS NOT NULL
THEN

UPDATE `review` SET `review_text`= rev_text ,`review_date`= rev_date , rating = rat WHERE product_id= pid AND user_id= uid;

SELECT status;


ELSE

INSERT INTO `review`( `review_text`, `review_date`, `product_id`, `user_id` , rating) VALUES (rev_text , rev_date , pid , uid , rat ) ; 

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `test` (IN `abc` VARCHAR(50), OUT `order_id_max` VARCHAR(50))  begin
set order_id_max = abc;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `test1` ()  BEGIN
SELECT * FROM USER;
SELECT * FROM products;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `userRating` (IN `p_id` INT, IN `uid` INT, OUT `rat` INT)  BEGIN
select rating into rat  from review where user_id = uid and product_id = p_id;

if(rat IS NULL)
then
set rat = 0;
end if;
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
(1, 0, 15652, 1508, 1408),
(6, 14, 6366, 172, 0),
(7, 18, 2157, 500, 0),
(8, 15, 6123, 824, 0),
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
(111, 'cart', 12, 'user', 0, 3, 1);

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
  `msg_date` date NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_email` varchar(50) DEFAULT NULL
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
  `msg_id` int(5) NOT NULL,
  `id` int(10) NOT NULL
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
(1, 2, 2, 141),
(1, 9, 5, 142),
(1, 15, 4, 143),
(1, 15, 4, 144),
(2, 3, 2, 145),
(2, 3, 2, 146),
(3, 9, 2, 147),
(3, 9, 2, 148),
(4, 2, 2, 149),
(4, 2, 2, 150),
(5, 1, 1, 151),
(6, 11, 1, 152),
(6, 8, 3, 153),
(6, 10, 3, 154),
(6, 7, 1, 155),
(7, 2, 2, 156),
(7, 4, 7, 157),
(7, 14, 4, 158),
(8, 3, 5, 159),
(9, 11, 1, 160),
(9, 12, 2, 161),
(9, 4, 1, 162),
(9, 3, 4, 163),
(10, 3, 1, 164),
(11, 2, 1, 165),
(12, 3, 1, 166),
(13, 1, 1, 167),
(15, 8, 3, 168),
(15, 10, 2, 169),
(16, 8, 1, 170),
(17, 3, 1, 171),
(18, 1, 1, 172),
(19, 11, 1, 173),
(20, 2, 5, 174),
(21, 8, 1, 175),
(22, 9, 1, 176),
(23, 9, 1, 177),
(23, 3, 1, 178),
(24, 11, 1, 179),
(25, 7, 1, 180),
(26, 11, 1, 181),
(27, 4, 1, 182),
(28, 3, 1, 183),
(29, 9, 3, 184),
(29, 1, 1, 185),
(30, 10, 1, 186),
(31, 5, 1, 187),
(32, 9, 1, 188),
(33, 3, 1, 189),
(34, 2, 1, 190),
(35, 3, 1, 191),
(36, 8, 1, 192),
(37, 8, 1, 193),
(38, 1, 1, 194),
(39, 8, 1, 195),
(40, 8, 1, 196),
(41, 11, 2, 197),
(42, 3, 1, 198),
(43, 3, 1, 199),
(44, 4, 1, 200),
(45, 9, 1, 201),
(46, 9, 3, 202),
(46, 1, 1, 203),
(46, 11, 1, 204),
(46, 2, 1, 205),
(47, 1, 1, 206),
(50, 3, 1, 207),
(51, 8, 1, 208),
(52, 3, 1, 209),
(53, 9, 3, 210),
(53, 10, 3, 211),
(54, 2, 1, 212),
(55, 3, 1, 213),
(55, 20, 1, 214),
(56, 9, 1, 215),
(57, 9, 1, 216),
(58, 3, 1, 217),
(59, 3, 1, 218),
(60, 2, 1, 219),
(61, 2, 1, 220),
(62, 3, 1, 221),
(63, 3, 1, 222),
(64, 2, 1, 223),
(65, 2, 1, 224),
(66, 11, 1, 225),
(66, 2, 1, 226);

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
(1, '2019-05-01', 'default', '', 0, 14, 62, 1777, 12, 'Kurigram', 12),
(2, '2019-05-01', 'default', '', 0, 14, 63, 66, 12, 'Rangpur', 12),
(3, '2019-05-01', 'default', '', 0, 15, 64, 470, 12, 'Kurigram', 12),
(4, '2019-05-01', 'default', '', 0, 14, 65, 470, 12, 'Dhaka', 12),
(5, '2019-05-01', 'default', '', 0, 14, 66, 33, 12, 'Rangpur', 12),
(6, '2019-05-01', 'bkash', '', 0, 12, 67, 574, 0, 'default', 0),
(7, '2019-05-01', 'default', '', 0, 14, 68, 3055, 12, 'Kurigram', 12),
(8, '2019-05-01', 'default', '', 0, 14, 69, 165, 12, 'Choose...', 12),
(9, '2019-05-01', 'bkash', '', 0, 12, 70, 1194, 0, 'default', 0),
(10, '2019-05-01', 'cash', '', 0, 12, 71, 33, 0, 'default', 0),
(11, '2019-05-01', 'bkash', '', 0, 12, 72, 343, 0, 'default', 0),
(12, '2019-05-01', 'bkash', '', 0, 12, 73, 33, 0, 'default', 0),
(13, '2019-05-01', 'cash', '', 0, 12, 74, 33, 0, 'default', 0),
(14, '2019-05-01', 'cash', '', 0, 12, 75, NULL, 0, 'default', 0),
(15, '2019-05-01', 'bkash', '', 0, 12, 76, 165, 0, 'default', 0),
(16, '2019-05-01', 'bkash', '', 0, 12, 77, 33, 0, 'default', 0),
(17, '2019-05-01', 'card', '', 0, 12, 78, 33, 0, 'default', 0),
(18, '2019-05-01', 'card', '', 0, 12, 79, 33, 0, 'default', 0),
(19, '2019-05-01', 'cash', '', 0, 12, 80, 33, 0, 'default', 0),
(20, '2019-05-02', 'card', '', 0, 12, 81, 1715, 0, 'default', 0),
(21, '2019-05-02', 'cash', '', 0, 12, 82, 33, 0, 'default', 0),
(22, '2019-05-02', 'bkash', '', 0, 12, 83, 343, 0, 'default', 0),
(23, '2019-05-02', 'bkash', '', 0, 12, 84, 376, 0, 'default', 0),
(24, '2019-05-02', 'bkash', '', 0, 12, 85, 33, 0, 'default', 0),
(25, '2019-05-02', 'nexus', '', 0, 12, 86, 343, 0, 'default', 0),
(26, '2019-05-02', 'cash', '', 0, 12, 87, 33, 0, 'default', 0),
(27, '2019-05-02', 'cash', '', 0, 12, 88, 343, 0, 'default', 0),
(28, '2019-05-02', 'cash', '', 0, 12, 89, 33, 0, 'default', 0),
(29, '2019-05-02', 'cash', '', 0, 12, 90, 1062, 0, 'default', 0),
(30, '2019-05-02', 'bkash', '', 0, 12, 91, 33, 0, 'default', 0),
(31, '2019-05-02', 'cash', '', 0, 12, 92, 33, 0, 'default', 0),
(32, '2019-05-03', 'cash', '', 0, 12, 93, 343, 0, 'default', 0),
(33, '2019-05-03', 'card', '', 0, 12, 94, 66, 0, 'default', 0),
(34, '2019-05-03', 'cash', '', 0, 12, 95, 1372, 0, 'default', 0),
(35, '2019-05-03', 'cash', '', 0, 12, 96, 66, 0, 'default', 0),
(36, '2019-05-03', 'bkash', '', 0, 12, 97, 66, 0, 'default', 0),
(37, '2019-05-03', 'bkash', '', 0, 12, 98, 66, 0, 'default', 0),
(38, '2019-05-03', 'jkljkl', '', 0, 12, 99, 376, 0, 'default', 0),
(39, '2019-05-03', 'cash', '', 0, 12, 100, 33, 0, 'default', 0),
(40, '2019-05-03', 'nexus', '', 0, 12, 101, 33, 0, 'default', 0),
(41, '2019-05-03', 'bkash', '', 0, 12, 102, 66, 0, 'default', 0),
(42, '2019-05-03', 'cash', '', 0, 12, 103, 33, 0, 'default', 0),
(43, '2019-05-03', 'cash', '', 0, 12, 104, 33, 0, 'default', 0),
(44, '2019-05-03', 'cash', '', 0, 12, 105, 343, 0, 'default', 0),
(45, '2019-05-03', 'bkash', '', 0, 12, 106, 343, 0, 'default', 0),
(46, '2019-05-03', 'bkash', '', 0, 12, 107, 1438, 0, 'default', 0),
(47, '2019-05-03', 'cash', '', 0, 12, 108, 66, 0, 'default', 0),
(48, '2019-05-03', 'cash', '', 0, 12, 109, NULL, 0, 'default', 0),
(49, '2019-05-03', 'cash', '', 0, 12, 110, NULL, 0, 'default', 0),
(50, '2019-05-03', 'cash', '', 0, 12, 111, 376, 0, 'default', 0),
(51, '2019-05-03', 'bkash', '', 0, 12, 112, 33, 0, 'default', 0),
(52, '2019-05-03', 'bkash', '', 0, 12, 113, 33, 0, 'default', 0),
(53, '2019-05-03', 'cash', '', 0, 12, 114, 1128, 0, 'default', 0),
(54, '2019-05-04', 'bkash', '', 0, 12, 115, 343, 0, 'default', 0),
(55, '2019-05-04', 'cash', '', 0, 12, 116, 66, 0, 'default', 0),
(56, '2019-05-04', 'cash', '', 0, 12, 117, 343, 0, 'default', 0),
(57, '2019-05-04', 'cash', '', 0, 12, 118, 343, 0, 'default', 0),
(58, '2019-05-04', 'cash', '', 0, 12, 119, 33, 0, 'default', 0),
(59, '2019-05-04', 'bkash', '', 0, 12, 120, 33, 0, 'default', 0),
(60, '2019-05-04', 'cash', '', 0, 12, 121, 343, 0, 'default', 0),
(61, '2019-05-04', 'card', '', 0, 12, 122, 409, 0, 'default', 0),
(62, '2019-05-04', 'cash', '', 0, 12, 123, 409, 0, 'default', 0),
(63, '2019-05-04', 'cash', '', 0, 12, 124, 66, 0, 'default', 0),
(64, '2019-05-04', 'cash', '', 0, 12, 125, 376, 0, 'default', 0),
(65, '2019-05-04', 'cash', '', 0, 12, 126, 1372, 0, 'default', 0),
(66, '2019-05-04', 'cash', '', 0, 12, 127, 818, 0, 'default', 0);

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
  `image` varchar(100) NOT NULL DEFAULT 'img/cat1.jpg',
  `rating` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_avlble`, `product_sell_price`, `product_original_price`, `category_id`, `descriptions`, `category_name`, `sub_category`, `image`, `rating`) VALUES
(1, 'Monitor LG', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg', 'img/cat1.jpg', 4),
(2, 'Monitor Samsung ', 343, 51, 235, 2356, 346, 'useful for home', 'monitor', 'samsung', 'img/cat1.jpg', 0),
(3, 'Monitor Walton', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'walton', 'img/cat1.jpg', 4),
(4, 'hard disk 1TB', 343, 34, 235, 2356, 346, 'useful for home', 'hdd', 'toshiba', 'img/cat1.jpg', 0),
(5, 'hard disk 2TB', 33, 75, 33, 34, 34, 'useful for home', 'hdd', 'western_digital', 'img/cat1.jpg', 0),
(6, 'hard disk 4TB', 33, 37, 33, 34, 34, 'useful for home', 'hdd', 'adata', 'img/cat1.jpg', 0),
(7, 'Printer Canon', 343, 34, 235, 2356, 346, 'useful for home', 'printer', 'canon', 'img/cat1.jpg', 0),
(8, 'Printer HP', 33, 33, 33, 34, 34, 'useful for home', 'printer', 'hp', 'img/cat1.jpg', 2),
(9, 'Ram Transcend', 343, 34, 235, 2356, 346, 'useful for home', 'ram', 'transcend', 'img/cat1.jpg', 0),
(10, 'Ram Adata', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'adata', 'img/cat1.jpg', 4),
(11, 'Ram Razor', 33, 39, 33, 34, 34, 'useful for home', 'ram', 'razor', 'img/cat1.jpg', 0),
(12, 'Motherboard GigaByte', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'gigabyte', 'img/cat1.jpg', 3),
(13, 'Motherboard Asus', 33, 33, 33, 34, 34, 'useful for home', 'motherboard', 'asus', 'img/cat1.jpg', 0),
(14, 'Motherboard Intel', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'intel', 'img/cat1.jpg', 0),
(15, 'Processor Intel', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg', 0),
(16, 'Processor AMD', 33, 34, 235, 34, 34, 'Computer processor , high quality', 'processor', 'amd', 'img/cat1.jpg', 0),
(17, 'Monitor LG Full HD', 33, 39, 33, 34, 34, 'useful for home', 'monitor', 'lg', 'img/cat1.jpg', 0),
(18, 'Monitor LG Full HD', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'lg', 'img/cat1.jpg', 0),
(19, 'Monitor Samsung 4K', 343, 34, 235, 2356, 346, 'useful for home', 'monitor', 'samsung', 'img/cat1.jpg', 0),
(20, 'Monitor Walton Plasma', 33, 33, 33, 34, 34, 'useful for home', 'monitor', 'walton', 'img/cat1.jpg', 0),
(21, 'hard disk 1TB 5400rpm', 343, 34, 235, 2356, 346, 'useful for home', 'hdd', 'toshiba', 'img/cat1.jpg', 0),
(22, 'hard disk 4TB 7200rpm', 33, 33, 33, 34, 34, 'useful for home', 'hdd', 'adata', 'img/cat1.jpg', 0),
(23, 'Printer Canon For Photos', 343, 34, 235, 2356, 346, 'useful for home', 'printer', 'canon', 'img/cat1.jpg', 0),
(24, 'Ram Adata 8GB', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'adata', 'img/cat1.jpg', 0),
(25, 'Ram Razor 16GB', 33, 33, 33, 34, 34, 'useful for home', 'ram', 'razor', 'img/cat1.jpg', 0),
(26, 'Printer HP 1080P', 33, 33, 33, 34, 34, 'useful for home', 'printer', 'hp', 'img/cat1.jpg', 0),
(27, 'Motherboard Intel Gaming', 343, 34, 235, 2356, 346, 'useful for home', 'motherboard', 'intel', 'img/cat1.jpg', 0),
(28, 'Processor Intel Core i3', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg', 0),
(29, 'Processor Intel Core i5', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg', 0),
(30, 'Processor Intel Core i9', 33, 33, 33, 34, 34, 'useful for home', 'processor', 'intel', 'img/cat1.jpg', 0),
(31, 'arfeaf', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C:\\xampp\\tmp\\phpFE53.tmp', 0),
(32, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/girl.png', 0),
(33, 'Afsana Moon', 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/boy32png', 0),
(34, 'Afsana Moon', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/girl33png', 0),
(35, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/.girl34png', 0),
(36, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/.girl35png', 0),
(37, 'Afsana Moon', 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/girl36.png', 0),
(38, 'faerf', 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/boy37.png', 0),
(39, 'faerf', 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/boy38.png', 0),
(40, 'iphone', 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/iphone39.jpg', 0),
(41, 'afaef', 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/Screenshot (2)40.png', 0);

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
(34, 2, 2, 187),
(35, 9, 5, 188),
(36, 15, 4, 189),
(37, 3, 2, 190),
(38, 9, 2, 191),
(39, 2, 2, 192),
(40, 1, 1, 193),
(41, 2, 2, 194),
(42, 4, 7, 195),
(43, 11, 1, 196),
(44, 8, 3, 197),
(45, 10, 3, 198),
(46, 2, 2, 199),
(47, 7, 1, 200),
(48, 14, 4, 201),
(49, 11, 1, 202),
(50, 12, 2, 203),
(51, 4, 1, 204),
(52, 6, 3, 205),
(53, 17, 3, 206),
(54, 3, 4, 207),
(55, 3, 5, 208),
(56, 3, 1, 209),
(57, 3, 2, 210),
(58, 5, 4, 211),
(59, 2, 2, 212),
(60, 6, 4, 213),
(61, 12, 4, 214),
(62, 15, 4, 215),
(63, 18, 8, 216),
(64, 39, 4, 217),
(65, 24, 4, 218),
(66, 2, 1, 219),
(67, 3, 1, 220),
(68, 1, 1, 221),
(69, 8, 3, 222),
(70, 10, 2, 223),
(71, 8, 1, 224),
(72, 3, 1, 225),
(73, 1, 1, 226),
(74, 11, 1, 227),
(75, 2, 5, 228),
(76, 8, 1, 229),
(77, 9, 1, 230),
(78, 9, 1, 231),
(79, 3, 1, 232),
(80, 11, 1, 233),
(81, 7, 1, 234),
(82, 11, 1, 235),
(83, 4, 1, 236),
(84, 7, 5, 237),
(85, 14, 5, 238),
(86, 17, 5, 239),
(87, 30, 5, 240),
(88, 3, 1, 241),
(89, 9, 3, 242),
(90, 1, 1, 243),
(91, 10, 1, 244),
(92, 5, 1, 245),
(93, 9, 1, 246),
(88, 3, 1, 247),
(89, 2, 1, 248),
(90, 3, 1, 249),
(91, 8, 1, 250),
(92, 8, 1, 251),
(93, 1, 1, 252),
(94, 8, 1, 253),
(95, 8, 1, 254),
(96, 11, 2, 255),
(97, 3, 1, 256),
(98, 3, 1, 257),
(99, 4, 1, 258),
(100, 9, 1, 259),
(101, 3, 5, 260),
(102, 9, 3, 261),
(103, 1, 1, 262),
(104, 11, 1, 263),
(105, 2, 1, 264),
(102, 1, 1, 265),
(103, 6, 1, 266),
(104, 1, 1, 267),
(105, 3, 1, 268),
(106, 8, 1, 269),
(107, 3, 1, 270),
(108, 9, 3, 271),
(109, 10, 3, 272),
(110, 2, 1, 273),
(111, 3, 1, 274),
(112, 20, 1, 275),
(113, 9, 1, 276),
(114, 9, 1, 277),
(115, 3, 1, 278),
(116, 3, 1, 279),
(117, 2, 1, 280),
(104, 2, 1, 281),
(105, 3, 1, 282),
(106, 3, 1, 283),
(107, 2, 1, 284),
(108, 2, 1, 285),
(109, 11, 1, 286),
(110, 2, 1, 287),
(111, 3, 1, 288),
(112, 9, 2, 289),
(113, 14, 2, 290);

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
  `review_status` varchar(50) NOT NULL DEFAULT 'valid',
  `review_date` date NOT NULL,
  `product_id` int(5) NOT NULL,
  `user_id` int(8) NOT NULL,
  `rating` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `review_text`, `review_status`, `review_date`, `product_id`, `user_id`, `rating`) VALUES
(14, 'it is a good product', '', '2019-02-19', 8, 2, 0),
(15, 'it is a good product', '', '2019-02-19', 7, 2, 0),
(17, 'really nice product', '', '2019-03-29', 9, 2, 0),
(18, 'good products', '', '2019-04-02', 2, 2, 0),
(19, 'frfarf', 'valid', '2019-05-14', 1, 1, 3),
(20, 'faerfaef', 'valid', '2019-05-04', 3, 12, 4),
(21, 'arfraef', 'valid', '2019-05-04', 7, 12, 2),
(22, 'arfraef', 'valid', '2019-05-04', 2, 12, 3),
(23, 'faer', 'valid', '2019-05-04', 1, 12, 4),
(24, 'arefeaarfaef', 'valid', '2019-05-04', 11, 12, 4),
(25, 'arfarfaef', 'valid', '2019-05-04', 12, 12, 3),
(26, 'farfarfraef', 'valid', '2019-05-04', 10, 12, 4),
(27, 'rferf', 'valid', '2019-05-04', 8, 12, 3);

--
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `after_insert_rating` AFTER INSERT ON `review` FOR EACH ROW BEGIN
call avgRating( NEW.product_id , @avgRating );
update products set rating = @avgRating where product_id = NEW.product_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_rating` AFTER UPDATE ON `review` FOR EACH ROW BEGIN
call avgRating( NEW.product_id , @avgRating );
update products set rating = @avgRating where product_id = NEW.product_id;
END
$$
DELIMITER ;

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
(4, '2019-04-22', '2019-04-28', 1, 4, 12),
(5, '2019-04-22', '2019-04-28', 1, 4, 12),
(6, '2019-04-28', '2019-04-28', 1, 12, 12),
(7, '2019-04-28', '2019-04-28', 1, 12, 12),
(8, '2019-05-01', NULL, 0, 12, NULL);

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
(251, 7, 19, 3),
(252, 8, 4, 4),
(253, 8, 11, 4),
(254, 8, 16, 4);

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
(20, 'ffaf', NULL, 'riyad298faerfaer', 1919448787, '2007-02-14', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad', NULL),
(29, '11', NULL, 'riyadmail@gmail.com', 1919448787, '2008-01-09', 'valid', 'admin', 'Muhammad Ahsan', 'Riyad', NULL),
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
(32, 0, '127.0.0.1', 0, 38),
(40, 0, '127.0.0.1', 0, 39),
(21, 0, '127.0.0.1', 0, 40),
(5, 0, '127.0.0.1', 0, 41),
(20, 0, '127.0.0.1', 0, 42);

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
(4797, 0);

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
-- Indexes for table `msg-g_user-admin`
--
ALTER TABLE `msg-g_user-admin`
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
  MODIFY `cart_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

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
-- AUTO_INCREMENT for table `msg-g_user-admin`
--
ALTER TABLE `msg-g_user-admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_includ_product`
--
ALTER TABLE `order_includ_product`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `order_t`
--
ALTER TABLE `order_t`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `promo_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `p_include_cart`
--
ALTER TABLE `p_include_cart`
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

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
  MODIFY `review_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

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
  MODIFY `counter` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
