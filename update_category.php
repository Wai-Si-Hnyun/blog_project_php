<?php

require_once('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = null;

    if (!empty($_POST['categoryName'])) {

        $updatedName = $_POST['categoryName'];
        $id = $_POST['categoryId'];

        $sql = "UPDATE categories SET categoryName = ? WHERE id = ?";

        try {
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("si", $updatedName, $id);
            $stmt->execute();

            //MySQL error handling
            if ($stmt->error) {
                throw new Exception($stmt->error);
            }

            header('location:admin.php');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        $error = 'Required';
    }
}
mysqli_close($connection);
?>