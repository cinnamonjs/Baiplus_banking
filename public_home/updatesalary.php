<?php
// Retrieve the data from the request
$salary = $_POST['salary'];
$salary_file = $_POST['salary_file'];
$customerID = $_POST['customer_id'];

require('connect.php');
if($salary_file !=  '') { 

    // Prepare the SQL statement
    $sql = "UPDATE customer SET salary = :sar, salary_file = :salary_file WHERE customer_id = :customerID";
    $stmt = $dbcon->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':sar', $salary);
    $stmt->bindParam(':salary_file', $salary_file);
    $stmt->bindParam(':customerID', $customerID);

    $result = $stmt->execute();

}
else { 
    // Prepare the SQL statement
    $sql = "UPDATE customer SET salary = :sar WHERE customer_id = :customerID";
    $stmt = $dbcon->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':sar', $salary);
    // $stmt->bindParam(':salary_file', $salary_file);
    $stmt->bindParam(':customerID', $customerID);

    $result = $stmt->execute();
}


if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Update salary success.',
        'data' => $result // Include the query result data in the response
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'SOmething is wrong.',
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