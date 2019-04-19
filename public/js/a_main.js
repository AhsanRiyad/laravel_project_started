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


$(function() {
    
	var url = $('#getUrl').html();

	var fullUrl = url+'req_count';


	//alert(fullUrl);
    $.ajax({
		url:fullUrl,
		method: 'POST',

		success: function(reply){
			//var res = JSON.stringify(reply);
			//alert(res);

			// alert(reply[0].c);
			$('#reqCount').html(reply[0].c);


			
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});




});



var quantity = 0 ; 

var total = 0;

var userid = $('#user_id_input').val();




var element1 = '<div class="form-row"> \
\
<div class="form-group col-md-2"> \
<label for="inputEmail4"> id</label> \
<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email"> \
</div>\
<div class="form-group col-md-2"> \
<label for="inputPassword4">Name</label>\
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
	userid = $('#user_id_input').val();

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
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
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

	var url = $('#getUrl').html();

	var fullUrl = url+'a_cart_delete/'+cart_id+'/'+user_id;

	$.ajax({
		url: fullUrl,
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
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
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
	var url = $('#getUrl').html();

	
	var fullUrl = url+'a_cart_update/'+cart_id+'/'+user_id+'/'+quantity;
	$.ajax({
		url: fullUrl ,
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
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
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

	var url = $('#getUrl').html();

	
	
	var fullUrl = url+'a_cart_reset/'+userid;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',

		success: function(reply){


			$('#product_list_div').html('');
		 	 		//alert('reset done');
		 	 		$('#user_id_input').val('');
		 	 		$('#totalAmount').val(0);
		 	 		$('#amount_paid_input').val(0);
		 	 		$('#salesPoint_id').val('');
		 	 	},
		 	 	error: function(error){
						//alert(error);
						alert('error');

					}
				});


});




$('#amount_paid_input').change(function(){


	var tk =  parseInt( $(this).val() , 10); 

	var total = parseInt( $('#totalAmount').val() , 10);
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


	var url = $('#getUrl').html();

	//alert('hi on click' + cart_id);
	total = 0;
	userid = $('#user_id_input').val();
//alert(userid);
$.ajax({
	url: url+'a_cart_show/'+userid,
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
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res.cart_products[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
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
	var url = $('#getUrl').html();

	$.ajax({
		url: url+'a_cart_order',
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
						alert(error);

					}
				});


});









$('#button_add_product_shipment').click(function(){

	if($('#select_products').val()==0)
	{
		alert('you must select product first');

		

		return;
	}
	if($('#select_product_qntity').val()==0){
		alert('choose product  quantity');
		return;
	}

	total = 0;

	var products = $('#select_products').val();
	var pat   = /([0-9]*)[\s]{0,}[-]{1}[\s]{0,}([a-zA-Z0-1]*)/;
	var v1 = pat.exec(products);
	
	
	var product_name = v1[2];
	//userid = $('#user_id_input').val();

	var productid = v1[1];
	var qntity = $('#select_product_qntity').val();
	var userid = $('#user_id').html();
	//var userid = '2';

	var user = {
		'uid' : userid , 
		'pid' : productid,
		'qntity': qntity

	}

	//alert(user);

	var jsonString = JSON.stringify(user);
	//alert(jsonString);
	var url = $('#getUrl').html();
	//alert(url);
	var fullUrl = url+'ship_req_bd';
	//alert(fullUrl);
	$.ajax({
		url: fullUrl,
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

					for(var i=0; i<res.length; i++){
						//alert(res[i].product_id);





						//total = total + res.cart_products[i].product_sell_price;

						//$('#totalAmount').val(total);

						//alert(total);


						var element1 = '<div class="form-row"> \
						\
						<div class="form-group col-md-2"> \
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+res[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
						<input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="'+res[i].product_name+'"">\
						</div>\
						\
						<div class="form-group col-md-2">\
						<label for="inputState">Quantity</label>\
						<select onchange="changeQntity(this);" id="inputState" class="form-control">\
						<option id="ship_quantity" value="0" selected>Choose...</option>\
						<option value="1" >1</option>\
						<option value="2" >2</option>\
						<option value="3" >3</option>\
						<option value="4" >4</option>\
						<option selected value='+res[i].product_quantity+' >'+res[i].product_quantity+'</option>\ \
						</select>\
						</div>\
						<div class="form-group col-md-2">\
						<label for="inputState">Update</label>\
						<button onClick = "update_it_ship('+res[i].id+' );"  class="btn btn-primary form-control">Update</button>\
						</div>\
						\
						<div class="form-group col-md-2">\
						<label for="inputState">Delete</label>\
						\
						<a onClick = "delete_it_ship('+res[i].id+');"><button class="btn btn-primary form-control">Delete</button></a>\
						</div>\
						</div>';

						element = element + element1;	
						
						$('#product_list_div').html(element);

					}

				},
				error: function(error){
						//alert(error);
						alert('error');

					}
				});


});



$('#button_reset_product_shipment').click(function(){
	var userid = $('#user_id').html();
	//alert(userid);
	//alert('reset button');
	var url = $('#getUrl').html();

	
	
	var fullUrl = url+'a_shipment_reset/'+userid;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',

		success: function(reply){

			//alert(reply);
			$('#product_list_div').html('');
			
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});


});


$('#button_request_shipment').click(function(){
	var userid = $('#user_id').html();
	//alert(userid);
	//alert('reset button');
	var url = $('#getUrl').html();

	
	
	var fullUrl = url+'a_shipment_request/'+userid;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',

		success: function(reply){

			//alert(reply);
			$('#product_list_div').html('');
			alert('Shipment Request Sent Successfully');
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});


});


