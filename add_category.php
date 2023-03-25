<?php

require_once('dbconnection.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    } else {
        $error = "Required";
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
    <title>Blog | Add Category</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="libraries/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="add-category-ctn">
        <a href="admin.php" class="btn btn-dark">Back</a>
        <h1>Add Category</h1>
        <form method="POST" action="add_category.php">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" name="categoryName" id="categoryName">
                <?php
                if (!empty($error)):
                    echo '<small class="text-danger">' . $error . '</small>';
                endif ?>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
</body>

</html>