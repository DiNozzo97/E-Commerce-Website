<?php 
require 'vendor/autoload.php'; // Import the MongoDB library
session_start();
if (!isset($_SESSION['userID'])) { // If the user isn't a signed in as a customer
    header('Location: ./mainpage.php'); // Then redirect them to the main page
} ?>
<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Orders | MovieBox</title>
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
                <div class="container">
                    <h1>My Orders</h1>
                </div>
                <!--    Table to display list of Orders    -->
                <table class="table table-striped">
                    <!--        Column Headings        -->
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Date/Time</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <!--       Table Data         -->
                    <tbody>
                    <?php
                    $client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

                    $collection = $client->movie_box->orders; // Select the database and collection      

                    $cursor = $collection->find(['customer.id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Find the documents that contain the customer's ID
                    foreach ($cursor as $document) { // For each order

                        $lastUpdated = $document['updated']; // get the updated mongo DateTime object
                        $lastUpdated = $lastUpdated->toDateTime(); // Convert it to a PHP DateTime object
                        $lastUpdated = $lastUpdated->format('d/m/Y H:i'); // Turn it into a pretty formatted string
                        echo 
                        "<tr>
                            <td>" . $document['order_number'] . "</td>
                            <td>" . $lastUpdated . "</td>
                            <td>" . $document['status'] . "</td>
                            <td>
                                <!--             View Button              -->
                                <button class='btn btn-primary' id='viewOrderButton' onclick='return viewOrder(" . $document['order_number'] . ");'>View</button>
                            </td>
                        </tr>";
                    }

                    ?>
                    </tbody>
                </table>

            </div>
            <footer></footer>
        </div>


        <?php require 'assets/modals.php'; ?>
        
        <!--    View Order Modal    -->
        <div id="viewOrderModal" class="modal fade" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h1>Order Details</h1>
                    </div>
                    <div class="modal-body">
                        <!--            Form to display the data            -->
                        <form id="existingOrderForm" class="form-horizontal" method="post">
                            <!--             Display each field with data               -->

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="orderNumber">Order No</label>  
                                <div class="col-md-4">
                                    <input id="orderNumber" name="orderNumber" type="number" class="form-control input-md" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="orderCreated">Order Created</label>  
                                <div class="col-md-4">
                                    <input id="orderCreated" name="orderCreated" type="datetime" class="form-control input-md" disabled>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-4 control-label" for="lastModified">Last Modified</label>  
                                <div class="col-md-4">
                                    <input id="lastModified" name="lastModified" type="datetime" class="form-control input-md" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="status">Status</label>  
                                <div class="col-md-4">
                                    <input id="status" name="status" type="text" class="form-control input-md" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="address">Delivery Address</label>
                                <div class="col-md-4">                     
                                    <textarea rows="5" cols="1000" class="form-control" id="address" name="address" disabled></textarea>
                                </div>
                            </div>

                            <!--    Table to display Products Purchased    -->
                            <div class="container-fluid">
                                <table class="table table-striped" id="lineItems">
                                    <!--        Column Headings        -->
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Title</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Line Price</th>
                                        </tr>
                                    </thead>
                                    <!--       Table Data         -->
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="orderTotal">Order Total</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">£</span>
                                        <input id="orderTotal" name="orderTotal" class="form-control" type="text" value="29.98" disabled>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>

