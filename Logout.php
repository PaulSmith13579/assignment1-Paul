<?php include "template.php";
session_start();
unset($_SESSION["user_id"]);
unset($_SESSION["name"]);
unset($_SESSION["username"]);
unset($_SESSION["level"]);
unset($_SESSION["shopping)cart"]);
header("Location:index.php");
?>