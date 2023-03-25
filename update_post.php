<?php

require_once('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $error = [];
  $categoryId = $_POST['categoryId'];
  $postTitle = $_POST['postTitle'];
  $postDescription = $_POST['postDescription'];

  if (!empty($categoryId) && !empty($postTitle) && !empty($postDescription)) {

    $id = $_POST['postId'];

    if (!empty($_FILES['postImage']['name'])) {
      $sql = "SELECT postImage FROM posts WHERE id = $id";
      $result = mysqli_query($connection, $sql);
      $row = mysqli_fetch_assoc($result);

      //Delete old image
      $oldImgName = $row['postImage'];
      $oldImgPath = 'images/' . $oldImgName;

      //Check old img exists or not
      if (file_exists($oldImgPath)) {
        unlink($oldImgPath);
      } else {
        echo "Image not found";
      }

      //Upload new image into directory
      $target_dir = "images/";
      $imgName = basename($_FILES['postImage']['name']);
      $target_file = $target_dir . $imgName;
      move_uploaded_file($_FILES["postImage"]["tmp_name"], $target_file);

      $sql = "UPDATE posts 
                SET postTitle = ?, postDescription = ?,
                    categoryId = ?, postImage = ?
                WHERE id = ?";

      $stmt = $connection->prepare($sql);
      $stmt->bind_param("ssisi", $postTitle, $postDescription, $categoryId, $imgName, $id);
    } else {
      $sql = "UPDATE posts 
                SET postTitle = ?, postDescription = ?, categoryId = ?
                WHERE id = ?";

      $stmt = $connection->prepare($sql);
      $stmt->bind_param("ssii", $postTitle, $postDescription, $categoryId, $id);
    }

    try {
      $stmt->execute();

      if ($stmt->error) {
        throw new Exception($stmt->error);
      }

      header('location:admin.php');
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  } else {
    //Error handling
    if (empty($categoryId)) {
      $error['categoryId'] = "Required";
    }

    if (empty($postTitle)) {
      $error['postTitle'] = "Required";
    }

    if (empty($postDescription)) {
      $error['postDescription'] = "Required";
    }
  }
}
mysqli_close($connection);
?>