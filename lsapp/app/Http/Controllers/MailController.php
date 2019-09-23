<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   public function basic_email() {
      $data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('riyad298@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('riyad.for.test@gmail.com','Virat Gandhi');
      });
      //echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('email.orderConfirm', $data, function($message) {
         $message->to('riyad298@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('riyad.for.test@gmail.com','Virat Gandhi');
      });
      //echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email($receiverName, $receiverEmail ) {

      $email = $receiverEmail ;
      $name = $receiverName ;
      $data = array('name'=>$receiverName);
      Mail::send(['text'=>'email.plain_text'], $data, function($message) {
         $message->to($email, $name)->subject
            ('Umart Shopping Invoice');
         $message->attach(public_path().'/pdf/confirm.pdf');
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });
      //echo "Email Sent with attachment. Check your inbox.";
   }
}