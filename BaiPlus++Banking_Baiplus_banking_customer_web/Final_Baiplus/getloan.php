<?php
    require_once('connect.php');

    // Get the user input from the AJAX request
    $loan_id = $_POST['loan_id'];
    $account_id = $_POST['account_id'];

    // Prepare and execute a query
    $stmt = $dbcon->prepare('SELECT * FROM loan WHERE loan_id = :loan_id AND account_id = :account_id');
    $stmt->bindParam(':loan_id', $loan_id);
    $stmt->bindParam(':account_id', $account_id);
    // $stmt->bindParam(':biller_id', $biller_id);
    $stmt->execute();

 
  // Fetch the query result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Loan exists.',
        'data' => $result // Include the query result data in the response
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Loan does not exist.',
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
