// update profile page disable input and backgroundColor change js;
// performed by capturing the object using onclick event
// function removeDisabled(obj){

// 	obj.parentNode.nextSibling.nextElementSibling.disabled = false;
// 	obj.style.backgroundColor = "#51A5D0";


// }



$( document ).ready(function() {

	

});

//csrf problem
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});




// javascript onclick id getting examples solved
// very very important
function cplay(check_id)
{
	
	console.log(check_id.parentNode.nextSibling.nextSibling);
	
	// var is = mb.nextSibling;
	// var idd = is.id;
	// console.log(idd);

	//event.parentNode.style.backgroundColor = "red";
}

// know which event is fired
$("#tipo-imovel").on("click change", function(event){
	console.log(event.type + " is fired");
});




//login page validation
$('#exampleInputEmail1').change(function(){

	var val =  $(this).val();

	var emailPat = /^[\D]+[a-zA-Z0-9]*@[a-zA-Z]{3,8}\.{1}[a-zA-Z]{2,3}$/g;
	var result = emailPat.test(val);

	if(result == true)
	{

		$('#idExampleInputEmail1Small').text('Email is Valid');
		$('#idExampleInputEmail1Small').css({'color' : 'green' , 'font-weight' : 'bold'  });
	}else{

		$('#idExampleInputEmail1Small').text('Invalid Email');
		$('#idExampleInputEmail1Small').css({'color' : 'red' , 'font-weight' : 'bold'  });
	}



});



 //registration page validation
 var pass1, pass2;
 $('#exampleInputPassword1').change(function(){
 	pass1 = $(this).val();
 	//alert(pass1);
 	var patt = /^[\S]{6,20}$/g;

 	var status = patt.test(pass1);

 	if(!status){
 		//alert('wrong passw');
 		$('#password_error').text('invalid password');
 		$('#password_error').removeClass('text-success').addClass('text-danger');
 	}else{
 		$('#password_error').text('valid');

 		$('#password_error').removeClass('text-danger').addClass('text-success');

 	}

 })

 $('#exampleInputPassword2').change(function(){
 	pass2 = $(this).val();

 	if(pass1!=pass2){
 		$('#idexampleInputPassword1').text('password not matched');
 		$('#idexampleInputPassword1').css({'color' : 'red' , 'font-weight' : 'bold'  });
 	}
 	else if(pass1==pass2) {
 		$('#idexampleInputPassword1').text('Password*');
 		$('#idexampleInputPassword1').css({'color' : 'black' , 'font-weight' : 'normal'  });
 	}


 })

// number validation
var pass1, pass2;
$('#exampleInputMobile').change(function(){
	var number = $(this).val();

	var patt = /((017|018|016|019|014|013){1}[\d]{8}){1}/;

	var result = patt.test(number);
	console.log(result);
	console.log(number.length);
	if(result==false ||  number.length>11){
		$('#exampleLabelMobile').text('number not valid');
		$('#exampleLabelMobile').css({'color' : 'red' , 'font-weight' : 'bold'  });
	}
	else{
		$('#exampleLabelMobile').text('Mobile*');
		$('#exampleLabelMobile').css({'color' : 'black' , 'font-weight' : 'normal'  });
	}
	
})


//name validation
$('#fnInput').change(function(){

	var value = $(this).val() ;
	var patt = /([\W]{1,}|(\d){1,})/g;
	var result = patt.test(value);
	
	

	
	if(result)
	{	
		$('#fnLabel').text('invalid name');
		$('#fnLabel').css({'color' : 'red' , 'font-weight' : 'bold'  });

	}else{
		$('#fnLabel').text('First Name*');
		$('#fnLabel').css({'color' : 'black' , 'font-weight' : 'normal'  });
	}


});


$('#lnInput').change(function(){

	var value = $(this).val() ;
	var patt = /([\W]{1,}|(\d){1,})/g;
	var result = patt.test(value);
	
	

	
	if(result)
	{	
		$('#lnLabel').text('invalid name');
		$('#lnLabel').css({'color' : 'red' , 'font-weight' : 'bold'  });

	}else{
		$('#lnLabel').text('Last Name*');
		$('#lnLabel').css({'color' : 'black' , 'font-weight' : 'normal'  });
	}


});



//update profile pagae
function removeDisabled(obj){

	obj.parentNode.nextSibling.nextElementSibling.disabled = false;
	obj.style.backgroundColor = "#51A5D0";
	var objectSmall = obj.parentNode;


	$(objectSmall).removeClass('text-danger');
	$(objectSmall).addClass('text-info');

}


