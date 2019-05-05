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


   

    protected $receiverName;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiverName)
    {
        $this->receiverName = $receiverName;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($receiverName)
    {
        //

        //session('receiverName') = $receiverName;

        
        Mail::to('riyad298@gmail.com')->send(new SendEmailMailable());
        /*$data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'email.plain_text'], $data, function($message) {
         $message->to('riyad298@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('riyad.for.test@gmail.com','aferfa');
      });*/
 
  // return $results;
 // return $results[0][0]->product_id;


    }
}
