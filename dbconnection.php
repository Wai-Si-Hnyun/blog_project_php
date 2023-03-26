<?php

// Database Connection
$servername = "localhost";
$username = "root";
$password = "admin123";
$dbname = "blog";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
  die("Connection fail..." . mysqli_connect_error());
}

?>