<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $accountName = $_POST["account_name"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $cardID = $_POST["card_id"];
    $accountType = $_POST["account_type"];

    // Validate password and confirm password
    if ($password != $confirmPassword) {
        echo "<script>alert('Create account failed: Passwords do not match');</script>";
    } else {
        session_start();
        if (isset($_SESSION['customer_ID'])) {
            $customerID = $_SESSION['customer_ID'];

            // Connect to the database
            $con = mysqli_connect("mysql", "root", "", "baiplus_database");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Retrieve customer data from the database based on the customer ID
            $sql = "SELECT * FROM customer WHERE customer_ID = '$customerID'";
            $result = mysqli_query($con, $sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Check if the input matches the customer data
                if ($row["customer_password"] == $password && $row["customer_ID"] == $cardID) {
                    // Generate current date and time
                    $accountDOP = date('Y-m-d H:i:s');

                    // Insert a new row into the account table
                    $insertSql = "INSERT INTO account (account_name, account_DOP, account_balance, customer_ID, account_type, account_status, bank_id)
                                  VALUES ('$accountName', '$accountDOP', 0, '$customerID', '$accountType', 'Active', 'BPBANK')";
                    if (mysqli_query($con, $insertSql)) {
                        // Success message
                        echo "<script>alert('Create account successful');</script>";
                    } else {
                        // Error message
                        echo "<script>alert('Create account failed: " . mysqli_error($con) . "');</script>";
                    }
                } else {
                    // Validation failed
                    echo "<script>alert('Create account failed: Invalid password or card ID');</script>";
                }
            } else {
                // Customer not found
                echo "<script>alert('Create account failed: Customer not found');</script>";
            }

            mysqli_close($con);
        } else {
            // Session not found or customer ID not set
            echo "<script>alert('Create account failed: Invalid session');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="Asset/css/createaccountstyle.css">
      <!-- incon -->
      <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- ไอคอนบนแท็บ -->
    <link rel="shortcut icon" href="Asset/img/Baiplus_final-removebg-preview.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<nav>
          <!--Nav BAR-->
      < <nav>
        <div class="nav-bar home__containter header">

            <a href="account__main.php" class="logo"><img src="Asset/img/Baiplus_final-removebg-preview.png"
                    class="nav__icon" alt="" /></i>BaiPlus</a>
      
            <ul class="navbar">
                <li><a href="account__main.php">Home</a></li>
                <li class="home__dropdown-link">
                    <div href="">ACCOUNTS&nbsp;&nbsp;<i class='bx bxs-chevron-down'></i></div>
                    <ul class="home__dropdown">
                        <li><a href="account__main.php">Profile Accounts</a></li>
                        <li><a href="account__other.php">Anothers Accounts</a></li>
                    </ul>
                </li>
                <li class="home__dropdown-link">
                    <div href="">TRANSACTION&nbsp;&nbsp;<i class='bx bxs-chevron-down'></i></div>
                    <ul class="home__dropdown">
                        <li><a href="tranfer_selectBank.html">Tranfer</a></li>
                        <li><a href="paybill.html">Pay Bill</a></li>
                        <li><a href="withdraw.html">Withdraw</a></li>
                        <li><a href="loan.html">Loan</a></li>
                    </ul>
                </li>
                <li><a href="history_tran.html">HISTORY</a></li>
                <li class="home__dropdown-link">
                    <div href="">CREDIT CARD&nbsp;&nbsp;<i class='bx bxs-chevron-down'></i></div>
                    <ul class="home__dropdown">
                        <li><a href="card_holder.php">All Card</a></li>
                        <li><a href="createcard.php">New Card</a></li>
                    </ul>
                </li>


                <li class="nav__btns">
                    <i class="uil uil-moon change-theme" id="theme-button"></i>
                </li>

                <li class="home__dropdown-link">
                    <div class="nav__acc-bg">
                        <div class="nav__img"><img src="Asset/img/avatar.svg"></div>
                    </div>
                    <ul class="home__dropdown">
                        <li><a href="account__edit.html">Setting</a></li>
                        <li><a href="account__other.php">Anothers</a></li>
                        <li><a href="index.html">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <img class="test-img" src="Asset/img/treebran02.png">
        <img class="test-img2" src="Asset/img/treebran02.png">
    <img class="test-img3" src="Asset/img/account__bg.jpg">
<div class="account-wrapper">
  <div class="title">
    CREATE ACCOUNTS
  </div>
  <div class="form">
   <form id="myForm" method="POST" action="newaccount.php" onsubmit="return validateForm()">
       <div class="inputfield">
          <label>Account Name</label>
          <input type="text" name="account_name" class="input">
       </div>  
       <div class="inputfield">
          <label>Password</label>
          <input type="password" name="password" class="input">
       </div>  
       <div class="inputfield">
          <label>Confirm Password</label>
          <input type="password" name="confirm_password" class="input">
       </div> 
       <div class="inputfield">
          <label>Thai National ID Card</label>
          <input type="text" name="card_id" class="input">
       </div>  
       <div class="inputfield">
          <label>Account Type</label>
          <div class="custom_select">
            <select name="account_type">
              <option value="">Select</option>
              <option value="Savings">Savings</option>
              <option value="Fixed Deposit">Fixed Deposit</option>
              <option value="Current Deposit">Current Deposit</option>
            </select>
          </div>
       </div> 
      <div class="inputfield terms">
          <!-- <label class="check">
            <input type="checkbox" name="option[]" value="accepted">
            <span class="checkmark"></span>
          </label>
          <p>Agreed to terms and conditions</p> -->
       </div> 
       <div class="inputfield">
        <input type="submit" value="Submit" class="btn">
      </div>
    </form>
</div>

  </div>
</div>  


</body>
</html>
