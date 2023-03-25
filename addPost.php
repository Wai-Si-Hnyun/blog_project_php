<?php

require_once('dbconnection.php');
$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postTitle = $_POST['postTitle'];
    $postDescription = $_POST['postDescription'];
    $categoryId = $_POST['categoryId'];

    if (
        !empty($postTitle) && !empty($postDescription) &&
        !empty($categoryId) && !empty($_FILES['postImage']['name'])
    ) {

        //Save image in local
        $target_dir = "images/";
        $imgName = basename($_FILES['postImage']['name']);
        $target_file = $target_dir . $imgName;
        move_uploaded_file($_FILES["postImage"]["tmp_name"], $target_file);

        //Save image name in database
        $sql = "INSERT INTO posts (categoryId, postTitle, postDescription, postImage) 
              VALUES (?, ?, ?, ?)";

        try {
            //Object-oriented Style
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("isss", $categoryId, $postTitle, $postDescription, $imgName);
            $stmt->execute();

            //MySQL error handling
            if ($stmt->error) {
                throw new Exception($stmt->error);
            }

            header('location:admin.php');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }


    } else {
        if (empty($postTitle)) {
            $error['postTitle'] = "Required";
        }

        if (empty($postDescription)) {
            $error['postDescription'] = "Required";
        }

        if (empty($categoryId)) {
            $error['categoryId'] = "Required";
        }

        if (empty($_FILES['postImage']['name'])) {
            $error['postImage'] = "Required";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog | Add Post</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="libraries/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="add-post-ctn">
    <a href="admin.php" class="btn btn-dark">Back</a>
    <h1>Add Post</h1>
    <form method="POST" action="addPost.php" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="categoryId" class="form-label">Category</label>
        <select name="categoryId" id="categoryId" class="form-select">
          <option value="">Choose category</option>
          <?php
          require_once('dbconnection.php');
          $sql = "SELECT * FROM categories";
          $result = mysqli_query($connection, $sql);
          $rows = mysqli_num_rows($result);

          if ($rows == 0) {
              echo "";
          } else {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "
                  <option value='{$row['id']}'>{$row['categoryName']}</option>
                  ";
              }
          }
          mysqli_close($connection);
          ?>
        </select>
        <?php
        if (!empty($error['categoryId'])):
            echo '<small class="text-danger">' . $error['categoryId'] . '</small>';
        endif ?>
      </div>
      <div class="mb-3">
        <label for="postTitle" class="form-label">Post Title</label>
        <input type="text" class="form-control" name="postTitle" id="postTitle">
        <?php
        if (!empty($error['postTitle'])):
            echo '<small class="text-danger">' . $error['postTitle'] . '</small>';
        endif ?>
      </div>
      <div class="mb-3">
        <label for="postDescription" class="form-label">Post Description</label>
        <textarea class="form-control" name="postDescription" id="postDescription" rows="10"></textarea>
        <?php
        if (!empty($error['postDescription'])):
            echo '<small class="text-danger">' . $error['postDescription'] . '</small>';
        endif ?>
      </div>
      <div class="mb-3">
        <label for="postImage" class="form-label">Post Image</label>
        <input class="form-control" type="file" name="postImage" id="postImage" accept="image/*">
        <?php
        if (!empty($error['postImage'])):
            echo '<small class="text-danger">' . $error['postImage'] . '</small>';
        endif ?>
      </div>
      <button type="submit" class="btn btn-primary">Add</button>
    </form>
  </div>
</body>

</html>