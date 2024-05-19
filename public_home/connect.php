<?php 

    $servername = "mysql";
    $username = "root";
    $password = "";
    $dbname = "baiplus_database";

    try {
        $dbcon = new PDO("mysql:host=$servername;dbname=$dbname" , $username , $password);
        //set err
        $dbcon->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
    } catch (PDOException $e) {
        echo "ต่อ database ไม่ได้ กรุณาลองใหม่หรือติดต่อกัปตันขบวนรถไฟที่ห้องโถงของขบวน <br>" . $e->getMessage();
        die("Connection failed: " . $e->getMessage());
    }

?>