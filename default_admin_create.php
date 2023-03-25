<?php

require_once('dbconnection.php');

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";

try {
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    if ($stmt->error) {
        throw new Exception($stmt->error);
    }

    header('location:login.php');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

mysqli_close($connection);

?>