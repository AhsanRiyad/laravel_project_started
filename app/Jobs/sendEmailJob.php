<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Http\Request;
use PDF;
use Mail;
use DB;


use App\Mail\SendEmailMailable;

class sendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


   

    protected $uid ;  
    protected $payment_method ;  
    protected $receiverEmail ; 
    protected $receiverName;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid ,  $payment_method ,  $receiverEmail , $receiverName)
    {
        $this->receiverEmail = $receiverEmail;
        $this->payment_method = $payment_method;
        $this->uid = $uid;
        $this->receiverName = $receiverName;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {




    DB::statement('call order_t(? , ?)' , [ $this->uid , $this->payment_method ]);




    DB::statement("CALL order_invoice( ? , @order_id , @total , @date )" , [ $this->uid ]);

    $res = DB::select("select @order_id as order_id , @total as total , @date as date");




    $order_details = DB::select("select o.* , p.product_name , p.product_price , (o.qntity*p.product_price) as total from order_includ_product  o, products p where
        o.product_id = p.product_id and order_id = (?)", [$res[0]->order_id]);





    $data = ['order_details' => $order_details , 'date' => $res[0]->date , 'total' => $res[0]->total ];
  


    $data = array('name'=>'name');





   Mail::send(['text'=>'email.plain_text'], $data, function($message) {
        
         $message->to($this->receiverEmail , $this->receiverName )->subject
            ('Umart Shopping Invoice');
         $message->attach(public_path().'/pdf/Invoice.pdf');
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });



    }
}
