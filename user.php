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
                header("Location:banned.php");
                exit();
            }
        } else {
            echo "Data not found or connection error";
        }

    } catch (PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
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
if (isset($_POST['likeAction'])) {
    $postID = $_POST['postid'];
    $likeAction = $_POST['likeAction'];
    $currentUser = $_SESSION['username'];

    $likeQuery = "SELECT * FROM likes WHERE postid = :postid AND username = :username";
    $likeStmt = $dbh->prepare($likeQuery);
    $likeStmt->bindParam(':postid', $postID);
    $likeStmt->bindParam(':username', $currentUser);
    $likeStmt->execute();
    $existingLike = $likeStmt->fetch(PDO::FETCH_ASSOC);

    if ($likeAction === 'like') {
        if ($existingLike) {
            if ($existingLike['unliked'] == 1) {
                $updateQuery = "UPDATE likes SET liked = 1, unliked = 0 WHERE postid = :postid AND username = :username";
                $updateStmt = $dbh->prepare($updateQuery);
                $updateStmt->bindParam(':postid', $postID);
                $updateStmt->bindParam(':username', $currentUser);
                $updateStmt->execute();
            }
        } else {
            $insertQuery = "INSERT INTO likes (postid, username, liked, unliked) VALUES (:postid, :username, 1, 0)";
            $insertStmt = $dbh->prepare($insertQuery);
            $insertStmt->bindParam(':postid', $postID);
            $insertStmt->bindParam(':username', $currentUser);
            $insertStmt->execute();
        }
    } elseif ($likeAction === 'unlike') {
        if ($existingLike) {
            if ($existingLike['liked'] == 1) {
                $updateQuery = "UPDATE likes SET liked = 0, unliked = 1 WHERE postid = :postid AND username = :username";
                $updateStmt = $dbh->prepare($updateQuery);
                $updateStmt->bindParam(':postid', $postID);
                $updateStmt->bindParam(':username', $currentUser);
                $updateStmt->execute();
            }
        } else {
            $insertQuery = "INSERT INTO likes (postid, username, liked, unliked) VALUES (:postid, :username, 0, 1)";
            $insertStmt = $dbh->prepare($insertQuery);
            $insertStmt->bindParam(':postid', $postID);
            $insertStmt->bindParam(':username', $currentUser);
            $insertStmt->execute();
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];
    $username = $_SESSION['username'];

    try {
        $insertQuery = "INSERT INTO globalchat (username, message) VALUES (:username, :message)";
        $insertStmt = $dbh->prepare($insertQuery);
        $insertStmt->bindParam(':username', $username);
        $insertStmt->bindParam(':message', $message);
        $insertStmt->execute();

    } catch (PDOException $e) {
        echo "Message Error: " . $e->getMessage();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['privatemessage'])) {
    $message = $_POST['privatemessage'];
    $sender = $_SESSION['username'];
    $recipient = $_POST['recipient'];
    try {
        $insertQuery = "INSERT INTO chat (sender ,recipient, message) VALUES (:sender,:recipient, :message)";
        $insertStmt = $dbh->prepare($insertQuery);
        $insertStmt->bindParam(':sender', $sender);
        $insertStmt->bindParam(':recipient', $recipient);
        $insertStmt->bindParam(':message', $message);
        $insertStmt->execute();

    } catch (PDOException $e) {
        echo "Message Error: " . $e->getMessage();
    }
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
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle responsivelogo"
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
                src="telescope.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Explore"></a>
        <a href="Random"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos"
                src="comet.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Random Match"></a>
        <a href="following.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos"
                src="bootes.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Following"></a>
        <a href="world.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos"
                src="earth.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="World"></a>
        <a href="information"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos"
                src="saturn.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="İnformation"></a>
    </div>
    <div class="position-absolute mt-3 text-center dropdown end-0 responsivedropdowncontainer" style="top:0;right:0;">
        <a href="profile.php" style="text-decoration:none;font-family:'Courier New', Courier, monospace;">
            <img <?php echo 'src="' . $profilePhoto . '"' ?>
                class=" border border-dark border-opacity-25 border-5 responsivedropdownpp" alt="123" />
            <p class="text-light text-center">
                <?php echo $username; ?>
            </p>
        </a>
        <a href="profile.php"><button
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons">Profile</button></a>
        <br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons">Logout</button>
        </form>

    </div>
    <div class="scrollable-container w-100 mt-1 responsiveposter" style="overflow-y:auto;height:40rem;">
        <?php
        include 'connect.php';

        try {
            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $postQuery = "SELECT * FROM post ORDER BY time DESC";
            $postStmt = $dbh->query($postQuery);

            if ($postStmt) {
                while ($row = $postStmt->fetch(PDO::FETCH_ASSOC)) {
                    $userQuery = "SELECT profilephoto FROM user WHERE username = '" . $row["username"] . "'";
                    $userStmt = $dbh->query($userQuery);
                    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

                    $likeCountQuery = "SELECT COUNT(*) AS likeCount FROM likes WHERE postid = :postid AND liked = 1";
                    $likeCountStmt = $dbh->prepare($likeCountQuery);
                    $likeCountStmt->bindParam(':postid', $row["postid"]);
                    $likeCountStmt->execute();
                    $likeCountRow = $likeCountStmt->fetch(PDO::FETCH_ASSOC);

                    $unlikeCountQuery = "SELECT COUNT(*) AS unlikeCount FROM likes WHERE postid = :postid AND unliked = 1";
                    $unlikeCountStmt = $dbh->prepare($unlikeCountQuery);
                    $unlikeCountStmt->bindParam(':postid', $row["postid"]);
                    $unlikeCountStmt->execute();
                    $unlikeCountRow = $unlikeCountStmt->fetch(PDO::FETCH_ASSOC);

                    echo '
            <div class="w-25 post responsivepost">
                <div class="card post border border-dark text-white responsivecardpost">
                    <img src="data/posts/' . $row["photo"] . '" class="card-img-top responsivepostphoto" alt="...">
                    <div class="card-body border border-dark" style="background-color:black;">
                        <div class="mt-2 mx-2">
                            <a class="text-light h4" style="text-decoration:none;" href="https://egoistsky.free.nf/egoist?username=' . $row["username"] . '">
                                <img src="' . $userRow["profilephoto"] . '" class="rounded-circle mx-1 responsivepostimage" >
                                ' . $row["username"] . '
                            </a>
                        </div>
                        <br>
                        <p class="card-text">' . $row["description"] . '</p>
                        <br>
                        <p class="card-text"><small class="text-white-50">' . $row["time"] . '</small></p>
                        
                        <form method="post" action="">
                            <input type="hidden" name="postid" value="' . $row["postid"] . '">
                            <button type="submit" class="mt-2 imghover btn btn-outline-success w-25" name="likeAction" value="like">
                                Like
                            </button>
                            <button type="submit" class="mt-2 mx-1 imghover btn btn-outline-danger w-25" name="likeAction" value="unlike">
                                Unlike
                            </button>
                        </form>
                        <p class="card-text"><small class="text-white-50">Likes: ' . $likeCountRow["likeCount"] . '</small></p>
                        <p class="card-text"><small class="text-white-50">Unlikes: ' . $unlikeCountRow["unlikeCount"] . '</small></p>
                    </div>
                </div>
            </div>
            <br>';
                }

            } else {
                echo "Data not found";
            }
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        ?>


        <br>
    </div>
    <div>
        <input type="image" class="top-100 end-0 translate-middle-y mx-4 imghover responsivephotobutton"
            src="bubble.png" id="formOpener2">

        <input id="formOpener" type="image"
            class="top-100 end-0 translate-middle-y mx-3 imghover responsivephotobutton2" src="picture.png">
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

    <div class=" border bg-light rounded-5 light border-dark position-absolute top-50 start-50 translate-middle text-center"
        id="hiddenForm2" style="display: none;--bs-bg-opacity: .9;height:30%;width:30%;">
        <button id="chat" class="w-25 mt-4 btn btn-outline-primary" style="font-family:Fantasy;">Private Chat</button>
        <br>
        <button id="globalchat" class="w-25 mt-4 btn btn-outline-success" style="font-family:Fantasy;">Global
            Chat</button>
        <br>
        <button id="formCloser2" class="w-25 mt-4 btn btn-outline-danger" style="font-family:Fantasy;">close</button>
    </div>
    <div class="w-50 border bg-dark rounded-5 light border-dark position-absolute top-50 start-50 translate-middle text-center"
        id="hiddenForm3" style="display: none;--bs-bg-opacity: .9;height:80%;">
        <input type="text" placeholder="searcher...">
        <div class="" style="height:30rem;overflow-y: auto;">
            <?php
            try {
                $query = "SELECT username FROM user";
                $stmt = $dbh->query($query);

                if ($stmt) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<button name="' . $row["username"] . '" class="chatopener w-25 mt-4 btn btn-outline-light" style="font-family:Fantasy;">' . $row["username"] . '</button>';
                    }
                } else {
                    echo "User not found.";
                }
            } catch (PDOException $e) {
                echo "Connection Error: " . $e->getMessage();
            }
            ?>

        </div>
        <button id="formCloser3" class="w-25 mt-4 btn btn-outline-danger" style="font-family:Fantasy;">close</button>
        <button id="back" class="w-25 mt-4 btn btn-outline-light" style="font-family:Fantasy;">Go Back</button>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8 border bg-light rounded-2 position-absolute top-50 start-50 translate-middle"
                id="hiddenForm4" style="display: none; --bs-bg-opacity: .9; height: 95%;">
                <div class=" rounded-2 mt-1 " style="height: 28rem; overflow-y: auto;">
                    <p class="h3 text-center mt-3">Global Chat</p>

                    <?php
                    try {
                        $query = "SELECT * FROM globalchat ORDER BY time DESC";
                        $stmt = $dbh->query($query);

                        if ($stmt) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '
                            <div class="w-100 text-center border border-dark mt-3">
                                <a class="h5">' . $row["username"] . '</a>
                                <p>' . $row["message"] . '</p>
                                <p class="text-body-tertiary">' . $row["time"] . '</p>
                            </div>';
                            }
                        } else {
                            echo "Data not found";
                        }
                    } catch (PDOException $e) {
                        echo "Connection Error: " . $e->getMessage();
                    }
                    ?>

                </div>
                <div class="mt-3">
                    <form method="post" action="">
                        <input type="text" class="form-control" name="message" placeholder="Type your message here...">
                        <br>
                        <button class="btn btn-dark w-100" type="submit">Send</button>
                    </form>
                    <button id="formCloser4" class="btn btn-outline-danger w-100 mt-3"
                        style="font-family: Fantasy;">Close</button>
                    <button id="back2" class="btn btn-outline-dark w-100 mt-2" style="font-family: Fantasy;">Go
                        Back</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8 border bg-light rounded-2 position-absolute top-50 start-50 translate-middle"
                id="hiddenForm5" style="display: none; --bs-bg-opacity: .9; height: 95%;">
                <div class=" rounded-2 mt-1 " style="height: 28rem; overflow-y: auto;">
                    <p class="h3 text-center mt-3">Private Message Box</p>

                    <?php
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];

                        try {
                            $query = "SELECT * FROM chat WHERE recipient = :username ORDER BY time DESC";
                            $stmt = $dbh->prepare($query);
                            $stmt->bindParam(':username', $username);
                            $stmt->execute();

                            if ($stmt) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '
                <div class="w-100 text-center border border-dark mt-3">
                    <a class="h5">' . $row["sender"] . '</a>
                    <p>' . $row["message"] . '</p>
                    <p class="text-body-tertiary">' . $row["time"] . '</p>
                </div>';
                                }
                            } else {
                                echo "Data not found";
                            }
                        } catch (PDOException $e) {
                            echo "Connection Error: " . $e->getMessage();
                        }
                    } else {
                        echo "User not in the session";
                    }
                    ?>



                </div>
                <div class="mt-3">
                    <form method="post" action="">
                        <input type="text" class="form-control" name="privatemessage"
                            placeholder="Type your message here...">
                        <br>
                        <button class="btn btn-dark w-100" type="submit">Send</button>
                        <input type="hidden" value="" name="recipient">
                    </form>
                    <button id="formCloser5" class="btn btn-outline-danger w-100 mt-3"
                        style="font-family: Fantasy;">Close</button>
                    <button id="back3" class="btn btn-outline-dark w-100 mt-2" style="font-family: Fantasy;">Go
                        Back</button>
                </div>
            </div>
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
                console.error('Search Error:', error);
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
<script>
    document.getElementById('formOpener2').onclick = function () {
        document.getElementById('hiddenForm2').style.display = 'block';
    };
    document.getElementById('formCloser2').onclick = function () {
        document.getElementById('hiddenForm2').style.display = 'none';
    };
