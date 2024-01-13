<?php
// Assuming you have included the necessary connect.php file that handles the database connection

// Retrieve the data sent from the client
$customerID = $_POST['customerID'];
$amount = $_POST['amount'];
$rate = $_POST['rate'];
$duration = $_POST['duration'];
$currentDateTime = date('Y-m-d H:i:s');
require('connect.php');

$getacc = $dbcon->prepare("SELECT account_id FROM account WHERE customer_ID = :customerID ORDER BY account_balance LIMIT 1");
$getacc->bindParam(':customerID', $customerID);
$getacc->execute();

$accountID = $getacc->fetchColumn();
  // Prepare the SQL statement for inserting the data into the loan table
  $insertQuery = "INSERT INTO loan (account_id, loan_amount, loan_interest, loan_duration, loan_start_date, loan_type) VALUES (:customerID, :amount, :rate, :duration ,:startdate, 'Personal Loan')";
  $stmt = $dbcon->prepare($insertQuery);

  // Bind the values to the prepared statement parameters
  $stmt->bindParam(':customerID', $accountID);
  $stmt->bindParam(':amount', $amount);
  $stmt->bindParam(':rate', $rate);
  $stmt->bindParam(':duration', $duration);
  $stmt->bindParam(':startdate', $currentDateTime);

  // Execute the prepared statement
  $stmt->execute();

  $qqr = $dbcon->prepare("UPDATE account SET account_balance = account_balance + :tranAmount WHERE account_id = :accountID");
    $qqr->bindParam(':tranAmount', $amount);
    $qqr->bindParam(':accountID', $accountID);
    $qqr->execute();

  // Check if the insertion was successful
  if ($stmt->rowCount() > 0 && $qqr) {
    // Insertion successful
    echo $accountID;
  } else {
    // Insertion failed
    echo "Error: Failed to insert the loan.";
  }
?>
