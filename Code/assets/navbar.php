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

                        <form class="navbar-form navbar-left" id="search" name ="name" method="GET" action="searchResults.php">
                            <div class="form-group">
                                <input name="search" id="search" type="text" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" id="searchbutton" class="btn btn-default" aria-label="Left Align">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>
                        </form>

                        <!-- ------------------ SHOPPING CART ------------------- -->
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                              <a href="#" onClick="refreshCart();" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-shopping-cart"></span> Basket<span class="caret"></span></a>
                              <ul class="dropdown-menu dropdown-cart" role="menu">
							  <div id='basketItems'>
                         
								</div>
                                <li><p class="cartTotal"><strong>Total: £<span id='totalBasketPrice'>0.00</span></strong></p></li>
                                <li class="divider"></li>
                                <li><a class="text-center" href="checkout.php">Checkout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>