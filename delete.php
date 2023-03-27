<?php

require_once('db.php');
$db = new DB();

if (isset($_GET['db']) && isset($_GET['id'])) {

    $dbname = $_GET['db'];
    $id = $_GET['id'];
    $db->destroy($dbname, $id);

} else {
    die('Error: ' . 'Parameters not found');
}
mysqli_close($connection);
?>