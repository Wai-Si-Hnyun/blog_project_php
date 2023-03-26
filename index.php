<?php

require_once('dbconnection.php');

//Check admin is created of not
$sql = "SELECT * FROM users WHERE username = 'admin'";
$result = mysqli_query($connection, $sql);
$rows = mysqli_num_rows($result);

//If there is no admin account, create default admin
if ($rows == 0) {
  require_once('default_admin_create.php');
}

//Redirect route to login page
header("Location: login.php");
exit();

?>