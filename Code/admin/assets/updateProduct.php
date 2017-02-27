<?php
require '../../vendor/autoload.php'; // Import the MongoDB library

$errors = []; // Create an array to hold the errors

$barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_NUMBER_INT); // Sanitize the barcode provided 

if (isset($_POST['title']) and $_POST['title'] != "") { // If a title was provided
	$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['existingTitle'] = "Please enter a title"; // Add an error message to the errors array
}

if (isset($_POST['description']) and $_POST['description'] != "") { // If a title was provided
	$title = filter_var($_POST['description'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['existingDescription'] = "Please enter a description"; // Add an error message to the errors array
}


if (isset($_POST['releaseDate']) and $_POST['releaseDate'] != "" and (preg_match('/\d{4}-\d{2}-\d{2}/', $_POST['releaseDate']) )) { // If a valid date was provided
	$releaseDate = new MongoDB\BSON\UTCDateTime(new DateTime($_POST['releaseDate'])); // Create a mongo DateTime object from the given string
} else { // Otherwise
	$errors['existingReleaseDate'] = "Please select a valid date"; // Add an error message to the errors array
}

if (isset($_POST['director']) and $_POST['director'] != "") { // If a director was provided
	$director = filter_var($_POST['director'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$director = explode(',', $director); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['existingDirector'] = "Please enter a comma seperated list of directors"; // Add an error message to the errors array
}

if (isset($_POST['duration']) and $_POST['duration'] != "") { // If a director was provided
	$duration = intval(filter_var($_POST['duration'], FILTER_SANITIZE_NUMBER_INT)); // Sanitize the number provided 
} else { // Otherwise
	$errors['existingDuration'] = "Please enter a running time in minutes"; // Add an error message to the errors array
}

if (isset($_POST['cast']) and $_POST['cast'] != "") { // If a cast was provided
	$cast = filter_var($_POST['cast'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$cast = explode(',', $cast); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['existingCast'] = "Please enter a comma seperated list of cast members"; // Add an error message to the errors array
}

if (isset($_POST['studio']) and $_POST['studio'] != "") { // If a studio was provided
	$studio = filter_var($_POST['studio'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$studio = explode(',', $studio); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['existingStudio'] = "Please enter a comma seperated list of studios"; // Add an error message to the errors array
}

if (isset($_POST['category']) and $_POST['category'] != "") { // If a category was provided
	$category = filter_var($_POST['category'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$category = explode(',', $category); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['existingCategory'] = "Please enter a comma seperated list of categories"; // Add an error message to the errors array
}

if (isset($_POST['language']) and $_POST['language'] != "") { // If a language was provided
	$language = filter_var($_POST['language'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
	$language = explode(',', $language); // Turn the comma seperated string in to an array
} else { // Otherwise
	$errors['existingLanguage'] = "Please enter a comma seperated list of languages"; // Add an error message to the errors array
}

if (isset($_POST['format']) and $_POST['format'] != "") { // If a format was provided
	$format = filter_var($_POST['format'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['existingFormat'] = "Please enter a format"; // Add an error message to the errors array
}

if (isset($_POST['certificate']) and $_POST['certificate'] != "") { // If a certificate was provided
	$certificate = filter_var($_POST['certificate'], FILTER_SANITIZE_STRING); // Sanitize the text provided 
} else { // Otherwise
	$errors['existingCertificate'] = "Please enter a certificate"; // Add an error message to the errors array
}

if (isset($_POST['price']) and $_POST['price'] != "") { // If a price was provided
	$price = intval(filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT)); // Sanitize the number provided  and remove the decimal point
} else { // Otherwise
	$errors['existingPrice'] = "Please enter a price"; // Add an error message to the errors array
}

if (isset($_POST['quantity']) and $_POST['quantity'] != "") { // If a quantity was provided
	$quantity = intval(filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)); // Sanitize the number provided 
} else { // Otherwise
	$errors['existingQuantity'] = "Please enter a quantity"; // Add an error message to the errors array
}

if (isset($_POST['trailer']) and $_POST['trailer'] != "") { // If a trailer was provided
	$trailer = filter_var($_POST['trailer'], FILTER_SANITIZE_URL); // Sanitize the url provided 
} else { // Otherwise
	$errors['existingTrailer'] = "Please enter a trailer URL"; // Add an error message to the errors array
}

if (isset($_POST['artwork']) and $_POST['artwork'] != "") { // If a artwork was provided
	$artwork = filter_var($_POST['artwork'], FILTER_SANITIZE_URL); // Sanitize the url provided 
} else { // Otherwise
	$errors['existingArtwork'] = "Please enter a artwork URL"; // Add an error message to the errors array
}


if (count($errors) > 0) { // If there were blank fields
	echo json_encode($errors); // Encode the messages in JSON to send back to the JS script
    exit; // Don't execute any more of the script
}

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->products; // Select the database and collection      

$update = $collection->updateOne( // Update the db document with all of the new field values
		['barcode' => $barcode],
		['$set' => ['details.title' => $title, 
		'details.description' => $description,
		'details.release_date' => $releaseDate,
		'details.format' => $format,
		'details.certificate' => $certificate,
		'details.duration' => $duration,
		'details.audio_language' => $language,
		'details.studio' => $studio,
		'details.director' => $director,
		'details.cast' => $cast,
		'details.category' => $category,
		'details.trailer_url' => $trailer,
		'quantity_available' => $quantity,
		'artwork' => $artwork,
		'price' => $price
		]]
		);

echo json_encode(['result' => 'success']); // Return success
exit();




