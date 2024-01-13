<?php
    require_once('connect.php');

    // Get the user input from the AJAX request
    $userInput = $_POST['customerID'];
    // $bank = $_POST['choice'];

    // Prepare and execute a query
    $stmt = $dbcon->prepare('SELECT * FROM customer WHERE customer_id = :userInput ');
    $stmt->bindParam(':userInput', $userInput);
    // $stmt->bindParam(':bank', $bank);
    $stmt->execute();

  // Fetch the query result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Customer exists in the database.',
        'data' => $result // Include the query result data in the response
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Customer does not exist in the database.',
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