function ship_details(id)
{ 


    //alert('hi');

	//var userid = $('#user_id').html();
	//alert(userid);
	//alert('reset button');
	var url = $('#getUrl').html();

	//alert(id);
	
	var fullUrl = url+'a_shipment_details/'+id;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',
		success: function(reply){
			//var res = JSON.stringify(reply);
			//$('#dialog').html(reply);
			//$( "#dialog" ).dialog( "open" );
			//alert(reply[0].product_id);
			var h = '';

			var thead = '<table class="table">\
								<thead class="thead-dark">\
								<th>Id</th>\
								<th>Product Name</th>\
								<th>Quantity</th>\
								</thead>\
								';

			var hleg =  '</tbody>\
						</table>';

			for(var i=0; i<reply.length ; i++){

				var ht = 
				'<tbody>\<tr>\
								<td>'+reply[i].product_id+'</td>\
								<td>'+reply[i].product_name+'</td>\
								<td>'+reply[i].product_quantity+'</td>\
								</tr>;'
				var h = h + ht ;


			}

			$('#dialog').html(thead+h+hleg);
			$( "#dialog" ).dialog( "open" );






			//$('#product_list_div').html('');
			//alert('Shipment Request Sent Successfully');
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});


	



	$( "#dialog" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		width: 630,
		position: { my: 'top', at: 'top+150' },
		hide: {
			effect: "explode",
			duration: 1000
		}
	});


      //$( "#dialog" ).dialog( "open" );


  }



