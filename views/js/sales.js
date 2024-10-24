/*=============================================
LOAD DYNAMIC PRODUCTS TABLE
=============================================*/

// $.ajax({

// 	url: "ajax/datatable-products.ajax.php",
// 	success:function(answer){
		
// 		console.log("answer", answer);

// 	}

// })


$('.salesTable').DataTable({
	"ajax": "ajax/datatable-sales.ajax.php", 
	"deferRender": true,
	"retrieve": true,
	"processing": true
});

 
/*=============================================
ADDING PRODUCTS TO THE SALE FROM THE TABLE
=============================================*/

$(".salesTable tbody").on("click", "button.addProductSale", function() {
    var idProduct = $(this).attr("idProduct");

    // Remove the "addProductSale" class to prevent adding the same product again
    $(this).removeClass("btn-primary addProductSale");
    $(this).addClass("btn-default");

    var datum = new FormData();
    datum.append("idProduct", idProduct);

    $.ajax({
        url: "ajax/products.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            console.log("AJAX Response:", answer);

            // Check if the status is 'success' and if product data exists
            if (answer.status === 'success' && answer.product) {
                // Retrieve product details from the product object
                var description = answer.product.description;
                var price = answer.product.sellingPrice;

                console.log("Description:", description);
                console.log("Price:", price);

                if (description === undefined || price === undefined) {
                    console.error("Missing product data:", { description, price });
                    return;
                }

                // Append the product details including quantity input
                $(".newProduct").append(
                    '<div class="row" style="padding:5px 15px">' +
                        '<!-- Product description -->' +
                        '<div class="col-xs-6" style="padding-right:0px">' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                    '<button type="button" class="btn btn-danger btn-xs removeProduct" idProduct="' + answer.product.id + '">' +
                                        '<i class="fa fa-times"></i>' +
                                    '</button>' +
                                '</span>' +
                                '<input type="text" class="form-control newProductDescription" idProduct="' + answer.product.id + '" name="addProductSale" value="' + description + '" readonly required>' +
                            '</div>' +
                        '</div>' +
                        '<!-- Product quantity -->' +
                        '<div class="col-xs-3 enterQuantity" style="padding-left:0px">' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>' +
                                '<input type="number" class="form-control newProductQuantity" name="newProductQuantity" min="1" value="1" required>' +
                            '</div>' +
                        '</div>' +
                        '<!-- Product price -->' +
                        '<div class="col-xs-3 enterPrice" style="padding-left:0px">' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon"><i class="fa fa-money"></i></span>' +
                                '<input type="text" class="form-control newProductPrice" realPrice="' + price + '" name="newProductPrice" value="' + price + '" readonly required>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );

                // Update total prices immediately after adding a new product
                addingTotalPrices();
                listProducts();

                // FORMAT PRODUCT PRICE
                $(".newProductPrice").number(true, 2);

                // Add event listener for quantity change to update price
                $(".newProduct").on("change", "input.newProductQuantity", function() {
                    var priceElement = $(this).closest('.row').find('.newProductPrice');
                    var realPrice = Number(priceElement.attr("realPrice"));
                    var quantity = Number($(this).val());

                    // Calculate and update final price
                    if (!isNaN(realPrice) && quantity > 0) {
                        var finalPrice = quantity * realPrice;
                        priceElement.val(finalPrice.toFixed(2));
                    }

                    // Update total prices after quantity change
                    addingTotalPrices();
                });

            } else {
                console.error("Product data not found or error in response.");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
});

 
/*=============================================
WHEN TABLE LOADS EVERYTIME THAT NAVIGATE IN IT
=============================================*/

$(".salesTable").on("draw.dt", function(){

	if(localStorage.getItem("removeProduct") != null){

		var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));

		for(var i = 0; i < listIdProducts.length; i++){

			$("button.recoverButton[idProduct='"+listIdProducts[i]["idProduct"]+"']").removeClass('btn-default');
			$("button.recoverButton[idProduct='"+listIdProducts[i]["idProduct"]+"']").addClass('btn-primary addProductSale');

		}


	}


})

/*=============================================
REMOVE PRODUCTS FROM THE SALE AND RECOVER BUTTON
=============================================*/

var idRemoveProduct = [];

localStorage.removeItem("removeProduct");

$(".saleForm").on("click", "button.removeProduct", function(){

	console.log("$(this)", $(this));
	$(this).parent().parent().parent().parent().remove();

	console.log("idProduct", idProduct);
	var idProduct = $(this).attr("idProduct");

	/*=============================================
	STORE IN LOCALSTORAGE THE ID OF THE PRODUCT WE WANT TO DELETE
	=============================================*/

	if(localStorage.getItem("removeProduct") == null){

		idRemoveProduct = [];
	
	}else{

		idRemoveProduct.concat(localStorage.getItem("removeProduct"))

	}

	idRemoveProduct.push({"idProduct":idProduct});

	localStorage.setItem("removeProduct", JSON.stringify(idRemoveProduct));

	$("button.recoverButton[idProduct='"+idProduct+"']").removeClass('btn-default');

	$("button.recoverButton[idProduct='"+idProduct+"']").addClass('btn-primary addProductSale');

	if($(".newProducto").children().length == 0){

	
		$("#newTotalSale").val(0);
		$("#totalSale").val(0);
		$("#newTotalSale").attr("totalSale",0);

	}else{

		// ADDING TOTAL PRICES

    	addingTotalPrices()


        listProducts()

	}

})

/*=============================================
ADDING PRODUCT FROM A DEVICE
=============================================*/

var numProduct = 0;

$(".btnAddProduct").click(function(){

	numProduct ++;

	var datum = new FormData();
	datum.append("getProducts", "ok");

	$.ajax({

		url:"ajax/products.ajax.php",
      	method: "POST",
      	data: datum,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(answer){
      	    
      	    	$(".newProduct").append(

          	'<div class="row" style="padding:5px 15px">'+

			  '<!-- Product description -->'+
	          
	          '<div class="col-xs-6" style="padding-right:0px">'+
	          
	            '<div class="input-group">'+
	              
	              '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs removeProduct" idProduct><i class="fa fa-times"></i></button></span>'+

	              '<select class="form-control newProductDescription" id="product'+numProduct+'" idProduct name="newProductDescription" required>'+

	              '<option>Select product</option>'+

	              '</select>'+  

	            '</div>'+

	          '</div>'+

	          '<!-- Product quantity -->'+

	          '<div class="col-xs-3 enterQuantity">'+
	            
	             '<input type="number" class="form-control newProductQuantity" name="newProductQuantity" min="1" value="1" stock newStock required>'+

	          '</div>' +

	          '<!-- Product price -->'+

	          '<div class="col-xs-3 enterPrice" style="padding-left:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><i class="fa fa-money"></i></span>'+
	                 
	              '<input type="text" class="form-control newProductPrice" realPrice="" name="newProductPrice" readonly required>'+
	 
	            '</div>'+
	             
	          '</div>'+

	        '</div>');


	        // ADDING PRODUCTS TO THE SELECT

	         answer.forEach(functionForEach);

	         function functionForEach(item, index){

	         	if(item.stock != 0){

		         	$("#product"+numProduct).append(

						'<option idProduct="'+item.id+'" value="'+item.description+'">'+item.description+'</option>'
		         	)

		         }

	         }


			addingTotalPrices()


	        $(".newProductPrice").number(true, 2);

      	}


	})

})

 
/*=============================================
SELECT PRODUCT
=============================================*/

$(".saleForm").on("change", "select.newProductDescription", function(){

	var productName = $(this).val();

	var newProductDescription = $(this).parent().parent().parent().children().children().children(".newProductDescription");

	var newProductPrice = $(this).parent().parent().parent().children(".enterPrice").children().children(".newProductPrice");

	var newProductQuantity = $(this).parent().parent().parent().children(".enterQuantity").children(".newProductQuantity");

	var datum = new FormData();
    datum.append("productName", productName);


	  $.ajax({

     	url:"ajax/products.ajax.php",
      	method: "POST",
      	data: datum,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(answer){
      	    
      	    $(newProductDescription).attr("idProduct", answer["id"]);
      	    $(newProductQuantity).attr("stock", answer["stock"]);
      	    $(newProductQuantity).attr("newStock", Number(answer["stock"])-1);
      	    $(newProductPrice).val(answer["sellingPrice"]);
      	    $(newProductPrice).attr("realPrice", answer["sellingPrice"]);

  	      // GROUP PRODUCTS IN JSON FORMAT

	        listProducts()

      	}

      })
})

/*=============================================
MODIFY QUANTITY
=============================================*/

$(".saleForm").on("change", "input.newProductQuantity", function() {
    // Find the price element
    var priceElement = $(this).parent().parent().find(".enterPrice .newProductPrice");

    // Get the real price from the attribute
    var realPrice = Number(priceElement.attr("realPrice"));
    
    // Debugging log to check realPrice value
    console.log("Real price:", realPrice);

    // Get the current quantity from the input
    var quantity = Number($(this).val());
    
    // Debugging log for the quantity
    console.log("Current quantity:", quantity);

  
    // Calculate final price based on quantity and real price
    var finalPrice = quantity * realPrice;
    
    // Check if finalPrice is valid
    if (isNaN(finalPrice)) {
        console.error("Final price calculation resulted in NaN");
        return; // Exit if the final price is invalid
    }

    // Set the calculated final price to the price input
    priceElement.val(finalPrice.toFixed(2)); // Format to 2 decimal places

    // Log the final price for debugging
    console.log("Final price:", finalPrice);

    // Update total prices
    addingTotalPrices();
    listProducts(); // Call to listProducts() to update product list
});

/*============================================ 
PRICES ADDITION 
=============================================*/
function addingTotalPrices() {
    var priceItem = $(".newProductPrice");
    var arrayAdditionPrice = [];  

    for (var i = 0; i < priceItem.length; i++) {
        // Push each price value into the array
        var itemPrice = Number($(priceItem[i]).val());
        if (!isNaN(itemPrice)) { // Check if itemPrice is a valid number
            arrayAdditionPrice.push(itemPrice);
        }
    }

    // Log the prices being added for debugging
    console.log("Prices array:", arrayAdditionPrice);

    // Function to add up all the prices in the array
    function additionArrayPrices(totalSale, numberArray) {
        return totalSale + numberArray;
    }

    // Calculate the total price using reduce
    var addingTotalPrice = arrayAdditionPrice.reduce(additionArrayPrices, 0); // Initialize totalSale to 0

    // Log the calculated total price for debugging
    console.log("Total price:", addingTotalPrice);

    // Update the total sale inputs
    $("#newSaleTotal").val(addingTotalPrice.toFixed(2)); // Format to 2 decimal places
    $("#saleTotal").val(addingTotalPrice.toFixed(2)); // Format to 2 decimal places
    $("#newSaleTotal").attr("totalSale", addingTotalPrice);
}



/*=============================================
FINAL PRICE FORMAT
=============================================*/

$("#newSaleTotal").number(true, 2);

/*=============================================
SELECT PAYMENT METHOD
=============================================*/

$("#newPaymentMethod").change(function(){

	var method = $(this).val();

	if(method == "cash"){

		$(this).parent().parent().removeClass("col-xs-6");

		$(this).parent().parent().addClass("col-xs-4");

		$(this).parent().parent().parent().children(".paymentMethodBoxes").html(

			 '<div class="col-xs-4">'+ 

			 	'<div class="input-group">'+ 

			 		'<span class="input-group-addon"><i class="fa fa-money"></i></span>'+ 

			 		'<input type="text" class="form-control" id="newCashValue" placeholder="000000" required>'+

			 	'</div>'+

			 '</div>'+

			 '<div class="col-xs-4" id="getCashChange" style="padding-left:0px">'+

			 	'<div class="input-group">'+

			 		'<span class="input-group-addon"><i class="fa fa-money"></i></span>'+

			 		'<input type="text" class="form-control" id="newCashChange" placeholder="000000" readonly required>'+

			 	'</div>'+

			 '</div>'

		 )

		// Adding format to the price

		$('#newCashValue').number( true, 2);
      	$('#newCashChange').number( true, 2);


      	// List method in the entry
      	listMethods()

	}else{

		$(this).parent().parent().removeClass('col-xs-4');

		$(this).parent().parent().addClass('col-xs-6');

		 $(this).parent().parent().parent().children('.paymentMethodBoxes').html(

		 	'<div class="col-xs-6" style="padding-left:0px">'+
                        
                '<div class="input-group">'+
                     
                  '<input type="number" min="0" class="form-control" id="newTransactionCode" placeholder="Transaction code"  required>'+
                       
                  '<span class="input-group-addon"><i class="fa fa-lock"></i></span>'+
                  
                '</div>'+

              '</div>')

	}

	

})

/*=============================================
CASH CHANGE
=============================================*/
$(".saleForm").on("change", "input#newCashValue", function(){

	
	var cash = $(this).val();
	console.log("cash", cash);

	var change =  Number(cash) - Number($('#saleTotal').val());
	console.log("change", change);

	var newCashChange = $(this).parent().parent().parent().children('#getCashChange').children().children('#newCashChange');

	newCashChange.val(change);

})

/*=============================================
CHANGE TRANSACTION CODE
=============================================*/
$(".saleForm").on("change", "input#newTransactionCode", function(){

     listMethods()


})


/*=============================================
LIST ALL THE PRODUCTS
=============================================*/

function listProducts(){

	var productsList = [];

	var description = $(".newProductDescription");

	var quantity = $(".newProductQuantity");

	var price = $(".newProductPrice");

	for(var i = 0; i < description.length; i++){

		productsList.push({ "id" : $(description[i]).attr("idProduct"), 
							  "description" : $(description[i]).val(),
							  "quantity" : $(quantity[i]).val(),
							  "stock" : $(quantity[i]).attr("newStock"),
							  "price" : $(price[i]).attr("realPrice"),
							  "totalPrice" : $(price[i]).val()})
	}

	$("#productsList").val(JSON.stringify(productsList)); 

}

/*=============================================
LIST METHOD PAYMENT
=============================================*/

function listMethods(){

	var listMethods = "";

	if($("#newPaymentMethod").val() == "cash"){

		$("#listPaymentMethod").val("cash");

	}else{

		$("#listPaymentMethod").val($("#newPaymentMethod").val()+"-"+$("#newTransactionCode").val());

	}

}

/*=============================================
EDIT SALE BUTTON
=============================================*/
$(".tables").on("click", ".btnEditSale", function(){

	var idSale = $(this).attr("idSale");

	window.location = "index.php?route=edit-sale&idSale="+idSale;


})

/*=============================================
FUNCTION TO DEACTIVATE "ADD" BUTTONS WHEN THE PRODUCT HAS BEEN SELECTED IN THE FOLDER
=============================================*/

function removeAddProductSale(){

	//We capture all the products' id that were selected in the sale
	var idProducts = $(".removeProduct");

	//We capture all the buttons to add that appear in the table
	var tableButtons = $(".salesTable tbody button.addProductSale");

	//We navigate the cycle to get the different idProducts that were added to the sale
	for(var i = 0; i < idProducts.length; i++){

		//We capture the IDs of the products added to the sale
		var button = $(idProducts[i]).attr("idProduct");
		
		//We go over the table that appears to deactivate the "add" buttons
		for(var j = 0; j < tableButtons.length; j ++){

			if($(tableButtons[j]).attr("idProduct") == button){

				$(tableButtons[j]).removeClass("btn-primary addProductSale");
				$(tableButtons[j]).addClass("btn-default");

			}
		}

	}
	
}

/*=============================================
EVERY TIME THAT THE TABLE IS LOADED WHEN WE NAVIGATE THROUGH IT EXECUTES A FUNCTION
=============================================*/

$('.salesTable').on( 'draw.dt', function(){

	removeAddProductSale();

})



/*=============================================
DELETE SALE
=============================================*/
$(".tables").on("click", ".btnDeleteSale", function(){

  var idSale = $(this).attr("idSale");

  swal({
        title: 'Are you sure you want to delete this?',
        text: "If you're not, you can cancel!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes, delete it!'
      }).then(function(result){
        if (result.value) {
          
            window.location = "index.php?route=sales&idSale="+idSale;
        }

  })

})

/*=============================================
PRINT BILL
=============================================*/

$(".tables").on("click", ".btnPrintBill", function(){

	var saleCode = $(this).attr("saleCode");

	window.open("extensions/tcpdf/pdf/bill.php?code="+saleCode, "_blank");

})


/*=============================================
DATES RANGE
=============================================*/

$('#daterange-btn').daterangepicker(
  {
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 days': [moment().subtract(29, 'days'), moment()],
      'this month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment(),
    endDate  : moment()
  },
  function (start, end) {
    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var initialDate = start.format('YYYY-MM-DD');

    var finalDate = end.format('YYYY-MM-DD');

    var captureRange = $("#daterange-btn span").html();
   
   	localStorage.setItem("captureRange", captureRange);
   	console.log("localStorage", localStorage);

   	window.location = "index.php?route=sales&initialDate="+initialDate+"&finalDate="+finalDate;

  }

)

/*=============================================
CANCEL DATES RANGE
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

	localStorage.removeItem("captureRange");
	localStorage.clear();
	window.location = "sales";
})

/*=============================================
CAPTURE TODAY'S BUTTON
=============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function(){

	var todayButton = $(this).attr("data-range-key");

	if(todayButton == "Today"){

		var d = new Date();
		
		var day = d.getDate();
		var month= d.getMonth()+1;
		var year = d.getFullYear();

		if(month < 10){

			var initialDate = year+"-0"+month+"-"+day;
			var finalDate = year+"-0"+month+"-"+day;

		}else if(day < 10){

			var initialDate = year+"-"+month+"-0"+day;
			var finalDate = year+"-"+month+"-0"+day;

		}else if(month < 10 && day < 10){

			var initialDate = year+"-0"+month+"-0"+day;
			var finalDate = year+"-0"+month+"-0"+day;

		}else{

			var initialDate = year+"-"+month+"-"+day;
	    	var finalDate = year+"-"+month+"-"+day;

		}	

    	localStorage.setItem("captureRange", "Today");

    	window.location = "index.php?route=sales&initialDate="+initialDate+"&finalDate="+finalDate;

	}

})

/*=============================================
OPEN XML FILE IN A NEW TAB
=============================================*/

$(".openXML").click(function(){

	var file = $(this).attr("file");
	window.open(file, "_blank");


})

