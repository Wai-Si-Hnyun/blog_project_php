<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog | Admin panel</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="libraries/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">
    <a href="logout.php" class="btn btn-danger mb-3 mt-3">Logout</a>
    <h1 class="my-4">Admin Panel</h1>
    <section>
      <h2>Category</h2>
      <a href="addCategory.php" class="btn btn-dark my-2">Add Category</a>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Category Name</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php
          require_once('dbconnection.php');

          $sql = "SELECT * FROM categories";
          $result = mysqli_query($connection, $sql);
          $rows = mysqli_num_rows($result);

          if ($rows == 0) {
            echo "
                  <tr>
                    <th scope='row'></th>
                      <td>No data</td>
                    </tr>
                  ";
          } else {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "
                    <tr>
                        <th scope='row'>{$row['id']}</th>
                        <td>{$row['categoryName']}</td>
                        <td>
                          <a href='editCategory.php?id={$row['id']}&name={$row['categoryName']}'
                           class='text-primary'>Edit</a>
                        </td>
                        <td>
                          <a href='delete.php?db=categories&id={$row['id']}' class='text-danger'>
                            Delete
                          </a>
                        </td>
                    </tr>
                  ";
            }
          }
          ?>
        </tbody>
      </table>
    </section>
    <section class="my-5">
      <h2>Post</h2>
      <a href="addPost.php" class="btn btn-dark">Add Post</a>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">PostImage</th>
            <th scope="col">CategoryId</th>
            <th scope="col">PostTitle</th>
            <th scope="col">PostDescription</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php

          $sql = "SELECT * FROM posts";
          $result = mysqli_query($connection, $sql);
          $rows = mysqli_num_rows($result);

          if ($rows == 0) {
            echo "
              <tr>
                <th scope='row'></th>
                <td>No data</td>
              </tr>
              ";
          } else {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "
                  <tr>
                    <th scope='row'>{$row['id']}</th>
                    <th class='col-4'><img class='w-25' src='images/{$row['postImage']}' alt='post_image'></th>
                    <td>{$row['categoryId']}</td>
                    <td>{$row['postTitle']}</td>
                    <td>{$row['postDescription']}</td>
                    <td><a href='editPost.php?id={$row['id']}' class='text-primary'>Edit</a></td>
                    <td><a href='delete.php?db=posts&id={$row['id']}' class='text-danger'>Delete</a></td>
                  </tr>
                ";
            }
          }
          ?>
        </tbody>
      </table>
    </section>
    <section>
      <h2>Comment</h2>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">PostID</th>
            <th scope="col">Comment</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $sql = "SELECT * FROM  comments";
          $result = mysqli_query($connection, $sql);
          $rows = mysqli_num_rows($result);

          if ($rows == 0) {
            echo "
                <tr>
                  <th scope='row'></th>
                  <td>No data</td>
                </tr>
              ";
          } else {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "
                  <tr>
                    <th scope='row'>{$row['id']}</th>
                    <td>{$row['postId']}</td>
                    <td>{$row['cmtDescription']}</td>
                    <td><a href='delete.php?db=comments&id={$row['id']}' class='text-danger'>Delete</a></td>
                  </tr>
                ";
            }
          }
          mysqli_close($connection);
          ?>
        </tbody>
      </table>
    </section>
  </div>
</body>

</html>