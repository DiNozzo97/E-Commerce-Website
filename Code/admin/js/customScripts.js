// The function alertActivator will create an alert to notify the user of an action 
function alertActivator(location, type, message, closable) {
	if (closable == true) 
		$('#'+location+'Alert').html('<div class="alert alert-'+type+' alert-dismissible fade in '+location+'" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +message+'</div>');
	else
		$('#'+location+'Alert').html('<div class="alert alert-'+type+' fade in '+location+'" role="alert">'+message+'</div>');
}

// This function is executed when the user attemps to login and sends and recieves AJAX data
function processLogin() {
	clearErrors(); // Clear any existing error messages

	var attemptedEmail = $('#emailLogin').val(); // Collect the email address from the login form
	var attemptedPassword = $('#passwordLogin').val(); // Collect the password from the login form

	var data = { // Store the email/password in a data object that can be easily sent via ajax
		emailLogin: attemptedEmail,
		passwordLogin: attemptedPassword
	};


	$.ajax({ // AJAX Request
		dataType: 'json',
		type: 'POST',
		url: './assets/validateStaffLogin.php',
		data: data,
		success: function(ajaxResponse) {
			switch(ajaxResponse.result) {
				case "successfulLogin": // If login is successful
					window.location.href = './orders.php'; // Redirect to the Orders page
					break;
				case "incorrectCredentials": // If incorrect credentials were entered
					alertActivator("login", "danger", "The email address or password you entered is incorrect.", false); // Display an alert
					$('.form-group:has(input[name*="Login"])').addClass('has-error'); // make the fields red
					break;
				default: // Otherwise
					$.each(ajaxResponse, function(key, value) { // For each key-value pair returned in the JSON response (each field not completed)
						var errorMsg = '<label class="error-msg text-danger">'+value+'</label>'; // The label text that will be displayedd, reminding the user to enter the missing field
						$('.form-group:has(input[name="' + key + '"])').addClass('has-error'); // Make the field group red
						$('div > input[name="' + key + '"]').after(errorMsg); //Insert the error message directly after the input box
					});
			}
		},
	});
	return false;
}


function viewOrder(orderNum) {
 	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: 'assets/viewOrder.php',
 		data: {orderNum: orderNum}, // Provide the php script with the order number
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("This order doesn't appear to exist :(");
 				return;
 			}

			$.each(ajaxResponse.orderDetails, function(key, value) { // For each pair returned
				$('#'+key).val(value); // Set the value of the key field in the form to the value returned
			});

			$("#lineItems tbody > tr").remove(); // Clear any rows already in the table

			$.each(ajaxResponse.lineItems, function(key, item) { // For each item
				var tablerow = "<tr><td>" + item.barcode + "</td><td>" + item.title + "</td><td>" + item.qty + "</td><td>£" + item.unitPrice + "</td><td>£" + item.linePrice + "</td></tr>"; // Prepare a table row in a string
				$("#lineItems tbody").append(tablerow); // Add the table row to the table
			});

			$('#viewOrderModal').modal('show'); // Show the modal
 		}
 	});
 	return false;
}



function showUpdateOrder (orderNum) {
	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: 'assets/showUpdateOrder.php',
 		data: {orderNum: orderNum}, // Provide the php script with the order number
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("This order doesn't appear to exist :(");
 				return;
 			}

 			$("#newStatusSelect select").val(ajaxResponse.status); // Set the current status as the currently set option
 			$("#statusUpdateSubmit").attr("onclick", "return updateOrderStatus("+ orderNum +");")
			$('#updateStatusModal').modal('show'); // Show the modal
 		}
 	});
}

function updateOrderStatus (orderNum) {
	var newStatus = $("#newStatusSelect select").val(); // fetch the new status
	var data = { // Create an object to send to the PHP script
		orderNum: orderNum,
		status: newStatus
	};

	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: 'assets/updateOrderStatus.php',
 		data: data, // Provide the php script with the order number and new status
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("Oops, an error has occured :(");
 				return;
 			} else if (ajaxResponse.result == "success")
 				$('#updateStatusModal').modal('hide'); // Show the modal
 				location.reload(); // Refresh the page
 		}
 	});
}

function showDeleteOrder(orderNum) {
	$("#orderDeleteYes").attr("onclick", "return deleteOrder("+ orderNum +");");
	$('#deleteOrderModal').modal('show'); // Show the modal

}

function deleteOrder(orderNum) {
	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: 'assets/deleteOrder.php',
 		data: {orderNum: orderNum}, // Provide the php script with the order number
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("Oops, an error has occured :(");
 				return;
 			} else if (ajaxResponse.result == "success")
 				$('#deleteOrderModal').modal('hide'); // Show the modal
 				location.reload(); // Refresh the page
 		}
 	});
}

function loadProductDetails(barcode) {
 	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: 'assets/productDetails.php',
 		data: {barcode: barcode}, // Provide the php script with the order number
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("This product doesn't appear to exist :(");
 				return "error";
 			}

			$.each(ajaxResponse.productDetails, function(key, value) { // For each pair returned
				$('#'+key).val(value); // Set the value of the key field in the form to the value returned
			});

			
 		}
 	});
 	return false;
}

function viewProduct(barcode) {
	loadProductDetails(barcode);
	$("#existingProductForm :input").prop("disabled", true); // Disable the fields so that they can't be editted
	$( "#saveButton" ).hide(); // hide the save button

	$('#existingProduct').modal('show'); // Show the modal
	
}

