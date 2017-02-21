<?php
session_start(); // Load the Session
session_unset(); // Unset all of the session Variables
session_destroy(); // Destroy the session
header("location:../"); // Return the user to the home page
exit(); // exit

?>