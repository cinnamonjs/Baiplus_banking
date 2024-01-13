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
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/solid.css">
    <!-- ไอคอนบนแท็บ -->
    <link rel="shortcut icon" href="Asset/img/Baiplus_final-removebg-preview.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Baiplus Accounts</title>


    
</head>
    <script src="Asset/js/main.js"></script>
    <script src="Asset/js/darkmode.js"></script>
  <body>
        <!--Nav BAR-->
      < <nav>
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
    <img class="test-img3" src="Asset/img/account__bg.jpg">
 <main>
 
     <section class="account__content2">
        <div class="section__title account__title">Other Accounts</div>
        <div class="account__center">
        <div class="section account__container ">
            
            <?php
            // Check if the customer ID is stored in the session
            session_start();
            if (isset($_SESSION['customer_ID'])) {
                $customer_ID = $_SESSION['customer_ID'];

                // Connect to the database
                $con = mysqli_connect("localhost", "root", "", "baiplus_database");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Retrieve account data for the logged-in customer
                $sql = "SELECT * FROM account WHERE customer_ID = '$customer_ID'";
                $result = mysqli_query($con, $sql);

                // Display account data
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $account_name = $row['account_name'];
                        $account_balance = $row['account_balance'];
                        $bank_id= $row['bank_id'];

                        echo "<div class='account__box'>";
                        echo "<img src='Asset/images/image-restaurant.jpg' alt=''>";
                        echo "<h3>$" . number_format($account_balance, 2) . "</h3>";
                        echo "<div class='content'>";
                        echo "<div class='text'>";
                        echo "<h3>$account_name</h3>";
                        echo "<p><i class='bx bxs-bank'></i> $bank_id</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No accounts found.</p>";
                }

                // Close the database connection
                mysqli_close($con);
            } else {
                echo "<p>Customer ID not found.</p>";
            }
            ?>
        </div>
    </div> 
    </section>
</main>
    
</body>
</html>