<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username'])) {
    $loggedInUsername = $_SESSION['username'];

    try {
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':username', $loggedInUsername);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $profilePhoto = $row['profilephoto'];
            $biography = $row['biography'];
        } else {
            echo "Data not found or connection error";
        }
    } catch (PDOException $e) {
        echo "Bağlantı Hatası: " . $e->getMessage();
    }

    try {
        $query_posts = "SELECT photo FROM post WHERE username = :username";
        $stmt_posts = $dbh->prepare($query_posts);
        $stmt_posts->bindParam(':username', $loggedInUsername);
        $stmt_posts->execute();

        $loggedInUserPosts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Bağlantı Hatası: " . $e->getMessage();
    }

    // Eğer URL'den bir kullanıcı adı alındıysa, o kullanıcının verilerini ve postlarını al
    if (isset($_GET['username'])) {
        $clickedUsername = $_GET['username'];

        try {
            // Tıklanan kullanıcının verilerini al
            $query = "SELECT * FROM user WHERE username = :username";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':username', $clickedUsername);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $clickedProfilePhoto = $row['profilephoto'];
                $clickedBiography = $row['biography'];
            } else {
                echo "Data not found or connection error";
            }
        } catch (PDOException $e) {
            echo "Bağlantı Hatası: " . $e->getMessage();
        }

        // Tıklanan kullanıcının postlarını al
        try {
            $query_posts = "SELECT photo FROM post WHERE username = :username ORDER BY time DESC";
            $stmt_posts = $dbh->prepare($query_posts);
            $stmt_posts->bindParam(':username', $clickedUsername);
            $stmt_posts->execute();

            $clickedUserPosts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Bağlantı Hatası: " . $e->getMessage();
        }
    }
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egoistsky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="astronom.ico">
    <link rel="stylesheet" href="style.css">
    <style>
        .scrollable-container {
            width: 20%;
            right: 0;
            height: 25rem;
            overflow-y: auto;
            margin-right: 16%;
            margin-top: -6%;
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }

        .scrollable-container::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable-container::-webkit-scrollbar-thumb {
            background-color: transparent;
        }

        .dropdown {
            display: inline-block;
        }

        .dropdown-content {

            visibility: hidden;



        }

        .dropdown:hover .dropdown-content {

            visibility: visible;
        }
    </style>
</head>

<body class="bg-black">
    <a href="" class="mx-3 mt-2"></a>

    <div><a href="https://egoistsky.free.nf/user"
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder"
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle" style="width: 6%;"
                src="astronomy.png" alt="logo"></a></div>
    <div>
        <div class="position-absolute mt-2 w-25 text-center dropdown end-0" style="top:0;right:0;">
            <a href="profile.php" style="text-decoration:none;font-family:'Courier New', Courier, monospace;">
                <img <?php echo 'src="' . $profilePhoto . '"' ?> class=" border border-dark border-opacity-25 border-5"
                    alt="123" style="border-radius:50%;width:6.5rem;;height:6.5rem;" />
                <p class="text-light text-center">
                    <?php echo $loggedInUsername ?>
                </p>
            </a>
            <a href="profile.php"><button class="btn btn-outline-light mt-2 dropdown-content"
                    style="font-size:12.5px;width:32%;">Profile</button></a>
            <br>
            <button class="btn btn-outline-light mt-2 dropdown-content"
                style="font-size:12.5px;width:32%;">Settings</button>
            <br>
            <form method="post" action=""><button type="submit" name="logout"
                    class="btn btn-outline-light mt-2 dropdown-content"
                    style="font-size:12.5px;width:32%;">Logout</button>
            </form>

        </div>
        <div class="top-0 start-50 position-absolute translate-middle-x mt-2 text-center">
            <input type="image" class="rounded-circle mx-2 border border-black" style="width:6.5rem;height:6.5rem;"
                <?php echo 'src="' . $clickedProfilePhoto . '"' ?>>

            <br>
            <p class="h3 text-light" style="font-family: system-ui;">
                <?php echo '' . $clickedUsername . '' ?>
            </p>

            <a href="" style="text-decoration: none;">
                <p class="h5 text-white-50 mt-1">Followers : 60M</p>
            </a>
            <a href="" style="text-decoration: none;">
                <p class="h5 text-white-50">Following : 671</p>
            </a>
            <p class="h5 text-light" style="font-family:Gill Sans, sans-serif;">
                <?php echo '' . $clickedBiography . '' ?>
            </p>


            <button type="submit" name="follow" id="followButton" class="w-25 btn btn-primary">Follow</button>
            <br>
            <br>



            <div class="scrollable-container w-100 mt-1">
                <?php foreach ($clickedUserPosts as $post): ?>
                    <input type="image" class="w-25 rounded-1 border-black imghoverprofile"
                        src="data/posts/<?php echo $post['photo']; ?>" style="height:12rem;">
                <?php endforeach; ?>
            </div>
        </div>

    </div>
    <div class="top-50 start-0 translate-middle-y mx-1" style="width:24%;margin-top:1%;position: fixed;">
        <a href="Reels.php"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover "
                style="margin-left: 50%;" src="telescope.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Reels"></a>
        <a href="trends.php"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="comet.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Trends"></a>
        <a href=""><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="bootes.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Groups"></a>
        <a href=""><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="earth.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Languages"></a>
        <a href="information.php"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="saturn.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="İnformation"></a>
    </div>


    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</html>