<?php
session_start();
include 'connect.php';

if (isset($_POST['share'])) {
    $username = $_SESSION['username'];
    $description = $_POST['description'];
    $targetDirectory = "data/posts/";
    $fileName = uniqid() . "_" . basename($_FILES["fileToUpload"]["name"]);
    $targetPath = $targetDirectory . $fileName;

    $queryUserId = "SELECT id FROM user WHERE username = :username";
    $statementUserId = $dbh->prepare($queryUserId);
    $statementUserId->bindParam(':username', $username);
    $statementUserId->execute();
    $result = $statementUserId->fetch();
    $userid = $result['id'];

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetPath)) {
        try {
            $query = "INSERT INTO post (username, posterid, photo, description) VALUES (:username, :posterid, :photo, :description)";
            $statement = $dbh->prepare($query);
            $statement->bindParam(':username', $username);
            $statement->bindParam(':posterid', $userid);
            $statement->bindParam(':photo', $fileName);
            $statement->bindParam(':description', $description);

            if ($statement->execute()) {
                header("Location:https://egoistsky.free.nf/user");
                exit();
            } else {
                echo "Sql Error";
            }

        } catch (PDOException $e) {
            echo "Veritabanı hatası: " . $e->getMessage();
        }
    } else {
        echo "File couldnt upload";
    }
} else {
    header("Location:https://egoistsky.free.nf/user");
}
?>