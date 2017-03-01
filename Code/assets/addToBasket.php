<?php

require '../vendor/autoload.php'; // Import the MongoDB library
session_start();
$_SESSION['userID'] = "58ab3691cd21167b5940b8e2";
// if (!isset($_SESSION['userID'])) { // If the user isn't a signed in as a customer
    // echo json_encode(['result' => 'show login']); // Send a message to js and exit
	// exit();
// }

// $barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_NUMBER_INT); // Sanitize the barcode provided
$barcode = "87174184684469";

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$productsCollection = $client->movie_box->products; // Select the database and collection   
$document = $productsCollection->findOne(['barcode'=>$barcode]); //Retrieve the film document
$certificate = strtoupper($document['details']['certificate']);


switch($certificate) {
	case "U": 
	case "PG":
		$movieAge = 0;
		break;
	case "12":
	case "12A":
		$movieAge = 12;
		break;
	case "15":
		$movieAge = 15;
		break;
	case "18":
	case "18":
		$movieAge = 18;
		break;
}
		
$customerCollection = $client->movie_box->customers; // Select the database and collection   
$customerDocument = $customerCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Retrieve the customer document
$customerDob = $customerDocument['dob'];
$customerDob = $customerDob->toDateTime(); //Convert from mongo to php date/time object
$currentDate = new DateTime();
$customerAge = $customerDob->diff($currentDate); // Create a date interval object to represent the difference between two dates
$customerAge = $customerAge->format('%y');

if ($customerAge < $movieAge) {
	 echo json_encode(['result' => 'under age']); // Send a message to js and exit
	exit();
}

$productInBasket = false;
foreach ($customerDocument['basket']['items'] as $product) { //For each product in the basket 
	if ($product['barcode'] == $barcode) { //Check to see if product barcode equals the requested barcode
		$productInBasket = true; //If so, set variable to true
		var_dump($product);
		break; //Break out of the foreach loop
	}
}

// if ($productInBasket) {
	// $customerCollection->updateOne(['basket.items.barcode'=>$barcode], ['$inc'=>['
	

 





