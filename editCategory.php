<?php

require_once('dbconnection.php');

if (isset($_GET['id']) && isset($_GET['name'])) {
    $id = $_GET['id'];
    $categoryName = $_GET['name'];
}

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
    <form method="POST" action="editCategory.php">
      <div class="mb-3">
        <label for="categoryName" class="form-label">Category Name</label>
        <?php
        echo "<input type='text' class='form-control' name='categoryName'
            id='categoryName' value='{$categoryName}' >";
        echo "<input type='hidden' name='categoryId' value='{$id}' >";
        if (!empty($error)):
            echo '<small class="text-danger">' . $error . '</small>';
        endif ?>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</body>

</html>