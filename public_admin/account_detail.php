<?php
session_start();
$_SESSION['previous_page'] = basename($_SERVER['PHP_SELF']);
$previousPage = $_SESSION['previous_page'] ?? 'unknown';
require_once "db.php";

$param1 = $_SESSION['param1']; // username
$param2 = $_SESSION['param2']; // password
$param3 = $_SESSION['param3']; // role
$param4 = $_SESSION['param4']; // fname
$param5 = $_SESSION['param5']; // lname
$param6 = $_SESSION['param6']; // employee_id

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $account_name = $_POST['account_name'];
    $account_DOP = $_POST['account_DOP'];
    $account_balance = $_POST['account_balance'];
    $customer_id = $_POST['customer_id'];
    $account_type = $_POST['account_type'];
    $account_status = $_POST['account_status'];
    $bank_id = $_POST['bank_id'];

    $stmt = $conn->prepare("SELECT * FROM account WHERE account_id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $change = 0;
    if ($_POST['account_name'] != $row['account_name']) {
        $changes_account_name = $row['account_name'] . '->' . $_POST['account_name'] . ',';
        $change = 1;
    }
    if ($_POST['account_balance'] != $row['account_balance']) {
        $changes_account_balance = $row['account_balance'] . '->' . $_POST['account_balance'] . ',';
        $change = 1;
    }
    if ($_POST['customer_id'] != $row['customer_id']) {
        $changes_customer_id = $row['customer_id'] . '->' . $_POST['customer_id'] . ',';
        $change = 1;
    }
    if ($_POST['account_type'] != $row['account_type']) {
        $changes_account_type = $row['account_type'] . '->' . $_POST['account_type'] . ',';
        $change = 1;
    }
    if ($_POST['account_status'] != $row['account_status']) {
        if ($_POST['account_status'] == 'Suspend') {
            $changes_account_status = $row['account_status'] . '->' . $_POST['account_status'] . '(Waiting for Approve)' . ',';
        } else {
            $changes_account_status = $row['account_status'] . '->' . $_POST['account_status'] . ',';
        }
        $change = 1;
    }
    if ($_POST['bank_id'] != $row['bank_id']) {
        $changes_bank_id = $row['bank_id'] . '->' . $_POST['bank_id'] . ',';
        $change = 1;
    }

    if ($change == 1) {
        $sum_change = $changes_account_name . $changes_account_balance . $changes_customer_id . $changes_account_type . $changes_account_status . $changes_bank_id;

        $sql = $conn->prepare("UPDATE `account` SET account_id = :id, 
        account_name = :account_name, 
        account_DOP = :account_DOP, 
        account_balance = :account_balance, 
        customer_id = :customer_id, 
        account_type = :account_type, 
        account_status = :account_status, 
        bank_id = :bank_id 
        WHERE account_id = :id");

        $sql->bindParam(":id", $id);
        $sql->bindParam(":account_name", $account_name);
        $sql->bindParam(":account_DOP", $account_DOP);
        $sql->bindParam(":account_balance", $account_balance);
        $sql->bindParam(":customer_id", $customer_id);
        $sql->bindParam(":account_type", $account_type);
        $sql->bindParam(":account_status", $account_status);
        $sql->bindParam(":bank_id", $bank_id);
        $sql->execute();

        $sql = $conn->prepare("INSERT INTO `managehistory` (`datetime`, employee_id, account_id, action_type)  VALUE (NOW(), :employee_id, :id, :sum_change)");
        $sql->bindParam(":employee_id", $param6);
        $sql->bindParam(":id", $id);
        $sql->bindParam(":sum_change", $sum_change);
        $sql->execute();
    }

    if ($change == 1) {
        $_SESSION['success'] = "Data has been updated successfully";
        header("location: account.php");
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: account.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Account Detail</title>

    <link rel="icon" href="img/favicon.ico" type="img/ico">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<style>
    table.table td a.delete {
        color: #F44336;
    }

    .circle {
        border-radius: 50%;
        width: 150px;
        height: 150px;
    }
</style>

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-light sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-0">
                    <img src="img\baiplus_logo.png.png" alt="baiplus_logo" width="71">
                </div>
                <div class="sidebar-brand-text mx-3">BaiPlus <sup>+</sup></div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Management
            </div>

            <?php
            if ($param3 == 'Manager') {
            ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Account Manager</span>
                    </a>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Account Manager</h6>
                            <a class="collapse-item" href="customer.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard" viewBox="0 0 16 16">
                                    <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5ZM9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8Zm1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Z" />
                                    <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2ZM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12V4Z" />
                                </svg> Customer</a>
                            <a class="collapse-item" href="account.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg> Account</a>
                            <a class="collapse-item" href="bank.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                                    <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.501.501 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89L8 0ZM3.777 3h8.447L8 1 3.777 3ZM2 6v7h1V6H2Zm2 0v7h2.5V6H4Zm3.5 0v7h1V6h-1Zm2 0v7H12V6H9.5ZM13 6v7h1V6h-1Zm2-1V4H1v1h14Zm-.39 9H1.39l-.25 1h13.72l-.25-1Z" />
                                </svg> Bank</a>
                            <a class="collapse-item" href="employee.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                </svg> Employee</a>
                            <a class="collapse-item" href="credit_card.php"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z" />
                                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z" />
                                </svg> Credit Card</a>
                            <a class="collapse-item" href="loan.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                </svg> Loan</a>
                            <a class="collapse-item" href="postcode.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mailbox" viewBox="0 0 16 16">
                                    <path d="M4 4a3 3 0 0 0-3 3v6h6V7a3 3 0 0 0-3-3zm0-1h8a4 4 0 0 1 4 4v6a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V7a4 4 0 0 1 4-4zm2.646 1A3.99 3.99 0 0 1 8 7v6h7V7a3 3 0 0 0-3-3H6.646z" />
                                    <path d="M11.793 8.5H9v-1h5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.354-.146l-.853-.854zM5 7c0 .552-.448 0-1 0s-1 .552-1 0a1 1 0 0 1 2 0z" />
                                </svg> Postcode</a>
                            <a class="collapse-item" href="managehistory.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                                </svg> Manage History</a>
                        </div>
                    </div>
                </li>
            <?php
            }
            ?>

            <?php
            if ($param3 == 'Administrator') {
            ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>Administrator</span>
                    </a>
                    <div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Administrator</h6>
                            <a class="collapse-item" href="customer.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard" viewBox="0 0 16 16">
                                    <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5ZM9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8Zm1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Z" />
                                    <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2ZM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12V4Z" />
                                </svg> Customer</a>
                            <a class="collapse-item" href="account.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg> Account</a>
                            <a class="collapse-item" href="bank.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                                    <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.501.501 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89L8 0ZM3.777 3h8.447L8 1 3.777 3ZM2 6v7h1V6H2Zm2 0v7h2.5V6H4Zm3.5 0v7h1V6h-1Zm2 0v7H12V6H9.5ZM13 6v7h1V6h-1Zm2-1V4H1v1h14Zm-.39 9H1.39l-.25 1h13.72l-.25-1Z" />
                                </svg> Bank</a>
                            <a class="collapse-item" href="employee.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                </svg> Employee</a>
                            <a class="collapse-item" href="credit_card.php"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z" />
                                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z" />
                                </svg> Credit Card</a>
                            <a class="collapse-item" href="transaction.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z" />
                                    <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z" />
                                    <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z" />
                                    <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z" />
                                </svg> Transaction</a>
                            <a class="collapse-item" href="bill.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                                </svg> Bill</a>
                            <a class="collapse-item" href="biller.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                                </svg> Biller Info</a>
                            <a class="collapse-item" href="loan.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                </svg> Loan</a>
                            <a class="collapse-item" href="postcode.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mailbox" viewBox="0 0 16 16">
                                    <path d="M4 4a3 3 0 0 0-3 3v6h6V7a3 3 0 0 0-3-3zm0-1h8a4 4 0 0 1 4 4v6a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V7a4 4 0 0 1 4-4zm2.646 1A3.99 3.99 0 0 1 8 7v6h7V7a3 3 0 0 0-3-3H6.646z" />
                                    <path d="M11.793 8.5H9v-1h5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.354-.146l-.853-.854zM5 7c0 .552-.448 0-1 0s-1 .552-1 0a1 1 0 0 1 2 0z" />
                                </svg> Postcode</a>
                            <a class="collapse-item" href="managehistory.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                                </svg> Manage History</a>
                            <a class="collapse-item" href="error.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bug" viewBox="0 0 16 16">
                                    <path d="M4.355.522a.5.5 0 0 1 .623.333l.291.956A4.979 4.979 0 0 1 8 1c1.007 0 1.946.298 2.731.811l.29-.956a.5.5 0 1 1 .957.29l-.41 1.352A4.985 4.985 0 0 1 13 6h.5a.5.5 0 0 0 .5-.5V5a.5.5 0 0 1 1 0v.5A1.5 1.5 0 0 1 13.5 7H13v1h1.5a.5.5 0 0 1 0 1H13v1h.5a1.5 1.5 0 0 1 1.5 1.5v.5a.5.5 0 1 1-1 0v-.5a.5.5 0 0 0-.5-.5H13a5 5 0 0 1-10 0h-.5a.5.5 0 0 0-.5.5v.5a.5.5 0 1 1-1 0v-.5A1.5 1.5 0 0 1 2.5 10H3V9H1.5a.5.5 0 0 1 0-1H3V7h-.5A1.5 1.5 0 0 1 1 5.5V5a.5.5 0 0 1 1 0v.5a.5.5 0 0 0 .5.5H3c0-1.364.547-2.601 1.432-3.503l-.41-1.352a.5.5 0 0 1 .333-.623zM4 7v4a4 4 0 0 0 3.5 3.97V7H4zm4.5 0v7.97A4 4 0 0 0 12 11V7H8.5zM12 6a3.989 3.989 0 0 0-1.334-2.982A3.983 3.983 0 0 0 8 2a3.983 3.983 0 0 0-2.667 1.018A3.989 3.989 0 0 0 4 6h8z" />
                                </svg> Error</a>
                            <a class="collapse-item" href="requesting.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                                </svg> Pending Request</a>
                        </div>
                    </div>
                </li>
            <?php
            }
            ?>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Addons
            </div>

            <li class="nav-item">
                <a class="nav-link" href="table.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="advance.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-heart-fill" viewBox="0 0 16 16">
                        <path d="M2 15.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v13.5zM8 4.41c1.387-1.425 4.854 1.07 0 4.277C3.146 5.48 6.613 2.986 8 4.412z" />
                    </svg>
                    <span>Advanced Analysis Report</span></a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="top-tools-bar">
                        <h1 class="animated-text" style="margin-top: 10px;">BaiPlus : Online Banking System Management</h1>
                    </div>

                    <style>
                        .animated-text {
                            font-size: 1rem;
                            text-align: center;
                            overflow: hidden;
                            white-space: nowrap;
                            color: #333;
                            border-right: 0.15em solid #333;
                            animation: typing 0.5s steps(40, end), blink-caret 1.5s step-end infinite;
                            transition: border-color 0.5s ease-out;
                        }

                        @keyframes typing {
                            from {
                                width: 0;
                            }

                            to {
                                width: 100%;
                            }
                        }

                        @keyframes blink-caret {

                            from,
                            to {
                                border-color: transparent;
                            }

                            50% {
                                border-color: #333;
                            }
                        }

                        .animated-text:hover {
                            border-color: #999;
                        }
                    </style>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <?php
                        if ($param3 == 'Administrator') {
                        ?>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>

                                    <?php
                                    $query = "SELECT mh.account_id, mh.employee_id, e.employee_fname, e.employee_lname, mh.action_type, mh.datetime
                  FROM managehistory mh
                  INNER JOIN employee e ON mh.employee_id = e.employee_id
                  WHERE (mh.action_type LIKE '%Active->Suspend(Waiting for Approve),%' OR
                       mh.action_type LIKE '%Inactive->Suspend(Waiting for Approve),%')";

                                    $result = $conn->query($query);
                                    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                                    $alert_count = count($rows);
                                    ?>

                                    <span class="badge badge-danger badge-counter">
                                        <?php if ($alert_count > 2) { ?>
                                            <?php echo $alert_count; ?>+
                                        <?php } else { ?>
                                            <?php echo $alert_count; ?>
                                        <?php } ?>
                                    </span>
                                </a>

                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header">
                                        Alerts Center
                                    </h6>
                                    <div class="dropdown-scrollable">
                                        <?php foreach ($rows as $row) { ?>
                                            <?php
                                            $formatted_date = date('F d, Y H:i:s', strtotime($row['datetime']));
                                            ?>
                                            <a class="dropdown-item d-flex align-items-center" href="requesting.php">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-primary">
                                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="small text-gray-500"><?php echo $formatted_date; ?></div>
                                                    <span class="font-weight-bold">From : <?php echo $row['employee_fname']; ?> <?php echo $row['employee_lname']; ?><br></span>
                                                    <?php echo $row['action_type']; ?> to Account ID : <?php echo $row['account_id']; ?>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500" href="requesting.php">Show All Alerts</a>
                                </div>
                            </li>

                            <style>
                                .dropdown-scrollable {
                                    max-height: 300px;
                                    overflow-y: scroll;
                                }
                            </style>
                        <?php
                        }
                        ?>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $param4 . ' ' . $param5 . '<br>' . $param3; ?></span>

                                <?php
                                if ($param3 == 'Administrator') {
                                ?>
                                    <img class="img-profile rounded-circle" src="img/administrator.gif">
                                <?php
                                }
                                ?>
                                <?php
                                if ($param3 == 'Manager') {
                                ?>
                                    <img class="img-profile rounded-circle" src="img/manager.gif">
                                <?php
                                }
                                ?>
                                <?php
                                if ($param3 == 'Owner') {
                                ?>
                                    <img class="img-profile rounded-circle" src="img/owner.gif">
                                <?php
                                }
                                ?>
                            </a>

                            <div class="modal fade bd-example-modal-lg" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="modal-body text-center">
                                                <div class="form-group">
                                                    <img class="img-profile rounded-circle" src="img/<?php echo $param3; ?>.gif" width="150">
                                                </div>
                                                <h2> <?php echo $param3; ?> </h2>
                                                <label> Username : <?php echo $param1; ?> </label>
                                                <h4> <?php echo $param4; ?> <?php echo $param5; ?> </h4>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profileModal">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="employee_profile.php">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="managehistory.php">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="modal-body">

                    <form action="account_detail.php" method="POST" enctype="multipart/form-data">
                        <?php
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $stmt = $conn->query("SELECT * FROM account WHERE account_id = '$id'");
                            $stmt->execute();
                            $data = $stmt->fetch();
                        }
                        ?>
                        <div class="form-group">
                            <label for="id"> Account ID </label>
                            <input type="text" value="<?php echo $data['account_id']; ?>" class="form-control" name="id" required disabled>
                        </div>

                        <div class="form-row">
                            <div class="form-group col">
                                <label>Account Name</label>
                                <input type="text" value="<?php echo $data['account_name']; ?>" class="form-control" name="account_name" disabled>
                            </div>
                            <div class="form-group col">
                                <label>Account DOP</label>
                                <input type="datetime-local" name="account_DOP" class="form-control" value="<?php echo $data['account_DOP']; ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label> Account Balance </label>
                            <input type="number" step="0.01" oninput="truncateDecimals(this, 2);" name="account_balance" class="form-control" value="<?php echo $data['account_balance']; ?>" disabled>
                        </div>

                        <div class="form-row">
                            <div class="form-group col">
                                <label> Customer ID </label>
                                <?php
                                $stmt = $conn->query("SELECT customer_id FROM customer");
                                $stmt->execute();
                                $result_customer_id = $stmt->fetchAll();
                                $count = count($result_customer_id);
                                ?>
                                <input type="text" value="<?php echo $data['customer_id']; ?>" class="form-control" name="customer_id" required disabled>
                            </div>
                            <div class="form-group col">
                                <label>Account Type</label>
                                <select name="account_type" class="form-control" disabled>
                                    <?php if ($data['account_type'] === "Savings") : ?>
                                        <option value="Savings" selected>Savings Account</option>
                                    <?php else : ?>
                                        <option value="Savings">Savings Account</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_type'] === "Current") : ?>
                                        <option value="Current" selected>Current Account</option>
                                    <?php else : ?>
                                        <option value="Current">Current Account</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_type'] === "Deposit") : ?>
                                        <option value="Deposit" selected>Deposit Account</option>
                                    <?php else : ?>
                                        <option value="Deposit">Deposit Account</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_type'] === "Business") : ?>
                                        <option value="Business" selected>Business Account</option>
                                    <?php else : ?>
                                        <option value="Business">Business Account</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label> Account Status </label>
                                <select name="account_status" class="form-control" disabled>
                                    <?php if ($data['account_status'] === "Active") : ?>
                                        <option value="Active" selected>Active</option>
                                    <?php else : ?>
                                        <option value="Active">Active</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_status'] === "Inactive") : ?>
                                        <option value="Inactive" selected>Inactive</option>
                                    <?php else : ?>
                                        <option value="Inactive">Inactive</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_status'] === "Freeze Temp") : ?>
                                        <option value="Freeze Temp" selected>Freeze Temp</option>
                                    <?php else : ?>
                                        <option value="Freeze Temp">Freeze Temp</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_status'] === "Freeze Permanent") : ?>
                                        <option value="Freeze Permanent" selected>Freeze Permanent</option>
                                    <?php else : ?>
                                        <option value="Freeze Permanent">Freeze Permanent</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_status'] === "Suspend") : ?>
                                        <option value="Suspend" selected>Suspend</option>
                                    <?php else : ?>
                                        <option value="Suspend">Suspend</option>
                                    <?php endif; ?>

                                    <?php if ($data['account_status'] === "Closed") : ?>
                                        <option value="Closed" selected>Closed</option>
                                    <?php else : ?>
                                        <option value="Closed">Closed</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label> Bank ID </label>
                                <?php
                                $conn = mysqli_connect("mysql", "root", "", "baiplus_final");
                                $result = mysqli_query($conn, "SELECT bank_id, bank_name FROM bank");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $bank_id = $row['bank_id'];
                                    $bank_name = $row['bank_name'];
                                }
                                mysqli_close($conn);
                                ?>
                                <input type="text" value="<?php echo $data['bank_id'];  ?>" class="form-control" name="bank_id" required disabled>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <a href="requesting.php" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</a>
                    </form>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>
                            <img src="img/mybplogo.png" alt="My Logo" width="30" />
                            &nbsp;Copyright &copy; BaiPlus 2023
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-outline-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        const table = document.getElementById("dataTable");
        const rows = table.getElementsByTagName("tr");
        for (let i = 1; i < rows.length; i++) {
            const cell = document.createElement("td");
            cell.textContent = i;
            rows[i].insertBefore(cell, rows[i].firstChild);
        }
    </script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var img = document.getElementById("imgpreview");
                img.src = reader.result;
                img.style.display = "block";
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        function previewImageEdit(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var img = document.getElementById("imgpreviewEdit");
                img.src = reader.result;
                img.style.display = "block";
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file)
            }
        }
    </script>
    <script>
        function truncateDecimals(element, decimalPlaces) {
            if (element.value.indexOf('.') !== -1) {
                if (element.value.split('.')[1].length > decimalPlaces) {
                    element.value = parseFloat(element.value).toFixed(decimalPlaces);
                }
            }
        }
    </script>
</body>

</html>