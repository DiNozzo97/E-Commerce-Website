<!DOCTYPE html>
<html>
<head>
    <!--   Page Title     -->
    <title>Admin | MovieBox</title>
    <!--    Import Bootstrap CSS (NOT OUR CODE)    -->
    <link rel='stylesheet' type='text/css' href='../css/bootstrap.css'>
    <!--    Import custom login CSS    -->
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <!--    Import JQuery Library (NOT OUR CODE)    -->
    <script src='../js/jquery-3.1.1.min.js'></script>
    <!--    Import Bootstrap JS Library (NOT OUR CODE)    -->
    <script src='../js/bootstrap.min.js'></script>
    <!-- Import Our custom scripts -->
    <script src='js/customScripts.js'></script>
</head>
<body>
    <!--  This div represents the main box  -->
    <div id="box">
        <!--    This div contains all of the box content    -->
        <div id="box-content">
            <!--      This div contains the logo that is displayed above the login form      -->
            <div id="logo">
                <img src="../media/logo.png">
            </div>
            <!--     This is the main Login form       -->
            <div id="loginAlert"></div> 
            <form class="form-horizontal" id="admin-login">
            <div class="form-group">
                <input type="email" class="form-control inputBox" id="emailLogin" name="emailLogin" placeholder="Email">
                </div>
                <div class="form-group">
                <input type="password" class="form-control inputBox" id="passwordLogin" name="passwordLogin" placeholder="Password">
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="loginButton"></label>
                    <div class="col-md-4 center-block">
                        <input type="submit" id="loginButton" name="loginButton" class="btn btn-default center-block" onclick="return processLogin();" value="Login">
                    </div>  
                </div>
            </form>
        </div>
    </div>
</body>
</html>