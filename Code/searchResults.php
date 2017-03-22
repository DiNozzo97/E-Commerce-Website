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


            $client = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB serve

                //Select a database
                $db = $client->movie_box;

                //Extract the data that was sent to the server
                $search_string = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

                //Create a PHP array with our search criteria
                $findCriteria = [
                    '$text' => [ '$search' => $search_string ] 
                 ];

                //Find all of the customers that match  this criteria
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

