<?php

require_once('db.php');
$db = new DB();
$error = $db->updatePost();

if (isset($_GET['id'])) {
    $post = $db->show($_GET['id']);

    //Assign values to variables
    $id = $post['id'];
    $categoryId = $post['categoryId'];
    $postTitle = $post['postTitle'];
    $postDescription = $post['postDescription'];
    $postImage = $post['postImage'];
}

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
    <div class="container col-8">
        <a href="admin.php" class="btn btn-dark mt-3">Back</a>
        <h1>Add Category</h1>
        <form method="POST" action="edit_post.php?id=<?php echo $id ?>" enctype="multipart/form-data">
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
                    $categories = $db->index("categories");

                    foreach ($categories as $category) {
                        $selected = ($category['id'] == $categoryId) ? 'selected' : '';
                        echo "
                            <option value='{$category['id']}' {$selected}>
                                {$category['categoryName']}
                            </option>
                        ";
                    }
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
                <textarea class='form-control' 
                    name='postDescription' id='postDescription'
                    cols="10" rows='10'><?php echo $postDescription ?></textarea>
                <?php
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