<?php

require_once('db.php');
$db = new DB();
$errors = $db->storePost();

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
        <form method="POST" action="add_post.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="categoryId" class="form-label">Category</label>
                <select name="categoryId" id="categoryId" class="form-select">
                    <option value="">Choose category</option>
                    <?php
                    $categories = $db->index('categories');

                    if ($categories) {
                        foreach ($categories as $category) {
                            echo "
                                <option value='{$category['id']}'>{$category['categoryName']}</option>
                            ";
                        }
                    } else {
                        echo "";
                    }
                    ?>
                </select>
                <?php
                if (!empty($errors['categoryId'])):
                    echo '<small class="text-danger">' . $errors['categoryId'] . '</small>';
                endif ?>
            </div>
            <div class="mb-3">
                <label for="postTitle" class="form-label">Post Title</label>
                <input type="text" class="form-control" name="postTitle" id="postTitle">
                <?php
                if (!empty($errors['postTitle'])):
                    echo '<small class="text-danger">' . $errors['postTitle'] . '</small>';
                endif ?>
            </div>
            <div class="mb-3">
                <label for="postDescription" class="form-label">Post Description</label>
                <textarea class="form-control" name="postDescription" id="postDescription" cols="10" rows="10"></textarea>
                <?php
                if (!empty($errors['postDescription'])):
                    echo '<small class="text-danger">' . $errors['postDescription'] . '</small>';
                endif ?>
            </div>
            <div class="mb-3">
                <label for="postImage" class="form-label">Post Image</label>
                <input class="form-control" type="file" name="postImage" id="postImage" accept="image/*">
                <?php
                if (!empty($errors['postImage'])):
                    echo '<small class="text-danger">' . $errors['postImage'] . '</small>';
                endif ?>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
</body>

</html>