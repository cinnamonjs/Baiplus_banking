<?php
$con = mysqli_connect("localhost", "root", "", "baiplus_database");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve form data using the $_POST superglobal
$customer_fname = $_POST['customer_fname'];
$customer_lname = $_POST['customer_lname'];
$customer_email = $_POST['customer_email'];
$customer_DOB = $_POST['customer_DOB'];
$customer_gender = $_POST['customer_gender'];
$customer_address = $_POST['customer_address'];
$customer_postcode = $_POST['customer_postcode'];
$customer_phone = $_POST['customer_phone'];
$customer_password = $_POST['customer_password'];
$customer_ID = $_POST['customer_ID'];
$card_code = $_POST['card_code'];
$account_pin = $_POST['account_pin'];
$salary = $_POST['salary'];

$file_name = '';

if (isset($_FILES['salary_file'])) {
    $file_name = $_FILES['salary_file']['name'];
    // Rest of your form processing code
}

$check_sql = "SELECT * FROM customer WHERE customer_ID = '$customer_ID'";
$check_result = mysqli_query($con, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // Customer ID already exists
    header('Location: sign-up.html?status=failure'); // Redirect to the signup page with exists status
    exit();
}

$check_sql = "SELECT * FROM customer WHERE customer_postcode = '$customer_postcode'";
$check_result = mysqli_query($con, $check_sql);

if (mysqli_num_rows($check_result) == 0) {
    // Customer ID already exists
    header('Location: sign-up.html?status=failure2'); // Redirect to the signup page with exists status
    exit();
}

// Insert data into the customer table
$sql = "INSERT INTO customer (customer_fname, customer_lname, customer_email, customer_DOB, customer_gender, customer_address, customer_postcode, customer_phone, customer_password, customer_ID, card_code, account_pin, salary, salary_file) VALUES ('$customer_fname', '$customer_lname', '$customer_email', '$customer_DOB', '$customer_gender', '$customer_address', '$customer_postcode', '$customer_phone', '$customer_password', '$customer_ID', '$card_code', '$account_pin', '$salary', '$file_name')";

if (mysqli_query($con, $sql)) {
    // Success
    header('Location: sign-up.html?status=success'); // Redirect to the signup page with success status
} else {
    // Failure
    header('Location: sign-up.html?status=failure'); // Redirect to the signup page with failure status
}


// Close database connection
mysqli_close($con);
?>
