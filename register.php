<?php

session_start();

require_once('dbconnection.php');

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $hashPass = password_hash($password, PASSWORD_DEFAULT);

        // Query database to add user
        $sql = "INSERT INTO users (userName, password) VALUES ('$username', '$hashPass')";

        try {
            mysqli_query($connection, $sql);
            $_SESSION['username'] = $username;
            header('location:home.php');
        } catch (\Throwable $th) {
            echo "Cannot create user. Try another username";
        }
    } else {
        $error['username'] = "Required";
        $error['password'] = "Required";
    }
}


mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="libraries/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="register-ctn">
        <h1>Register</h1>
        <form method="POST" action="register.php">
            <div class="mb-3 mt-5">
                <label for="username" class="form-label">UserName</label>
                <input type="text" class="form-control" name="username" id="username">
                <?php
                if (isset($error['username'])):
                    echo '<small class="text-danger">' . $error['username'] . '</small>';
                endif ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
                <?php
                if (isset($error['password'])):
                    echo '<small class="text-danger">' . $error['password'] . '</small>';
                endif ?>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <span class="login-link">Already a user? <a href="login.php">login</a></span>
        </form>
    </div>
</body>

</html>