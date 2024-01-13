<?php
// Get the request data
$oldPassword = $_POST['old_password'];
$newPassword = $_POST['new_password'];
$newPasswordConfirm = $_POST['new_confirm_password'];
$oldPin = $_POST['old_pin'];
$newPin = $_POST['new_pin'];
$newPinConfirm = $_POST['new_confirm_pin'];
$customerID = $_POST['customer_id'];

require('connect.php');

// Simulating the retrieval of stored password and pin from the database based on user ID
$stmt = $dbcon->prepare("SELECT customer_password , account_pin FROM customer WHERE customer_ID = :userId");
$stmt->bindParam(":userId", $customerID);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$storedPassword = $row['customer_password'];
$storedPin = $row['account_pin'];


if (!empty($newPassword) && !empty($oldPin) && $newPassword === $newPasswordConfirm && $newPin === $newPinConfirm) {
    // Case 3: Update both password and PIN
    // Update both logic here
    $stmt = $dbcon->prepare("UPDATE customer SET customer_password = :pasword, account_pin = :pin WHERE customer_ID = :userId");
    $stmt->bindParam(":pasword", $newPassword);
    $stmt->bindParam(":pin", $newPin);
    $stmt->bindParam(":userId", $customerID);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Password and PIN updated successfully.']);
  } 
else if ($oldPassword == $storedPassword) {
  // Old password matches
  if (!empty($newPassword) && $newPassword === $newPasswordConfirm) {
    // Case 1: Update password
    $stmt = $dbcon->prepare("UPDATE customer SET customer_password = :pasword WHERE customer_ID = :userId");
    $stmt->bindParam(":pasword", $newPassword);
    $stmt->bindParam(":userId", $customerID);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
  } else {
    // Invalid or incomplete data
    echo json_encode(['status' => 'error', 'message' => 'Invalid new password.']);
  }
} else if ( $oldPin === $storedPin  ) {
    // Case 2: Update PIN
    // Update PIN logic here
    if ( !empty($newPin) && $newPin === $newPinConfirm ) { 
        $stmt = $dbcon->prepare("UPDATE customer SET account_pin = :pasword WHERE customer_ID = :userId");
        $stmt->bindParam(":pasword", $newPin);
        $stmt->bindParam(":userId", $customerID);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'PIN updated successfully.']);
    }
    else { 
        echo json_encode(['status' => 'error', 'message' => 'Invalid new pin.']);
    }
  } 
  else {
  // Old password and pin don't match
  echo json_encode(['status' => 'error', 'message' => 'Old password or PIN does not match.']);
}
?>
