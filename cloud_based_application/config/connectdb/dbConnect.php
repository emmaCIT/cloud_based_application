<?php
$connect_error = "Sorry, we\'re experiencing connection problems.";
$link=mysqli_connect("localhost", "root", "");
mysqli_select_db($link,"emma") or die ($connect_error);
?>

