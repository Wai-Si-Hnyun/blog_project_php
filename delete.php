<?php

require_once('dbconnection.php');

if (isset($_GET['db']) && isset($_GET['id'])) {

    $db = $_GET['db'];
    $id = $_GET['id'];

    $sql = "DELETE FROM $db WHERE id = ?";
    try {
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->error) {
            throw new Exception($stmt->error);
        }

        header('location:admin.php');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    echo "Parameters not found";
}
mysqli_close($connection);
?>