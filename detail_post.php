<?php

require_once('db.php');
$db = new DB();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $post = $db->show($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db->storeComment();
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
            <img src='images/{$post['postImage']}' class='img-fluid' alt='Blog Post Image'>
            <h1 class='mt-3'>{$post['postTitle']}</h1>
            <p class='lead'>{$post['postDescription']}</p>
            "
                    ?>
                <hr>
                <h3 class="mt-4">Comments</h3>
                <ul class="list-unstyled">
                    <?php
                    
                    $comments = $db->showCommentsForPost($id);

                    if ($comments) {
                        foreach($comments as $comment) {
                            echo "
                                <li class='media my-4'>
                                    <div class='media-body'>
                                        <p> - {$comment['cmtDescription']}</p>
                                    </div>
                                </li>
                            ";
                        }
                    } else {
                        echo "
                            <li class='media my-4'>
                                <div class='media-body'>
                                    <p class='text-danger'>No comment</p>
                                </div>
                            </li>
                        ";
                    }
                    ?>
                </ul>
                <h3 class="mt-4">Leave a Comment</h3>
                <form method="POST" action="detail_post.php">
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