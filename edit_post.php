<?php

require_once('dbconnection.php');

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM posts WHERE id = $id";
  try {
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    //Assign into variables
    $postTitle = $row['postTitle'];
    $postDescription = $row['postDescription'];
    $postImage = $row['postImage'];
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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
    <form method="POST" action="update_post.php.php" enctype="multipart/form-data">
      <div class="mb-3">
        <?php
        echo "<img src='images/{$postImage}' alt='Post Image'>"
          ?>
      </div>
      <div class="mb-3">
        <label for="categoryId" class="form-label">Category</label>
        <select name="categoryId" id="categoryId" class="form-select">
          <option value="">Choose category</option>
          <?php
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
        <?php
        echo "<input type='text' class='form-control' name='postTitle'
                 id='postTitle' value='{$postTitle}' >
                 <input type='hidden' name='postId' value='{$id}' >";

        if (!empty($error['postTitle'])):
          echo '<small class="text-danger">' . $error['postTitle'] . '</small>';
        endif ?>
      </div>
      <div class="mb-3">
        <label for="postDescription" class="form-label">Post Description</label>
        <?php

        echo "
            <textarea class='form-control' name='postDescription' id='postDescription' rows='10'
            >{$postDescription}
            </textarea>
          ";

        if (!empty($error['postDescription'])):
          echo '<small class="text-danger">' . $error['postDescription'] . '</small>';
        endif ?>
      </div>
      <div class="mb-3">
        <label for="postImage" class="form-label">Post Image</label>
        <input class="form-control" type="file" name="postImage" id="postImage" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</body>

</html>