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
session_start();

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

        .scrollable-container::-webkit-scrollbar-thumb {
            background-color: transparent;
        }
    </style>
</head>

<body class="grad" style="background-image: url(ngtsky.jpg);
	background-size: cover;">
    <a href="" class="mx-3 mt-2"></a>
    <div><a href="https://egoistsky.free.nf" class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder"
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle" style="width: 6%;"
                src="astronomy.png" alt="logo"></a></div>
    <div class="position-absolute top-0 start-50 translate-middle mt-4" style="width:33%;">
        <form><input type="search" placeholder="Search..." class=" form-control"></form>
    </div>
    <div class="top-50 start-0 translate-middle-y mx-1" style="width:24%;margin-top:1%;position: fixed;">
        <a href="Reels.php"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover "
                style="margin-left: 50%;" src="telescope.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-title="Reels"></a>
        <a href="trends.php"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="comet.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-title="Trends"></a>
        <a href=""><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="bootes.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-title="Groups"></a>
        <a href=""><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="earth.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-title="Languages"></a>
        <a href="information.php"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="saturn.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-title="İnformation"></a>
    </div>
    <div class=" scrollable-container start-50 position-absolute translate-middle-x w-25"
        style="overflow-y: auto;height:43rem;margin-top:-3%;">
        <div class="w-100 post">
            <div class="card post text-white">
                <video width="372" height="660" controls>
                    <source src=""  alt="video1" type="video/mp4">
                </video>
                <div class="card-body" style="background-color:black;width:100%;">
                    <a href=""><img src="" class="rounded-5 mx-4" style="width:15%;" alt="???"></a>
                    <h5 class="card-title fs-4">???</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.
                    </p>
                    <br>
                    <p class="card-text"><small class="text-white-50">Last updated 3 mins ago</small></p>
                    <input type="image" class="mt-2 imghover" style="width: 10%;" src="sun.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like">
                    <input type="image" class="mt-2 mx-1 imghover" style="width: 10%;" src="mercury.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike">
                </div>
            </div>
        </div>
        <br>
        <div class="w-100 post">
            <div class="card post text-white">
                <video width="372" height="660" controls>
                    <source src=""  alt="video2" type="video/mp4">
                </video>
                <div class="card-body" style="background-color:black;width:100%;">
                    <a href=""><img src="" class="rounded-5 mx-4" style="width:15%;" alt="???"></a>
                    <h5 class="card-title fs-4">???</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.
                    </p>
                    <br>
                    <p class="card-text"><small class="text-white-50">Last updated 3 mins ago</small></p>
                    <input type="image" class="mt-2 imghover" style="width: 10%;" src="sun.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like">
                    <input type="image" class="mt-2 mx-1 imghover" style="width: 10%;" src="mercury.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike">
                </div>
            </div>
        </div>
        <div class="w-100 post">
            <div class="card post text-white">
                <video width="372" height="660" controls>
                    <source src=""  alt="video3" type="video/mp4">
                </video>
                <div class="card-body" style="background-color:black;width:100%;">
                    <a href=""><img src="" class="rounded-5 mx-4" style="width:15%;" alt="???"></a>
                    <h5 class="card-title fs-4">???</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.
                    </p>
                    <br>
                    <p class="card-text"><small class="text-white-50">Last updated 3 mins ago</small></p>
                    <input type="image" class="mt-2 imghover" style="width: 10%;" src="sun.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like">
                    <input type="image" class="mt-2 mx-1 imghover" style="width: 10%;" src="mercury.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike">
                </div>
            </div>
        </div>
        <div class="w-100 post">
            <div class="card post text-white">
                <video width="372" height="660" controls>
                    <source src="videos\Rockstar in his prime.mp4" type="video/mp4">
                </video>
                <div class="card-body" style="background-color:black;width:100%;">
                    <a href=""><img src="" class="rounded-5 mx-4" style="width:15%;" alt="???"></a>
                    <h5 class="card-title fs-4">???</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.
                    </p>
                    <br>
                    <p class="card-text"><small class="text-white-50">Last updated 3 mins ago</small></p>
                    <input type="image" class="mt-2 imghover" style="width: 10%;" src="sun.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like">
                    <input type="image" class="mt-2 mx-1 imghover" style="width: 10%;" src="mercury.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike">
                </div>
            </div>
        </div>
        <div class="w-100 post">
            <div class="card post text-white">
                <video width="372" height="660" controls>
                    <source src="" alt="video1" type="video/mp4">
                </video>
                <div class="card-body" style="background-color:black;width:100%;">
                    <a href=""><img src="" class="rounded-5 mx-4" style="width:15%;" alt="???"></a>
                    <h5 class="card-title fs-4">???</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.
                    </p>
                    <br>
                    <p class="card-text"><small class="text-white-50">Last updated 3 mins ago</small></p>
                    <input type="image" class="mt-2 imghover" style="width: 10%;" src="sun.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like">
                    <input type="image" class="mt-2 mx-1 imghover" style="width: 10%;" src="mercury.png"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike">
                </div>
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

</html>