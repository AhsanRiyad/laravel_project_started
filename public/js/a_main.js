/*var pat   = /([0-9]*)[\s]{0,}[-]{1}[\s]{0,}([a-zA-Z0-1]*)/;


var v1 = pat.exec('1 - farfa');

alert(v1[1]);


var $products = $('#select_products').val();*/



/*$('#select_products').change(function(){
	var $products = $(this).val();

	var pat   = /([0-9]*)[\s]{0,}[-]{1}[\s]{0,}([a-zA-Z0-1]*)/;


	var v1 = pat.exec($products);

	alert(v1[1]);


});
*/



var quantity = 0 ; 

var total = 0;

var userid = $('#user_id_input').val();




var element1 = '<div class="form-row"> \
    \
    <div class="form-group col-md-2"> \
      <label for="inputEmail4">Product id</label> \
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email"> \
    </div>\
    <div class="form-group col-md-2"> \
      <label for="inputPassword4">Product Name</label>\
      <input disabled type="password" class="form-control" id="inputPassword4" placeholder="Password">\
    </div>\
\
  <div class="form-group col-md-2">\
    <label for="inputState">Quantity</label>\
      <select id="inputState" class="form-control">\
        <option selected>Choose...</option>\
        <option>...</option>\
      </select>\
  </div>\
  <div class="form-group col-md-2">\
    <label for="inputState">Update</label>\
       <input type="submit" value="update" class="btn btn-primary form-control">\
  </div>\
  \
  <div class="form-group col-md-2">\
    <label for="inputState">+Update+</label>\
       <input type="submit" value="update" class="btn btn-primary form-control">\
  </div>\
  </div>';







