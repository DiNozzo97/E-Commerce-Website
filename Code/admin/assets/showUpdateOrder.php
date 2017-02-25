<?php
require '../../vendor/autoload.php'; // Import the MongoDB library

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->orders; // Select the database and collection

$orderNumber = intval(filter_var($_POST['orderNum'], FILTER_SANITIZE_NUMBER_INT)); // Sanatize the recieved order number

$document = $collection->findOne(['order_number' => $orderNumber], ['projection' => ['status' => 1]]); // Retrieve the status of the order

if (empty($document)) { // If no document is returned
	echo json_encode([['result'] => "error"]); // return an error
	exit();
} else { // otherwise
	echo json_encode(['result' => "success", 'status' => $document['status']]); // return success and the status
	exit();
}
