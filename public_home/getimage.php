<?php
    require_once('connect.php');

    // Get the user input from the AJAX request
    $accountID = $_POST['accountID'];

    // Prepare and execute a query
    $stmt = $dbcon->prepare('SELECT img FROM customer WHERE customer_id = (SELECT customer_id FROm account WHERE account_id = :account_id)');
    $stmt->bindParam(':account_id', $accountID);
    $stmt->execute();

    // Check if a matching row exists
    // if ($stmt->fetch()) {
    //     $response = 'Data exists in the database.';
    //   } else {
    //     $response = 'Data does not exist in the database.';
    //   }
      
    //   echo $response; // Send the response back to JavaScript

  // Fetch the query result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Data exists in the database.',
        'data' => $result // Include the query result data in the response
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Data does not exist in the database.',
        'data' => null // Set data to null when no match is found
    );
}
    
    // Encode the response as JSON
    $jsonResponse = json_encode($response);
    
    // Set the appropriate content type header
    header('Content-Type: application/json');
    
    // Send the JSON response back to JavaScript
    echo $jsonResponse;
?>
