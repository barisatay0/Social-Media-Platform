<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

try {
    $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Arama sorgusu oluşturma
    if (isset($_GET['search_query'])) {
        $searchQuery = '%' . $_GET['search_query'] . '%';
        $stmt = $dbh->prepare("SELECT * FROM user WHERE username LIKE :searchQuery");
        $stmt->bindParam(':searchQuery', $searchQuery);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if (count($result) > 0) {
            echo '<div class="w-100 position-absolute" style="margin-top:2%;font-size:2rem;">';
            foreach ($result as $row) {
                echo '<p class="w-100"><a href="' . $row['username'] . '"><button class="w-100 btn btn-outline-light">' . $row['username'] . '</button></a></p>';
            }
            echo '</div>';
        } else {
            echo "";
        }
    } else {
        // Arama sorgusu yoksa burada başka bir şey yapabilirsiniz.
        // Şu an için boş bıraktım.
    }
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>