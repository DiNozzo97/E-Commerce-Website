<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout|MovieBox</title>
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
                            <h1>Checkout</h1>
                            <div class="row">
                                <div class="col-md-9">
                                    <p>
                                        <strong>Delivery Address:</strong><br>
                                        John Smith<br>
                                        Middlesex University<br>
                                        The Burroughs<br>
                                        London<br>
                                        NW4 4BT
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <h2 style="margin-top: 0px;">Recommendations</h2>
                                    <img src="media/products/insideOut.jpg" width="50px">
                                    <img src="media/products/insideOut.jpg" width="50px">
                                    <img src="media/products/insideOut.jpg" width="50px">
                                    <img src="media/products/insideOut.jpg" width="50px">
                                </div>
                            </div>
                        </div>
                        <!-- Order Summary Table -->
                        <div class="container">
                            <table class="table table-striped table-vert-middle">
                                <!--        Column Headings        -->
                                <thead>
                                    <tr>
                                        <th class="text-left">DVD Cover</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-right">Line Price</th>
                                    </tr>
                                </thead>
                                <!--       Table Data         -->
                                <tbody>
                                    <tr>
                                        <td class="text-left"><img src="media/products/insideOut.jpg" width="50px"></td>
                                        <td class="text-center">Inside Out</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">£14.99</td>
                                        <td class="text-right">£29.98</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center"><strong>Order Total:</strong></td>
                                        <td class="text-right"><strong>£29.98</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="confirmation.php"><button type="button" class="btn btn-success pull-right">Complete Order</button></a>
                        </div>
                    </div>


                    <footer></footer>

                    <?php require 'assets/modals.php'; ?>


                </body>

                </html>

