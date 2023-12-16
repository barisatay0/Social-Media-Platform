<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $currentDate = date('Y-m-d');

    try {
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalLoginsQuery = "SELECT COUNT(*) AS total_logins FROM user_logs";
        $stmtTotalLogins = $dbh->prepare($totalLoginsQuery);
        $stmtTotalLogins->execute();
        $totalLogins = $stmtTotalLogins->fetch(PDO::FETCH_ASSOC)['total_logins'];

        $todayLoginsQuery = "SELECT COUNT(*) AS today_logins FROM user_logs WHERE DATE(login_time) = :currentDate";
        $stmtTodayLogins = $dbh->prepare($todayLoginsQuery);
        $stmtTodayLogins->bindParam(':currentDate', $currentDate);
        $stmtTodayLogins->execute();
        $todayLogins = $stmtTodayLogins->fetch(PDO::FETCH_ASSOC)['today_logins'];
        if ($row) {
            $profilePhoto = $row['profilephoto'];
        } else {
            echo "Data not found or connection error";
        }
    } catch (PDOException $e) {
        echo "Bağlantı Hatası: " . $e->getMessage();
    }
} else {
    header("Location: login");
    exit();
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$userId = $row['id'];

$queryUserRole = "SELECT role FROM roles WHERE id = :id";
$stmtUserRole = $dbh->prepare($queryUserRole);
$stmtUserRole->bindParam(':id', $userId);
$stmtUserRole->execute();
$userRole = $stmtUserRole->fetch(PDO::FETCH_ASSOC)['role'];

if ($userRole !== 'admin' && $userRole !== 'moderator') {
    header("Location:user.php");
    exit();
}

$userLogsQuery = "SELECT * FROM user_logs";
$stmtUserLogs = $dbh->prepare($userLogsQuery);
$stmtUserLogs->execute();
$userLogs = $stmtUserLogs->fetchAll(PDO::FETCH_ASSOC);
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



<body class="bg-dark">
    <a href="" class="mx-3 mt-2"></a>
    <div><a href="https://egoistsky.free.nf/user"
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder "
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle responsivelogo" style=""
                src="astronomy.png" alt="logo"></a></div>
    </div>
    <div class="position-absolute mt-3 text-center dropdown end-0 responsivedropdowncontainer" style="top:0;right:0;">
        <a href="profile.php" style="text-decoration:none;font-family:'Courier New', Courier, monospace;">
            <img <?php echo 'src="' . $profilePhoto . '"' ?>
                class=" border border-dark border-opacity-25 border-5 responsivedropdownpp" alt="123" style="" />
            <p class="text-light text-center">
                Moderator Name :
                <?php echo $username; ?>
            </p>
        </a>
        <a href="profile.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Profile</button></a>

        <br>

        <a href="admin.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Admin
                Page</button></a><br>
        <a href="moderator.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Moderator
                Page</button></a><br>
        <a href="manageexplore.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Manage Explore</button></a>
        <br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Logout</button>
        </form>

    </div>
    <table class="table w-50 text-light position-absolute translate-middle start-50">
        <thead>
            <tr>
                <th scope="col">Photo</th>
                <th scope="col">Description</th>
                <th scope="col">Username</th>
                <th scope="col">Time</th>
                <th scope="col">Delete Post</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $postQuery = "SELECT * FROM post";
            $stmtPosts = $dbh->prepare($postQuery);
            $stmtPosts->execute();
            $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);


            foreach ($posts as $post) {
                echo '<tr>';
                echo '<td class="w-25"><img class="w-75" src="data/posts/' . $post['photo'] . '" alt="Post Photo"></td>';
                echo '<td>' . $post['description'] . '</td>';
                echo '<td>' . $post['username'] . '</td>';
                echo '<td>' . $post['time'] . '</td>';
                echo '<td>
                        <form action="" method="post">
                            <input type="hidden" name="postid" value="' . $post['postid'] . '">
                            <button class="btn btn-danger" type="submit" name="deleteButton">Delete Post</button>
                        </form>
                    </td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
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

</html>
<?php
if (isset($_POST['deleteButton'])) {
    $postId = $_POST['postid'];


    $getPhotoQuery = "SELECT photo FROM post WHERE postid = :postid";
    $stmtGetPhoto = $dbh->prepare($getPhotoQuery);
    $stmtGetPhoto->bindParam(':postid', $postId);
    $stmtGetPhoto->execute();
    $photoName = $stmtGetPhoto->fetch(PDO::FETCH_ASSOC)['photo'];


    $photoPath = 'data/posts/' . $photoName;
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }

    $deleteQuery = "DELETE FROM post WHERE postid = :postid";
    $stmtDelete = $dbh->prepare($deleteQuery);
    $stmtDelete->bindParam(':postid', $postId);


    if ($stmtDelete->execute()) {
        echo '<script>alert("Post successfully deleted!");</script>';


    } else {
        echo '<script>alert("Failed to delete post!");</script>';
    }
}
?>