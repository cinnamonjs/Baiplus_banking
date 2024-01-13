<?php
// Retrieve the customer ID from the POST request
$customerID = $_POST['customerID'];

require('connect.php');

$stmt = $dbcon->prepare("SELECT account_id, bank_id FROM account WHERE customer_id = :customerID");
$stmt->bindParam(':customerID', $customerID);
$stmt->execute();

// Fetch the account IDs and bank IDs
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create an empty array to hold the grouped account IDs with bank names
$groupedAccounts = [];

// Iterate over the accounts and retrieve the corresponding bank names
foreach ($accounts as $account) {
  $accountID = $account['account_id'];
  $bankID = $account['bank_id'];

  // Prepare and execute the query to fetch the bank name
  $stmt = $dbcon->prepare("SELECT bank_name FROM bank WHERE bank_id = :bankID");
  $stmt->bindParam(':bankID', $bankID);
  $stmt->execute();

  // Fetch the bank name
  $bankName = $stmt->fetchColumn();

  // Check if the account ID already exists in the grouped accounts array
  if (isset($groupedAccounts[$accountID])) {
    // Account ID already exists, add the bank name to the existing account
    $groupedAccounts[$accountID]['bank_name'] = $bankName;
  } else {
    // Account ID doesn't exist, create a new entry in the grouped accounts array
    $groupedAccounts[$accountID] = [
      'account_id' => $accountID,
      'bank_name' => $bankName
    ];
  }
}

// Convert the grouped accounts array to a simple array of values
$finalAccounts = array_values($groupedAccounts);

// Return the accounts array as JSON to JavaScript
$response = json_encode($finalAccounts);
echo $response;
?>
