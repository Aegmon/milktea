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
ADDING ADDONS TO THE SALE FROM THE TABLE
=============================================*/

$(".addonTable tbody").on("click", "button.btnAddons", function() {
    var idIngredient = $(this).attr("idIngredient");
    var price = parseFloat($(this).attr("data-price"));
    var addon = $(this).attr("data-addon");


$(".newProduct").append(
    '<div class="row" style="padding:5px 15px">' +
        '<div class="col-xs-6" style="padding-right:0px">' +
            '<div class="input-group">' +
                '<span class="input-group-addon">' +
                    '<button type="button" class="btn btn-danger btn-xs removeAddons" idIngredient="' + idIngredient + '">' +
                        '<i class="fa fa-times"></i>' +
                    '</button>' +
                '</span>' +
                '<input type="text" class="form-control newAddon" idIngredient="' + idIngredient + '" name="addAddon" value="' + addon + '" readonly required>' +
            '</div>' +
        '</div>' +
        '<div class="col-xs-3 enterQuantity" style="padding-left:0px">' +
            '<div class="input-group">' +
                '<span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>' +
                '<input type="number" class="form-control newAddonQuantity" name="newAddonQuantity" min="1" value="1" required>' +
            '</div>' +
        '</div>' +
        '<div class="col-xs-3 enterPrice" style="padding-left:0px">' +
            '<div class="input-group">' +
                '<span class="input-group-addon"><i class="fa fa-money"></i></span>' +
                '<input type="text" class="form-control newAddonPrice" realPrice="' + price + '" name="newAddonPrice" value="' + price.toFixed(2) + '" readonly required>' +
            '</div>' +
        '</div>' +
    '</div>'
);

    addingTotalPrices();
    listAddons();

    // Update total prices after quantity change
    $(".newProduct").on("change", ".newAddonQuantity", function() {
        var priceElement = $(this).closest('.row').find('.newAddonPrice');
        var realPrice = Number(priceElement.attr("realPrice"));
        var quantity = Number($(this).val());

        // Calculate and update final price
        if (!isNaN(realPrice) && quantity > 0) {
            var finalPrice = quantity * realPrice;
            priceElement.val(finalPrice.toFixed(2));
            addingTotalPrices(); // Update total prices whenever quantity changes
        }
    });
});

function listAddons() {
    var addonlist = [];
    $(".newAddon").each(function(index) {
        var addon = $(this);
        var quantity = $(".newAddonQuantity").eq(index);
        var price = $(".newAddonPrice").eq(index);
        addonlist.push({
            "id": addon.attr("idIngredient"),
            "addon": addon.val(),
            "quantity": quantity.val(),
            "price": price.attr("realPrice"),
            "totalPrice": price.val()
        });
    });

    $("#addonList").val(JSON.stringify(addonlist));
}


/*=============================================
ADDING PRODUCTS TO THE SALE FROM THE TABLE
=============================================*/

// Event handler for adding a product sale
$(".salesTable tbody").on("click", "button.addProductSale", function() {
    var idProduct = $(this).attr("idProduct");
    var productSize = $(this).data("size"); // Get the selected size

    // Check if a size is selected
    if (!productSize) {
        alert("Please select a size (Regular or Large) before adding the product.");
        return;
    }

    var datum = new FormData();
    datum.append("idProduct", idProduct);
    datum.append("productSize", productSize); // Send the selected size

    $.ajax({
        url: "ajax/products.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            if (answer.status === 'success' && answer.product) {
                var description = answer.product.description;
                var price = parseFloat(answer.product.sellingPrice);

                // Check if the product already exists
                var existingProduct = $(".newProduct").find(`.newProductDescription[idProduct="${answer.product.id}"][data-size="${productSize}"]`);

                if (existingProduct.length) {
                    // Remove the existing product entry
                    existingProduct.closest('.row').remove();
                    $(this).removeClass("btn-primary addProductSale").addClass("btn-default");
                }

                // Append the product details including size
                $(".newProduct").append(
                    '<div class="row" style="padding:5px 15px">' +
                        '<div class="col-xs-6" style="padding-right:0px">' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                    '<button type="button" class="btn btn-danger btn-xs removeProduct" idProduct="' + answer.product.id + '" data-size="' + productSize + '">' +
                                        '<i class="fa fa-times"></i>' +
                                    '</button>' +
                                    '<button type="button" class="btn btn-success btn-xs btnViewProduct" idProduct="' + answer.product.id + '" data-toggle="modal" data-target="#modalViewProduct">' +
                                        '<i class="fa fa-eye"></i>' +
                                    '</button>' +
                                '</span>' +
                                '<input type="text" class="form-control newProductDescription" idProduct="' + answer.product.id + '" data-size="' + productSize + '" name="addProductSale" value="' + description + ' (' + productSize + ')" readonly required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-xs-3 enterQuantity" style="padding-left:0px">' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>' +
                                '<input type="number" class="form-control newProductQuantity" name="newProductQuantity" min="1" value="1" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-xs-3 enterPrice" style="padding-left:0px">' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon"><i class="fa fa-money"></i></span>' +
                                '<input type="text" class="form-control newProductPrice" realPrice="' + price + '" name="newProductPrice" value="' + price.toFixed(2) + '" readonly required>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );

                // Update total prices after adding the product
                addingTotalPrices();
                listProducts();
            } else {
                console.error("Error adding product:", answer.message);
            }
        },
        error: function(err) {
            console.error("Error during AJAX request:", err);
        }
    });
});

