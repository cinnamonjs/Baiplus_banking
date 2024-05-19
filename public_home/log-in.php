<?php
session_start();

$con = mysqli_connect("mysql", "root", "", "baiplus_database");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve form data using the $_POST superglobal
$customer_ID = $_POST['customer_ID'];
$customer_password = $_POST['customer_password'];

// Query the database to check if the provided ID and password match
$sql = "SELECT * FROM customer WHERE customer_ID = '$customer_ID' AND customer_password = '$customer_password'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) == 1) {
    // Login successful
    $_SESSION['customer_ID'] = $customer_ID;
    mysqli_close($con);
    header('Location: log-in.html?status=success'); // Redirect to the login page with success status
    exit();
} else {
    // Login failed
    mysqli_close($con);
    header('Location: log-in.html?status=failure'); // Redirect to the login page with failure status
    exit();
}
?>
