<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $check_username_sql = "SELECT COUNT(*) as count FROM user WHERE username = :username";
    $stmt_check_username = $dbh->prepare($check_username_sql);
    $stmt_check_username->bindParam(':username', $username);
    $stmt_check_username->execute();
    $username_exists = $stmt_check_username->fetchColumn();

    $check_email_sql = "SELECT COUNT(*) as count FROM user WHERE email = :email";
    $stmt_check_email = $dbh->prepare($check_email_sql);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();
    $email_exists = $stmt_check_email->fetchColumn();

    if ($username_exists > 0) {
        echo '<script>alert("This user not avaible!");</script>';
    } elseif ($email_exists > 0) {
        echo '<script>alert("This Email not avaible!");</script>';
    } else {
        try {

            $insert_user_sql = "INSERT INTO user (username, password, email, firstname, lastname) 
                                VALUES (:username, :password, :email, :firstname, :lastname)";
            $stmt_insert_user = $dbh->prepare($insert_user_sql);
            $stmt_insert_user->bindParam(':username', $username);
            $stmt_insert_user->bindParam(':password', $hashed_password);
            $stmt_insert_user->bindParam(':email', $email);
            $stmt_insert_user->bindParam(':firstname', $firstname);
            $stmt_insert_user->bindParam(':lastname', $lastname);
            $stmt_insert_user->execute();
            $new_id = $dbh->lastInsertId();

            if ($_FILES["profilephoto"]["error"] == UPLOAD_ERR_OK) {
                $target_directory = "data/photos/";
                $file_extension = strtolower(pathinfo($_FILES["profilephoto"]["name"], PATHINFO_EXTENSION));
                $unique_filename = uniqid() . '.' . $file_extension;

                $target_file = $target_directory . $unique_filename;

                $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");

                if (in_array($image_file_type, $allowed_extensions)) {
                    if (move_uploaded_file($_FILES["profilephoto"]["tmp_name"], $target_file)) {
                        $update_photo_sql = "UPDATE user SET profilephoto = :profilephoto WHERE id = :id";
                        $stmt_photo = $dbh->prepare($update_photo_sql);
                        $file_path = $target_file;
                        $stmt_photo->bindParam(':profilephoto', $file_path);
                        $stmt_photo->bindParam(':id', $new_id);
                        $stmt_photo->execute();
                    } else {
                        echo '<script>alert("File Upload Error.");</script>';
                    }
                } else {
                    echo '<script>alert("Only JPG, JPEG, PNG ve GIF Files Can Be Upload.");</script>';
                }
            }

            $default_role = "user";
            $sql_role = "INSERT INTO roles (id, role) VALUES (:id, :role)";
            $stmt_role = $dbh->prepare($sql_role);
            $stmt_role->bindParam(':id', $new_id);
            $stmt_role->bindParam(':role', $default_role);
            $stmt_role->execute();

            header('Location: user');
            exit;
        } catch (PDOException $e) {
            echo "Hata: " . $e->getMessage();
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
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="astronomy.ico">
    <link rel="stylesheet" href="style.css">
</head>

<body class="grad" style="background-image: url(ngtsky.jpg);
    background-size: cover;">
    <a href="https://egoistsky.free.nf" class="start-50 top-25 mt-4 text-center position-absolute translate-middle"><img
            src="astronomy.png" class="border border-black rounded-circle border-3 mt-5" style="width:16%;"></a>

    <form class="w-25 text-white position-absolute top-50 start-50 translate-middle" enctype="multipart/form-data"
        method="post">
        <div class="mb-4">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
        </div>
        <div class="mb-4">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                required>
        </div>
        <div class="mb-4">
            <label for="exampleInputEmail1" class="form-label">First Name</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                name="firstname" required>
        </div>
        <div class="mb-4">
            <label for="exampleInputEmail1" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="lastname"
                required>
        </div>
        <div class="mb-4">
            <label for="exampleInputEmail1" class="form-label">Profile Photo</label>
            <input type="file" class="form-control border border-black" id="formFile" aria-describedby="emailHelp"
                name="profilephoto" required>
        </div>
        <button type="submit" class="btn btn-outline-light w-100">Sign Up</button>
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
<script>
</html >