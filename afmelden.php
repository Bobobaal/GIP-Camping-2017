<?php
session_start();
$_SESSION["igebruikersrol"] = "user";
$_SESSION["iklantid"] = "";
header("location:index.php");
?>