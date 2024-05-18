<?php 

    require_once("connect.php");

  
    // Process the user's choice
    $choice = $_POST['select__bank'];
    echo "The user chose: " . $choice;

    // Redirect the user to another page
    // header("Location: transfer_selecttran.php?choice=" . urlencode($choice));
    header("Location: tranfer_selecttran.html?choice=" . urlencode($choice));
    exit();

?>