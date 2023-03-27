<?php

require_once('db.php');
$db = new DB();
$error = $db->storeCategory();

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
    <div class="container col-5">
        <a href="admin.php" class="btn btn-dark mt-4">Back</a>
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