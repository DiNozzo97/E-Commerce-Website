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
		url: '../assets/validateCustomerLogin.php',
		data: data,
		success: function(ajaxResponse) {
			switch(ajaxResponse.result) {
				case "successfulLogin": // If login is successful
					location.reload(); // Refresh the page
					break;
				case "incorrectCredentials": // If incorrect credentials were entered
					alertActivator("login", "danger", "The email address or password you entered is incorrect.", false); // Display an alert
					$('.form-group:has(input[name*="Login"])').addClass('has-error'); // make the fields red
					break;
				default: // Otherwise
					$.each(ajaxResponse, function(key, value) { // For each key-value pair returned in the JSON response (each field not completed)
						var errorMsg = '<label class="error-msg">'+value+'</label>'; // The label text that will be displayedd, reminding the user to enter the missing field
						$('.form-group:has(input[name="' + key + '"])').addClass('has-error'); // Make the field group red
						$('div > input[name="' + key + '"]').before(errorMsg); //Insert the error message directly after the input box
					});
				}
			}
		});
	return false;
}

// This function is executed when the user presses the 'save' button when editting their details
function validateCustomerEditData() {
	clearErrors(); // Clear any existing error messages

	var data = { // Store the email/password in a data object that can be easily sent via ajax
		firstNameEditUser: $("#firstNameEditUser").val(),
		lastNameEditUser: $("#lastNameEditUser").val(),
		emailEditUser: $("#emailEditUser").val(),
		passwordEditUser: $("#passwordEditUser").val(),
		confirmPasswordEditUser: $("#confirmPasswordEditUser").val(),
		addressLine1EditUser: $("#addressLine1EditUser").val(),
		addressLine2EditUser: $("#addressLine2EditUser").val(),
		cityEditUser: $("#cityEditUser").val(),
		postcodeEditUser: $("#postcodeEditUser").val()
	};

	$.ajax({ // AJAX Request
		dataType: 'json',
		type: 'POST',
		url: '../assets/editCustomerDetails.php',
		data: {type: "update", data: data},
		success: function(ajaxResponse) {
			if (ajaxResponse.result == 'success') // If all was validated successfully
				$('#editUserModal').modal('hide'); // Hide the modal
			else 
			$.each(ajaxResponse, function(key, value) { // For each key-value pair returned in the JSON response (each field not completed)
				var errorMsg = '<label class="error-msg">'+value+'</label>'; // The label text that will be displayedd, reminding the user to enter the missing field
				$('.form-group:has(input[name="' + key + '"])').addClass('has-error'); // Make the field group red
				$('div > input[name="' + key + '"]').before(errorMsg); //Insert the error message directly after the input box
			});
			}
		});
	return false;
}

function viewOrder(orderNum) {
 	$.ajax({ // AJAX Request
 		dataType: 'json',
 		type: 'POST',
 		url: '../assets/viewOrder.php',
 		data: {orderNum: orderNum}, // Provide the php script with the order number
 		success: function(ajaxResponse) {
 			if (ajaxResponse.result == "error") {
 				alert("This order doesn't belong to you!");
 				return;
 			}

			$.each(ajaxResponse.orderDetails, function(key, value) { // For each pair returned
				$('#'+key).val(value); // Set the value of the key field in the form to the value returned
			});
			
			$("#lineItems tbody > tr").remove(); // Clear any rows already in the table

			$.each(ajaxResponse.lineItems, function(key, item) { // For each item
				var tablerow = "<tr><td><img src='" + item.artwork + "' width='50px'></td><td>" + item.title + "</td><td>" + item.qty + "</td><td>£" + item.unitPrice + "</td><td>£" + item.linePrice + "</td></tr>"; // Prepare a table row in a string
				$("#lineItems tbody").append(tablerow); // Add the table row to the table
			});

			$('#viewOrderModal').modal('show'); // Show the modal
 		}
 	});
 	return false;
}

// Clears all error messages from all forms
function clearErrors() {
	$(".form-group.has-error").removeClass('has-error');
	$("label.error-msg, .alert").remove();

}

function addToBasket(barcode) {
	var data = {barcode: barcode,
				type: "inc"};
	$.ajax({ // AJAX Request
	 		dataType: 'json',
	 		type: 'POST',
	 		url: '../assets/addToBasket.php',
	 		data: data, // Provide the data to send to the php script
	 		success: function(ajaxResponse) {
				$("#basketFeedback").empty();
					if (ajaxResponse.result == 'success') { 
						alertActivator("basketFeedback", 'success', "Successfuly added to basket", true);
					} else if (ajaxResponse.result == 'show login') {
						$('#loginModal').modal('show');
					} else { 
						alertActivator("basketFeedback", 'danger', "Sorry, you are not old enough to buy this product", true);
					}
				refreshCart();
	 		}
	 	});
}

function decreaseBasketQuantity(barcode) {
	var data = {barcode: barcode,
				type: "dec"};
	$.ajax({ // AJAX Request
	 		dataType: 'json',
	 		type: 'POST',
	 		url: '../assets/addToBasket.php',
	 		data: data, // Provide the data to send to the php script
	 		success: function(ajaxResponse) {
				cartRefresh();
	 		}
	 	});
}

function refreshCart() {
	$.ajax({ // AJAX Request
	 		dataType: 'json',
	 		type: 'POST',
	 		url: '../assets/retrieveBasket.php',
	 		success: function(ajaxResponse) {
				$("#basketItems").empty();
				$.each(ajaxResponse.basketLine, function(key, value) {
					$('#basketItems').append("<li><span class='item'><span class='item-left'><img src='" + value.artwork + "'alt='' width='50px' /><span class='item-info'><a href='" + value.hyperlink + "'><span>" + value.title + "</span></a><span>" + value.price + "</span></span></span><span class='item-right'><button class='btn btn-xs btn-success' onClick='addToBasket(" + value.barcode + ");'>+</button><input type='text' name='qty' id='qty' value='" + value.quantity + "' disabled><button class='btn btn-xs btn-danger' onClick='decreaseBasketQuantity(" + value.barcode + ");'>-</button></span></span></li>");
				});
				$("#totalBasketPrice").text(ajaxResponse.totalPrice);
	 		}
	 	});
}

$( document ).ready(function() { // When the page has loaded

	$('#loginModal').on('hidden.bs.modal', function () { // When the login modal is closed
	 	clearErrors(); // Clear all errors
	 	$('input[name*="Login"]','#loginForm').val(''); // Clear all the fields in the form

	 });

	$('#editUserModal').on('show.bs.modal', function () { // When the Edit User Details modal is displayed
	 	clearErrors(); // Clear all errors
	 	$.ajax({ // AJAX Request
	 		dataType: 'json',
	 		type: 'POST',
	 		url: '../assets/editCustomerDetails.php',
	 		data: {type: "fetch"}, // Tell the PHP code this is the fetch part of edit user
	 		success: function(ajaxResponse) {
	 			$.each(ajaxResponse, function(key, value) { // For each pair returned
	 				$('#'+key).val(value); // Set the value of the key field in the form to the value returned
	 			});
	 		}
	 	});
	 });


});





