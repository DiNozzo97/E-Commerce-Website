<?php
require '../vendor/autoload.php'; // Import the MongoDB library

session_start(); // Start the PHP Session

$errors = []; // Create an array to hold the errors

if (isset($_POST['emailLogin']) and $_POST['emailLogin'] != "") { // If an email attempt was given 
	$testEmail = strtolower(filter_var($_POST['emailLogin'], FILTER_SANITIZE_EMAIL)); // Sanitize the email address provided 
} else { // Otherwise
	$errors['emailLogin'] = "Please enter your email address."; // Add an error message to the errors array
}

if (isset($_POST['passwordLogin']) and $_POST['passwordLogin'] != "") { // If an password attempt was given 
	$testPassword = $_POST['passwordLogin']; // Store the password in a variable
} else { //Otherwise
	$errors['passwordLogin'] = "Please enter your password."; // Add an error message to the errors array
}

if (count($errors) > 0) { // If there were blank fields
	echo json_encode($errors); // Encode the messages in JSON to send back to the JS script
    exit; // Don't execute any more of the script
}

// At this point we can assume that an email address and password were entered.

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->customers; // Select the database and collection      

$document = $collection->findOne(['email_address' => $testEmail]); // Find the document that contains the customers email address


if (!empty($document)) { // If a document with the email address given is returned
	if (password_verify($testPassword, $document['password_hash'])) { // If the password is correct and matches that encrypted in the hash
		$_SESSION['userID'] = (string)$document['_id'];
		$_SESSION['firstName'] = $document['name']['first_name'];
		echo json_encode(['result' => 'successfulLogin']); // Tell the JS that the credentials were successful
		exit;
	} else { // else (the password is wrong )
		echo json_encode(['result' => 'incorrectCredentials']); // Tell the JS that the credentials were incorrect
		exit;
	}
} else { // else (no documents are returned and therefore the email address entered doesn't exist)
		echo json_encode(['result' => 'incorrectCredentials']); // Tell the JS that the credentials were incorrect
		exit;
	}