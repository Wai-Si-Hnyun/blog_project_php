<?php

session_start();

$_SESSION['user'] = "";

header('location:login.php');

exit();

?>