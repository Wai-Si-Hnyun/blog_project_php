<?php

require_once('dbconnection.php');

if (!empty($_POST['categoryName'])) {
    $categoryName = $_POST['categoryName'];

    $sql = "INSERT INTO categories (categoryName) VALUES (?)";

    try {
        //Functional syle
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $categoryName);
        mysqli_stmt_execute($stmt);

        //MySQL error handling
        if (mysqli_stmt_error($stmt)) {
            throw new Exception(mysqli_stmt_error($stmt));
        }

        header('location:admin.php');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
mysqli_close($connection);
?>