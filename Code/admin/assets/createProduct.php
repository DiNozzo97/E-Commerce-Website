<?php
require '../../vendor/autoload.php'; // Import the MongoDB library

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->products; // Select the database and collection   

$errors = []; // Create an array to hold the errors

$barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_NUMBER_INT); // Sanitize the barcode provided

$barcodeDocument = $collection->findOne(['barcode' => $barcode]);

if (!empty($barcodeDocument)) {
	$errors['newBarcode'] = "Barcode already in use"; 
} elseif ($barcode == "") {
	$errors['newBarcode'] = "Please enter a valid barcode"; 
}

if (isset($_POST['title']) and $_POST['title'] != "") { // If a title was provided
	$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['newTitle'] = "Please enter a title"; // Add an error message to the errors array
}

if ($_POST['description'] != "") { // If a description was provided
	$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['newDescription'] = "Please enter a description"; // Add an error message to the errors array
}


if (preg_match('/\d{4}-\d{2}-\d{2}/', $_POST['releaseDate'])) { // If a valid date was provided
	$releaseDate = new MongoDB\BSON\UTCDateTime(new DateTime($_POST['releaseDate'])); // Create a mongo DateTime object from the given string
} else { // Otherwise
	$errors['newReleaseDate'] = "Please select a valid date"; // Add an error message to the errors array
}

if (preg_match("/^[-',a-zA-Z\s]+$/", $_POST['director'])) { // If a valid director was provided
	$director = filter_var($_POST['director'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$director = explode(',', $director); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['newDirector'] = "Please enter a comma seperated list of directors"; // Add an error message to the errors array
}

if ($_POST['duration'] != "") { // If a director was provided
	$duration = intval(filter_var($_POST['duration'], FILTER_SANITIZE_NUMBER_INT)); // Sanitize the number provided 
} else { // Otherwise
	$errors['newDuration'] = "Please enter a running time in minutes"; // Add an error message to the errors array
}

if (preg_match("/^[-',a-zA-Z\s]+$/", $_POST['cast'])) { // If a valid cast was provided
	$cast = filter_var($_POST['cast'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$cast = explode(',', $cast); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['newCast'] = "Please enter a comma seperated list of cast members"; // Add an error message to the errors array
}

if ($_POST['studio'] != "") { // If a studio was provided
	$studio = filter_var($_POST['studio'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$studio = explode(',', $studio); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['newStudio'] = "Please enter a comma seperated list of studios"; // Add an error message to the errors array
}

if (preg_match("/^[-,a-zA-Z\s]+$/", $_POST['category'])) { // If a category was provided
	$category = filter_var($_POST['category'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$category = explode(',', $category); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['newCategory'] = "Please enter a comma seperated list of categories"; // Add an error message to the errors array
}

if (preg_match("/^[-,a-zA-Z\s]+$/", $_POST['language'])) { // If a language was provided
	$language = filter_var($_POST['language'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$language = explode(',', $language); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['newLanguage'] = "Please enter a comma seperated list of languages"; // Add an error message to the errors array
}

if (preg_match("/^[-,a-zA-Z\s]+$/", $_POST['format'])) { // If a format was provided
	$format = filter_var($_POST['format'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['newFormat'] = "Please enter a format"; // Add an error message to the errors array
}

if (preg_match("/^[\da-zA-Z\s]+$/", $_POST['format'])) { // If a certificate was provided
	$certificate = filter_var($_POST['certificate'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['newCertificate'] = "Please enter a valid certificate"; // Add an error message to the errors array
}

if ($_POST['price'] != "") { // If a price was provided
	$price = intval(filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT)); // Sanitize the number provided  and remove the decimal point
} else { // Otherwise
	$errors['newPrice'] = "Please enter a price"; // Add an error message to the errors array
}

if (preg_match("/\d+$/", $_POST['quantity'])) { // If a quantity was provided
	$quantity = intval(filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)); // Sanitize the number provided 
} else { // Otherwise
	$errors['newQuantity'] = "Please enter a valid quantity"; // Add an error message to the errors array
}

if ($_POST['trailer'] != "") { // If a trailer was provided
	$trailer = filter_var($_POST['trailer'], FILTER_SANITIZE_URL); // Sanitize the url provided 
} else { // Otherwise
	$errors['newTrailer'] = "Please enter a trailer URL"; // Add an error message to the errors array
}

if ($_POST['artwork'] != "") { // If a artwork was provided
	$artwork = filter_var($_POST['artwork'], FILTER_SANITIZE_URL); // Sanitize the url provided 
} else { // Otherwise
	$errors['newArtwork'] = "Please enter a artwork URL"; // Add an error message to the errors array
}


if (count($errors) > 0) { // If there were blank fields
	echo json_encode($errors); // Encode the messages in JSON to send back to the JS script
    exit; // Don't execute any more of the script
}
   

$update = $collection->insertOne( // insert the data into the db
		['barcode' => $barcode, 
		'quantity_available' => $quantity,
		'artwork' => $artwork,
		'price' => $price,
		'details' => [
		'title' => $title, 
		'description' => $description,
		'release_date' => $releaseDate,
		'format' => $format,
		'certificate' => $certificate,
		'duration' => $duration,
		'audio_language' => $language,
		'studio' => $studio,
		'director' => $director,
		'cast' => $cast,
		'category' => $category,
		'trailer_url' => $trailer
		]]
		);

echo json_encode(['result' => 'success']); // Return success
exit();




