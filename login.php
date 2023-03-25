<?php

session_start();

require_once('dbconnection.php');

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query database to check user exit
        $sql = "SELECT * FROM users WHERE userName = ?";

        try {
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($stmt->error) {
                throw new Exception($stmt->error);
            } else if (mysqli_num_rows($result) == 0) {
                throw new Exception("User not found");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        //If user is found, check the password
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                if ($row['role'] == 'admin') {
                    header('location:admin.php');
                } else {
                    header('location:home.php');
                }
            } else {
                // Error message for password is incorrect condition
                $error['password'] = "Incorrect password";
            }
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
                if (!empty($error['username'])) :
                    echo '<small class="text-danger">' . $error['username'] . '</small>';
                endif
                ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
                <?php
                if (!empty($error['password'])) :
                    echo '<small class="text-danger">' . $error['password'] . '</small>';
                endif
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <span class="register-link">Not a user? <a href="register.php">register</a></span>
        </form>
    </div>
</body>

</html>