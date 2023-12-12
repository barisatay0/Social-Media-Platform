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

try {
    $query_posts = "SELECT photo FROM post WHERE username = :username ORDER BY time DESC";
    $stmt_posts = $dbh->prepare($query_posts);
    $stmt_posts->bindParam(':username', $username);
    $stmt_posts->execute();

    $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Bağlantı Hatası: " . $e->getMessage();
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
    </style>
</head>

<body class="bg-black">
    <a href="" class="mx-3 mt-2"></a>

    <div><a href="https://egoistsky.free.nf/user"
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder"
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle" style="width: 6%;"
                src="astronomy.png" alt="logo"></a></div>
    <div>
        <div class="top-0 start-50 position-absolute translate-middle-x mt-2 text-center">
            <input type="image" class="rounded-circle mx-2 border border-black" style="width:6.5rem;height:6.5rem;"
                <?php echo 'src="' . $profilePhoto . '"' ?>>

            <br>
            <p class="h3 text-light" style="font-family: system-ui;">
                <?php echo '' . $username . '' ?>
            </p>

            <a href="" style="text-decoration: none;">
                <p class="h5 text-white-50 mt-1">Followers : 60M</p>
            </a>
            <a href="" style="text-decoration: none;">
                <p class="h5 text-white-50">Following : 671</p>
            </a>
            <p class="h5 text-light" style="font-family:Gill Sans, sans-serif;">
                <?php echo '' . $biography . '' ?>
            </p>
            <a href="edit.php"><button class="btn btn-outline-light" style="width:25%;">Edit Profile</button></a>
            <a class="btn btn-outline-light" style="width:25%;">Settings</a>
            <br>
            <br>
            <div class="scrollable-container w-100 mt-2">
                <?php foreach ($posts as $post): ?>
                    <img class="w-25 rounded-1 border-black imghoverprofile" src="data/posts/<?php echo $post['photo']; ?>"
                        style="height:12rem;" data-photo="<?php echo $post['photo']; ?>">
                <?php endforeach; ?>
            </div>
            <div id="myModal" class="modal scrollable-container mt-5 position-absolute translate-middle start-50 top-50"
                style="width:60%;height:49rem;border:none;">
                <button class="close h5 btn btn-danger text-light">&times;</button>
                <div class="modal-content" style="background-color:#090918;border:none;">
                    <img id="modalImage" src="" style="max-width: 35rem; max-height: 25rem;">
                    <div>
                        <button class="btn btn-outline-danger w-100 mt-2" id="deleteButton" style="border:none;">Delete
                            Photo</button>
                        <button class="btn btn-outline-light  w-100 mt-2" id="editButton" style="border:none;">Edit
                            Photo Description</button>
                    </div>
                    <br>

                    <br>
                </div>
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
<script>
    var images = document.getElementsByClassName('imghoverprofile');
    var modal = document.getElementById('myModal');
    var modalImg = document.getElementById('modalImage');

    for (var i = 0; i < images.length; i++) {
        images[i].addEventListener('click', function () {
            modal.style.display = 'block';
            modalImg.src = this.src;
            modalImg.dataset.photo = this.getAttribute('data-photo');
        });
    }

    var span = document.getElementsByClassName('close')[0];
    span.onclick = function () {
        modal.style.display = 'none';
    };
</script>

</html>