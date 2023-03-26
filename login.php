<?php

require_once('db.php');
$db = new DB();
$error = $db->login();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Login</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="libraries/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="login-ctn">
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">UserName</label>
                <input type="text" class="form-control" name="username" id="username">
                <?php
                if (!empty($error['username'])):
                    echo '<small class="text-danger">' . $error['username'] . '</small>';
                endif ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
                <?php
                if (!empty($error['password'])):
                    echo '<small class="text-danger">' . $error['password'] . '</small>';
                endif ?>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <span class="register-link">Not a user? <a href="register.php">register</a></span>
        </form>
    </div>
</body>

</html>