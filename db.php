<?php

class DB
{
    protected $connection;

    public function __construct()
    {
        try {
            $this->connection = mysqli_connect("localhost", "root", "Aeiou6453!", "blog");
            if (!$this->connection) {
                throw new Exception("Connection fail..." . mysqli_connect_error());
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //Create default admin account
    public function createDefaultAdmin()
    {
        //Check admin is created of not
        $sql = "SELECT * FROM users WHERE username = 'admin'";
        $result = mysqli_query($this->connection, $sql);
        $rows = mysqli_num_rows($result);

        //If there is no admin account, create default admin
        if ($rows == 0) {
            $username = 'admin';
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            $role = 'admin';

            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";

            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("sss", $username, $password, $role);
            $stmt->execute();

            if ($stmt->error) {
                die("Error: " . $stmt->error);
            }
        }
        //Redirect route to login page
        header('location:login.php');
    }

    /**
     * Register user
     *
     * @return $error an array of error message
     */
    public function login()
    {
        session_start();
        $error = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {

                $username = $_POST['username'];
                $password = $_POST['password'];

                // Query database to check user exit
                $sql = "SELECT * FROM users WHERE userName = ?";

                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($stmt->error) {
                    die("Error: " . $stmt->error);
                } else if (mysqli_num_rows($result) == 0) {
                    $error['username'] = "User not found";
                }

                //If user is found, check the password
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row['password'])) {
                        //Store username  in session
                        $_SESSION['username'] = $username;

                        if ($row['role'] == 'admin') {
                            header('location:admin.php');
                        } else {
                            header('location:home.php');
                        }
                    } else {
                        // Error message for password is incorrect condition
                        $error['password'] = "Incorrect password";
                    }
                }
            } else {
                $error['username'] = "Required";
                $error['password'] = "Required";
            }
        }
        mysqli_close($this->connection);
        return $error;
    }

    /**
     * Register user
     *
     * @return $error an array of error message
     */
    public function register()
    {
        session_start();
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {

                $username = $_POST['username'];
                $password = $_POST['password'];

                $hashPass = password_hash($password, PASSWORD_DEFAULT);

                // Query database to add user
                $sql = "INSERT INTO users (userName, password) VALUES (?, ?)";

                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("ss", $username, $hashPass);
                $stmt->execute();

                if ($stmt->error) {
                    die("Error: " . $stmt->error);
                }

                $_SESSION['username'] = $username;
                header('location:home.php');
            } else {
                if (empty($_POST['username']))
                    $error['username'] = "Required";

                if (empty($_POST['password']))
                    $error['password'] = "Required";
            }
        }
        mysqli_close($this->connection);
        return $error;
    }

    /**
     * Logout user
     * @return void
     */
    public function logout()
    {
        session_start();
        $_SESSION['username'] = "";
        header('location:login.php');
    }

    /**
     * Get all data from specific tables
     * @param string $table table name
     * @return array $data an array of data
     */
    public function index($table)
    {
        $sql = "SELECT * FROM `$table`";
        $stmt = mysqli_query($this->connection, $sql);
        $data = mysqli_fetch_all($stmt, MYSQLI_ASSOC);

        if (mysqli_error($this->connection)) {
            die("Error: " . mysqli_error($this->connection));
        }
        return $data;
    }

    /**
     * Get data from specific table by id
     *
     * @param $id id of the specific data
     * @return array $result an array of data
     */
    public function show($id)
    {
        $sql = "SELECT * FROM posts WHERE id = $id";

        $stmt = mysqli_query($this->connection, $sql);
        $result = mysqli_fetch_assoc($stmt);

        return $result;
    }

    /**
     * Store new category
     * @return string $error error message
     */
    public function storeCategory()
    {
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['categoryName'])) {
                $categoryName = $_POST['categoryName'];

                $sql = "INSERT INTO categories (categoryName) VALUES (?)";

                //Functional syle
                $stmt = mysqli_prepare($this->connection, $sql);
                mysqli_stmt_bind_param($stmt, "s", $categoryName);
                mysqli_stmt_execute($stmt);

                //MySQL error handling
                if (mysqli_stmt_error($stmt)) {
                    die("Error: " . mysqli_stmt_error($stmt));
                }

                header('location:admin.php');
            } else {
                $error = "Required";
            }
        }
        return $error;
    }

    /**
     * Store new post
     * @return array $errors an array of error message
     */
    public function storePost()
    {
        $errors = [];

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

                //Object-oriented Style
                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("isss", $categoryId, $postTitle, $postDescription, $imgName);
                $stmt->execute();

                //MySQL error handling
                if ($stmt->error) {
                    die("Error: " . $stmt->error);
                }

                header('location: admin.php');
            } else {
                if (empty($postTitle)) {
                    $errors['postTitle'] = "Required";
                }

                if (empty($postDescription)) {
                    $errors['postDescription'] = "Required";
                }

                if (empty($categoryId)) {
                    $errors['categoryId'] = "Required";
                }

                if (empty($_FILES['postImage']['name'])) {
                    $errors['postImage'] = "Required";
                }
            }
        }
        return $errors;
    }

    /**
     * Update category
     * @return string $error error message
     */
    public function updateCategory()
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['categoryName'])) {

                $updatedName = $_POST['categoryName'];
                $id = $_POST['categoryId'];

                $sql = "UPDATE categories SET categoryName = ? WHERE id = ?";

                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("si", $updatedName, $id);
                $stmt->execute();

                //error handling
                if ($stmt->error) {
                    die("Error: " . $stmt->error);
                }

                header('location:admin.php');
            } else {
                $error = 'Required';
            }
        }
        return $error;
    }

    /**
     * Update post
     * @return array $error an array of error message
     */
    public function updatePost()
    {
        $error = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryId = $_POST['categoryId'];
            $postTitle = $_POST['postTitle'];
            $postDescription = $_POST['postDescription'];

            if (!empty($categoryId) && !empty($postTitle) && !empty($postDescription)) {

                $id = $_POST['postId'];

                if (!empty($_FILES['postImage']['name'])) {
                    $sql = "SELECT postImage FROM posts WHERE id = $id";
                    $result = mysqli_query($this->connection, $sql);
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

                    $stmt = $this->connection->prepare($sql);
                    $stmt->bind_param(
                        "ssisi",
                        $postTitle,
                        $postDescription,
                        $categoryId,
                        $imgName,
                        $id
                    );
                } else {
                    $sql = "UPDATE posts 
                            SET postTitle = ?, postDescription = ?, categoryId = ?
                            WHERE id = ?";

                    $stmt = $this->connection->prepare($sql);
                    $stmt->bind_param("ssii", $postTitle, $postDescription, $categoryId, $id);
                }

                $stmt->execute();

                if ($stmt->error) {
                    die("Error: " . $stmt->error);
                }

                header('location:admin.php');
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
        return $error;
    }

}

?>