function ship_accept(id){

	var url = $('#getUrl').html();

	var userid = $('#user_id').html();
	
	var fullUrl = url+'ship_accept/'+id+'/'+userid;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',
		success: function(reply){
			//var res = JSON.stringify(reply);
			//$('#dialog').html(reply);
			//$( "#dialog" ).dialog( "open" );
			//alert(reply[0].product_id);
			//alert(res);
			

			//alert('accepted');

			var tr = '';

			for(var i=0; reply[0].length>i ; i++){
				//alert(reply[i].id);

			var tr1 = '<tr>\
			        <td> '+reply[0][i].id+' </td>\
			        <td>'+reply[0][i].req_date+'</td>\
			        <td> '+reply[0][i].last_name+'  </td>\
			        <td><button onclick="ship_details('+reply[0][i].id+')" class="btn btn-success" >Details</button></td>\
			        <td><button onclick="ship_accept('+reply[0][i].id+')" class="btn btn-success" >Accept</button></td>\
			        <td><button onclick="ship_reject('+reply[0][i].id+')" class="btn btn-danger" >Reject</button></td>\
			      	</tr>';


			   tr = tr + tr1;

			   //alert(tr);



			   alert('accepted');



			}

			$('#tbody').html(tr);
			//res = JSON.stringify(reply);
			//alert(res);
			//alert(reply[1][0].c);
			$('#reqCount').text(reply[1][0].c);


			//$('#product_list_div').html('');
			//alert('Shipment Request Sent Successfully');
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});



}


function ship_reject(id){
	

	var url = $('#getUrl').html();

	var userid = $('#user_id').html();
	
	var fullUrl = url+'ship_accept/'+id+'/'+userid;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',
		success: function(reply){
			//var res = JSON.stringify(reply);
			//$('#dialog').html(reply);
			//$( "#dialog" ).dialog( "open" );
			//alert(reply[0].product_id);
			//alert(res);
			

			//alert('accepted');

			var tr = '';

			for(var i=0; reply[0].length>i ; i++){
				//alert(reply[i].id);

			var tr1 = '<tr>\
			        <td> '+reply[0][i].id+' </td>\
			        <td>'+reply[0][i].req_date+'</td>\
			        <td> '+reply[0][i].last_name+'  </td>\
			        <td><button onclick="ship_details('+reply[0][i].id+')" class="btn btn-success" >Details</button></td>\
			        <td><button onclick="ship_accept('+reply[0][i].id+')" class="btn btn-success" >Accept</button></td>\
			        <td><button onclick="ship_reject('+reply[0][i].id+')" class="btn btn-danger" >Reject</button></td>\
			      	</tr>';


			   tr = tr + tr1;

			   //alert(tr);



			   




			}
			alert('rejected');
			$('#tbody').html(tr);
			$('#reqCount').text(reply[1][0].c);


			//$('#product_list_div').html('');
			//alert('Shipment Request Sent Successfully');
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});
}





function delete_it_ship(ship_temp_id){

	//alert(ship_temp_id );





	var url = $('#getUrl').html();

	var userid = $('#user_id').html();
	
	var fullUrl = url+'delete_it_ship/'+ship_temp_id+'/'+userid;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',
		success: function(reply){
			//var res = JSON.stringify(reply);
			//$('#dialog').html(reply);
			//$( "#dialog" ).dialog( "open" );
			//alert(reply[0].product_id);
			//alert(res);
			
			//alert(reply);
			//alert('accepted');



			var element = '';

					
						//alert(reply[i].product_id);


						var element = '';

					for(var i=0; i<reply.length; i++){
						//alert(reply[i].product_id);

						//total = total + reply.cart_products[i].product_sell_price;

						//$('#totalAmount').val(total);

						//alert(total);

						//alert(reply[i].product_id);
						var element1 = '<div class="form-row"> \
						\
						<div class="form-group col-md-2"> \
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+reply[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
						<input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="'+reply[i].product_name+'"">\
						</div>\
						\
						<div class="form-group col-md-2">\
						<label for="inputState">Quantity</label>\
						<select onchange="changeQntity(this);" id="inputState" class="form-control">\
						<option id="ship_quantity" value="0" selected>Choose...</option>\
						<option value="1" >1</option>\
						<option value="2" >2</option>\
						<option value="3" >3</option>\
						<option value="4" >4</option>\
						<option selected value='+reply[i].product_quantity+' >'+reply[i].product_quantity+'</option>\ \
						</select>\
						</div>\
						<div class="form-group col-md-2">\
						<label for="inputState">Update</label>\
						<button onClick = "update_it_ship('+reply[i].id+' );"  class="btn btn-primary form-control">Update</button>\
						</div>\
						\
						<div class="form-group col-md-2">\
						<label for="inputState">Delete</label>\
						\
						<a onClick = "delete_it_ship('+reply[i].id+');"><button class="btn btn-primary form-control">Delete</button></a>\
						</div>\
						</div>';

						element = element + element1;	
						
						

					}
					alert('deleted');
					$('#product_list_div').html(element);

						//total = total + res.cart_products[i].product_sell_price;

						//$('#totalAmount').val(total);

						


			//$('#product_list_div').html('');
			//alert('Shipment Request Sent Successfully');
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});


}