// Event handler for size button clicks
$(document).on('click', '.btn-size', function() {
    var productId = $(this).attr("idProduct"); // Get the product ID
    var productSize = $(this).data("size"); // Get the size (Regular or Large)

    // Update the add button to reflect the selected size
    var addButton = $(this).closest('tr').find('.addProductSale');
    addButton.attr('idProduct', productId);
    addButton.data('size', productSize); // Use data() instead of attr() for better handling
    addButton.text('Add ' + productSize); // Update button text

    // Highlight the selected size button
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
});

$(".newProduct").on("click", "button.btnViewProduct", function () {
    var idProduct = $(this).attr("idProduct");

    // Clear previous ingredients
    $("#ingredientsList").empty();

    // AJAX request to get product details including ingredients
    $.ajax({
        url: "ajax/products.ajax.php",
        method: "POST",
        data: { idProduct: idProduct },
        dataType: "json",
        success: function (response) {
            console.log("AJAX Response:", response); // Debugging

            // Check if the product details were received
            if (response && response.product) {
                // Set product title and description
                $("#productTitle").text(response.product.name); // Assuming the product has a name field
                $("#productDescription").text(response.product.description); // Assuming a description field exists

                // Check if ingredients were received
                if (response.ingredients && response.ingredients.length > 0) {
                    // Loop through each ingredient and append to the list
                    response.ingredients.forEach(function (ingredient) {
                        if (ingredient.measurement === 'mililiters') {
                            ingredient.measurement = 'ml'; // Convert measurement to ml if needed
                        }
                        $("#ingredientsList").append(
                            `<li class="list-group-item">
                                <h3>${ingredient.ingredient}</h3>
                                <p>Measurement: <strong>${ingredient.ingredient_needed}</strong> ${ingredient.measurement}</p>
                            </li>`
                        );
                    });
                } else {
                    $("#ingredientsList").append(
                        `<li class="list-group-item">No ingredients found for this product.</li>`
                    );
                }

                // Show the modal after populating data
                $("#modalViewProduct").modal("show");
            } else {
                console.error("Product data not found in response.");
                $("#ingredientsList").append(
                    `<li class="list-group-item">Error: Product not found.</li>`
                );
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus, errorThrown); // Debugging
            console.log("Response Text:", jqXHR.responseText); // Debugging
            $("#ingredientsList").append(
                `<li class="list-group-item">Error loading ingredients.</li>`
            );
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


$(".addonTable").on("draw.dt", function(){

	if(localStorage.getItem("removeAddons") != null){

		var listIdProducts = JSON.parse(localStorage.getItem("removeAddons"));

		for(var i = 0; i < listIdProducts.length; i++){

			$("button.recoverButtonaddons[idIngredient='"+listIdProducts[i]["idIngredient"]+"']").removeClass('btn-default');
			$("button.recoverButtonaddons[idIngredient='"+listIdProducts[i]["idIngredient"]+"']").addClass('btn-primary btnAddons');

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
var idRemoveAddon = [];

// Clear previous "removeAddons" data if it exists in localStorage
localStorage.removeItem("removeAddons");

$(".saleForm").on("click", "button.removeAddons", function() {
    var idIngredient = $(this).attr("idIngredient");

    console.log("idIngredient", idIngredient);

    // Remove the parent row of the clicked button (likely to be an addon)
    $(this).parent().parent().parent().parent().remove();

    // If there is no previous "removeAddons" data in localStorage, initialize idRemoveAddon array
    if (localStorage.getItem("removeAddons") === null) {
        idRemoveAddon = [];
    } else {
        // Otherwise, retrieve existing data from localStorage and combine it with the new id
        idRemoveAddon = JSON.parse(localStorage.getItem("removeAddons"));
    }

    // Push the current ingredient id to the removeAddons array
    idRemoveAddon.push({ "idIngredient": idIngredient });

    // Store the updated idRemoveAddon array in localStorage
    localStorage.setItem("removeAddons", JSON.stringify(idRemoveAddon));

    // Change the "recover" button's class back to the "Add" button's original state
    $("button.recoverButton[idIngredient='" + idIngredient + "']")
        .removeClass('btn-default')
        .addClass('btn-primary btnAddons');

    // Handle total price adjustments after item removal
    if ($(".newProducto").children().length == 0) {
        $("#newTotalSale").val(0);
        $("#totalSale").val(0);
        $("#newTotalSale").attr("totalSale", 0);
    } else {
        // Recalculate total prices
        addingTotalPrices();
        listProducts();
    }
});

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
    var priceElement = $(this).closest('.row').find(".enterPrice .newProductPrice");
    var realPrice = Number(priceElement.attr("realPrice"));
    var quantity = Number($(this).val());

    // Validate realPrice and quantity
    if (isNaN(realPrice) || isNaN(quantity) || quantity < 1) {
        console.error("Invalid price or quantity.");
        return;
    }

    // Calculate final price based on quantity and real price
    var finalPrice = quantity * realPrice;

    // Set the calculated final price to the price input
    priceElement.val(finalPrice.toFixed(2)); // Format to 2 decimal places
    console.log("Final price:", finalPrice);

    // Update total prices
    addingTotalPrices();
    listProducts(); // Update product list
});


/*============================================ 
PRICES ADDITION 
=============================================*/
function addingTotalPrices() {
    var arrayAdditionPrice = [];  

    // Calculate the total price for base products
    $(".newProductPrice").each(function() {
        var itemPrice = Number($(this).val());
        if (!isNaN(itemPrice)) {
            arrayAdditionPrice.push(itemPrice);
        }
    });

    // Calculate the total price for add-ons
    var addonTotalPrice = 0; 
    $(".newAddonPrice").each(function() {
        var addonPrice = Number($(this).val());
        if (!isNaN(addonPrice)) {
            addonTotalPrice += addonPrice; // Sum the total price of each add-on
        }
    });

    // Combine the product total and the add-on total
    var addingTotalPrice = arrayAdditionPrice.reduce((total, price) => total + price, 0) + addonTotalPrice; 

    // Log the calculated total price for debugging
    console.log("Total price (including addons):", addingTotalPrice);

    // Update the total sale inputs
    $("#newSaleTotal, #saleTotal").val(addingTotalPrice.toFixed(2)); // Format to 2 decimal places
    $("#newSaleTotal").attr("totalSale", addingTotalPrice);
}


// Function to create the list of addons
function listAddons() {
    var addonlist = [];
    var addons = $(".newAddon");
    var quantities = $(".newAddonQuantity");
    var prices = $(".newAddonPrice");

    for (var i = 0; i < addons.length; i++) {
        addonlist.push({
            "id": $(addons[i]).attr("idIngredient"),
            "addon": $(addons[i]).val(),
            "quantity": $(quantities[i]).val(),
            "price": $(prices[i]).attr("realPrice"),
            "totalPrice": $(prices[i]).val()
        });
    }

    $("#addonList").val(JSON.stringify(addonlist));
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

function showAddonTable() {
    var table = document.getElementById("addonTable");
    var button = document.getElementById("addonButton");

    if (table.style.display === "none") {
        table.style.display = "block";
        button.innerHTML = 'Close Addons <i class="fa fa-minus"></i>';
    } else {
        table.style.display = "none";
        button.innerHTML = 'Addons <i class="fa fa-plus"></i>';
    }
}