//profile update ajax request
//var obj = JSON.stringify(json);


$("#idButtonUpdateProfileDashboard").on('click' , function(){
		 // var i = $('#input').val();
		 // alert(i); 
		 var email =  $('#idInputEmailUpdateProfileDashboard').val();
		 var password = $('#idInputPasswordUpdateProfileDashboard').val();
		 var mobile = $('#idInputMobileUpdateProfileDashboard').val();

		 if(email!='' || password!=''|| mobile!=''){

		 	var json = {
		 		'email' : email,
		 		'password' : password,
		 		'mobile' : mobile
		 	}

		 	var jsonString = JSON.stringify(json);





		 	$.ajax({
		 		url: 'http://localhost:3000/dashboard/updateprofile',
		 		method: 'POST',
		 		data: { 'myinfo': jsonString},
		 		success: function(reply){
					// var reply = JSON.parse(response);
					console.log(reply);
					
					console.log(reply.msg);

					if(reply.status == true){
						alert('profile updated successfully');
					}
					
				},
				error: function(error){

				}
			});


		 }
		 else{
		 	console.log('null submission');
		 }


		});




//search suggestion 
var reqUrl = $('#autosearchUrl').html();

$( function() {

	function split( val ) {
		return val.split(  );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}





	$( "#autosearch" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
      	if ( event.keyCode === $.ui.keyCode.TAB &&
      		$( this ).autocomplete( "instance" ).menu.active ) {
      		event.preventDefault();
      }
  })
      .autocomplete({
      	source: function( request, response ) {

      		//getting category value
      		var catValue =  $("#idcategory").val();
      		$("#idcategory").change(function(){

      			var catValue =  $("#idcategory").val();

      			console.log(catValue);


      		});


      		$.getJSON( reqUrl , {
      			term: extractLast( request.term ) , 
      			cat : catValue
      		}, response );
      	},
      	search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
          	return false;
          }
      },
      focus: function() {
          // prevent value inserted on focus
          return false;
      }
  });
  } );




//add to cart 
$("#addToCart").on('click' , function(){
		 // var i = $('#input').val();
		 // alert(i); 
		 //alert('hello');

		 var userid =  $('#user_id').val();
		 var productid = $('#productId').val();
		 var qntity = $('#productQuantity').val();

		 
		 	 //alert(userid + 'user' + productid + 'pro');

		 	 var user = {
		 	 	'uid' : userid , 
		 	 	'pid' : productid,
		 	 	'qntity': qntity

		 	 }
		 	 
		 	 var jsonString = JSON.stringify(user);
		 	 //var s = $('#postReviewUrl').html();
		 	 //alert(s);
		 	 //return;
		 	 $.ajax({
		 	 	url: $('#postReviewUrl').html(),
		 	 	method: 'POST',
		 	 	data: { 'myinfo': jsonString 

		 	 },
		 	 headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		 	 success: function(reply){
		 	 	var res = JSON.parse(reply);
					 //alert(reply.status);
					 alert(res.status);

					 if(reply.status == 'updated'){
					 	//alert(reply);
					 	$('#cart_count').html(res.cartCount) ;

					 }else{
					 	//alert(reply);
					 	$('#cart_count').html(res.cartCount) ;

					 }




					},
					error: function(error){
						alert(error);
					}
				});


		 	});
/*
if(reply == 'updated'){
					 	alert('added to cart // qntity');
					 }else{
					 	alert('added to cart // new product');


					 }
*/



// rating starts

/*$('#rating_id').click(function(){
	alert('rating clicked');
})*/

/*$('.rating').eq(0).click(function(){
	//alert('rating 0 clicked');

	$('.rating').eq(0).click(function(){
	//alert('rating 0 clicked');

})

})*/

// rating ends


function rateCheck(obj, num){
	/*alert(num + 'clicked');*/

	for(var i=0; i<num; i++){
			//alert('rating clicked');
			$( ".rating" ).eq(i).removeClass( "text-warning" ).addClass( "text-warning" );

	}

	if( num<5 ){
		for( var i = 5; i>num; i-- ){
			//alert('inside loop'+ i);
			$( ".rating" ).eq(i-1).removeClass( "text-warning" );
			//alert('inside loop'+ i);
		} 
		//alert('i less than 5')

	}



}