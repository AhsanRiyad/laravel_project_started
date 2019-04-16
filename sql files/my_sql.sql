
DELIMITER $$
CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `a_order_t`(IN `uid` INT, IN `total` INT , IN `paid` INT , IN `sales_point` VARCHAR(50))
BEGIN
DECLARE o_no, p_id , qntity INT;
DECLARE status VARCHAR(20);
DECLARE b INT DEFAULT 0;
DECLARE cur_1 CURSOR FOR 
SELECT product_id , quantity FROM CART WHERE user_id = uid;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;

SELECT MAX(order_id) INTO o_no FROM ORDER_T;
INSERT INTO `order_t`(`order_id`, `order_date`,   `user_id` , `total_amount` , `paid` , `sales_point`) VALUES (o_no+1 , SYSDATE() ,  uid , total_amount , paid , sales_point );

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
DELIMITER ;
