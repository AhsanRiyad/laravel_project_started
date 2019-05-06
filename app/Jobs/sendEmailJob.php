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





    $data = array('name'=>$this->receiverEmail);



   Mail::send('email.new_order', $data, function($message) {
        
         $message->to('riyad298@gmail.com' , 'Riyad' )->subject
            ('Umart Shopping Invoice');
         $message->attach(public_path().'/pdf/Invoice.pdf');
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });




   Mail::send('email.done', $data, function($message) {
        
         $message->to($this->receiverEmail , $this->receiverName )->subject
            ('Umart Shopping Invoice');
         $message->attach(public_path().'/pdf/Invoice.pdf');
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });



    }
}
