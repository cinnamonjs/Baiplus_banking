<?php
// Retrieve the transaction amount and account ID from the POST request
$tranAmount = $_POST['tranamount'];
$accountID = $_POST['accountid'];

require('connect.php'); // Include the file to establish database connection

// Prepare and execute the query to update the account balance
$stmt = $dbcon->prepare("UPDATE account SET account_balance = account_balance + :tranAmount WHERE account_id = :accountID");
$stmt->bindParam(':tranAmount', $tranAmount);
$stmt->bindParam(':accountID', $accountID);
$stmt->execute();

// Check if the update was successful
if ($stmt->rowCount() > 0) {
    // The account balance was successfully updated
    echo "Account balance updated successfully.";
} else {
    // Failed to update the account balance
    echo "Failed to update account balance.";
}
?>