function showEditProduct(barcode) {
	loadProductDetails(barcode);
	$("#existingProductForm :input").prop("disabled", false); // enable the fields so that they can be editted
	$("#existingBarcode").prop("disabled", true); // lock the barcode field
	$( "#saveButton" ).show(); // hide the save button

	$('#existingProduct').modal('show'); // Show the modal
	
}

function saveEditProduct() {
	clearErrors();
	var barcode = $('#existingBarcode').val();
	var title = $('#existingTitle').val();
	var description = $('#existingDescription').val();
	var releaseDate = $('#existingReleaseDate').val();
	var director = $('#existingDirector').val();
	var duration = $('#existingDuration').val();
	var cast = $('#existingCast').val();
	var studio = $('#existingStudio').val();
	var category = $('#existingCategory').val();
	var language = $('#existingLanguage').val();
	var format = $('#existingFormat').val();
	var certificate = $('#existingCertificate').val();
	var price = $('#existingPrice').val();
	var quantity = $('#existingQuantity').val();
	var trailer = $('#existingTrailer').val();
	var artwork = $('#existingArtwork').val();

	var editProductData = {
		barcode: barcode,
		title: title,
		description: description,
		releaseDate: releaseDate,
		director: director,
		duration: duration,
		cast: cast,
		studio: studio,
		category: category,
		language: language,
		format: format,
		certificate: certificate,
		price: price,
		quantity: quantity,
		trailer: trailer,
		artwork: artwork
	};

		$.ajax({ // AJAX Request
		dataType: 'json',
		type: 'POST',
		url: './assets/updateProduct.php',
		data: editProductData,
		success: function(ajaxResponse) {
			console.log(ajaxResponse);
			switch(ajaxResponse.result) {
				case "success": // If update is successful
					$('#existingProduct').modal('hide'); // Show the modal
					location.reload(); // Refresh the page
					break;
				default: // Otherwise
					$.each(ajaxResponse, function(key, value) { // For each key-value pair returned in the JSON response (each field not completed)
						var errorMsg = '<label class="error-msg text-danger">'+value+'</label>'; // The label text that will be displayedd, reminding the user to enter the missing field
						$('.form-group:has(input[name="' + key + '"])').addClass('has-error'); // Make the field group red
						$('div > input[name="' + key + '"]').after(errorMsg); //Insert the error message directly after the input box
					});
			}
		},
	});
	return false;
}

function showDeleteProduct(barcode) {
	$("#productDeleteYes").attr("onclick", "return deleteProduct("+ barcode +");");
	$('#deleteProductModal').modal('show'); // Show the modal

}

function deleteProduct(barcode) {
	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: 'assets/deleteProduct.php',
 		data: {barcode: barcode}, // Provide the php script with the barcode
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("Oops, an error has occured :(");
 				return;
 			} else if (ajaxResponse.result == "success")
 				$('#deleteProductModal').modal('hide'); // Hide the modal
 				location.reload(); // Refresh the page
 		}
 	});
}

function showNewProduct() {
	$('#newProductForm').find('textarea, :text, input[type=date], input[type=number]').val('');
	$('#newProduct').modal('show'); // Show the modal
	
}

function saveNewProduct() {
	clearErrors();
	var barcode = $('#newBarcode').val();
	var title = $('#newTitle').val();
	var description = $('#newDescription').val();
	var releaseDate = $('#newReleaseDate').val();
	var director = $('#newDirector').val();
	var duration = $('#newDuration').val();
	var cast = $('#newCast').val();
	var studio = $('#newStudio').val();
	var category = $('#newCategory').val();
	var language = $('#newLanguage').val();
	var format = $('#newFormat').val();
	var certificate = $('#newCertificate').val();
	var price = $('#newPrice').val();
	var quantity = $('#newQuantity').val();
	var trailer = $('#newTrailer').val();
	var artwork = $('#newArtwork').val();

	var newProductData = {
		barcode: barcode,
		title: title,
		description: description,
		releaseDate: releaseDate,
		director: director,
		duration: duration,
		cast: cast,
		studio: studio,
		category: category,
		language: language,
		format: format,
		certificate: certificate,
		price: price,
		quantity: quantity,
		trailer: trailer,
		artwork: artwork
	};

		$.ajax({ // AJAX Request
		dataType: 'json',
		type: 'POST',
		url: './assets/createProduct.php',
		data: newProductData,
		success: function(ajaxResponse) {
			console.log(ajaxResponse);
			switch(ajaxResponse.result) {
				case "success": // If update is successful
					$('#existingProduct').modal('hide'); // Show the modal
					location.reload(); // Refresh the page
					break;
				default: // Otherwise
					$.each(ajaxResponse, function(key, value) { // For each key-value pair returned in the JSON response (each field not completed)
						var errorMsg = '<label class="error-msg text-danger">'+value+'</label>'; // The label text that will be displayedd, reminding the user to enter the missing field
						$('.form-group:has(input[name="' + key + '"]), .form-group:has(textarea[name="' + key + '"])').addClass('has-error'); // Make the field group red
						$('div > input[name="' + key + '"], div > textarea[name="' + key + '"]').after(errorMsg); //Insert the error message directly after the input box
					});
			}
		},
	});
	return false;
}

function clearErrors() {
	$(".form-group.has-error").removeClass('has-error');
	$("label.error-msg, .alert").remove();

}