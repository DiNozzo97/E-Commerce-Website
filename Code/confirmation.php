<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmed|MovieBox</title>
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

        <?php require 'assets/navbar.php';
        if (!isset($_SESSION['userID'])) { // If the user isn't a signed in as a customer
            header('Location: ./'); // Then redirect them to the main page
        } ?>

            <!-- ------------------ CONTENT ------------------- -->

            <div id="content" class="container">
                <div class="container">
                    <h1>Confirmation <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></h1>
                    <p class="text-success">Order Confirmed. Your Order Number: <strong><?= $_GET['id']; ?></strong><br> click <a href="./">here</a> to return to the homepage.</p>
                </div>
            </div>
            <footer></footer>
        </div>

        <?php require 'assets/modals.php'; ?>



    </body>

    </html>

