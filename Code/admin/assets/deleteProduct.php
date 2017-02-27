<?php
require '../../vendor/autoload.php'; // Import the MongoDB library

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->products; // Select the database and collection

$barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_NUMBER_INT); // Sanatize the recieved order number

$delete = $collection->deleteOne(['barcode' => $barcode]); // Delete the order document

if ($delete->getDeletedCount() == 1) { // If 1 document was deleted
	echo json_encode(['result' => 'success']); // Return success
	exit();
} else { // Otherwise
	echo json_encode(['result' => 'error']); // Return error
	exit();
}

