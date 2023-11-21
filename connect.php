<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

try {
    $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection Error: " . $e->getMessage();
}
?>
