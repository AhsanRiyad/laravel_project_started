

  $pdf = PDF::loadView('email.orderConfirm', $data)->save('pdf/confirm.pdf');
  //return $pdf->download('invoice.pdf');


  // return $results;
 // return $results[0][0]->product_id;