$('#button_add_product').click(function(){

	if($('#user_id_input').val()=='')
	{
		alert('you must select customer id first');

		return;
	}


	total = 0;

	var products = $('#select_products').val();
	var pat   = /([0-9]*)[\s]{0,}[-]{1}[\s]{0,}([a-zA-Z0-1]*)/;
	var v1 = pat.exec(products);
	
	
	var product_name = v1[2];


	var productid = v1[1];
	var qntity = $('#select_product_qntity').val();
	var userid = $('#user_id_input').val();


	 var user = {
		 	 	'uid' : userid , 
		 	 	'pid' : productid,
		 	 	'qntity': qntity

		 	 }

	//alert(user);

	var jsonString = JSON.stringify(user);
	//alert(jsonString);
	var url = $('#postReviewUrl').html();
	//alert(url);

	$.ajax({
		 	 	url: url,
		 	 	method: 'POST',
		 	 	data: { 'myinfo': jsonString 

		 	 },
		 	 
		 	 success: function(reply){
		 	 	var res = JSON.parse(reply);
					 // alert(reply);
					 //alert(reply);
					//alert(reply);
					//alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					var element = '';

					for(var i=0; i<res.cart_products.length; i++){

						total = total + res.cart_products[i].product_sell_price;

						$('#totalAmount').val(total);

						//alert(total);


						var element1 = '<div class="form-row"> \
    \
    <div class="form-group col-md-2"> \
      <label for="inputEmail4">Product id</label> \
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
    </div>\
    <div class="form-group col-md-2"> \
      <label for="inputPassword4">Product Name</label>\
      <input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="'+res.cart_products[i].product_name+'"">\
    </div>\
\
  <div class="form-group col-md-2">\
    <label for="inputState">Quantity</label>\
      <select onchange="changeQntity(this);" id="inputState" class="form-control">\
        <option selected>Choose...</option>\
        <option >1</option>\
        <option >1</option>\
        <option >2</option>\
        <option >3</option>\
        <option selected value='+res.cart_products[i].quantity+' >'+res.cart_products[i].quantity+'</option>\ \
      </select>\
  </div>\
  <div class="form-group col-md-2">\
    <label for="inputState">Update</label>\
       <button onClick = "update_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"  class="btn btn-primary form-control">Update</button>\
  </div>\
  \
  <div class="form-group col-md-2">\
    <label for="inputState">Delete</label>\
       \
       <a onClick = "delete_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"><button class="btn btn-primary form-control">Delete</button></a>\
  </div>\
  </div>';

  					element = element + element1;








						
						
						$('#product_list_div').html(element);


					};

					





					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});


});








function delete_it(cart_id , user_id){
	//alert('hi on click' + cart_id + ' '+ user_id);



	total = 0;


	$.ajax({
		 	 	url: 'http://localhost:3000/a_cart_delete/'+cart_id+'/'+user_id,
		 	 	method: 'POST',
		 	
		 	 success: function(reply){
		 	 	


		 	 	var res = JSON.parse(reply);
					 // alert(reply);
					 //alert(reply);
					//alert(reply);
					//alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					var element = '';

					if(res.cart_products.length==0){
							var element = '';
							$('#product_list_div').html(element);
							return;
						}

					for(var i=0; i<res.cart_products.length; i++){

						if(res.cart_products.length==0){
							var element = '';
							$('#product_list_div').html(element);
							break;
						}

						total = total + res.cart_products[i].product_sell_price;

						$('#totalAmount').val(total);

						var element1 = '<div class="form-row"> \
    \
    <div class="form-group col-md-2"> \
      <label for="inputEmail4">Product id</label> \
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
    </div>\
    <div class="form-group col-md-2"> \
      <label for="inputPassword4">Product Name</label>\
      <input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="'+res.cart_products[i].product_name+'"">\
    </div>\
\
  <div class="form-group col-md-2">\
    <label for="inputState">Quantity</label>\
      <select onchange="changeQntity(this);" id="inputState" class="form-control">\
        <option selected>Choose...</option>\
        <option >1</option>\
        <option >1</option>\
        <option >2</option>\
        <option >3</option>\
        <option selected value='+res.cart_products[i].quantity+' >'+res.cart_products[i].quantity+'</option>\ \
      </select>\
  </div>\
  <div class="form-group col-md-2">\
    <label for="inputState">Update</label>\
       <button onClick = "update_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"  class="btn btn-primary form-control">Update</button>\
  </div>\
  \
  <div class="form-group col-md-2">\
    <label for="inputState">Delete</label>\
       \
       <a onClick = "delete_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"><button class="btn btn-primary form-control">Delete</button></a>\
  </div>\
  </div>';

  					element = element + element1;
				
						
						$('#product_list_div').html(element);


					};


					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});
	
}

function update_it(cart_id , user_id){
	//alert('hi on click' + cart_id);
total = 0;

	$.ajax({
		 	 	url: 'http://localhost:3000/a_cart_update/'+cart_id+'/'+user_id+'/'+quantity,
		 	 	method: 'POST',
		 	
		 	 success: function(reply){
		 	 	


		 	 	var res = JSON.parse(reply);
					 // alert(reply);
					 //alert(reply);
					//alert(reply);
					//alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					var element = '';

					for(var i=0; i<res.cart_products.length; i++){


						total = total + res.cart_products[i].product_sell_price;

						$('#totalAmount').val(total);

						var element1 = '<div class="form-row"> \
    \
    <div class="form-group col-md-2"> \
      <label for="inputEmail4">Product id</label> \
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
    </div>\
    <div class="form-group col-md-2"> \
      <label for="inputPassword4">Product Name</label>\
      <input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="'+res.cart_products[i].product_name+'"">\
    </div>\
\
  <div class="form-group col-md-2">\
    <label for="inputState">Quantity</label>\
      <select onchange="changeQntity(this);" id="inputState" class="form-control">\
        <option selected>Choose...</option>\
        <option >1</option>\
        <option >1</option>\
        <option >2</option>\
        <option >3</option>\
        <option selected value='+res.cart_products[i].quantity+' >'+res.cart_products[i].quantity+'</option>\ \
      </select>\
  </div>\
  <div class="form-group col-md-2">\
    <label for="inputState">Update</label>\
       <button onClick = "update_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"  class="btn btn-primary form-control">Update</button>\
  </div>\
  \
  <div class="form-group col-md-2">\
    <label for="inputState">Delete</label>\
       \
       <a onClick = "delete_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"><button class="btn btn-primary form-control">Delete</button></a>\
  </div>\
  </div>';

  					element = element + element1;
				
						
						$('#product_list_div').html(element);


					};
					alert('quantity updated');

					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});

}


function changeQntity(e){
	//alert(e.value);

	quantity = e.value;
}




$('#user_id_input').change(function(){

	$('#product_list_div').html('');

	userid = $(this).val();
	total = 0 ; 

	$('#totalAmount').val(total);


});






$('#button_reset_product').click(function(){
	userid = $('#user_id_input').val();
	//alert(userid);

	$.ajax({
		 	 	url:'http://localhost:3000/a_cart_reset/'+userid,
		 	 	method: 'POST',
		 	
		 	 success: function(reply){
		 	 	
		 	 		
		 	 		$('#product_list_div').html('');
		 	 		//alert('reset done');
		 	 		 $('#user_id_input').val(0);
					$('#totalAmount').val(0);
					 $('#amount_paid_input').val(0);
					$('#salesPoint_id').val(0);
					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});


});




$('#amount_paid_input').change(function(){

	 var tk =  $(this).val();
	 var total =  $('#totalAmount').val();
	 var pat = /^[\d]*$/;

	 var status = pat.test(tk);
	 //alert(total + ' ' + tk);
	 if(status && tk<=total){
	 	var amount_left = total - tk ; 

	 	$('#amount_left_input').val(amount_left);
	 }else{
	 	alert('invalid amount');
	 	$('#amount_left_input').val(0);
	 }

});







$('#button_show_product').click(function(){




	//alert('hi on click' + cart_id);
total = 0;
userid = $('#user_id_input').val();
//alert(userid);
	$.ajax({
		 	 	url: 'http://localhost:3000/a_cart_show/'+userid,
		 	 	method: 'POST',
		 	
		 	 success: function(reply){
		 	 	


		 	 	var res = JSON.parse(reply);
		 	 	//alert(res);
					 // alert(reply);
					 //alert(reply);
					//alert(reply);
					//alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					var element = '';

					for(var i=0; i<res.cart_products.length; i++){


						total = total + res.cart_products[i].product_sell_price;

						$('#totalAmount').val(total);

						var element1 = '<div class="form-row"> \
    \
    <div class="form-group col-md-2"> \
      <label for="inputEmail4">Product id</label> \
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
    </div>\
    <div class="form-group col-md-2"> \
      <label for="inputPassword4">Product Name</label>\
      <input  type="text" class="form-control" id="inputPassword4" placeholder="Password" value='+res.cart_products[i].product_name+'>\
    </div>\
\
  <div class="form-group col-md-2">\
    <label for="inputState">Quantity</label>\
      <select onchange="changeQntity(this);" id="inputState" class="form-control">\
        <option selected>Choose...</option>\
        <option >1</option>\
        <option >1</option>\
        <option >2</option>\
        <option >3</option>\
        <option selected value='+res.cart_products[i].quantity+' >'+res.cart_products[i].quantity+'</option>\ \
      </select>\
  </div>\
  <div class="form-group col-md-2">\
    <label for="inputState">Update</label>\
       <button onClick = "update_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"  class="btn btn-primary form-control">Update</button>\
  </div>\
  \
  <div class="form-group col-md-2">\
    <label for="inputState">Delete</label>\
       \
       <a onClick = "delete_it('+res.cart_products[i].cart_id+' , '+res.cart_products[i].user_id+');"><button class="btn btn-primary form-control">Delete</button></a>\
  </div>\
  </div>';

  					element = element + element1;
				
						
						$('#product_list_div').html(element);


					};
					//alert('quantity updated');

					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});

});





$('#button_confirm_order').click(function(){


	
	userid = $('#user_id_input').val();
	var total = $('#totalAmount').val();
	var paid = $('#amount_paid_input').val();
	var salesPoint = $('#salesPoint_id').val();


	 var user = {
		 	 	'uid' : userid , 
		 	 	'total' : total,
		 	 	'paid': paid,
		 	 	'salesPoint' : salesPoint

		 	 }

	//alert(user);

	var jsonString = JSON.stringify(user);
	//alert(jsonString);
	//var url = $('#postReviewUrl').html();
	//alert(url);

	$.ajax({
		 	 	url: 'http://localhost:3000/a_cart_order',
		 	 	method: 'POST',
		 	 	data: { 'myinfo': jsonString 

		 	 },
		 	 
		 	 success: function(reply){
		 	 	//var res = JSON.parse(reply);

					  alert(reply);
					 //alert(reply);
					//alert(reply);
					//alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					//var element = '';
					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});


});