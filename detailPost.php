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

        if (!$result) {
            echo "Error: " . mysqli_error($connection);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['comment'])) {
        $cmtDescription = $_POST['comment'];
        $postId = $_POST['postId'];

        $sql = "INSERT INTO comments (postId, cmtDescription) 
              VALUES ('$postId', '$cmtDescription')";

        try {
            $result = mysqli_query($connection, $sql);
            if (!$result) {
                echo mysqli_error($connection);
            }
            header('location:detailPost.php?id=' . $postId);
        } catch (Exception $e) {
            echo "Error:" . $e->getMessage();
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
  <title>Blog | Home</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="libraries/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container mt-5">
    <a href="home.php" class="btn btn-dark mb-3">Back</a>
    <div class="row">
      <div class="col-md-8">
        <?php
        echo "
            <img src='images/{$postImage}' class='img-fluid' alt='Blog Post Image'>
            <h1 class='mt-3'>{$postTitle}</h1>
            <p class='lead'>{$postDescription}</p>
            "
        ?>
        <hr>
        <h3 class="mt-4">Comments</h3>
        <ul class="list-unstyled">
          <?php
        //Functional Style
        $sql = "SELECT * FROM comments WHERE postId = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "
                    <li class='media my-4'>
                    <div class='media-body'>
                        <p> - {$row['cmtDescription']}</p>
                    </div>
                    </li>
                ";
        }
        mysqli_close($connection);
        ?>
        </ul>
        <h3 class="mt-4">Leave a Comment</h3>
        <form method="POST" action="detailPost.php">
          <div class="mb-3">
            <textarea class="form-control" name="comment" id="comment" rows="3"
              placeholder="Comment Here..."></textarea>
            <?php
            echo "<input type='hidden' name='postId' value='{$id}'>"
            ?>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>