<?php

require '../vendor/autoload.php'; // Import the MongoDB library
session_start();
if (!isset($_SESSION['userID'])) { // If the user isn't a signed in as a customer
    echo json_encode(['result' => 'show login']); // Send a message to js and exit
	exit();
}

$barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_NUMBER_INT); // Sanitize the barcode provided

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$productsCollection = $client->movie_box->products; // Select the database and collection   
$productDocument = $productsCollection->findOne(['barcode'=>$barcode]); //Retrieve the film document
$certificate = strtoupper($productDocument['details']['certificate']);


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

$productCollection = $client->movie_box->products; // Select the database and collection   

$productDocument = $productCollection->findOne(['barcode' => $barcode]); // Retrieve the products document


$productInBasket = false;
foreach ($customerDocument['basket']['items'] as $product) { //For each product in the basket 
	if ($product['barcode'] == $barcode) { //Check to see if product barcode equals the requested barcode
		$productInBasket = true; //If so, set variable to true
		$productQuantity = $product['quantity'];
		break; //Break out of the foreach loop
	}
}

if ($_POST['type'] == 'inc') {
	if ($productInBasket) {
		if ($productQuantity == $productDocument['quantity_available']) {
			echo json_encode(['result' => 'none in stock']);
			exit();
		} else {
		$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID']), 'basket.items.barcode'=>$barcode], ['$inc'=>['basket.items.$.quantity' => 1]]); //Increment the quantity by 1 in the database
		}
	} else {
		$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$push'=> ['basket.items'=>['barcode'=>$barcode,'title'=>$productDocument['details']['title'],'unit_price'=>$productDocument['price'], 'quantity'=>1, 'artwork'=>$productDocument['artwork']]]]);
	}
	$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$inc'=>['basket.basket_total' => $productDocument['price']]]); //Increment the quantity by 1 in the database
} else {
	if ($productQuantity == 1) {
		$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$pull'=> ['basket.items' => ['barcode' => $barcode]]]); // Remove product from basket array
	} else {
		$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID']), 'basket.items.barcode'=>$barcode], ['$inc'=>['basket.items.$.quantity' => -1]]); //Decrement the quantity by 1 in the database
	}
	$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$inc'=>['basket.basket_total' => -$productDocument['price']]]); //Decrement the quantity by 1 in the database
}

if ($customerDocument['basket']['basket_total'] < 0) { // If the total basket price is less than 0 (Just a precautionary check that nothing's messing up)
		$customerCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$set'=>['basket.basket_total' => 0]]); // then set it to 0

}
	
echo json_encode(['result' => 'success']); // Send a message to js and exit
	exit();	

 





