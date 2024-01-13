<?php
// Retrieve the data from the request
$address = $_POST['address'];
$postcode = $_POST['postcode'];
$customerID = $_POST['customer_id'];

require('connect.php');

// Prepare the SQL statement
$sql = "UPDATE customer SET customer_address = :adr, customer_postcode = :postcode WHERE customer_id = :customerID";
$stmt = $dbcon->prepare($sql);

// Bind the parameters
$stmt->bindParam(':adr', $address);
$stmt->bindParam(':postcode', $postcode);
$stmt->bindParam(':customerID', $customerID);

$result = $stmt->execute();

if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Update address success.',
        'data' => $result // Include the query result data in the response
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Postcode does not exist in the database.',
        'data' => null // Set data to null when no match is found
    );
}

// Convert the response data to JSON
$jsonResponse = json_encode($response);

// Set the appropriate headers
header('Content-Type: application/json');

// Send the JSON response
echo $jsonResponse;
exit;
?>