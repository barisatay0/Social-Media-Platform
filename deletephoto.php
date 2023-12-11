<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photo'])) {
    $photoToDelete = $_POST['photo'];
    try {
        $deleteQuery = "DELETE FROM post WHERE photo = :photo";
        $stmt = $dbh->prepare($deleteQuery);
        $stmt->bindParam(':photo', $photoToDelete);
        $stmt->execute();
        $filePath = 'data/posts/' . $photoToDelete;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        echo 'Success';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request';
}
?>