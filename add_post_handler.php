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