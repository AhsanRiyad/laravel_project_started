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









var element1 = '<div class="form-row"> \
    \
    <div class="form-group col-md-2"> \
      <label for="inputEmail4">Product id</label> \
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email"> \
    </div>\
    <div class="form-group col-md-2"> \
      <label for="inputPassword4">Product Name</label>\
      <input  type="password" class="form-control" id="inputPassword4" placeholder="Password">\
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


	var products = $('#select_products').val();
	var pat   = /([0-9]*)[\s]{0,}[-]{1}[\s]{0,}([a-zA-Z0-1]*)/;
	var v1 = pat.exec(products);
	
	
	var product_name = v1[2];


	var productid = v1[1];
	var qntity = $('#select_product_qntity').val();
	var userid = $('#user_id').html();


	 var user = {
		 	 	'uid' : userid , 
		 	 	'pid' : productid,
		 	 	'qntity': qntity

		 	 }

	//alert(user);

	var jsonString = JSON.stringify(user);
	//alert(jsonString);
	var url = $('#postReviewUrl').html();
	alert(url);

	$.ajax({
		 	 	url: url,
		 	 	method: 'POST',
		 	 	data: { 'myinfo': jsonString 

		 	 },
		 	 
		 	 success: function(reply){
		 	 	var res = JSON.parse(reply);
					 // alert(reply);
					 //alert(reply);
					alert(reply);
					alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					var element = '';

					for(var i=0; i<res.cart_products.length; i++){

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
      <select id="inputState" class="form-control">\
        <option selected>Choose...</option>\
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
	alert('hi on click' + cart_id + ' '+ user_id);






	$.ajax({
		 	 	url: 'http://localhost:3000/a_cart_delete/'+cart_id+'/'+user_id,
		 	 	method: 'POST',
		 	
		 	 success: function(reply){
		 	 	


		 	 	var res = JSON.parse(reply);
					 // alert(reply);
					 //alert(reply);
					alert(reply);
					alert(res.cart_products.length);

					//alert(res.cart_products[0].cart_id);
					// alert(res.cart_products.length);
					var element = '';

					for(var i=0; i<res.cart_products.length; i++){

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
      <select id="inputState" class="form-control">\
        <option selected>Choose...</option>\
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

function update_it(){
	alert('hi on click' + cart_id);
}