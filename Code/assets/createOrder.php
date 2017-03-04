<?php

require '../vendor/autoload.php'; // Import the MongoDB library
session_start();
$_SESSION['userID'] = "58ab3691cd21167b5940b8e2";

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$productsCollection = $client->movie_box->products; // Select the database and collection   
$customersCollection = $client->movie_box->customers; // Select the database and collection   

$customerDocument = $customersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Find the document that contains the customers ID

$outOfStock = []; // Prepare an array to hold  out of stock items
foreach ($customerDocument['basket']['items'] as $item) { // For each basket item
	$productDocument = $productsCollection->findOne(['barcode' => $item['barcode']]); // Find the document that contains the item
	if ($item['quantity'] > $productDocument['quantity_available']) {
		array_push($outOfStock, $item['title']);
	}
}

if (count($outOfStock) > 0) { // If there were products out of stock
	echo json_encode(['result' => 'outOfStock', 'products' => $outOfStock]); // Encode the messages in JSON to send back to the JS script
    exit; // Don't execute any more of the script
}

// Get the current order number
$countersCollection = $client->movie_box->counters; // Select the database and collection 
$orderNumberDocument = $countersCollection->findOne(['_id' => "order_number"]); // Find the document containing the next order number

$orderNumber = $orderNumberDocument['value']; // Store the next order number
$countersCollection->updateOne(['_id' => "order_number"], ['$inc'=>['value' => 1]]); //Increment the order number by 1 in the database

$created = new MongoDB\BSON\UTCDateTime();
$updated = new MongoDB\BSON\UTCDateTime();
$customer = ['id' => $customerDocument['_id'],
			 'name' => $customerDocument['name']['first_name'] . " " . $customerDocument['name']['last_name'],
			 'address' => 
			 	['address_line1' => $customerDocument['shipping_address']['address_line1'],
			 	'address_line2' => $customerDocument['shipping_address']['address_line2'],
			 	'city' => $customerDocument['shipping_address']['city'],
			 	'postcode' => $customerDocument['shipping_address']['postcode']]];

$line_items = [];

$productsCollection = $client->movie_box->products; // Select the database and collection 

foreach ($customerDocument['basket']['items'] as $item) { // For each basket item
    $barcode = $item['barcode'];
    $artwork = $item['artwork'];
    $title = $item['title'];
    $qty = $item['quantity'];
    $unitPrice = $item['unit_price'];
    $linePrice = $unitPrice * $qty;

    $productsCollection->updateOne(['barcode' => $barcode], ['$inc'=>['quantity_available' => -$qty]]); // Decrease the quantity available by the quantity ordered

    array_push($line_items, ['product_barcode' => $barcode, 'artwork' => $artwork, 'product_title' => $title, 'quantity' => $qty, 'unit_price' => $unitPrice, 'line_price' => $linePrice]);  // Push each item into the array
}

$totalOrderValue = $customerDocument['basket']['basket_total'];

$ordersCollection = $client->movie_box->orders; // Select the database and collection   

// Insert the item into the order collection
$createOrder = $ordersCollection->insertOne([
    'order_number' => $orderNumber,
    'status' => 'Ordered',
    'created' => $created,
    'updated' => $updated,
    'customer' => $customer,
    'line_items' => $line_items,
    'total_order_value' => $totalOrderValue
]);

$customersCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$set' => ['basket.items' => [], 'basket.basket_total' => 0]]); // Empty the basket

echo json_encode(['result' => 'success', 'orderNumber' => $orderNumber]); // Send the message back to the JS
exit();




