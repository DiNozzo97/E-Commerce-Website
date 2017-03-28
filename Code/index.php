<! DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MovieBox</title>
    <!-- Import Libraries (Not our code) -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Our custom stylesheet -->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <script src="js/customScripts.js"></script>
</head>

<body>
    <?php require 'assets/navbar.php'; ?>

                <!-- ------------------ CONTENT ------------------- -->

                <div id="content">
                    <!-- Slideshow -->
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="http://placehold.it/1300x315" alt="...">
                                <div class="carousel-caption">
                                    <h3>Caption Text</h3>
                                </div>
                            </div>
                            <div class="item">
                                <img src="http://placehold.it/1300x315" alt="...">
                                <div class="carousel-caption">
                                    <h3>Caption Text</h3>
                                </div>
                            </div>
                            <div class="item">
                                <img src="http://placehold.it/1300x315" alt="...">
                                <div class="carousel-caption">
                                    <h3>Caption Text</h3>
                                </div>
                            </div>
                            <div class="item">
                                <img src="http://placehold.it/1300x315" alt="...">
                                <div class="carousel-caption">
                                    <h3>Caption Text</h3>
                                </div>
                            </div>
                            <div class="item">
                                <img src="http://placehold.it/1300x315" alt="...">
                                <div class="carousel-caption">
                                    <h3>Caption Text</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                        <!-- Bottom 'Info Panels' -->
                        <section id="deliverySquare" style="float: left; width:25%;">
                            <img src="media/delivery.png" id="delivery">
                            <h1> Next Day Delivery</h1>
                            <p> fhfhosi fsijfsidjfoisjfoi dsfjosidjfoidsf oidfjoisjfois sojfosif djflksfj dsfljlsdkfjlksd cjoisjvoiv dscsdjclksd vjdvoijsdics vvkljxlvkjsiodv vjoixjvosidjo</p>
                        </section>   
                        <section style="float: left; width:25%;">
                            <img src="media/tick.png" id="subscribe">
                            <h1> Subscribe </h1>
                            <p> fhfhosi fsijfsidjfoisjfoi dsfjosidjfoidsf oidfjoisjfois sojfosif djflksfj dsfljlsdkfjlksd cjoisjvoiv dscsdjclksd vjdvoijsdics vvkljxlvkjsiodv vjoixjvosidjo</p>
                        </section>
                        <section style="float: left; width:25%;">
                            <img src="media/feedback.png" id="feedback" >
                            <h1> Give us Feedback</h1>
                            <p> fhfhosi fsijfsidjfoisjfoi dsfjosidjfoidsf oidfjoisjfois sojfosif djflksfj dsfljlsdkfjlksd cjoisjvoiv dscsdjclksd vjdvoijsdics vvkljxlvkjsiodv vjoixjvosidjo</p>
                        </section>
                    </div>
                    
                </div>
                <footer></footer>

                <?php require 'assets/modals.php'; ?>

            </body>

            <script>
        // Make the slideshow change automatically every 3 seconds
        $('.carousel').carousel({
            interval: 3000
        });

    </script>
    </html>

