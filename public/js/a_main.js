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
		 	 headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		 	 success: function(reply){
		 	 	//var res = JSON.parse(reply);
					 // alert(reply);
					 alert('success');
					},
					error: function(error){
						//alert(error);
					 alert('error');

					}
			});


});




