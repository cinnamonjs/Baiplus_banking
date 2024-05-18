<?php
// Retrieve the request data
$tranamount = $_POST['tranamount'];
$trandetail = $_POST['trandetail'];
$transferor = $_POST['transferor'];
$receiver = $_POST['receiver'];
$trans_type = $_POST['trans_type'];
$bill_id = $_POST['bill_id'];
$loan_id = $_POST['loan_id'];


$timezone = new DateTimeZone('Asia/Bangkok'); // Set the desired timezone (GMT+7)
$currentDateTime = new DateTime('now', $timezone); // Create a DateTime object with the current datetime

$tran_date = $currentDateTime->format('Y-m-d H:i:s'); // Format the datetime as desired (YYYY-MM-DD HH:MM:SS)



// Perform necessary sanitization or validation on the received data
require_once('connect.php');

// if($bill_id == null){
//     echo 'is null ไอสัส';
// }
// echo $bill_id;
    if( $trans_type == 'Transfer'){
        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO transaction (transaction_amount, transaction_date	, transaction_detail, account_transferor, account_receiver, trans_type)
                VALUES (:tranamount, :tran_date, :trandetail, :transferor, :receiver, :trans_type)";

        // Prepare the statement
        $stmt = $dbcon->prepare($sql); 

        // Bind the parameters to the statement
        $stmt->bindParam(':tranamount', $tranamount , PDO::PARAM_STR);
        $stmt->bindParam(':trandetail', $trandetail);
        $stmt->bindParam(':transferor', $transferor);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':trans_type', $trans_type);
        $stmt->bindParam(':tran_date', $tran_date);
        // $stmt->bindParam(':bill_id', $bill_id);
        // $stmt->bindParam(':loan_id', $loan_id);

        // Execute the statement
        $result = $stmt->execute();

        if ($result) {
            // Data inserted successfully
            echo $tran_date;
        } else {
            // Error occurred while executing the statement
            echo "Error";
        }
    }
    if( $trans_type == 'Withdrawal'){

        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO transaction (transaction_amount, transaction_date	, transaction_detail, account_transferor, trans_type)
                VALUES (:tranamount, :tran_date, :trandetail, :transferor, :trans_type)";

        // Prepare the statement
        $stmt = $dbcon->prepare($sql); 

        // Bind the parameters to the statement
        $stmt->bindParam(':tranamount', $tranamount , PDO::PARAM_STR);
        $stmt->bindParam(':trandetail', $trandetail);
        $stmt->bindParam(':transferor', $transferor);
        $stmt->bindParam(':trans_type', $trans_type);
        $stmt->bindParam(':tran_date', $tran_date);
        // $stmt->bindParam(':bill_id', $bill_id);
        // $stmt->bindParam(':loan_id', $loan_id);

        // Execute the statement
        $result = $stmt->execute();

        if ($result) {
            // Data inserted successfully
            echo $tran_date;
        } else {
            // Error occurred while executing the statement
            echo "Error";
        }
    }
    if( $trans_type == 'Bill Payment') { 

         // Prepare the SQL statement with placeholders
         $sql = "INSERT INTO transaction (transaction_amount, transaction_date	, transaction_detail, account_transferor, account_receiver, trans_type , bill_id)
         VALUES (:tranamount, :tran_date, :trandetail, :transferor, :receiver, :trans_type , :bill_id)";

        // Prepare the statement
        $stmt = $dbcon->prepare($sql); 

        // Bind the parameters to the statement
        $stmt->bindParam(':tranamount', $tranamount , PDO::PARAM_STR);
        $stmt->bindParam(':trandetail', $trandetail);
        $stmt->bindParam(':transferor', $transferor);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':trans_type', $trans_type);
        $stmt->bindParam(':tran_date', $tran_date);
        $stmt->bindParam(':bill_id' , $bill_id);
        // $stmt->bindParam(':bill_id', $bill_id);
        // $stmt->bindParam(':loan_id', $loan_id);

        // Execute the statement
        $result = $stmt->execute();

        if ($result) {
            // Data inserted successfully
            $transaction_id = $dbcon->lastInsertId();
            $response = array(
                'transaction_id' => $transaction_id,
                'tran_date' => $tran_date
            );
            echo json_encode($response);
        } else {
            // Error occurred while executing the statement
            echo "Error";
        }
    }
    if( $trans_type == 'Loan Installment Payment') { 

        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO transaction (transaction_amount, transaction_date	, transaction_detail, account_transferor, trans_type , loan_id)
        VALUES (:tranamount, :tran_date, :trandetail, :transferor, :trans_type , :loan_id)";

       // Prepare the statement
       $stmt = $dbcon->prepare($sql); 

       // Bind the parameters to the statement
       $stmt->bindParam(':tranamount', $tranamount , PDO::PARAM_STR);
       $stmt->bindParam(':trandetail', $trandetail);
       $stmt->bindParam(':transferor', $transferor);
    //    $stmt->bindParam(':receiver', $receiver);
       $stmt->bindParam(':trans_type', $trans_type);
       $stmt->bindParam(':tran_date', $tran_date);
       $stmt->bindParam(':loan_id' , $loan_id);
       // $stmt->bindParam(':bill_id', $bill_id);
       // $stmt->bindParam(':loan_id', $loan_id);

       // Execute the statement
       $result = $stmt->execute();

       if ($result) {
           // Data inserted successfully
           $transaction_id = $dbcon->lastInsertId();
           $response = array(
               'transaction_id' => $transaction_id,
               'tran_date' => $tran_date
           );
           echo json_encode($response);
       } else {
           // Error occurred while executing the statement
           echo "Error";
       }
   }

    // Close the statement and the database connection
    // $stmt = null;
    // $pdo = null;
?>
