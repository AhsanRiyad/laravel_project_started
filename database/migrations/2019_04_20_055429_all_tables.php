<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('total_tk')->default(0);
            $table->integer('paid_tk')->default(0);
            $table->integer('balance_available')->default(0) ;       
        });

        Schema::create('cart', function (Blueprint $table) {
            $table->increments('cart_id');
            $table->string('cart_status')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('g_u_type')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('quantity')->default(0);           
        });  

        Schema::create('factory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default(0);
            $table->string('location')->default(0);
        });

        Schema::create('factory_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factory_id')->default(0);
            $table->integer('materials_id')->default(0);
            $table->integer('qntity')->default(0);
        });

        Schema::create('money_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transfer_date')->default(NULL);
            $table->date('receive_date')->default(NULL);
            $table->integer('transfered_by')->default(0);
            $table->integer('received_by')->default(0);
            $table->integer('status')->default(0);
            $table->integer('amount')->default(0);
        });

         Schema::create('order_includ_product', function (Blueprint $table) {
            $table->increments('counter');
            $table->integer('order_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('qntity')->default(0);
        });

         Schema::create('order_t', function (Blueprint $table) {
            $table->increments('counter');
            $table->date('order_date')->default(NULL);
            $table->string('payment_method')->default(0);
            $table->string('payment_status')->default(0);
            $table->integer('return_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('total_amount')->default(0);
            $table->integer('paid')->default(0);
            $table->string('sales_point')->default(0);
            $table->integer('admin_id')->default(0);
        });

         Schema::create('p_include_cart', function (Blueprint $table) {
            $table->increments('counter');
            $table->integer('cart_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('product_qntity')->default(0);
            
        });

         Schema::create('raw_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->default(0);
            $table->string('name')->default(0);
            
            
        });

         Schema::create('shipment', function (Blueprint $table) {
            $table->increments('id');
            $table->date('req_date')->default(NULL);
            $table->date('acc_date')->default(NULL);
            $table->integer('status')->default(0);
            $table->integer('admin_id_req')->default(0);
            $table->integer('admin_id_acc')->default(0);
            
            
        }); 

         Schema::create('shipment_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipment_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('product_quantity')->default(0);
            
            
        });

         Schema::create('shipment_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('product_quantity')->default(0);
            
            
        }); 

         Schema::create('user', function (Blueprint $table) {
            $table->increments('u_id');
            $table->string('u_password')->default(0);
            $table->string('u_address')->default(0);
            $table->string('u_email')->default(0);
            $table->integer('u_mobile')->default(0);
            $table->date('dob')->default(NULL);
            $table->string('u_status')->default(0);
            $table->string('u_type')->default(0);
            $table->string('first_name')->default(0);
            $table->string('last_name')->default(0);

            
            
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_name')->default(0);
            $table->integer('product_price')->default(0);
            $table->integer('product_avlble')->default(0);
            $table->integer('product_sell_price')->default(0);
            $table->integer('product_original_price')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('descriptions')->default(0);
            $table->string('category_name')->default(0);
            $table->string('sub_category')->default(0);
            
            
            
            
            
        });







    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('factory');
        Schema::dropIfExists('factory_materials');
        Schema::dropIfExists('money_transfer');
        Schema::dropIfExists('order_includ_product');
        Schema::dropIfExists('order_t');
        Schema::dropIfExists('p_include_cart');
        Schema::dropIfExists('raw_materials');
        Schema::dropIfExists('shipment');
        Schema::dropIfExists('shipment_product');
        Schema::dropIfExists('shipment_temp');
        Schema::dropIfExists('user');
        Schema::dropIfExists('products');
    }
}
