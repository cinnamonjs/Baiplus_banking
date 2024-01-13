<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $cardNumber = $_POST["card_number"];
    $salary = $_POST["salary"];

    $file_name = '';

    if (isset($_FILES['salary_file'])) {
    $file_name = $_FILES['salary_file']['name'];
    // Rest of your form processing code
}

    session_start();
    if (isset($_SESSION['customer_ID'])) {
        $customer_ID = $_SESSION['customer_ID'];

        // Connect to the database
        $con = mysqli_connect("localhost", "root", "", "baiplus_database");
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve customer data from the database based on the customer ID
        $sql = "SELECT * FROM customer WHERE customer_ID = '$customer_ID'";
        $result = mysqli_query($con, $sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if the input matches the customer data
            if ($row["customer_fname"] == $firstName && $row["customer_lname"] == $lastName && $row["customer_ID"] == $customer_ID) {
                // Update the salary and upload the file for the customer
                $updateSql = "UPDATE customer SET salary = '$salary', salary_file = '$file_name' WHERE customer_ID = '$customer_ID'";
                // salary_file = '$file'
                if ($con->query($updateSql) === TRUE) {
                    // Generate random values for card_no, card_exp, and cvv
                    $cardNo = generateRandomNumeric(16);
                    $cardExp = date('Y-m-d', strtotime('+5 years'));
                    $cvv = generateRandomNumeric(3);

                    // Insert a new row into the creditcard table
                    $insertSql = "INSERT INTO creditcard (card_no, card_exp, cvv, max_limit, customer_ID, bank_id, card_status)
                                  VALUES ('$cardNo', '$cardExp', '$cvv', 100000, '$customer_ID', 'BPBANK', 0)";
                    if ($con->query($insertSql) === TRUE) {
                        // Success message
                        echo "<script>alert('Credit-card request success');</script>";
                    } else {
                        // Error message
                        echo "<script>alert('Failed to update salary and upload file: " . $con->error . "');</script>";
                    }
                } else {
                    // Identity verification failed
                    echo "<script>alert('Failed identity verification');</script>";
                }
            } else {
                // Customer not found
                echo "<script>alert('Customer not found');</script>";
            }

            mysqli_close($con);
        } else {
            // Session not found or customer ID not set
            echo "<script>alert('Session not found or customer ID not set');</script>";
        }
    }
}
// Function to generate a random numeric string
function generateRandomNumeric($length) {
    $numeric = '0123456789';
    $numericLength = strlen($numeric);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $numeric[rand(0, $numericLength - 1)];
    }
    return $randomString;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- custom css file link  -->
    <link rel="stylesheet" href="Asset/css/style-card.css">
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
        <!-- incon -->
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script>
        function enableSubmitButton() {
            var checkbox = document.getElementById("termsCheckbox");
            var submitButton = document.getElementById("submitBtn");
            submitButton.disabled = !checkbox.checked;
        }
    </script>
    <style>
        .submit-btn {
            background-color: #ccc; /* Gray color */
            cursor: not-allowed; /* Show 'not-allowed' cursor when disabled */
        }

        .submit-btn:enabled {
            background-color: #ff5c5c; /* Change to your desired color when enabled */
            cursor: pointer;
        }
    </style>
    <style src="Asset/js/darkmode.js"></style>
</head>
<body>
<nav>
        <div class="nav-bar home__containter header">
            <!--LOGO-->
            <a href="account__main.php" class="logo"><img src="Asset/img/Baiplus_final-removebg-preview.png"
                    class="nav__icon" alt="" /></i>BaiPlus</a>
            <!--Menu Icon-->
            <!-- <label for="menu"><i class='bx bx-menu' id="menu-icon"></i></label> -->
            <!--Navlist-->
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
                <li><a href="history_tran.php">HISTORY</a></li>
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
    <!-- <img class="test-img" src="Asset/img/treebran02.png">
        <img class="test-img2" src="Asset/img/treebran02.png"> -->
        <!-- <img class="test-img3" src="Asset/img/bg-bank.jpg"> -->
    <img class="test-img3" src="Asset/img/account__bg2.jpg">
    <section class="section createcard">
        <div class="section__title">Create Cradit Card</div>
        <div class="section__subtitle"></div>
<div class="container">
<div class='all-content'>
    <div class="card-container">

        <div class="front">
            <div class="image">
                <img src="Asset/images/chip.png" alt="">
                <img src="Asset/images/master.png" alt="">
            </div>
            <div class="card-number-box">################</div>
            <div class="flexbox">
                <div class="box">
                    <span>card holder</span>
                    <div class="card-holder-name">full name</div>
                </div>
                <div class="box">
                    <span>expires</span>
                    <div class="expiration">
                        <span class="exp-month">mm</span>
                        <span class="exp-year">yy</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="back">
            <div class="stripe"></div>
            <div class="box">
                <span>cvv</span>
                <div class="cvv-box"></div>
                <img src="master.png" alt="">
            </div>
        </div>

    </div>

    <form method="POST" enctype="multipart/form-data">
        <div class="inputBox">
            <span>First name</span>
            <input type="text" maxlength="16" class="card-number-input" name="first_name">
        </div>
        <div class="inputBox">
            <span>Last name</span>
            <input type="text" maxlength="16" class="card-number-input" name="last_name">
        </div>
        <div class="inputBox">
            <span>Personal ID</span>
            <input type="text" maxlength="16" class="card-number-input" name="card_number">
        </div>
        <div class="inputBox">
            <span>Salary</span>
            <input type="text" class="" name="salary">
        </div>

        <input type="file" id="uploadBtn" name ='salary_file'>
        <label for="uploadBtn"><i class='bx bx-upload'></i> Upload</label>

        <div class="checkbox">
            <span>Yes, I accept the Terms of Use</span>
            <input type="checkbox" id="termsCheckbox" onchange="enableSubmitButton()">
        </div>

        <input type="submit" value="Submit" class="submit-btn" id="submitBtn" disabled>
    </form>
    </div>
</div>
</section>
</body>
</html>
