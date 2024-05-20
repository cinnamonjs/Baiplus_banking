<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_ID'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit;
}

// Retrieve the logged-in user's ID
$loggedInUserID = $_SESSION['customer_ID'];

// Connect to the database
$connection = mysqli_connect("mysql", "root", "", "baiplus_database");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Asset/css/swiper-bundle.min.css">

    <!-- css -->
    <link rel="stylesheet" href="Asset/css/creditstyle.css">
    <!-- incon -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/solid.css">
    <!-- ไอคอนบนแท็บ -->
    <link rel="shortcut icon" href="Asset/img/Baiplus_final-removebg-preview.png">

</head>
<script src="Asset/js/main.js"></script>
    <script src="Asset/js/darkmode.js"></script>
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
    <img class="test-img3" src="Asset/img/account__bg2.jpg">
    <section class="section createcard">
        <div class="section__title"> Cradit Card</div>
        <div class="section__subtitle"></div>
<div class="main-creditcard-container">
    <?php
    $connection = mysqli_connect("mysql", "root", "", "baiplus_database");
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve card information based on the logged-in user's ID
    $sql = "SELECT * FROM creditcard WHERE customer_ID = '$loggedInUserID'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($card = mysqli_fetch_assoc($result)) {
            $customerID = $card['customer_ID'];
            $customerSql = "SELECT CONCAT(customer_fname, ' ', customer_lname) AS card_holder FROM customer WHERE customer_ID = '$customerID'";
            $customerResult = mysqli_query($connection, $customerSql);
            $customer = mysqli_fetch_assoc($customerResult);
    
            // Display the card details
            ?>
            
        <div class="all_content">
            <div class="card-container">
                <div class="card-front">
                    <div class="image">
                        <img src="Asset/images/chip.png" alt="">
                        <img src="Asset/images/master.png" alt="">
                    </div>
                    <div class="card-number-box"><?php echo $card['card_no']; ?></div>
                    <div class="card-flexbox">
                        <div class="card-box">
                            <span>card holder</span>
                            <div class="card-holder-name"><?php echo $customer['card_holder']; ?></div>
                        </div>
                        <div class="card-box">
                            <span>expires</span>
                            <div class="expiration">
                                <span class="exp-month"><?php echo substr($card['card_exp'], 5, 2); ?></span>
                                <span class="exp-year"><?php echo substr($card['card_exp'], 2, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-back">
                    <!-- Rest of the card back content -->
                    <div class="stripe"></div>
                    <div class="card-box">
                        <span>cvv</span>
                        <div class="cvv-box"><?php echo $card['cvv']; ?></div>
                        <img src="Asset/images/master.png" alt="">
                    </div>
                </div>
            </div>

            <!-- Display the form with card details -->
            <form action="">
                <div class="inputBox">
                    <span>card number</span>
                    <p type="text" maxlength="16" class="card-number-input"><?php echo $card['card_no']; ?></p>
                </div>
                <div class="inputBox">
                    <span>card holder</span>
                    <p type="text" class="card-holder-input"><?php echo $customer['card_holder']; ?></p>
                </div>
                <div class="card-flexbox">
                    <div class="inputBox">
                        <span>expiration mm</span>
                        <p name="" id="" class="month-input"><?php echo substr($card['card_exp'], 5, 2); ?></p>
                    </div>
                    <div class="inputBox">
                        <span>expiration yy</span>
                        <p name="" id="" class="years-input"><?php echo substr($card['card_exp'], 2, 2); ?></p>
                    </div>
                    <div class="inputBox">
                        <span>cvv</span>
                        <p type="text" maxlength="4" class="cvv-input"><?php echo $card['cvv']; ?></p>
                    </div>
                </div>
            </form>
        </div>
            <?php
        }
    } else {
        echo "No cards found.";
    }
    
    // Close the database connection
    mysqli_close($connection);
    ?>
 </div>


<script>
    document.querySelector('.card-number-input').oninput = () => {
        document.querySelector('.card-number-box').innerText = document.querySelector('.card-number-input').value;
    }

    document.querySelector('.card-holder-input').oninput = () => {
        document.querySelector('.card-holder-name').innerText = document.querySelector('.card-holder-input').value;
    }

    document.querySelector('.month-input').oninput = () => {
        document.querySelector('.exp-month').innerText = document.querySelector('.month-input').value;
    }

    document.querySelector('.years-input').oninput = () => {
        document.querySelector('.exp-year').innerText = document.querySelector('.years-input').value;
    }

    document.querySelector('.cvv-input').onmouseenter = () => {
        document.querySelector('.card-front').style.transform = 'perspective(1000px) rotateY(-180deg)';
        document.querySelector('.card-back').style.transform = 'perspective(1000px) rotateY(0deg)';
    }

    document.querySelector('.cvv-input').onmouseleave = () => {
        document.querySelector('.card-front').style.transform = 'perspective(1000px) rotateY(0deg)';
        document.querySelector('.card-back').style.transform = 'perspective(1000px) rotateY(180deg)';
    }

    document.querySelector('.cvv-input').oninput = () => {
        document.querySelector('.cvv-box').innerText = document.querySelector('.cvv-input').value;
    }
</script>
</body>
</html>
