<nav class="navbar navbar-inverse navbar-fixed-top">

    <!-- ------------------ HEADER --------------------- -->
    <header>   
    <?php 
    if (session_id() == "") // If the session hasn't been started
        session_start(); // Start the session in order to load any session variables

    if (isset($_SESSION['userID'])) { // If there is a user signed in then display the user menu?>

        <div class="dropdown"id="login1">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Welcome <?php echo $_SESSION['firstName']; ?>
              <span class="caret"></span></button>
              <ul class="dropdown-menu">
                  <li><a href="#" data-toggle="modal" data-target="#editUserModal">Edit My Details</a></li>
                  <li><a href="myOrders.php">My Orders</a></li>
                  <li><a href="assets/logout.php">Logout</a></li>
              </ul>
          </div>
          <?php } else { // Otherwise display a login button ?>
            <button type="login" id="login1" class="btn btn-default" data-toggle="modal" data-target="#loginModal">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>Login</button>
            <?php } ?>
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>Categories<span class="caret"></span></button>

                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">See All</a></li>
                        <li><a href="#">Best Selling</a></li>
                        <li><a href="#">Latest Releases</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Genres</a>
                            <ul class="dropdown-menu">
                                <li><a tabindex="-1" href="#">Action</a></li>
                                <li><a href="#">Adventure</a></li>
                                <li><a href="#">Animation</a></li>
                                <li><a href="#">Drama</a></li>
                                <li><a href="#">Family</a></li>
                                <li><a href="#">Historical</a></li>
                                <li><a href="#">Horror</a></li>
                                <li><a href="#">Romance</a></li>
                                <li><a href="#">Science Fiction</a></li>
                                <li><a href="#">Thriller</a></li>
                                <li><a href="#">Western</a></li>
                            </ul>	
                        </div> 
                    </header>


                    <!-- ------------------ NAV PANEL ------------------- -->
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <a class="navbar-brand" href="mainpage.php">
                                <img alt="Brand" src="media/logo.png" id="logo"><!--improve logo-->
                            </a>
                            <!--    -->
                        </div>

                        <form class="navbar-form navbar-left" form id="search">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                            <a href="searchResults.php"><button type="button" id="searchbutton" class="btn btn-default" aria-label="Left Align">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button></a>
                        </form>

                        <!-- ------------------ SHOPPING CART ------------------- -->
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-shopping-cart"></span> 1 Items<span class="caret"></span></a>
                              <ul class="dropdown-menu dropdown-cart" role="menu">
                                  <li>
                                      <span class="item">
                                        <span class="item-left">
                                            <img src="media/products/insideOut.jpg" alt="" width="50px" />
                                            <span class="item-info">
                                                <a href="product.php"><span>Inside Out</span></a>
                                                <span>£14.99</span>
                                            </span>
                                        </span>
                                        <span class="item-right">
                                            <button class="btn btn-xs btn-success">+</button>
                                            <input type="text" name="qty" id="qty" value="2" disabled>
                                            <button class="btn btn-xs btn-danger">-</button>
                                        </span>
                                    </span>
                                </li>
                                <li><p class="cartTotal"><strong>Total: £<span>29.98</span></strong></p></li>
                                <li class="divider"></li>
                                <li><a class="text-center" href="checkout.php">Checkout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>