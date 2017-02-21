<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inside Out|MovieBox</title>
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
                <div class="container">
                    <div class="row" style="margin-top: 75px;">
                        <!-- Image -->
                        <div class="col-md-2">
                            <img src="media/products/insideOut.jpg" width="150px">
                        </div>
                        <!-- Main Information -->
                        <div class="col-md-3">
                            <h2>Inside Out</h2>
                            <strong>£14.99</strong><br>
                            <i>8717418468446</i><br>
                            <!-- Button to activate Trailer in a lightbox -->
                            <a class="btn btn-danger venobox venoboxvid vbox-item btn-sm" data-gall="gall-video" data-type="youtube" href="https://youtu.be/WIDYqBMFzfg"><i class="fa fa-youtube"></i> Watch Trailer</a><br>
                            <p>Studio: <strong>Walt Disney</strong></p>
                            <p>Duration: <strong>91mins</strong></p>
                            <p>Release Date: <strong>23rd November 2015</strong></p>
                        </div>
                        <!-- right 'add to backet' box -->
                        <div class="col-md-3 col-md-offset-4 well text-center">
                            <p class="text-success"><strong>12</strong> copies currently in stock</p>
                            <a class="btn btn-success" href="#">Add to cart</a>
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
                      <div role="tabpanel" class="tab-pane active" id="description">Award-winning animated comedy drama from Disney/Pixar featuring the voice talents of Amy Poehler, Phyllis Smith, Bill Hader, Lewis Black and Mindy Kaling. When eleven-year-old Riley (Kaitlyn Dias) is forced to relocate to San Francisco after her dad gets a new job she has trouble adjusting to her new surroundings. Her emotions - Joy (Poehler), Sadness (Smith), Fear (Hader), Anger (Black) and Disgust (Kaling) - that reside in Headquarters, the control centre of her mind, try to help her navigate her way through the big change. However, after a slight mishap at Headquarters the situation gets out of hand, causing Riley's emotional state to worsen. Will the five emotions be able to restore order and make Riley feel better about her new life? The voice cast also includes Richard Kind, Diane Lane, Kyle MacLachlan and Frank Oz. The film won the Golden Globe Award for Best Animated Feature Film and the BAFTA and Academy Award for Best Animated Film.</div>
                      <!-- Product Details -->
                      <div role="tabpanel" class="tab-pane" id="details">
                       <table class="table table-striped">
                           <tr>
                               <th>Cast</th>
                               <td>Amy Poehler, Bill Hader, Lewis Black</td>
                           </tr>
                           <tr>
                               <th>Directed by</th>
                               <td>Pete Docter</td>
                           </tr>
                           <tr>
                               <th>Audio Languages</th>
                               <td>English, Italian</td>
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

