<?php
 require_once('connect.php');

 // Get the user input from the AJAX request
 $userInput = $_POST['customerID'];
//  echo $userInput;
//  $bank = $_POST['choice'];

 // Prepare and execute a query
 $stmt = $dbcon->prepare('SELECT account_pin FROM customer WHERE customer_id = :userInput ');
 $stmt->bindParam(':userInput', $userInput);
//  $stmt->bindParam(':bank', $bank);
 $stmt->execute();

// Check if a matching row exists
if ($stmt->rowCount() > 0) {
    $response = $stmt->fetchColumn(); // Fetch the pin value directly
} else {
    $response = 'No matching row found.';
}

echo $response; // Send the response back to JavaScript
?>