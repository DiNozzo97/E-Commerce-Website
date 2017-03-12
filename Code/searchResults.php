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
                            <strong>Sort By:</strong>
                            <select>
                              <option value="relevance">Relevance</option>
                              <option value="priceLToH">Price: Low to High</option>
                              <option value="priceHToL">Price: High to Low</option>
                              <option value="category">Category</option>
                          </select>
                      </div>
                  </div>

                  <!-- Product Grid -->
                  <div id="products" class="row">
                      <!-- Product (Inside Out) -->
                      <div class="item col-md-4">
                        <div class="thumbnail">
                            <img class="group" src="media/products/insideOut.jpg" width="150px" />
                            <div class="caption">
                                <h4 class="group inner list-group-item-heading">Inside Out</h4>
                                <p class="group inner list-group-item-text">Award-winning animated comedy drama from Disney/Pixar featuring the voice talents of Amy Poehler, Phyllis Smith, Bill Hader, Lewis Black and Mindy Kaling...</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="lead">£14.99</p>
                                    </div>

                                    <div class="col-md-4">
                                        <a class="btn btn-success" href="#">Add to cart</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-info" href="http://localhost/product.php?productid=8717418468446">More Info</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="products" class="row">
                      
                      <div class="item col-md-4">
                        <div class="thumbnail">
                            <img class="group" src="media/products/logan.jpg" width="150px" />
                            <div class="caption">
                                <h4 class="group inner list-group-item-heading">Logan</h4>
                                <p class="group inner list-group-item-text">Set in the near future, the movie centers on weary Logan as he cares for ailing Professor X in a hideout on the Mexican border.People used to call him the Wolverine. Now he’s just ‘Logan’. Years have passed, the world has changed, and the mutants are all but gone. Logan’s grown old, battle-scarred and weary from years of fighting. Charles Xavier is one of the only other mutants left, and he’s far from the man he once was. And when a mysterious girl arrives in Logan’s life, in desperate need of help, it’s time for the eternal soldier to go on one last mission. Hugh Jackman returns to his defining role for the final time in ‘Logan’, bringing the epic story of the Wolverine to a close.</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="lead">£30</p>
                                    </div>

                                    <div class="col-md-4">
                                        <a class="btn btn-success" href="#">Add to cart</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-info" href="http://localhost/product.php?productid=987654321">More Info</a>
                                    </div>
                                </div>
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

</html>

