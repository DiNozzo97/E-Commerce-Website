<?php
require '../vendor/autoload.php'; // Import the MongoDB library
session_start();
if (!isset($_SESSION['staffID'])) { // If the user isn't a signed in member of staff
    header('Location: ./'); // Then redirect them to the login page
} ?>


<!DOCTYPE html>
<html>
<head>
    <!--    Set Encoding type    -->
    <meta charset="utf-8">
    <!--    Page Title     -->
    <title>Orders | MovieBox</title>
    <!--    Import Bootstrap CSS (NOT OUR CODE)    -->
    <link rel='stylesheet' type='text/css' href='../css/bootstrap.css'>
    <!--    Import custom styles CSS    -->
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <!--    Import JQuery Library (NOT OUR CODE)    -->
    <script src='../js/jquery-3.1.1.min.js'></script>
    <!--    Import Bootstrap JS Library (NOT OUR CODE)    -->
    <script src='../js/bootstrap.min.js'></script>
    <!--    Import cust JS code    -->
    <script src="js/products.js"></script>
    <script src='js/customScripts.js'></script>
</head>

<body>
    <!--    Navbar    -->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <!--         Display Logo in navbar           -->
                <a class="navbar-brand" href="#"><img id="logo" src="../media/logo.png"></a>
            </div>

            <!--       Navbar buttons         -->
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#">Orders</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="assets/logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <h2 class="sub-header">Orders</h2>
        <!--    Table to display list of Orders    -->
        <table class="table table-striped">
            <!--        Column Headings        -->
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Created At</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <!--       Table Data         -->
            <tbody>
            <?php
                    $client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

                    $collection = $client->movie_box->orders; // Select the database and collection      

                    $cursor = $collection->find([], ['sort' => ['created' => -1]]); // Find all of the orders and sort them with the latest at the top
                    foreach ($cursor as $document) { // For each order

                        $created = $document['created']; // get the created mongo DateTime object
                        $created = $created->toDateTime(); // Convert it to a PHP DateTime object
                        $created = $created->format('d/m/Y H:i'); // Turn it into a pretty formatted string
                        echo 
                        "<tr>
                            <td>" . $document['order_number'] . "</td>
                            <td>" . $created . "</td>
                            <td>" . $document['customer']['name'] . "</td>
                            <td>" . $document['status'] . "</td>
                            <td>
                                <!--             Action Buttons               -->
                                <button class='btn btn-primary' id='viewOrderButton' onclick='return viewOrder(" . $document['order_number'] . ");'>View</button>
                                <button class='btn btn-success' id='updateStatusButton' onclick='return showUpdateOrder(" . $document['order_number'] . ");'>Update Status</button>
                                <button class='btn btn-danger' id='deleteOrderButton' onclick='return showDeleteOrder(" . $document['order_number'] . ");'>Delete</button>
                            </td>
                        </tr>";
                    }

                    ?>
            </tbody>
        </table>
    </div>

    <!--    Modals    -->

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
                            <label class="col-md-4 control-label" for="customerName">Customer</label>  
                            <div class="col-md-7">
                                <input id="customerName" name="customerName" type="text" class="form-control input-md" disabled>
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
                                        <th>Barcode</th>
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


    <!-- Modal to Update Order Status  -->
    <div id="updateStatusModal" class="modal fade" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Update Order Status</h2>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="statusSelect">Status</label>
                            <div class="col-md-4" id="newStatusSelect">
                                <select id="statusSelect" name="statusSelect" class="form-control">
                                    <option value="Ordered">Ordered</option>
                                    <option value="Dispatched">Dispatched</option>
                                    <option value="Delivered">Delivered</option>
                                </select>
                            </div>
                            <button type="button" id="statusUpdateSubmit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal to confirm Order Delete -->
    <div id="deleteOrderModal" class="modal fade" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Do you really want to permenantly delete this order?</h2>
                </div>
                <div class="modal-body">
                    <div class="settingsButtons">
                        <button type="button" id="orderDeleteYes" class="btn btn-success settings" >Yes</button>
                        <button type="button" class="btn btn-danger settings" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
