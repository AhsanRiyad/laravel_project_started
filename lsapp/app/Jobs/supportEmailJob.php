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

class supportEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


   

    protected $msg ;  
    protected $subject ;  
    protected $senderEmail ; 
    protected $senderName;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($msg ,  $subject ,  $senderEmail , $senderName)
    {
        $this->senderEmail = $senderEmail;
        $this->subject = $subject;
        $this->msg = $msg;
        $this->senderName = $senderName;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {





    $data = array('msg'=>$this->msg);



   Mail::send('email.support_msg', $data, function($message) {
        
         $message->to('riyad298@gmail.com' , 'Riyad' )->subject
            ($this->subject);
         
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });




   Mail::send('email.support_user', $data, function($message) {
        
         $message->to($this->senderEmail , $this->senderName )->subject
            ('Umart support confiramation');
         
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });



    }
}
