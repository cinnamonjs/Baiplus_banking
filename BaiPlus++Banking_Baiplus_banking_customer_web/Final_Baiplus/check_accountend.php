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
