<?php

require_once('dbconnection.php');

$sql = "SELECT * FROM users WHERE username = 'admin'";
$result = mysqli_query($connection, $sql);
$rows = mysqli_num_rows($result);

if ($rows == 0) {
  require_once('default_admin_create.php');
}

header("Location: login.php");
exit();

?>