<?php

session_start();

//Authorization process
if ($_SESSION['username'] != 'admin') {
    die("Permission denied");
}

require_once('db.php');
$db = new DB();

?>

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
            <a href="add_category.php" class="btn btn-dark my-2">Add Category</a>
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
                    $categories = $db->index('categories');

                    if ($categories) {
                        foreach ($categories as $category) {
                            echo "
                                <tr>
                                    <th scope='row'>{$category['id']}</th>
                                    <td>{$category['categoryName']}</td>
                                    <td>
                                    <a href='edit_category.php?id={$category['id']}&name={$category['categoryName']}'
                                    class='text-primary'>Edit</a>
                                    </td>
                                    <td>
                                    <a href='delete.php?db=categories&id={$category['id']}' class='text-danger'>
                                        Delete
                                    </a>
                                    </td>
                                </tr>
                            ";
                        }
                    } else {
                        echo "
                            <tr>
                                <th scope='row'></th>
                                <td>No data</td>
                            </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <section class="my-5">
            <h2>Post</h2>
            <a href="add_post.php" class="btn btn-dark">Add Post</a>
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
                    $posts = $db->index('posts');

                    if ($posts) {
                        foreach ($posts as $post) {
                            echo "
                                <tr>
                                    <th scope='row'>{$post['id']}</th>
                                    <th class='col-4'>
                                        <img class='w-25' src='images/{$post['postImage']}' alt='post_image'>
                                    </th>
                                    <td>{$post['categoryId']}</td>
                                    <td>{$post['postTitle']}</td>
                                    <td>{$post['postDescription']}</td>
                                    <td>
                                        <a href='edit_post.php?id={$post['id']}' class='text-primary'>Edit</a>
                                    </td>
                                    <td>
                                        <a href='delete.php?db=posts&id={$post['id']}' class='text-danger'>
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            ";
                        }
                    } else {
                        echo "
                            <tr>
                                <th scope='row'></th>
                                <td>No data</td>
                            </tr>
                        ";
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
                    $comments = $db->index('comments');

                    if ($comments) {
                        foreach ($comments as $comment) {
                            echo "
                                <tr>
                                    <th scope='row'>{$comment['id']}</th>
                                    <td>{$comment['postId']}</td>
                                    <td>{$comment['cmtDescription']}</td>
                                    <td>
                                        <a href='delete.php?db=comments&id={$comment['id']}' class='text-danger'>
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            ";
                        }
                    } else {
                        echo "
                            <tr>
                                <th scope='row'></th>
                                <td>No data</td>
                            </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>