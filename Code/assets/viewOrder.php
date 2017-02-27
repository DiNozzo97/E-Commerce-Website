<?php

require '../vendor/autoload.php'; // Import the MongoDB library

session_start(); // Start the PHP Session

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->orders; // Select the database and collection

$orderNumber = intval(filter_var($_POST['orderNum'], FILTER_SANITIZE_NUMBER_INT)); // Sanatize the recieved order number

$document = $collection->findOne(['order_number' => $orderNumber, 'customer.id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Find the order and as a security measure only collect the order from the db if it is owned by the currently signed in user.

if (empty($document)) { // If no documents are returned (a bug has occured or someone is trying to hack the system by manipulating JS!)
	echo json_encode(["result" => 'error']); // Return an error to the json
	exit();

} else { // If there was a document
	$returnArray = []; // Create an array to store the data to be returned

	$returnArray['orderDetails']['orderNumber'] = $orderNumber;

	$created = $document['created'];
	$created = $created->toDateTime(); // Convert it to a PHP DateTime object
	$created = $created->format('d/m/Y H:i'); // Turn it into a pretty formatted string

	$returnArray['orderDetails']['orderCreated'] = $created;

	$updated = $document['updated'];
	$updated = $updated->toDateTime(); // Convert it to a PHP DateTime object
	$updated = $updated->format('d/m/Y H:i'); // Turn it into a pretty formatted string

	$returnArray['orderDetails']['lastModified'] = $updated;

	$returnArray['orderDetails']['status'] = $document['status'];

	$address = $document['customer']['address']['address_line1'] . PHP_EOL; //

	if ($document['customer']['address']['address_line2'] != "") // If there is a second line to the address
		 $address = $address . $document['customer']['address']['address_line2'] . PHP_EOL; // Add it to the address variable

	$address = $address . $document['customer']['address']['city'] . PHP_EOL; // Add the city to the address variable
	$address = $address . $document['customer']['address']['postcode']; // Add the postcode to the address variable

	$returnArray['orderDetails']['address'] = $address;

	$returnArray['lineItems'] = []; // Declare new child array to store items

	foreach ($document['line_items'] as $item) {

		$itemDetails['artwork'] = $item['artwork'];
		$itemDetails['title'] = $item['product_title'];
		$itemDetails['qty'] = $item['quantity'];

		$unitPrice = $item['unit_price']; // Store price in pence
		$unitPrice = $unitPrice/100; // Make pounds
		$unitPrice = number_format((float)$unitPrice, 2, '.', ''); //to 2 DP 

		$itemDetails['unitPrice'] = $unitPrice;

		$linePrice = $item['line_price']; // Store price in pence
		$linePrice = $linePrice/100; // Make pounds
		$linePrice = number_format((float)$linePrice, 2, '.', ''); //to 2 DP 

		$itemDetails['linePrice'] = $linePrice;

		array_push($returnArray['lineItems'], $itemDetails); // Add the item array to the line items array

	}

	$total = $document['total_order_value']; // Store price in pence
	$total = $total/100; // Make pounds
	$total = number_format((float)$total, 2, '.', ''); //to 2 DP 

	$returnArray['orderDetails']['orderTotal'] = $total;

	$returnArray['result'] = "success";

	echo json_encode($returnArray); // Return the returnArray to the JS
	exit();

}

