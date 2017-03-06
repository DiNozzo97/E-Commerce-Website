<?php
require 'vendor/autoload.php'; //import the mongodb library
$barcode = $_GET['productid']; //get product id from url

$client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

$collection = $client->movie_box->products; // Select the database and collection      

$document = $collection->findOne(['barcode' => $barcode]); //Find the document that relates to the given barcode 
$price = $document['price']; //Store the price in pennies
$price = $price/100; //Convert into pounds
$price = number_format((float)$price, 2, '.', ''); //to 2 DP 
 
$languages  = "";
foreach($document['details']['audio_language'] as $language) {
		$languages = $languages . $language . ", " ;
}
$languages = substr($languages,0,-2); //Delete the final comma

$studios  = "";
foreach($document['details']['studio'] as $studio) {
		$studios = $studios . $studio . ", " ;
}
$studios = substr($studios,0,-2); //Delete the final comma

$directors  = "";
foreach($document['details']['director'] as $director) {
		$directors = $directors . $director . ", " ;
}
$directors = substr($directors,0,-2); //Delete the final comma

$categories  = "";
foreach($document['details']['category'] as $category) {
		$categories = $categories . $category . ", " ;
}
$categories = substr($categories,0,-2); //Delete the final comma

$cast  = "";
foreach($document['details']['cast'] as $castMember) {
		$cast = $cast . $castMember . ", " ;
}
$cast = substr($cast,0,-2); //Delete the final comma

$release = $document['details']['release_date'];
$release = $release->toDateTime(); //Convert to a php date/time object
$release = $release->format('jS F Y'); //Formatted string
 ?>
 
 
<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=$document['details']['title'];?>|MovieBox</title>
    <!-- Import Libraries (Not our code) -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="libraries/venobox/venobox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="libraries/venobox/venobox.min.js"></script>
    <!-- Import Our custom stylesheet -->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <script src="js/customScripts.js"></script>
</head>
<body>
    <div id="wrapper">
        <?php require 'assets/navbar.php'; ?>

            <!-- ------------------ CONTENT ------------------- -->

            <div id="content" class="container">
				<div id="basketFeedbackAlert"></div>
                <div class="container">
				
                    <div class="row" style="margin-top: 75px;">
                        <!-- Image -->
                        <div class="col-md-2">
                            <img src="<?=$document['artwork'];?>" width="150px">
                        </div>
                        <!-- Main Information -->
                        <div class="col-md-3">
                            <h2><?=$document['details']['title'];?></h2>
                            <strong>£<?=$price;?></strong><br>
                            <i><?=$barcode;?></i><br>
                            <!-- Button to activate Trailer in a lightbox -->
                            <a class="btn btn-danger venobox venoboxvid vbox-item btn-sm" data-gall="gall-video" data-type="youtube" href="<?=$document['details']['trailer_url'];?>"><i class="fa fa-youtube"></i> Watch Trailer</a><br>
                            <p>Studio: <strong><?=$studios;?></strong></p>
                            <p>Duration: <strong><?=$document['details']['duration'];?>mins</strong></p>
                            <p>Certificate: <strong><?=$document['details']['certificate'];?></strong></p>
                            <p>Release Date: <strong><?=$release;?></strong></p>
                        </div>
                        <!-- right 'add to backet' box -->
                        <div class="col-md-3 col-md-offset-4 well text-center">
							<?php
							$quantity = $document['quantity_available'];
							if ($quantity == 0) {
								echo "<p class='text-danger'><strong>Product currently not in stock</strong></p>";
							} else {
								echo "<p class='text-success'><strong>$quantity</strong> copies currently in stock</p>";
								echo "<button class='btn btn-success' onclick='return addToBasket(\"$barcode\");'>Add to cart</button>";
							}
							?>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 col-md-offset-2" id="productDescDetailTabs">

                      <!-- Tab Navigator -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Description</a></li>
                        <li role="presentation"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Product Details</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <!-- Description -->
                      <div role="tabpanel" class="tab-pane active" id="description"><?=$document['details']['description'];?></div>
                      <!-- Product Details -->
                      <div role="tabpanel" class="tab-pane" id="details">
                       <table class="table table-striped">
                           <tr>
                               <th>Cast</th>
                               <td><?=$cast;?></td>
                           </tr>
                           <tr>
                               <th>Directed by</th>
                               <td><?=$directors;?></td>
                           </tr>
                           <tr>
                               <th>Audio Languages</th>
                               <td><?=$languages;?></td>
                           </tr>
                       </table>
                   </div>
               </div>
           </div>
       </div>
   </div>


</div>
<footer></footer>
</div>


<?php require 'assets/modals.php'; ?>

</body>
<script>
    // Make trailer button trigger a venobox lightbox
    $('.venobox').venobox(); 
</script>

</html>

