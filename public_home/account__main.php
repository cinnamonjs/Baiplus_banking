<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Asset/css/swiper-bundle.min.css">

    <!-- css -->
    <link rel="stylesheet" href="Asset/css/style.css">
    <!-- incon -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/solid.css">
    <!-- ไอคอนบนแท็บ -->
    <link rel="shortcut icon" href="Asset/img/Baiplus_final-removebg-preview.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Baiplus Home Page</title>
</head>

<script src="Asset/js/main.js"></script>
<script src="Asset/js/darkmode.js"></script>

<body>
    <!--Nav BAR-->
    <nav>
        <div class="nav-bar home__containter header">
            <!--LOGO-->
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



    <!--Prooerties -->
    <img class="test-img3" src="Asset/img/account__bg.jpg">
    <section class="section section__account">
        <div class="section__bg">
        <div class="section__title">My Account</div>
        <div class="section__subtitle"></div>
        </div>
        <div class="account__grid2">

            <div class="account__center">

                <div class="account__box-main">
                <div class="content">
                    
                    <?php
                    session_start();
                    if (isset($_SESSION['customer_ID'])) {
                        $customer_ID = $_SESSION['customer_ID'];
        
                        // Connect to the database
                        $con = mysqli_connect("127.0.0.1", "root", "", "baiplus_database");
                        if (!$con) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                    }

                    // Retrieve the main account data for the logged-in user
                    $customerId = ""; // Replace with the logged-in customer's ID
                    $sql = "SELECT * FROM account WHERE customer_id = '$customer_ID' ";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $accountName = $row['account_name'];
                        $accountBalance = $row['account_balance'];
                        $bankName = $row['bank_id'];

                        echo "<img src='Asset/img/avatar.svg' class='account__img' alt=''>";
                        echo "<h3>$" . number_format($accountBalance, 2) . "</h3>";
                        echo "<div class='content'>";
                        echo "<div class='text'>";
                        echo "<h3>$accountName</h3>";
                        echo "<p><i class='bx bxs-bank'></i> $bankName</p>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<p>No main account found.</p>";
                    }

                    // Close the database connection
                    $con->close();
                    ?>
                </div>
                </div>
            </div>

            <div class="account__functions">
                <!-- Box1 -->
                <a class="account__func" href="withdraw.html">
                    <i class='bx bx-money-withdraw  account__icon'></i>
                    <h1>Withdraw</h1>
                </a>

                <!-- Box2 -->
                <a href="tranfer_selectBank.html" class="account__func">
                    <i class="fa-sharp fa-solid fa-arrow-right-arrow-left account__icon" style="font-size: 1.75rem;"></i>
                    <h1>Transfer</h1>
                </a>

                <!-- Box3 -->
                <a href="paybill.html" class="account__func feature-item">
                    <i class='bx bx-receipt account__icon'></i>
                    <h1>Pay Bill</h1>
                </a>

                <!-- Box4 -->
                <a href="card_holder.php" class="account__func feature-item">
                    <i class='bx bx-credit-card account__icon'></i>
                    <h1>Credit Card</h1>
                </a>

                <!-- Box5 -->
                <a href="loan.html" class="account__func feature-item">
                    <i class="fa-sharp fa-solid fa-hand-holding-dollar account__icon" style="font-size: 1.75rem;"></i>
                    <h1>Loan</h1>
                </a>

                <!-- Box6 -->
                <a href="newaccount.php" class="account__func feature-item">
                    <i class='fa-sharp fa-solid fa-user-plus account__icon' style="font-size: 1.55rem;"></i>
                    <h1>New Account</h1>
                </a>

            </div>
        </div>
    </section>
    </section>

</body>

</html>
