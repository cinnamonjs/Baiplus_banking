<?php 

require_once("connect.php");

// Process the user's choice
$choice = $_POST['select__bank'];

// Redirect the user to another page
header("Location: tranfer_selecttran.html?choice=" . urlencode($choice));
exit();

?>