function update_it_ship(ship_temp_id){

	//alert(ship_temp_id );



	var  qntity = $('#ship_quantity').val();

	var url = $('#getUrl').html();

	var userid = $('#user_id').html();
	
	var fullUrl = url+'update_it_ship/'+ship_temp_id+'/'+userid+'/'+qntity;
	//alert(fullUrl);

	$.ajax({
		url:fullUrl,
		method: 'POST',
		success: function(reply){
			//var res = JSON.stringify(reply);
			//$('#dialog').html(reply);
			//$( "#dialog" ).dialog( "open" );
			//alert(reply[0].product_id);
			//alert(res);
			
			//alert(reply);
			//alert('accepted');



			var element = '';

					
						//alert(reply[i].product_id);


						var element = '';

					for(var i=0; i<reply.length; i++){
						//alert(reply[i].product_id);

						//total = total + reply.cart_products[i].product_sell_price;

						//$('#totalAmount').val(total);

						//alert(total);

						//alert(reply[i].product_id);
						var element1 = '<div class="form-row"> \
						\
						<div class="form-group col-md-2"> \
						<label for="inputEmail4"> id</label> \
						<input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='+reply[i].product_id+'> \
						</div>\
						<div class="form-group col-md-2"> \
						<label for="inputPassword4">Name</label>\
						<input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="'+reply[i].product_name+'"">\
						</div>\
						\
						<div class="form-group col-md-2">\
						<label for="inputState">Quantity</label>\
						<select onchange="changeQntity(this);" id="inputState" class="form-control">\
						<option id="ship_quantity" value="0" selected>Choose...</option>\
						<option value="1" >1</option>\
						<option value="2" >2</option>\
						<option value="3" >3</option>\
						<option value="4" >4</option>\
						<option selected value='+reply[i].product_quantity+' >'+reply[i].product_quantity+'</option>\ \
						</select>\
						</div>\
						<div class="form-group col-md-2">\
						<label for="inputState">Update</label>\
						<button onClick = "update_it_ship('+reply[i].id+' );"  class="btn btn-primary form-control">Update</button>\
						</div>\
						\
						<div class="form-group col-md-2">\
						<label for="inputState">Delete</label>\
						\
						<a onClick = "delete_it_ship('+reply[i].id+');"><button class="btn btn-primary form-control">Delete</button></a>\
						</div>\
						</div>';

						element = element + element1;	
						
						

					}
					$('#product_list_div').html(element);
					alert('updated');
						//total = total + res.cart_products[i].product_sell_price;

						//$('#totalAmount').val(total);

						//alert(total);


			//$('#product_list_div').html('');
			//alert('Shipment Request Sent Successfully');
		},
		error: function(error){
						//alert(error);
						alert('error');

					}
				});


}


$('#amount').change(function(){

	var amount = parseInt($(this).val(), 10);
	var balance = parseInt($('#balance').val(), 10);
	//alert('invalid amount');

	if(amount>balance)
	{
		alert('invalid amount');
		$("#confirm_button").attr("disabled","true");
	}else{
		alert('Valid');
		$("#confirm_button").removeAttr("disabled");
	}



});


