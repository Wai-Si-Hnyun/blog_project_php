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
  <div class="home container mt-5">
    <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
    <h1>Blog Posts</h1>
    <hr>
    <div class="row">
      <?php
      require_once('dbconnection.php');
      $sql = "SELECT * FROM posts";
      $result = mysqli_query($connection, $sql);

      while ($row = mysqli_fetch_assoc($result)) {
        echo "
              <div class='card col-3 mb-3'>
                <img src='images/{$row['postImage']}'>
                <div class='card-body'>
                  <h2 class='card-title'>{$row['postTitle']}</h2>
                  <a href='detail_post.php?id={$row['id']}' class='btn btn-primary'>Read More</a>
                </div>
              </div>
            ";
      }
      mysqli_close($connection);
      ?>
    </div>
  </div>
</body>

</html>