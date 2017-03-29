<?php
require '../vendor/autoload.php'; //import the mongodb library
session_start();

$searchTerm = filter_input(INPUT_POST, 'search_term', FILTER_SANITIZE_STRING);

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->customers; // Select the database and collection      

$collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$pull'=> ['recent_searches' => ['search_term' => $searchTerm]]]); // Remove search from array

$document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['projection' => ['recent_searches' => 1]]); // Fetch the new recent searches


if (!empty((array)$document['recent_searches'])) { // If there are searches to base the recomendation on

    $searchesArray = (array)$document['recent_searches']; // Cast the searches on the document as an array
    usort($searchesArray, function($a, $b) {return $b['count'] - $a['count'];}); // Sort the searches based on count
    $searchTerm = $searchesArray[0]['search_term'];
    $cursor = $client->movie_box->products->find(['$text' => [ '$search' => $searchTerm]]); // Search for items that appear with the most popular search

    $newRec = "<h2 style='margin-top: 0px;'>Recommendations:</h2>
    <h5 style='margin-top: 0px;'>Based on your recent search for '$searchTerm'</h5>"
    ;

    $cursorArray = $cursor->toArray(); // Convert the cursor to an array

    // set number of results to 3 (or below if there aren't 3 results)
    if (count($cursorArray) >= 3) {
        $numberResults = 3;
    } else {
        $numberResults = count($cursorArray);
    }

    $recommendations = array_rand($cursorArray, $numberResults); // Randomise the selection of products from the array
    $recommendations = (array)$recommendations; // If only 1 id id returned make sure it is put into an array

    foreach ($recommendations as $key => $recommendation) { // For each product display it's artwork, linked to it's product page
        $productBarcode = $cursorArray[$recommendation]['barcode'];
        $artworkURL = $cursorArray[$recommendation]['artwork'];
        $newRec .= "<a href='product.php?productid=$productBarcode'><img src='$artworkURL' width='80px' style='padding-right:10px;'></a>";
    }

    $newRec .= "<a href='#' onClick='removeUpdateRec(\"$searchTerm\");'>Not relevant? Let me try again</a>";

    echo $newRec;
    
} else {
    echo "";
}
?>