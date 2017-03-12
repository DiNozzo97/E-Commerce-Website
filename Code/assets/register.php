<?php
require '../vendor/autoload.php'; // Import the MongoDB library

session_start();

    $fname=strip_tags($_POST['fname']);
    $lname=strip_tags($_POST['lname']);
    $day=strip_tags($_POST['day']);
    $month=strip_tags($_POST['month']);
    $year=strip_tags($_POST['year']);
    $email=strip_tags($_POST['email']);
    $password=strip_tags($_POST['password']);
    $password2=strip_tags($_POST['password2']);
    $addressLine1User=strip_tags($_POST['addressLine1User']);
    $addressLine2User=strip_tags($_POST['addressLine2EditUser']);
    $cityUser=strip_tags($_POST['cityUser']);
    $postcodeUser=strip_tags($_POST['postcodeUser']);

    $error = array();
        { 
        if(empty($email) or !filter_var($email,FILTER_SANITIZE_EMAIL))
        {
          $error['emailRegister'] = "Email Address is empty or invalid";
        }
        if(empty($password)){
          $error['passwordRegister'] = "Please enter password";
        }
        if(empty($password2)){
          $error['confirmPasswordRegister'] = "Please enter Confirm password";
        }
        if($password != $password2){
           $error['confirmPasswordRegister'] = "Password and Confirm password are not matching";
        }
        if(empty($fname)){
            $error['firstNameRegister'] = "Enter first name"; 
        }
        if(empty($lname)){
            $error['lastNameRegister'] = "Enter last name";
        }

 
        if(empty($day)){
            $error['dobDayRegister'] = "Enter day";
        }

 
 
        if(empty($month)){
            $error['dobMonthRegister'] = "Enter month";
        }
         
 
        if(empty($year)){
            $error['dobYearRegister'] = "Enter year";
        }
         
         
        if(empty($addressLine1User)){
            $error['addressLine1Register'] = "Enter first line of your address";
        }
 
 
        if(empty($addressLine2User)){
            $error['addressLine2Register'] = "Enter secound line of your address";
        }
         
            if(empty($cityUserr)){
            $error['cityRegister'] = "Enter city";
        }
         
 
        if(empty($postcodeUser)){
            $error['postcodeRegister'] = "Enter your postcode";
        }
        if(count($error == 0)){
            //database configuration

            $connection = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

                 //connecting to database
                 $database=$connection->movie_box;

                 //connect to specific collection
                 $collection=$database->user;

                 $query=array('email'=>$email);
                 //checking for existing user
                 $document=$collection->findOne($query);

                 if(empty($document)){
                     //Save the New user
                     $user=array('fname'=>$fname,'lname'=>$lname,'day'=>$day,'month'=>$month,'year'=>$year,'email'=>$email,'password'=>md5($password),'password'=>$password2);             
                     $collection->insertOne($user);
                     echo json_encode(['result' => 'success']);
                     exit();
                 }else{
                     echo json_encode(['emailRegister' => 'Email is already registered!']);
                     exit();
                 }


            

        }else{
            //Displaying the error
            echo json_encode($error);
            exit();
            }
        }