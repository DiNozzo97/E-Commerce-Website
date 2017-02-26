<?php

require '../../vendor/autoload.php'; // Import the MongoDB library

session_start(); // Start the PHP Session

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->products; // Select the database and collection

$barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_NUMBER_INT); // Sanatize the recieved barcode

$document = $collection->findOne(['barcode' => $barcode]); // Find the product

if (empty($document)) { // If no documents are returned (a bug has occured)
	echo json_encode(["result" => 'error']); // Return an error to the json
	exit();

} else { // If there was a document
	$returnArray = []; // Create an array to store the data to be returned

	$returnArray['productDetails']['existingBarcode'] = $barcode;

	$returnArray['productDetails']['existingTitle'] = $document['details']['title'];

	$release = $document['details']['release_date'];
	$release = $release->toDateTime(); // Convert it to a PHP DateTime object
	$release = $release->format('Y-m-d'); // Turn it into a pretty formatted string

	$returnArray['productDetails']['existingReleaseDate'] = $release;

	$directors = "";
	foreach ($document['details']['director'] as $director) {
		$directors = $directors . $director . ",";
	}
	$returnArray['productDetails']['existingDirector'] = $directors;
	
	$returnArray['productDetails']['existingDuration'] = $document['details']['duration'];

	$cast = "";
	foreach ($document['details']['cast'] as $member) {
		$cast = $cast . $member . ",";
	}
	$returnArray['productDetails']['existingCast'] = $cast;

	$studios = "";
	foreach ($document['details']['studio'] as $studio) {
		$studios = $studios . $studio . ",";
	}
	$returnArray['productDetails']['existingStudio'] = $studios;

	$categories = "";
	foreach ($document['details']['category'] as $category) {
		$categories = $categories . $category . ",";
	}
	$returnArray['productDetails']['existingCategory'] = $categories;

	$languages = "";
	foreach ($document['details']['audio_language'] as $language) {
		$languages = $languages . $language . ",";
	}
	$returnArray['productDetails']['existingLanguage'] = $languages;

	$returnArray['productDetails']['existingFormat'] = $document['details']['format'];

	$returnArray['productDetails']['existingCertificate'] = $document['details']['certificate'];

	$price = $document['price']; // Store price in pence
	$price = $price/100; // Make pounds
	$price = money_format('%.2n', $price); // Make it 2 DP
	$returnArray['productDetails']['existingPrice'] = $price;

	$returnArray['productDetails']['existingQuantity'] = $document['quantity_available'];

	$returnArray['productDetails']['existingTrailer'] = $document['details']['trailer_url'];

	$returnArray['productDetails']['existingArtwork'] = $document['artwork'];

	$returnArray['result'] = "success";

	echo json_encode($returnArray); // Return the returnArray to the JS
	exit();

}

