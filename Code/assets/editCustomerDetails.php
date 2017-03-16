<?php
require '../vendor/autoload.php'; // Import the MongoDB library

session_start(); // Start the PHP Session

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->customers; // Select the database and collection

if ($_POST['type'] == "fetch") { // If the 'fetch' type message is posted then

	$document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Find the document that belonging to the currently signed in user

	$userDetails = []; // Create an array to store the data

	// Add each piece of data in to the associative array
	$userDetails['firstNameEditUser'] = $document['name']['first_name'];
	$userDetails['lastNameEditUser'] = $document['name']['last_name'];
	$userDetails['emailEditUser'] = $document['email_address'];
	$userDetails['addressLine1EditUser'] = $document['shipping_address']['address_line1'];
	$userDetails['addressLine2EditUser'] = $document['shipping_address']['address_line2'];
	$userDetails['cityEditUser'] = $document['shipping_address']['city'];
	$userDetails['postcodeEditUser'] = $document['shipping_address']['postcode'];

	echo json_encode($userDetails); // Return the information to the javascript
	exit();

}

$errors = []; // Create an array to hold the errors

// Sanitize Data
$firstName =  filter_var($_POST['data']['firstNameEditUser'], FILTER_SANITIZE_STRING);
$lastName =  filter_var($_POST['data']['lastNameEditUser'], FILTER_SANITIZE_STRING);
$email =  strtolower(filter_var($_POST['data']['emailEditUser'], FILTER_SANITIZE_STRING));
$addressLine1 =  filter_var($_POST['data']['addressLine1EditUser'], FILTER_SANITIZE_STRING);
$addressLine2 =  filter_var($_POST['data']['addressLine2EditUser'], FILTER_SANITIZE_STRING);
$city =  filter_var($_POST['data']['cityEditUser'], FILTER_SANITIZE_STRING);
$postcode =  filter_var($_POST['data']['postcodeEditUser'], FILTER_SANITIZE_STRING);
$password =  filter_var($_POST['data']['passwordEditUser'], FILTER_SANITIZE_STRING);
$confirmPassword =  filter_var($_POST['data']['confirmPasswordEditUser'], FILTER_SANITIZE_STRING);


// Validate New Value with regular expressions and add an error if it doesn't match
if (!preg_match("/^[-'a-zA-Z]+$/", $firstName))
	$errors['firstNameEditUser'] = "Enter a valid first name";

if (!preg_match("/^[-'a-zA-Z]+$/", $lastName))
	$errors['lastNameEditUser'] = "Enter a valid last name";


// Check if email address is already associated with an account
$existingDocument = $collection->findOne(['email_address' => $email]); // Update the db document with all of the new field values

if (!empty($existingDocument) and $existingDocument['_id'] != $_SESSION['userID'])  { // If the email address given belongs to a customer that isn't the currently signed in user
	$errors['emailEditUser'] = "This email address is already associated with an account.";
}

// Validate email address using built in filter
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	$errors['emailEditUser'] = "Enter a valid email address";

// Make sure the address line 1 isn't blank
if ($addressLine1 == "")
	$errors['addressLine1EditUser'] = "Enter a valid address line 1";
// Make sure city isn't blank
if ($city == "")
	$errors['cityEditUser'] = "Enter a valid city";

// Validate postcode using external API
$postcodeLookup = @file_get_contents("http://api.postcodes.io/postcodes/$postcode/validate");
$postcodeLookup = json_decode($postcodeLookup);
if (!isset($postcodeUserLookup->result) or $postcodeUserLookup->result != true )
	$errors['postcodeEditUser'] = "Enter a valid UK postcode";

// See if password was changed and verify validity
if (($password == "") and ($confirmPassword == "")) {
	$passwordChange = false;
} else {
	$passwordChange = true;
	if ($password != $confirmPassword) {
		$errors['passwordEditUser'] = "The pasword you entered didn't match the confirm password field";
		$errors['confirmPasswordEditUser'] = ""; // This is a dirty fix to make the box go red without displaying a message
	} else {
		$passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
	}
}

if (count($errors) > 0) { // If there were invalid fields
	echo json_encode($errors); // Encode the messages in JSON to send back to the JS script
    exit; // Don't execute any more of the script
}

if ($passwordChange) { // If the password was changed then
	$collection->updateOne( // Update the db document with all of the new field values
		['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])],
		['$set' => ['email_address' => $email, 
		'password_hash' => $passwordHash,
		'name.first_name' => $firstName,
		'name.last_name' => $lastName,
		'shipping_address.address_line1' => $addressLine1,
		'shipping_address.address_line2' => $addressLine2,
		'shipping_address.city' => $city,
		'shipping_address.postcode' => $postcode]]
		);
} else { // Otherwisw
	$collection->updateOne( // Update the db document with all of the new field values (except for the password)
		['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])],
		['$set' => ['email_address' => $email, 
		'name.first_name' => $firstName,
		'name.last_name' => $lastName,
		'shipping_address.address_line1' => $addressLine1,
		'shipping_address.address_line2' => $addressLine2,
		'shipping_address.city' => $city,
		'shipping_address.postcode' => $postcode]]
		);
}

echo json_encode(['result' => 'success']); // Return a success message to the javascript
exit();