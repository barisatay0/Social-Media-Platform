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
            height: 24rem;
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
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder "
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle responsivelogo" style=""
                src="astronomy.png" alt="logo"></a></div>
    <div class="position-absolute top-0 start-50 translate-middle mt-4" style="width:33%;">
        <form name="searcher" method="post" action="search.php">
            <input type="search" id="searchInput" name="search" placeholder="Search..."
                class="form-control responsivesearch">
        </form>
        <div id="searchResults"></div>
    </div>

    <div class="top-50 start-0 translate-middle-y mx-1 responsivepages">
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
    <div class="position-absolute mt-3 text-center dropdown end-0 responsivedropdowncontainer" style="top:0;right:0;">
        <a href="profile.php" style="text-decoration:none;font-family:'Courier New', Courier, monospace;">
            <img <?php echo 'src="' . $profilePhoto . '"' ?>
                class=" border border-dark border-opacity-25 border-5 responsivedropdownpp" alt="123" style="" />
            <p class="text-light text-center">
                <?php echo $username; ?>
            </p>
        </a>
        <a href="profile.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Profile</button></a>
        <br>
        <button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Settings</button>
        <br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Logout</button>
        </form>

    </div>
    <div class="scrollable-container w-100 mt-1 responsiveposter" style="overflow-y:auto;height:40rem;">
        <?php
        include 'connect.php';

        try {
            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $username = $_SESSION['username'];
            $queryFollowing = "SELECT following FROM user WHERE username = :username";
            $stmtFollowing = $dbh->prepare($queryFollowing);
            $stmtFollowing->bindParam(':username', $username);
            $stmtFollowing->execute();

            $followingRow = $stmtFollowing->fetch(PDO::FETCH_ASSOC);

            if ($followingRow) {
                $followingUsersString = $followingRow['following'];
                $followingUsers = explode(',', $followingUsersString);

                $placeholders = str_repeat('?,', count($followingUsers) - 1) . '?';
                $queryPosts = "SELECT * FROM post WHERE username IN ($placeholders) ORDER BY time DESC";
                $stmtPosts = $dbh->prepare($queryPosts);
                $stmtPosts->execute($followingUsers);

                while ($row = $stmtPosts->fetch(PDO::FETCH_ASSOC)) {
                    $userQuery = "SELECT profilephoto FROM user WHERE username = '" . $row["username"] . "'";
                    $userStmt = $dbh->query($userQuery);
                    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);
                    $postId = $row["postid"];
                    $username = $_SESSION['username'];

                    $checkQuery = "SELECT liked, unliked FROM post WHERE postid = '$postId'";
                    $checkStmt = $dbh->query($checkQuery);
                    $likesInfo = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    $likedUsers = $likesInfo['liked'];
                    $unlikedUsers = $likesInfo['unliked'];
                    if (isset($_POST['liked'])) {
                        if (!empty($likedUsers) && strpos($likedUsers, $username) !== false) {
                            echo "Bu gönderiyi zaten beğendiniz.";
                        } else {
                            $updateQuery = "UPDATE post SET liked = CONCAT(IFNULL(liked, ''), '$username,') WHERE postid = '$postId'";
                            $dbh->query($updateQuery);
                        }
                    }

                    if (isset($_POST['unliked'])) {
                        if (!empty($likedUsers) && strpos($likedUsers, $username) !== false) {
                            $newLikedUsers = str_replace("$username,", "", $likedUsers);
                            $updateQuery = "UPDATE post SET liked = '$newLikedUsers' WHERE postid = '$postId'";
                            $dbh->query($updateQuery);
                        }

                        if (!empty($unlikedUsers) && strpos($unlikedUsers, $username) !== false) {
                            echo "Bu gönderiyi zaten beğenmediniz.";
                        } else {
                            $updateQuery = "UPDATE post SET unliked = CONCAT(IFNULL(unliked, ''), '$username,') WHERE postid = '$postId'";
                            $dbh->query($updateQuery);
                        }
                    }
                    echo '
            <div class="w-25 post responsivepost">
            
                <div class="card post border border-dark text-white responsivecardpost">
                <div class="mt-2 mx-2">
                <a class="text-light h3" style="text-decoration:none;" href="https://egoistsky.free.nf/egoist?username=' . $row["username"] . '"><img src="' . $userRow["profilephoto"] . '" class="rounded-circle mx-1 responsivepostimage" style="">' . $row["username"] . '</a>
                </div>
                
                <br>
                    <img src="data/posts/' . $row["photo"] . '" class="card-img-top responsivepostphoto" alt="...">
                    <div class="card-body border border-dark" style="background-color:black;">
                        

                        <p class="card-text">' . $row["description"] . '</p>
                        <br>
                        <p class="card-text"><small class="text-white-50">' . $row["time"] . '</small></p>
<form method="post" action="">
                <input type="hidden" name="postid" value="' . $row["postid"] . '">
                <input type="submit" class="mt-2 imghover btn btn-outline-success w-25" name="liked" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like" value="Like">
                <input type="submit" class="mt-2 mx-1 imghover btn btn-outline-danger w-25" name="unliked" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike" value="Unlike">
            </form>
                    </div>
                </div>
            </div>
            <br>';
                }
            } else {
                echo '<p class="text-white">Takip edilen kullanıcı bulunamadı</p>';
            }
        } catch (PDOException $e) {
            echo '<p class="text-white">Bağlantı hatası:</p> ' . $e->getMessage();
        }
        ?>
        <br>
    </div>
    <div>
        <input type="image" class="top-100 end-0 translate-middle-y mx-4 imghover responsivephotobutton" style=""
            src="bubble.png">

        <input id="formOpener" type="image"
            class="top-100 end-0 translate-middle-y mx-3 imghover responsivephotobutton2" style="" src="picture.png">
    </div>
    <div class="w-50 border bg-black rounded-5 light border-dark position-absolute top-50 start-50 translate-middle text-center"
        id="hiddenForm" style="display: none;--bs-bg-opacity: .9;height:74%;">
        <p class="h1 text-light mt-5" style="font-family:Fantasy;">Post</p>
        <form method="POST" action="https://egoistsky.free.nf/upload" id="myForm" class="mt-3"
            enctype="multipart/form-data">
            <input class="btn btn-outline-light w-75 mt-3" type="file" name="fileToUpload" required>
            <br>
            <textarea class="mt-4 w-75" name="description" placeholder="Description" id="description"
                style="border-radius: 3%; height: 5rem;"></textarea>
            <br>
            <input class="btn btn-outline-success w-75 mt-4" name="share" type="submit" value="Share"
                style="font-family:Fantasy;">
        </form>
        <button id="formCloser" class="w-25 mt-4 btn btn-outline-danger" style="font-family:Fantasy;">close</button>
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
<script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    searchInput.addEventListener('input', function () {
        const searchValue = this.value;
        if (searchValue === '') {
            searchResults.innerHTML = '';
            return;
        }
        fetch(`search.php?search_query=${searchValue}`)
            .then(response => response.text())
            .then(data => {
                searchResults.innerHTML = data;
            })
            .catch(error => {
                console.error('Arama hatası:', error);
            });
    });
</script>
<script>
    document.getElementById('formOpener').onclick = function () {
        document.getElementById('hiddenForm').style.display = 'block';
    };
    document.getElementById('formCloser').onclick = function () {
        document.getElementById('hiddenForm').style.display = 'none';
    };
</script>

</html>
<?php
include 'connect.php';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM user WHERE username LIKE '%$search%'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Sorgu hatası: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['username'] . "<br>";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}
?>