</script>
<script>
    document.getElementById('chat').onclick = function () {
        document.getElementById('hiddenForm2').style.display = 'none';
        document.getElementById('hiddenForm3').style.display = 'block';
    };
    document.getElementById('formCloser3').onclick = function () {
        document.getElementById('hiddenForm3').style.display = 'none';
    };
    document.getElementById('back').onclick = function () {
        document.getElementById('hiddenForm3').style.display = 'none';
        document.getElementById('hiddenForm2').style.display = 'block';
    };
</script>
<script>
    document.getElementById('globalchat').onclick = function () {
        document.getElementById('hiddenForm2').style.display = 'none';
        document.getElementById('hiddenForm4').style.display = 'block';
    };
    document.getElementById('formCloser4').onclick = function () {
        document.getElementById('hiddenForm4').style.display = 'none';
    };
    document.getElementById('back2').onclick = function () {
        document.getElementById('hiddenForm4').style.display = 'none';
        document.getElementById('hiddenForm2').style.display = 'block';
    };
</script>
<script>
    document.querySelectorAll('.chatopener').forEach(item => {
        item.addEventListener('click', event => {
            document.getElementById('hiddenForm3').style.display = 'none';
            document.getElementById('hiddenForm5').style.display = 'block';

            var recipientName = item.textContent;
            document.querySelector('input[name="recipient"]').value = recipientName;
        });
    });

    document.getElementById('formCloser5').onclick = function () {
        document.getElementById('hiddenForm5').style.display = 'none';
    };
    document.getElementById('back3').onclick = function () {
        document.getElementById('hiddenForm5').style.display = 'none';
        document.getElementById('hiddenForm3').style.display = 'block';
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
        die("Sql Error: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['username'] . "<br>";
        }
    } else {
        echo "User not found.";
    }
}
?>