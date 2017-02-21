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
					location.reload();
					break;
				case "incorrectCredentials": // If incorrect credentials were entered
					alertActivator("login", "danger", "The email address or password you entered is incorrect.", false); // Display an alert
					$('.form-group:has(input[name*="Login"])').addClass('has-error'); // make the fields red
					break;
				default: // Otherwise
					$.each(ajaxResponse, function(key, value) { // For each key-value pair returned in the JSON response (each field not completed)
						var errorMsg = '<label class="error-msg">'+value+'</label>'; // The label text that will be displayedd, reminding the user to enter the missing field
						$('.form-group:has(input[name="' + key + '"])').addClass('has-error'); // Make the field group red
						$('div > input[name="' + key + '"]').after(errorMsg); //Insert the error message directly after the input box
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