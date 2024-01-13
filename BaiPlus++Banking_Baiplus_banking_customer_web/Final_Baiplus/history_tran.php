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
$connection = mysqli_connect("localhost", "root", "", "baiplus_database");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve transaction history based on the logged-in user's ID
$sql = "SELECT t.*, 
            CONCAT(c1.customer_fname, ' ', c1.customer_lname) AS transferor_name, 
            CONCAT(c2.customer_fname, ' ', c2.customer_lname) AS receiver_name
        FROM transaction AS t
        INNER JOIN account AS a1 ON t.account_transferor = a1.account_id
        INNER JOIN customer AS c1 ON a1.customer_id = c1.customer_id
        LEFT JOIN account AS a2 ON t.account_receiver = a2.account_id
        LEFT JOIN customer AS c2 ON a2.customer_id = c2.customer_id
        WHERE a1.customer_id = '$loggedInUserID' OR a2.customer_id = '$loggedInUserID'
        ORDER BY t.transaction_date DESC";

$result = mysqli_query($connection, $sql);

// Close the database connection
mysqli_close($connection);
?>

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
    <!-- ไอคอนบนแท็บ -->
    <link rel="shortcut icon" href="Asset/img/Baiplus_final-removebg-preview.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Baiplus History</title>
    <!--==================== MAIN JS ====================-->
    <script src="Asset/js/main.js"></script>
    <script src="Asset/js/darkmode.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

</head>
<body>
    <!-- Navigation Bar -->
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
    <!-- Main -->
    <img class="test-img" src="Asset/img/treebran02.png">
        <img class="test-img2" src="Asset/img/treebran02.png">
    <img class="test-img3" src="Asset/img/account__bg.jpg">
    <main class="main">
        <section class="his__section section" id="select">
            <h2 class="section__title">History</h2>
            <span class="section__subtitle"> </span>
            <div class="his__bg">
                <div class="grid his__container">
                    <div class="his__box-topic" value="">
                        <div class="">Transferor</div>
                        <div class="">Recipient</div>
                        <div class="">Type</div>
                        <div class="">Amount</div>
                        <div class="">Date & Time</div>
                    </div>

                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($transaction = mysqli_fetch_assoc($result)) {
                            $transferor = $transaction['transferor_name'];
                            $receiver = $transaction['receiver_name'];
                            $type = $transaction['trans_type'];
                            $amount = $transaction['transaction_amount'];
                            $dateTime = $transaction['transaction_date'];
                            ?>

                            <div class="his__box">
                                <div class="info his__box-data"><?php echo $transferor; ?></div>
                                <div class="info his__box-data"><?php echo $receiver ? $receiver : 'no receiver'; ?></div>
                                <div class="info his__box-data"><?php echo $type; ?></div>
                                <div class="info his__box-data"><?php echo $amount; ?></div>
                                <div class="info his__box-data"><?php echo $dateTime; ?></div>
                            </div>

                            <?php
                        }
                    } else {
                        echo "<p>No transaction history found.</p>";
                    }
                    ?>

                </div>

                

            </div>
            <div class="btn__container">
            <button  class="button__summit info" onclick="printstatement()">Print PDF</button>
                </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="footer"></footer>

    <!-- Swiper JS -->
    <script src=""></script>
    <script>
        function printstatement() {  
        
                var tableData = [];

                // Get the transaction data from the HTML elements
                var transactionElements = document.getElementsByClassName('his__box');

                // Iterate over each transaction element and add its data to the tableData array
                for (var i = 0; i < transactionElements.length; i++) {
                var transferor = transactionElements[i].querySelector('.his__box-data:nth-child(1)').textContent;
                var receiver = transactionElements[i].querySelector('.his__box-data:nth-child(2)').textContent;
                var type = transactionElements[i].querySelector('.his__box-data:nth-child(3)').textContent;
                var amount = transactionElements[i].querySelector('.his__box-data:nth-child(4)').textContent;
                var dateTime = transactionElements[i].querySelector('.his__box-data:nth-child(5)').textContent;

                // Create an object for each transaction and add it to the tableData array
                tableData.push([transferor, receiver, type, amount, dateTime]);
                }
                // Create the document definition for pdfmake
                var docDefinition = {
                content: [
                    {
                    text: 'Baiplus Banking',
                    style: 'header',
                    alignment: 'center',
                    margin: [0, 30]
                    },
                    {
                    table: {
                        headerRows: 1,
                        widths: ['*', '*', '*', '*', '*'],
                        body: [
                        ['Transferor', 'Recipient', 'Type', 'Amount', 'Date & Time'],
                        ...tableData
                        ]
                    }
                    }
                ],
                styles: {
                    header: {
                    fontSize: 20,
                    bold: true,
                    margin: [0, 10] // Add margin to separate the text from the table
                    }
                }
                };



                // Generate the PDF
                pdfMake.createPdf(docDefinition).download('transaction_history.pdf');
        }
    </script>

</body>
</html>
