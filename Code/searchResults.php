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
                                        <a class="btn btn-info" href="product.php">More Info</a>
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

