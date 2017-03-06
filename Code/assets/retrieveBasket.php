<?php
require '../vendor/autoload.php'; // Import the MongoDB library
session_start();

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->customers; // Select the database and collection      

$basket = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['projection' => ['basket' => 1]]); // Find the customer's basket 

$basketLines = [];
foreach ($basket['basket']['items'] as $item) { // For each item

	
	$imgURL = $item['artwork'];
	$title = $item['title'];
	$price = $item['unit_price']; //Store the price in pennies
	$price = $price/100; //Convert into pounds
	$price = number_format((float)$price, 2, '.', ''); //to 2 DP 
	$price = "£" . $price;
	$productURL = "product.php?productid=" . $item['barcode'];
	$quantity = $item['quantity'];
	
	array_push($basketLines,['title'=>$title, 'artwork'=>$imgURL, 'price'=>$price, 'hyperlink'=>$productURL, 'quantity'=>$quantity]);
	
}

$totalPrice = $basket['basket']['basket_total'];
$totalPrice = $totalPrice/100; //Convert into pounds
$totalPrice = number_format((float)$totalPrice, 2, '.', ''); //to 2 DP 

// array_push($returnData, ['totalPrice'=>$totalPrice], ['basketLine' => $basketLines]);
$returnData['totalPrice'] = $totalPrice;
$returnData['basketLine'] = $basketLines;

echo json_encode($returnData);
