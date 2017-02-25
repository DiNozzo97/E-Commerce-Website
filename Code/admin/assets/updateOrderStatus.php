<?php
require '../../vendor/autoload.php'; // Import the MongoDB library

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->orders; // Select the database and collection

$orderNumber = intval(filter_var($_POST['orderNum'], FILTER_SANITIZE_NUMBER_INT)); // Sanatize the recieved order number
$newStatus = filter_var($_POST['status'], FILTER_SANITIZE_STRING); // Sanatize the recieved new status

$update = $collection->updateOne( // Update the db document with the new status and new update time to current timestamp
		['order_number' => $orderNumber],
		['$set' => ['status' => $newStatus, 'updated' => new MongoDB\BSON\UTCDateTime()]]
		);

if ($update->getModifiedCount() == 1) { // If 1 document was modified
	echo json_encode(['result' => 'success']); // Return success
	exit();
} else { // Otherwise
	echo json_encode(['result' => 'error']); // Return error
	exit();
}

