<?php
    require_once('connect.php');

    // Get the user input from the AJAX request
    $bill_id = $_POST['bill_id'];
    $biller_id = $_POST['biller_id'];

    // Prepare and execute a query
    $stmt = $dbcon->prepare('SELECT * FROM bill WHERE bill_id = :bill_id AND biller_id = :biller_id');
    $stmt->bindParam(':bill_id', $bill_id);
    $stmt->bindParam(':biller_id', $biller_id);
    $stmt->execute();

 
  // Fetch the query result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Bill exists.',
        'data' => $result // Include the query result data in the response
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'BIll does not exist or Bill number does not match with Biller.',
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
