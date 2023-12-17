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
            $banned = $row['banned'];

            if ($banned == 1) {
                header("Location: banned.php");
                exit();
            }
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

    if (isset($_GET['username'])) {
        $clickedUsername = $_GET['username'];

        try {
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
} else {
    header("Location:login.php");
    exit();
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
        .dropdown {
            display: inline-block;
        }

        .dropdown-content {

            visibility: hidden;



        }

        .dropdown:hover .dropdown-content {

            visibility: visible;
        }

        .scrollable-container {
            width: 20%;
            right: 0;
            height: 200px;
            overflow-y: auto;
            margin-right: 16%;
            margin-top: -6%;
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }

        .scrollable-container::-webkit-scrollbar {
            width: 6px;

        }

        .scrollable-container::-webkit-scrollbar-thumb {}

        .responsivelogo {
            width: 6%;
        }

        .responsivepages {
            position: fixed;
            margin-top: 1%;
            width: 24%;
        }

        .responsivepagelogos {
            margin-left: 50%;
        }

        .responsivepost {
            margin-left: 38%;
        }

        .responsivedropdowncontainer {
            width: 25%;
        }

        .responsivedropdownpp {
            border-radius: 50%;
            width: 6.5rem;
            height: 6.5rem;
        }

        .responsivepostphoto {
            height: 18rem;
        }

        .responsiveposter {}

        .responsivepostimage {
            width: 4rem;
            height: 4rem;
            font-family: "Lucida Console", "Courier New", monospace;
        }

        .profilebuttons {
            font-size: 12.5px;
            width: 32%;
        }

        .responsivephotobutton {
            width: 4%;
            position: fixed;
            margin-top: -5%;
            opacity: 75%;
        }

        .responsivephotobutton2 {
            width: 6%;
            position: fixed;
            margin-top: -10.5%;
            opacity: 85%;
        }

        // 600px //
        @media only screen and (max-width: 600px) {
            .responsivepagelogos {
                margin-left: 30%;
            }

            .responsivepages {
                margin-top: -21%;
                width: 34%;

            }

            .responsivelogo {
                width: 9%;
                margin-left: -1.5%;
            }

            .responsivesearch {
                width: 125%;
                margin-left: -12%;
            }

            .responsivepost {
                margin-left: 25%;
            }

            .responsivepostimage {
                width: 1.5rem;
                height: 1.5rem;
                font-family: "Lucida Console", "Courier New", monospace;
            }

            .responsivepostpp {}

            responsivedropdowncontainer {
                width: ;
            }

            .responsivedropdownpp {
                border-radius: 50%;
                width: 3.5rem;
                height: 3.5rem;

            }

            .responsivepostphoto {
                height: 18rem;
                width: 100%;
            }

            .responsivecardpost {
                width: 200%;
            }

            .dropdown-content {

                visibility: visible;


            }

            .profilebuttons {
                font-size: 12.5px;
                width: 48%;
            }
        }

        @media only screen and (max-width: 420px) {
            .responsivepagelogos {
                margin-left: 7%;
            }

            .responsivepages {
                margin-top: -54%;
                width: 45%;

            }

            .responsivelogo {
                width: 12%;
                margin-left: -8.5%;
            }

            .responsivesearch {
                width: 165%;
                margin-left: -40%;
            }

            .responsivepost {
                margin-left: 20%;
            }

            .responsivepostimage {
                width: 1.5rem;
                height: 1.5rem;
                font-family: "Lucida Console", "Courier New", monospace;
            }

            .responsivepostpp {}

            responsivedropdowncontainer {
                width: ;
            }

            .responsivedropdownpp {
                border-radius: 50%;
                width: 4rem;
                height: 4rem;

            }

            .responsivepostphoto {
                height: 18rem;
                width: 100%;
            }

            .responsivecardpost {
                width: 220%;
            }

            .dropdown-content {

                visibility: visible;


            }

            .profilebuttons {
                font-size: 12.5px;
                width: 68%;
                margin-left: -13%;
            }

            .responsivephotobutton {
                width: 16%;
                position: fixed;
                margin-top: -15%;
                opacity: 75%;
            }

            .responsivephotobutton2 {
                width: 20%;
                position: fixed;
                margin-top: -40%;
                opacity: 85%;
            }
        }

        @media only screen and (max-width: 380px) {
            .responsivepagelogos {
                margin-left: 6.5%;
            }

            .responsivepages {
                margin-top: -34%;
                width: 45%;

            }

            .responsivelogo {
                width: 12%;
                margin-left: -8.5%;
            }

            .responsivesearch {
                width: 165%;
                margin-left: -40%;
            }

            .responsivepost {
                margin-left: 20%;
            }

            .responsivepostimage {
                width: 1.5rem;
                height: 1.5rem;
                font-family: "Lucida Console", "Courier New", monospace;
            }

            .responsivepostpp {}

            responsivedropdowncontainer {
                width: ;
            }

            .responsivedropdownpp {
                border-radius: 50%;
                width: 4rem;
                height: 4rem;

            }

            .responsivepostphoto {
                height: 18rem;
                width: 100%;
            }

            .responsivecardpost {
                width: 220%;
            }

            .dropdown-content {

                visibility: visible;


            }

            .profilebuttons {
                font-size: 12.5px;
                width: 68%;
                margin-left: -13%;
            }

            .responsivephotobutton {
                width: 16%;
                position: fixed;
                margin-top: -15%;
                opacity: 75%;
            }

            .responsivephotobutton2 {
                width: 20%;
                position: fixed;
                margin-top: -40%;
                opacity: 85%;
            }
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
                <p class="h5 text-white-50 mt-1">Followers:
                    <?php
                    try {
                        $query_followers_count = "SELECT followers FROM user WHERE username = :username";
                        $stmt_followers_count = $dbh->prepare($query_followers_count);
                        $stmt_followers_count->bindParam(':username', $clickedUsername);
                        $stmt_followers_count->execute();

                        $row_followers_count = $stmt_followers_count->fetch(PDO::FETCH_ASSOC);
                        if ($row_followers_count) {
                            $followers_count = count(explode(',', $row_followers_count['followers']));
                            echo $followers_count;
                        }
                    } catch (PDOException $e) {
                        echo "Bağlantı Hatası: " . $e->getMessage();
                    }
                    ?>
                </p>
            </a>
            <a href="" style="text-decoration: none;">
                <p class="h5 text-white-50">Following:
                    <?php
                    try {
                        $query_following_count = "SELECT following FROM user WHERE username = :username";
                        $stmt_following_count = $dbh->prepare($query_following_count);
                        $stmt_following_count->bindParam(':username', $clickedUsername);
                        $stmt_following_count->execute();

                        $row_following_count = $stmt_following_count->fetch(PDO::FETCH_ASSOC);
                        if ($row_following_count) {
                            $following_count = count(explode(',', $row_following_count['following']));
                            echo $following_count;
                        }
                    } catch (PDOException $e) {
                        echo "Bağlantı Hatası: " . $e->getMessage();
                    }
                    ?>
                </p>
            </a>

            <p class="h5 text-light" style="font-family:Gill Sans, sans-serif;">
                <?php echo '' . $clickedBiography . '' ?>
            </p>

            <form method="post">
                <button type="submit" name="follow" id="followButton"
                    class="w-50 btn btn-outline-primary">Follow</button>
                <button type="submit" name="unfollow" id="followButton"
                    class="w-50 btn btn-outline-danger mt-1">Unfollow</button>
            </form>
            <br>
            <br>



            <div class="scrollable-container w-100 mt-1">
                <?php foreach ($clickedUserPosts as $post): ?>
                    <img class="rounded-1 border-black imghoverprofile" src="data/posts/<?php echo $post['photo']; ?>"
                        style="height:16rem;width:14rem;" onclick="showImage(this);">
                <?php endforeach; ?>
            </div>

            <div id="myModal" class="modal">
                <span class="close" onclick="closeModal()">&times;</span>
                <img class="modal-content" id="modalImg">
            </div>

        </div>

    </div>
    <div class="top-50 start-0 translate-middle-y mx-1" style="width:24%;margin-top:1%;position: fixed;">
        <a href="Explore"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos "
                style="" src="telescope.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Explore"></a>
        <a href="Random"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="comet.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Random Match"></a>
        <a href="following.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="bootes.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Following"></a>
        <a href="world.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="earth.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="World"></a>
        <a href="information"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="saturn.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
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
<script>
    function showImage(img) {
        var modal = document.getElementById('myModal');
        var modalImg = document.getElementById('modalImg');

        modal.style.display = 'block';
        modalImg.src = img.src;
    }

    function closeModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = 'none';
    }

</script>
<?php
session_start();
include 'connect.php';


if (isset($_POST['follow'])) {
    $clickedUsername = $_GET['username'];

    try {
        $query_follow = "SELECT followers FROM user WHERE username = :username";
        $stmt_follow = $dbh->prepare($query_follow);
        $stmt_follow->bindParam(':username', $clickedUsername);
        $stmt_follow->execute();

        $row = $stmt_follow->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $followers = $row['followers'];

            $followersArray = explode(',', $followers);
            if (!in_array($loggedInUsername, $followersArray)) {
                $followers .= $loggedInUsername . ',';
            }

            $update_query = "UPDATE user SET followers = :followers WHERE username = :username";
            $stmt_update = $dbh->prepare($update_query);
            $stmt_update->bindParam(':followers', $followers);
            $stmt_update->bindParam(':username', $clickedUsername);
            $stmt_update->execute();

            header("Location:https://egoistsky.free.nf/egoist?username=$clickedusername");

            $query_following = "SELECT following FROM user WHERE username = :username";
            $stmt_following = $dbh->prepare($query_following);
            $stmt_following->bindParam(':username', $loggedInUsername);
            $stmt_following->execute();

            $row_following = $stmt_following->fetch(PDO::FETCH_ASSOC);
            if ($row_following) {
                $following = $row_following['following'];

                $followingArray = explode(',', $following);
                if (!in_array($clickedUsername, $followingArray)) {
                    $following .= $clickedUsername . ',';
                }

                $update_following_query = "UPDATE user SET following = :following WHERE username = :username";
                $stmt_update_following = $dbh->prepare($update_following_query);
                $stmt_update_following->bindParam(':following', $following);
                $stmt_update_following->bindParam(':username', $loggedInUsername);
                $stmt_update_following->execute();
            }
        } else {
            echo "Kullanıcı bulunamadı veya bağlantı hatası";
        }
    } catch (PDOException $e) {
        echo "Bağlantı Hatası: " . $e->getMessage();
    }
}

if (isset($_POST['unfollow'])) {
    $clickedUsername = $_GET['username'];

    try {
        $query_follow = "SELECT followers FROM user WHERE username = :username";
        $stmt_follow = $dbh->prepare($query_follow);
        $stmt_follow->bindParam(':username', $clickedUsername);
        $stmt_follow->execute();

        $row = $stmt_follow->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $followers = $row['followers'];

            $followersArray = explode(',', $followers);
            $key = array_search($loggedInUsername, $followersArray);
            if ($key !== false) {
                unset($followersArray[$key]);
                $followers = implode(',', $followersArray);
            }

            $update_query = "UPDATE user SET followers = :followers WHERE username = :username";
            $stmt_update = $dbh->prepare($update_query);
            $stmt_update->bindParam(':followers', $followers);
            $stmt_update->bindParam(':username', $clickedUsername);
            $stmt_update->execute();

            header("Location:https://egoistsky.free.nf/egoist?username=$clickedusername");

            $query_following = "SELECT following FROM user WHERE username = :username";
            $stmt_following = $dbh->prepare($query_following);
            $stmt_following->bindParam(':username', $loggedInUsername);
            $stmt_following->execute();

            $row_following = $stmt_following->fetch(PDO::FETCH_ASSOC);
            if ($row_following) {
                $following = $row_following['following'];

                $followingArray = explode(',', $following);
                $key_following = array_search($clickedUsername, $followingArray);
                if ($key_following !== false) {
                    unset($followingArray[$key_following]);
                    $following = implode(',', $followingArray);
                }

                $update_following_query = "UPDATE user SET following = :following WHERE username = :username";
                $stmt_update_following = $dbh->prepare($update_following_query);
                $stmt_update_following->bindParam(':following', $following);
                $stmt_update_following->bindParam(':username', $loggedInUsername);
                $stmt_update_following->execute();
            }
        } else {
            echo "Kullanıcı bulunamadı veya bağlantı hatası";
        }
    } catch (PDOException $e) {
        echo "Bağlantı Hatası: " . $e->getMessage();
    }
}



?>

</html>