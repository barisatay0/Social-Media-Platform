<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    try {
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $profilePhoto = $row['profilephoto'];
            $banned = $row['banned'];

            if ($banned == 1) {
                header("Location: banned.php");
                exit();
            }

            $queryUserRole = "SELECT role FROM roles WHERE id = :id";
            $stmtUserRole = $dbh->prepare($queryUserRole);
            $stmtUserRole->bindParam(':id', $row['id']);
            $stmtUserRole->execute();
            $userRole = $stmtUserRole->fetch(PDO::FETCH_ASSOC)['role'];

            if ($userRole !== 'admin' && $userRole !== 'moderator') {
                header("Location:user.php");
                exit();
            }

        } else {
            echo "Data not found or connection error";
        }
    } catch (PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
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
$queryUsers = "SELECT COUNT(*) as userCount FROM roles WHERE role = 'user'";
$stmtUsers = $dbh->prepare($queryUsers);
$stmtUsers->execute();
$userCount = $stmtUsers->fetch(PDO::FETCH_ASSOC)['userCount'];

$queryAdmins = "SELECT COUNT(*) as adminCount FROM roles WHERE role = 'admin'";
$stmtAdmins = $dbh->prepare($queryAdmins);
$stmtAdmins->execute();
$adminCount = $stmtAdmins->fetch(PDO::FETCH_ASSOC)['adminCount'];

$queryModerators = "SELECT COUNT(*) as moderatorCount FROM roles WHERE role = 'moderator'";
$stmtModerators = $dbh->prepare($queryModerators);
$stmtModerators->execute();
$moderatorCount = $stmtModerators->fetch(PDO::FETCH_ASSOC)['moderatorCount'];

$queryPosts = "SELECT COUNT(*) as postCount FROM post";
$stmtPosts = $dbh->prepare($queryPosts);
$stmtPosts->execute();
$postCount = $stmtPosts->fetch(PDO::FETCH_ASSOC)['postCount'];

$queryUsersData = "SELECT * FROM user";
$stmtUsersData = $dbh->prepare($queryUsersData);
$stmtUsersData->execute();
$usersData = $stmtUsersData->fetchAll(PDO::FETCH_ASSOC);

$queryUserRole = "SELECT role FROM roles WHERE id = :id";
$stmtUserRole = $dbh->prepare($queryUserRole);
$stmtUserRole->bindParam(':id', $userId);
$stmtUserRole->execute();
$userRole = $stmtUserRole->fetch(PDO::FETCH_ASSOC)['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $head = $_POST['head'];
    $content = $_POST['content'];
    $url = $_POST['url'];

    $targetDirectory = "data/explore/";
    $targetFile = $targetDirectory . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sadece JPG, JPEG, PNG & GIF dosya formatları yüklenebilir.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Dosyanız yüklenemedi.";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            echo "Dosya " . htmlspecialchars(basename($_FILES["file"]["name"])) . " başarıyla yüklendi.";


            $query = "INSERT INTO explore (photo, head, content, url) VALUES (:photo, :head, :content, :url)";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':photo', $targetFile);
            $stmt->bindParam(':head', $head);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':url', $url);
            $stmt->execute();

            header("Location: success.php");
        } else {
            echo "Dosya yüklenirken bir hata oluştu.";
        }
    }
}
$queryExplore = "SELECT * FROM explore";
$stmtExplore = $dbh->prepare($queryExplore);
$stmtExplore->execute();
$exploreData = $stmtExplore->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete_explore'])) {
    $explore_id = $_POST['explore_id'];

    $deleteQuery = "DELETE FROM explore WHERE id = :explore_id";
    $stmt = $dbh->prepare($deleteQuery);
    $stmt->bindParam(':explore_id', $explore_id);
    $stmt->execute();


    header("Location: success.php");
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
                Page</button></a>
        <br>
        <a href="moderator.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">moderator Page</button></a>
        <br>
        <a href="manageexplore.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Manage Explore</button></a>
        <br>
        <a href="posttable.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Post
                Table</button></a>
        <br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Logout</button>
        </form>

    </div>
    <div class="text-center text-white" style="margin-left:30%;width:45%;">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">photo</label>
                <input type="file" name="file" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">head</label>
                <input type="text" name="head" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">content</label>
                <input type="text" name="content" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">url</label>
                <input type="text" name="url" class="form-control" id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-success w-50">Share Explore Content</button>
        </form>
        <br>
        <table class="table table-hover text-center">
            <thead>
                <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">head</th>
                    <th scope="col">content</th>
                    <th scope="col">url</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exploreData as $explore): ?>
                    <tr>
                        <td class="w-25"><img src="<?php echo $explore['photo']; ?>" class="w-100"></td>
                        <td>
                            <?php echo $explore['head']; ?>
                        </td>
                        <td>
                            <?php echo $explore['content']; ?>
                        </td>
                        <td>
                            <?php echo $explore['url']; ?>
                        </td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="explore_id" value="<?php echo $explore['id']; ?>">
                                <button type="submit" name="delete_explore" class="btn btn-danger">Delete</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


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

</html>