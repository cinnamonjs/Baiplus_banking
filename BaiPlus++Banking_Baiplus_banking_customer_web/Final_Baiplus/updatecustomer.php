<?php
    require_once('connect.php');

    // Get the user input from the AJAX request
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $gender = $_POST['gender'];
    $userInput = $_POST['userInput'];


    // Prepare and execute an update query
    $stmt = $dbcon->prepare('UPDATE customer SET 
        customer_fname = :firstname,
        customer_lname = :lastname,
        customer_email = :email,
        customer_phone = :phone,
        customer_DOB = :date,
        customer_gender = :gender
        WHERE customer_ID = :userInput');

    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':userInput', $userInput);

    $stmt->execute();

    $response = array(
        'status' => ($stmt->rowCount() > 0) ? 'success' : 'error'
    );

    // Encode the response as JSON
    $jsonResponse = json_encode($response);

    // Set the appropriate content type header
    header('Content-Type: application/json');

    // Send the JSON response back to JavaScript
    echo $jsonResponse;
?>
