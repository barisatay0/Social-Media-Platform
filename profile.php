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
        echo "Connection Error: " . $e->getMessage();
    }
} else {
    header("Location: login");
    exit();
}

try {
    $query_posts = "SELECT photo FROM post WHERE username = :username ORDER BY time DESC";
    $stmt_posts = $dbh->prepare($query_posts);
    $stmt_posts->bindParam(':username', $username);
    $stmt_posts->execute();

    $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection Error: " . $e->getMessage();
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

        .profilebuttons {
            width: 8rem;

        }

        .logo {
            width: 6%;
        }

        .pagebuttons {
            width: 24%;
            margin-top: 1%;
            position: fixed;
        }

        .pagebuttonsonly {
            margin-left: 50%;

        }

        .photocontainer {
            width: 100%;
        }

        .containerphotos {
            height: 20rem;
            width: 20rem;
        }

        .modalcontainer {
            width: 50%;
            height: 32rem;
            border: none;
        }

        @media only screen and (max-width: 400px) {

            .profilebuttons {
                width: 6rem;
                font-size: 10px;
                margin-left: 1rem;
            }

            .logo {
                width: 12%;
                margin-left: -10%;
            }

            .pagebuttons {
                width: 41%;
                margin-top: -52%;
                position: fixed;
            }

            .pagebuttonsonly {
                margin-left: 5%;

            }

            .photocontainer {
                width: 100%;
                margin-left: 6%;
            }

            .containerphotos {
                height: 14rem;
                width: 14rem;
            }

            .modalcontainer {
                width: 110%;
                height: 32rem;
                border: none;
            }
        }
    </style>
</head>

<body class="bg-black">
    <a href="" class="mx-3 mt-2"></a>

    <div><a href="https://egoistsky.free.nf/user"
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder"
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle logo" style=""
                src="astronomy.png" alt="logo"></a></div>
    <div>
        <div class="top-0 end-0 position-absolute translate-middle-x mt-2 profilebuttons" style="">
            <a href="edit.php"><button class="btn btn-outline-light  profilebuttons" style="">Edit Profile</button></a>
            <br>
            <a class="btn btn-outline-light mt-2 profilebuttons" style="">Settings</a>
        </div>
        <div class="top-0 start-50 position-absolute translate-middle-x mt-2 text-center">
            <input type="image" class="rounded-circle mx-2 border border-black" style="width:6.5rem;height:6.5rem;"
                <?php echo 'src="' . $profilePhoto . '"' ?>>

            <br>
            <p class="h3 text-light" style="font-family: system-ui;">
                <?php echo '' . $username . '' ?>
            </p>

            <a href="" style="text-decoration: none;">
                <p class="h5 text-white-50 mt-1">Followers:
                    <?php
                    try {
                        $query_followers_count = "SELECT followers FROM user WHERE username = :username";
                        $stmt_followers_count = $dbh->prepare($query_followers_count);
                        $stmt_followers_count->bindParam(':username', $username);
                        $stmt_followers_count->execute();

                        $row_followers_count = $stmt_followers_count->fetch(PDO::FETCH_ASSOC);
                        if ($row_followers_count) {
                            $followers_count = count(explode(',', $row_followers_count['followers']));
                            echo $followers_count;
                        }
                    } catch (PDOException $e) {
                        echo "Connection Error: " . $e->getMessage();
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
                        $stmt_following_count->bindParam(':username', $username);
                        $stmt_following_count->execute();

                        $row_following_count = $stmt_following_count->fetch(PDO::FETCH_ASSOC);
                        if ($row_following_count) {
                            $following_count = count(explode(',', $row_following_count['following']));
                            echo $following_count;
                        }
                    } catch (PDOException $e) {
                        echo "Connection Error: " . $e->getMessage();
                    }
                    ?>
                </p>
            </a>

            <p class="h5 text-light" style="font-family:Gill Sans, sans-serif;">
                <?php echo '' . $biography . '' ?>
            </p>
            <br>
            <br>
            <div class="scrollable-container mt-2 photocontainer border-top border-bottom border-dark border-3">
                <?php foreach ($posts as $post): ?>
                    <img class=" rounded-1 imghoverprofile containerphotos border border-2 border-dark"
                        src="data/posts/<?php echo $post['photo']; ?>" style="" data-photo="<?php echo $post['photo']; ?>">
                    <form method="POST" action="">
                        <input type="hidden" name="delete" value="<?php echo $post['photo']; ?>">
                        <button type="submit" class="btn btn-outline-danger w-100 mt-2" style="border:none;">Delete
                            Photo</button>
                    </form>
                    <br>
                <?php endforeach; ?>
            </div>

        </div>
        <div id="myModal"
            class="modal scrollable-container mt-5 position-absolute translate-middle start-50 top-50 text-center modalcontainer"
            style="">
            <button class="close h5 btn btn-danger text-light text-center">&times;</button>
            <div class="modal-content" style="background-color:#090918;border:none;">
                <img id="modalImage" class="border border-5 border-dark w-100" src="">
                <div>
                </div>
                <br>

                <br>
            </div>
        </div>
    </div>

    </div>
    <div class="top-50 start-0 translate-middle-y mx-1 pagebuttons" style="">
        <a href="Explore.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover pagebuttonsonly" style=""
                src="telescope.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Explore"></a>
        <a href="Random"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover pagebuttonsonly" style=""
                src="comet.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Random Match"></a>
        <a href="following.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover pagebuttonsonly" style=""
                src="bootes.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Following"></a>
        <a href="world.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover pagebuttonsonly" style=""
                src="earth.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="World"></a>
        <a href="information.php"><img
                class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover pagebuttonsonly" style=""
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
<script>
    var images = document.getElementsByClassName('imghoverprofile');
    var modal = document.getElementById('myModal');
    var modalImg = document.getElementById('modalImage');
    var deleteButton = document.getElementById('deleteButton');

    for (var i = 0; i < images.length; i++) {
        images[i].addEventListener('click', function () {
            modal.style.display = 'block';
            modalImg.src = this.src;
            modalImg.dataset.photo = this.getAttribute('data-photo');

            deleteButton.name = this.getAttribute('data-photo');
        });
    }


    var span = document.getElementsByClassName('close')[0];
    span.onclick = function () {
        modal.style.display = 'none';
    };


</script>
<?php
session_start();
include 'connect.php';

if (isset($_POST['delete'])) {
    $photoName = $_POST['delete'];

    try {
        $sqlLikes = "DELETE FROM likes WHERE postid IN (SELECT postid FROM post WHERE photo = :photoName)";
        $stmtLikes = $dbh->prepare($sqlLikes);
        $stmtLikes->bindParam(':photoName', $photoName, PDO::PARAM_STR);
        $stmtLikes->execute();

        $sqlPost = "DELETE FROM post WHERE photo = :photoName";
        $stmtPost = $dbh->prepare($sqlPost);
        $stmtPost->bindParam(':photoName', $photoName, PDO::PARAM_STR);
        $stmtPost->execute();

        $filePath = 'data/posts/' . $photoName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        echo '<script>window.location.replace("profile.php");</script>';
        exit();
    } catch (PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
    }
}
?>

</html>