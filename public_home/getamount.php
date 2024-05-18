<?php
// Retrieve the customer ID from the POST request
$customerID = $_POST['customerID'];

require('connect.php');
    // Prepare the SQL query to calculate the sum of amounts for the customer
    $query = "SELECT SUM(account_balance) AS Amount FROM account WHERE customer_ID = :customerID";
    $statement = $dbcon->prepare($query);
    $statement->bindParam(':customerID', $customerID);
    
    // Execute the query
    $statement->execute();
    
    // Fetch the result as an associative array
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    
    // Send the result back as JSON
    echo json_encode($result);
?>
