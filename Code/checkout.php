<?php
require 'vendor/autoload.php'; //import the mongodb library
session_start();

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->customers; // Select the database and collection      

$document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['projection' => ['basket' => 1, 'name' => 1, 'shipping_address' => 1, 'recent_searches' => 1]]); // Find the customer's document
?>
<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout|MovieBox</title>
    <!-- Import Libraries (Not our code) -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Our custom stylesheet -->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <script src="js/customScripts.js"></script>
</head>

<body>
    <div id="wrapper">
        <?php require 'assets/navbar.php'; ?>

                    <!-- ------------------ CONTENT ------------------- -->

                    <div id="content" class="container">
                    <div id="checkoutAlert"></div>
                        <div class="container">
                            <h1>Checkout</h1>
                            <div class="row">
                                <div class="col-md-9">
                                    <p>
                                        <strong>Delivery Address:</strong><br>
                                        <?=$document['name']['first_name'] . " " . $document['name']['last_name'];?><br>
                                        <?=$document['shipping_address']['address_line1']?><br>
                                        <?php if ($document['shipping_address']['address_line2'] != "") { echo $document['shipping_address']['address_line2'] . "<br>";} // If a second address line was provided then print it ?> 
                                        <?=$document['shipping_address']['city']?><br>
                                        <?=$document['shipping_address']['postcode']?>
                                    </p>
                                </div>
                                <div class='col-md-3' id='recommendations'>
                                <?php
                                // RECOMMENDATIONS
                                if (!empty((array)$document['recent_searches'])) { // If there are searches to base the recomendation on

                                    $searchesArray = (array)$document['recent_searches']; // Cast the searches on the document as an array
                                    usort($searchesArray, function($a, $b) {return $b['count'] - $a['count'];}); // Sort the searches based on count
                                    $searchTerm = $searchesArray[0]['search_term'];
                                    $cursor = $client->movie_box->products->find(['$text' => [ '$search' => $searchTerm]]); // Search for items that appear with the most popular search

                                    echo "<h2 style='margin-top: 0px;'>Recommendations:</h2>
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
                                        echo "<a href='product.php?productid=$productBarcode'><img src='$artworkURL' width='80px' style='padding-right:10px;'></a>";
                                    }

                                    echo "<a href='#' onClick='removeUpdateRec(\"$searchTerm\");'>Not relevant? Let me try again</a>";
                                    
                                }
                                ?>
                                </div>
                                
                            </div>
                        </div>
                        <!-- Order Summary Table -->
                        <div class="container">
                            <table class="table table-striped table-vert-middle">
                                <!--        Column Headings        -->
                                <thead>
                                    <tr>
                                        <th class="text-left">DVD Cover</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-right">Line Price</th>
                                    </tr>
                                </thead>
                                <!--       Table Data         -->
                                <tbody>
                                    <?php
                                        

                                        $basketTotal = $document['basket']['basket_total'];

                                        // Format the price
                                        $basketTotal = $basketTotal/100; // Convert into pounds
                                        $basketTotal = number_format((float)$basketTotal, 2, '.', ''); //to 2 DP 
                                        
                                        foreach ($document['basket']['items'] as $item) { // For each basket item
                                            $barcode = $item['barcode'];
                                            $artwork = $item['artwork'];
                                            $title = $item['title'];
                                            $qty = $item['quantity'];
                                            $unitPrice = $item['unit_price'];
                                            $linePrice = $unitPrice * $qty;

                                            // Format the prices
                                            $unitPrice = $unitPrice/100; // Convert into pounds
                                            $unitPrice = number_format((float)$unitPrice, 2, '.', ''); //to 2 DP 

                                            $linePrice = $linePrice/100; // Convert into pounds
                                            $linePrice = number_format((float)$linePrice, 2, '.', ''); //to 2 DP 

                                            echo 
                                            "<tr>
                                                <td class='text-left'><img src='$artwork' width='50px'></td>
                                                <td class='text-center'><a href='product.php?productid=$barcode'>$title</a></td>
                                                <td class='text-center'>$qty</td>
                                                <td class='text-center'>£$unitPrice</td>
                                                <td class='text-right'>£$linePrice</td>
                                            </tr>";
                                        }

                                    if (count($document['basket']['items']) > 0) {
                                        echo "
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class='text-center'><strong>Order Total:</strong></td>
                                            <td class='text-right'><strong>£$basketTotal</strong></td>
                                        </tr>"; 
                                    } else {
                                        echo "
                                        <tr>
                                            <td class='text-center' colspan='5'><strong>No products in your basket! :(</strong></td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php if (count($document['basket']['items']) > 0) {
                                echo "<button type='button' class='btn btn-success pull-right' onclick='return createOrder()'>Confirm Order</button>";
                                } ?>
                        </div>
                    </div>


                    <footer></footer>

                    <?php require 'assets/modals.php'; ?>


                </body>

                </html>

