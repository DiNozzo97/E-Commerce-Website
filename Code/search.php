<?php
require '../vendor/autoload.php'; // Import the MongoDB library

session_start(); // Start the PHP Session

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB serve

//Select a database
$db = $mongoClient->movie_box;

//Extract the data that was sent to the server
$search_string = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);

//Create a PHP array with our search criteria
$findCriteria = [
    '$text' => [ '$search' => $search_string ] 
 ];

//Find all of the customers that match  this criteria
$cursor = $db->products->find($findCriteria);

//Output the results
echo "<h1>Results</h1>";
foreach ($cursor as $cust){
   echo "<p>";
   echo "Customer name: " . $cust['title'];
   echo " Email: ". $cust['email'];
   echo " ID: " . $cust['_id'];
   echo "</p>";
}

//Close the connection
$mongoClient->close();


