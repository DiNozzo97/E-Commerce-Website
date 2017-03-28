<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Results|MovieBox</title>
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
                <!-- Top Title Box -->
                <div class="well well-sm titleBar">
                    <h2>Search Results</h2>
                    <div class="text-right">
                        <?php if (isset($_GET['sort'])) { // If the sort parameter has been provided
                                $sortBy = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING); // Sanitize and store the provided value
                            } else {
                                $sortBy = ""; // initialise the variable as an empty string
                            } ?>
                            <strong>Sort By:</strong>
                            <select id="sortBy" onchange="changeResultSort();">
                              <option value="relevance" <?php if ($sortBy == "relevance") echo "selected"; ?>>Relevance</option>
                              <option value="releaseEtoL" <?php if ($sortBy == "releaseEtoL") echo "selected"; ?>>Release Date: Earliest to Latest</option>
                              <option value="releaseLtoE" <?php if ($sortBy == "releaseLtoE") echo "selected"; ?>>Release Date: Latest to Earliest</option>
                              <option value="durationLtoH" <?php if ($sortBy == "durationLtoH") echo "selected"; ?>>Duration: Low to High</option>
                              <option value="durationHtoL" <?php if ($sortBy == "durationHtoL") echo "selected"; ?>>Duration: High to Low</option>
                              <option value="priceLToH" <?php if ($sortBy == "priceLToH") echo "selected"; ?>>Price: Low to High</option>
                              <option value="priceHToL" <?php if ($sortBy == "priceHToL") echo "selected"; ?>>Price: High to Low</option>
                          </select>
                      </div>
                  </div>
                  <div id="products" class="row">
                    <?php
            require './vendor/autoload.php'; // Import the MongoDB library

            switch ($sortBy) { // Switch case for different sorting parameters
                case 'priceLToH':
                $sortParam = ['sort'=>['price'=>1]];
                break;

                case 'priceHToL':
                $sortParam = ['sort'=>['price'=>-1]];
                break;

                case 'releaseEtoL':
                $sortParam = ['sort'=>['details.release_date'=>1]];
                break;

                case 'releaseLtoE':
                $sortParam = ['sort'=>['details.release_date'=>-1]];
                break;

                case 'durationLtoH':
                $sortParam = ['sort'=>['details.duration'=>1]];
                break;

                case 'durationHtoL':
                $sortParam = ['sort'=>['details.duration'=>-1]];
                break;

                case 'releaseLtoE':
                $sortParam = ['sort'=>['details.release_date'=>1]];
                break;

                case 'releaseEtoL':
                $sortParam = ['sort'=>['details.release_date'=>-1]];
                break;

                default: // Results are sorted by relevence by default so anything other than price can be sorted by relevance
                $sortParam = [];
                break;
            }


                $client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

                //Select a database
                $db = $client->movie_box;

                //Extract the data that was sent to the server
                $search_string = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);


                //Create a PHP array with our search criteria
            $findCriteria = [
            '$text' => [ '$search' => $search_string ] 
            ];

                 // if no search then display all products

            if ($search_string == "") {
               $findCriteria = [];
           }

                //Find all of the products that match  this criteria
           $cursor = $db->products->find($findCriteria, $sortParam);
           $results = false;

                //Output the results
           foreach ($cursor as $movie){
            $results = true;
            $artwork = $movie['artwork'];
            $title = $movie['details']['title'];
            $description = $movie['details']['description'];
            $price = $movie['price'];
            $price = $price/100;
            $price = number_format((float)$price, 2, '.', '');
            $price = '£' . $price;
            $barcode = $movie['barcode'];

            echo "
            <div class='item col-md-4'>
                <div class='thumbnail'>
                    <img class='group' src='$artwork' width='150px' />
                    <div class='caption'>
                        <h4 class='group inner list-group-item-heading'>$title</h4>
                        <p class='group inner list-group-item-text'>$description</p>
                        <div class='row'>
                            <div class='col-md-4'>
                                <p class='lead'>$price</p>
                            </div>

                            <div class='col-md-4'>
                                <button class='btn btn-success' onclick='return addToBasket(\"$barcode\");'>Add to cart</button>
                            </div>
                            <div class='col-md-4'>
                                <a class='btn btn-info' href='product.php?productid=$barcode'>More Info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
        if (!$results) {
            echo "<p class='has-error'><strong>No Results Found</strong></p>";
        } else { // if there are results then add the search term to db
            // RECOMENDATIONS
            if (isset($_SESSION['userID'])) { // If the user is signed in as a customer
                // Tidy up outdated search terms that are older than a week
                $currentDate = new DateTime(); // store the current date in php format
                $currentMongoDate = new MongoDB\BSON\UTCDateTime(); // store the current date in mongo format


                $customerDocument = $db->customers->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Retrieve the customer document

                foreach ($customerDocument['recent_searches'] as $savedSearch) { // For each saved search
                    $dateAdded = $savedSearch['date']->toDateTime(); // Get the date saved and convert to php date
                    $dateDiff = $dateAdded->diff($currentDate); // Get the difference between today and that date
                    if ($dateDiff->format('%d') > 7) { // if the difference in days is more than 7
                        $db->customers->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$pull'=> ['recent_searches' => ['search_term' => $savedSearch['search_term']]]]); // Remove search from array
                    }
                }

                $customerDocument = $db->customers->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])]); // Retrieve the (possibly updated) customer document


                // See if the search term (if not empty) is already in 'recent_searches'
                if ($search_string != "") {
                    $searchExists = false;
                    foreach ($customerDocument['recent_searches'] as $savedSearch) { // For each saved search
                        if ($savedSearch['search_term'] == strtolower($search_string)) { //Check to see if search term matches the requested search
                            $searchExists = true; //If so, set variable to true
                            $searchCount = $savedSearch['count'];
                            break; //Break out of the foreach loop
                        }
                    }

                    if ($searchExists) {
                        $db->customers->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID']), 'recent_searches.search_term'=> strtolower($search_string)], ['$inc'=>['recent_searches.$.count' => 1], '$set' => ['recent_searches.$.date' => $currentMongoDate]]); //Increment the count by 1 in the database and update date
                    } else {
                        $db->customers->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['userID'])], ['$push'=> ['recent_searches'=>['search_term'=>$search_string,'count'=> 1,'date'=>$currentMongoDate]]]); // Add the search to the array
                    }

                }
        }
        }
        ?>

    </div>
</div>
</div>

</div>
</div>
<footer></footer>
</div>

<?php require 'assets/modals.php'; ?>


</body>

</html>

