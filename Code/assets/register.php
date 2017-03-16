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
        } else {
		$passwordHash = password_hash($password, PASSWORD_DEFAULT); 
	    }

        if(!preg_match("/^[-'a-zA-Z]+$/", $fname)){
            $error['firstNameRegister'] = "Enter first name"; 
        }
        if(!preg_match("/^[-'a-zA-Z]+$/", $lname)){
            $error['lastNameRegister'] = "Enter last name";
        }

 
        if(!preg_match("[0-9]", $day)){
            $error['dobDayRegister'] = "Enter DOB";
        }
        
 
        if(!preg_match("[0-9]", $month)){
            $error['dobDayRegister'] = "Enter DOB";
        }
         
 
        if(!preg_match("[0-9]", $year)){
            $error['dobDayRegister'] = "Enter DOB";
        }
         
         
        if(empty($addressLine1User)){
            $error['addressLine1Register'] = "Enter first line of your address";
        }
 
 
        if(empty($addressLine2User)){
            $error['addressLine2Register'] = "Enter secound line of your address";
        }
         
            if(empty($cityUser)){
            $error['cityRegister'] = "Enter city";
            }
                
        $postcodeUserLookup = @file_get_contents("http://api.postcodes.io/postcodes/$postcodeUser/validate");
        $postcodeUserLookup = json_decode($postcodeUserLookup);
        if (!isset($postcodeUserLookup->result) or $postcodeUserLookup->result != true ) {
	    $error['postcodeRegister'] = "Enter a valid postcode";

        }



          if(count($error) == 0){
            //database configuration

            $connection = new MongoDB\Client("mongodb://localhost:27017"); // Connect to the MongoDB server

                 //connecting to database
                 $database=$connection->movie_box;

                 //connect to specific collection
                 $collection=$database->customers;

                 $query=array('email'=>$email);
                 //checking for existing user
                 $document=$collection->findOne($query);
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
              $dob = new DateTime("$year-$month-$day T00:00:00.000Z");


                 if(empty($document)){
                     //Save the New user
                     $user=array('email_address'=>$email, 'password_hash'=>$passwordHash, 'dob' => new MongoDB\BSON\UTCDateTime($dob->getTimestamp() * 1000), 'name' => array('first_name'=>$fname, 'last_name'=>$lname), 'shipping_address' => array('address_line1' => $addressLine1User, 'address_line2' => $addressLine2User, 'city' => $cityUser, 'postcode' => $postcodeUser), 'recent_searches'=> array(), 'basket' => array('basket_total' => 0, 'items' => array()));
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
