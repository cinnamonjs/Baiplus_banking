<?php
 require_once('connect.php');

 // Get the user input from the AJAX request
 $userInput = $_POST['userInput'];
 $bank = $_POST['choice'];

 // Prepare and execute a query
 $stmt = $dbcon->prepare('SELECT * FROM account WHERE account_id = :userInput AND bank_id = (SELECT bank_id FROm bank WHERE bank_name = :bank)');
 $stmt->bindParam(':userInput', $userInput);
 $stmt->bindParam(':bank', $bank);
 $stmt->execute();

 // Check if a matching row exists
 if ($stmt->fetch()) {
     $response = $stmt->fetch();
   } else {
     $response = $stmt->errorCode();
   }
   
   echo $response; // Send the response back to JavaScript
?>