<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
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
            SELECT status;END");


        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `cart` (IN `pid` INT, IN `uid` INT, IN `qnt` INT)  BEGIN
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
            END");



        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `cartPage` (IN `uid` INT)  BEGIN
            select p.product_id , pr.product_name , pr.product_price , pr.descriptions , p.product_qntity from cart c , p_include_cart p , products pr where p.cart_id = c.cart_id and p.product_id = pr.product_id and c.user_id = uid;

            select SUM(pr.product_price) as total from cart c , p_include_cart p , products pr where p.cart_id = c.cart_id and p.product_id = pr.product_id and c.user_id = uid;

            END");


        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `money_transfer` (IN `admin_id` INT, IN `amount_tk` INT)  NO SQL
            BEGIN

            insert into money_transfer (transfer_date , transfered_by , amount , status) VALUES (SYSDATE() , admin_id , amount_tk , 0);

                UPDATE account set balance_available = balance_available - amount_tk where user_id = 0;

                END");


        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `order_t` (IN `uid` INT, IN `o_date` DATE, IN `p_method` VARCHAR(20))  BEGIN
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
            END");



        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `shipment_cart` (IN `a_id` INT(10), IN `p_id` INT(10), IN `p_qntity` INT(10))  NO SQL
            BEGIN
            DECLARE counter INT ;

            SELECT  COUNT(*) INTO counter FROM `shipment_temp` WHERE product_id = p_id and admin_id =a_id;

            IF counter > 0 THEN
            UPDATE shipment_temp set product_quantity = product_quantity + p_qntity WHERE admin_id = a_id and product_id = p_id;
            ELSE
            INSERT INTO shipment_temp (product_id , admin_id , product_quantity) VALUES (p_id , a_id , p_qntity);
            END IF;

            END");


        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `shipment_req` (IN `a_id` INT)  NO SQL
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
            END");

        DB::unprepared("CREATE OR REPLACE DEFINER=`root`@`localhost` PROCEDURE `shipment_to_products` (IN `id` INT)  NO SQL
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

            END");



        // DB::unprepared("");








    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("drop PROCEDURE if exists `a_order_t`");
        DB::unprepared("drop PROCEDURE if exists `cart`");
        DB::unprepared("drop PROCEDURE if exists `cartPage`");
        DB::unprepared("drop PROCEDURE if exists `money_transfer`");
        DB::unprepared("drop PROCEDURE if exists `order_t`");
        DB::unprepared("drop PROCEDURE if exists `shipment_cart`");
        DB::unprepared("drop PROCEDURE if exists `shipment_req`");
        DB::unprepared("drop PROCEDURE if exists `shipment_to_products`");
        
    }
}
