<?php

require('connect.php');
session_start();

// Retrieve the customer ID from the session
$customerID = $_SESSION['customer_ID'];

// Return the customer ID as the response
echo $customerID;
?>
