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
    <title>Products | MovieBox</title>
    <!--    Import Bootstrap CSS (NOT OUR CODE)    -->
    <link rel='stylesheet' type='text/css' href='../css/bootstrap.css'>
    <!--    Import custom styles CSS    -->
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <!--    Import JQuery Library (NOT OUR CODE)    -->
    <script src='../js/jquery-3.1.1.min.js'></script>
    <!--    Import Bootstrap JS Library (NOT OUR CODE)    -->
    <script src='../js/bootstrap.min.js'></script>
    <!--    Import custom JS code    -->
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
                <li><a href="orders.php">Orders</a></li>
                <li class="active"><a href="#">Products</a></li>
                <li><a href="assets/logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <h2 class="sub-header">Products</h2>
        <button id="newProductButton" class="btn btn-success">Create New Product</button>
        <!--    Table to display list of products    -->
        <table class="table table-striped">
            <!--        Column Headings        -->
            <thead>
                <tr>
                    <th></th>
                    <th>Barcode</th>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <!--       Table Data         -->
            <tbody>
                <?php
                $client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

                $collection = $client->movie_box->products; // Select the database and collection      

                $cursor = $collection->find([], ['sort' => ['details.title' => 1]]); // Find all of the product and sort them alphabetically
                foreach ($cursor as $document) { // For each product


                    echo 
                    "<tr>
                        <td><img src='../" . $document['artwork'] . "' height='100px'></td>
                        <td>" . $document['barcode'] . "</td>
                        <td>" . $document['details']['title'] . "</td>
                        <td>
                            <!--             Action Buttons               -->
                            <button class='btn btn-primary' id='viewProductButton' onclick='return viewProduct(" . $document['barcode'] . ");'>View</button>
                            <button class='btn btn-success' id='editProductButton' onclick='return showEditProduct(" . $document['barcode'] . ");'>Edit</button>
                            <button class='btn btn-danger' id='deleteProductButton' onclick='return showDeleteProduct(" . $document['barcode'] . ");'>Delete</button>
                        </td>
                    </tr>";
                }

                ?>
            </tbody>
        </table>
    </div>

    <!--    Modals    -->

    <!-- New Product Modal -->
    <div id="newProduct" class="modal fade" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1>New Product</h1>
                </div>
                <div class="modal-body">
                    <!--            New Product Form            -->
                    <form class="form-horizontal" method="post">
                        <!--              Display Blank Fields, ready for data              -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="barcode">Barcode</label>  
                            <div class="col-md-4">
                                <input id="barcode" name="barcode" type="text" placeholder="Barcode" class="form-control input-md">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">Title</label>  
                            <div class="col-md-7">
                                <input id="title" name="title" type="text" placeholder="Title" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="description">Description</label>  
                            <div class="col-md-7">
                                <textarea id="description" name="description" type="text" placeholder="Description" class="form-control input-md" rows="7" cols="50"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="releaseDate">Release Date</label>  
                            <div class="col-md-4">
                                <input id="releaseDate" name="releaseDate" type="date" placeholder="dd/mm/yyyy" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="director">Director</label>  
                            <div class="col-md-7">
                                <input id="director" name="director" type="text" placeholder="eg.  Christopher Nolan,David Fincher" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="duration">Duration</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input id="duration" name="duration" class="form-control" placeholder="Duration" type="number">
                                    <span class="input-group-addon">mins</span>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="cast">Cast</label>  
                            <div class="col-md-7">
                                <input id="cast" name="cast" type="text" placeholder="eg. Sophie Turner,Jack Nicholson" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="studio">Studio</label>  
                            <div class="col-md-7">
                                <input id="studio" name="studio" type="text" placeholder="eg. Warner Bros,Pixar" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="category">Categories</label>  
                            <div class="col-md-7">
                                <input id="category" name="category" type="text" placeholder="eg. Animation,Children,Family" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="language">Language</label>  
                            <div class="col-md-7">
                                <input id="language" name="language" type="text" placeholder="eg. French,English,Spanish" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="format">Format</label>  
                            <div class="col-md-4">
                                <input id="format" name="format" type="text" placeholder="Format" class="form-control input-md">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="certificate">Certificate</label>  
                            <div class="col-md-2">
                                <input id="certificate" name="certificate" type="text" placeholder="Cert" class="form-control input-md">

                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="price">Price</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    <input id="price" name="price" class="form-control" placeholder="0.00" min="0.01" step="0.01" type="number">
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="quantity">Quantity Available</label>  
                            <div class="col-md-2">
                                <input id="quantity" name="quantity" type="number" placeholder="Qty" class="form-control input-md" min="0">

                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-md-4 control-label" for="trailer">Trailer URL</label>  
                            <div class="col-md-7">
                                <input id="trailer" name="trailer" type="text" placeholder="http://youtube.com/examplevid" class="form-control input-md">

                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="artwork">Artwork URL</label>  
                            <div class="col-md-7">
                                <input id="artwork" name="artwork" type="text" placeholder="http://example.com/image.jpg" class="form-control input-md">

                            </div>
                        </div>


                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!--    Existing Product Modal    -->
    <div id="existingProduct" class="modal fade" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1>Product Information</h1>
                </div>
                <div class="modal-body">
                    <!--            Form to display the data            -->
                    <form id="existingProductForm" class="form-horizontal" method="post">
                        <!--             Display each field with data               -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingBarcode">Barcode</label>  
                            <div class="col-md-4">
                                <input id="existingBarcode" name="existingBarcode" type="text" placeholder="Barcode" class="form-control input-md">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingTitle">Title</label>  
                            <div class="col-md-7">
                                <input id="existingTitle" name="existingTitle" type="text" placeholder="Title" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingDescription">Description</label>  
                            <div class="col-md-7">
                                <textarea id="existingDescription" name="existingDescription" type="text" placeholder="Title" class="form-control input-md" rows="7" cols="50"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingReleaseDate">Release Date</label>  
                            <div class="col-md-4">
                                <input id="existingReleaseDate" name="existingReleaseDate" type="date" placeholder="dd/mm/yyyy" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingDirector">Director</label>  
                            <div class="col-md-7">
                                <input id="existingDirector" name="existingDirector" type="text" placeholder="eg.  Christopher Nolan,David Fincher" class="form-control input-md" >
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingDuration">Duration</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input id="existingDuration" name="existingDuration" class="form-control" placeholder="Duration" type="number">
                                    <span class="input-group-addon">mins</span>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingCast">Cast</label>  
                            <div class="col-md-7">
                                <input id="existingCast" name="existingCast" type="text" placeholder="eg. Sophie Turner,Jack Nicholson" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingStudio">Studio</label>  
                            <div class="col-md-7">
                                <input id="existingStudio" name="existingStudio" type="text" placeholder="eg. Warner Bros,Pixar" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingCategory">Categories</label>  
                            <div class="col-md-7">
                                <input id="existingCategory" name="existingCategory" type="text" placeholder="eg. Animation,Children,Family" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingLanguage">Language</label>  
                            <div class="col-md-7">
                                <input id="existingLanguage" name="existingLanguage" type="text" placeholder="eg. French,English,Spanish" class="form-control input-md">
                                <span class="help-block">comma-seperated</span>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingFormat">Format</label>  
                            <div class="col-md-4">
                                <input id="existingFormat" name="existingFormat" type="text" placeholder="Format" class="form-control input-md">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingCertificate">Certificate</label>  
                            <div class="col-md-2">
                                <input id="existingCertificate" name="existingCertificate" type="text" placeholder="Cert" class="form-control input-md">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingPrice">Price</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    <input id="existingPrice" name="existingPrice" class="form-control" placeholder="0.00" min="0.01" step="0.01" type="number">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingQuantity">Quantity Available</label>  
                            <div class="col-md-2">
                                <input id="existingQuantity" name="existingQuantity" type="number" placeholder="Qty" class="form-control input-md">
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingTrailer">Trailer URL</label>  
                            <div class="col-md-7">
                                <input id="existingTrailer" name="existingTrailer" type="text" placeholder="http://youtube.com/examplevid" class="form-control input-md">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label" for="existingArtwork">Artwork URL</label>  
                            <div class="col-md-7">
                                <input id="existingArtwork" name="existingArtwork" type="text" placeholder="http://example.com/image.jpg" class="form-control input-md">
                            </div>
                        </div>

                        <button id="saveButton"type="submit" class="btn btn-success" onclick="return saveEditProduct();">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal to confirm product Delete -->
    <div id="deleteProductModal" class="modal fade" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Do you really want to permenantly delete this product?</h2>
                </div>
                <div class="modal-body">
                    <div class="settingsButtons">
                        <button type="button" class="btn btn-success settings" id="productDeleteYes" >Yes</button>
                        <button type="button" class="btn btn-danger settings" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
