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

        .scrollable-container::-webkit-scrollbar-thumb {
            background-color: transparent;
        }
    </style>
</head>



<body class="grad bgimage">
    <a href="" class="mx-3 mt-2"></a>
    <div><a href="https://egoistsky.free.nf/user"
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder"
            style="margin-left:12%;"><img class="border border-black border-3 rounded-circle" style="width: 6%;"
                src="astronomy.png" alt="logo"></a></div>
    <div class="position-absolute top-0 start-50 translate-middle mt-4" style="width:33%;">
        <form name="searcher" method="post" action="search.php">
            <input type="search" id="searchInput" name="search" placeholder="Search..." class="form-control">
        </form>
        <div id="searchResults"></div>
    </div>

    <div class="top-50 start-0 translate-middle-y mx-1" style="width:24%;margin-top:1%;position: fixed;">
        <a href="Reels"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover "
                style="margin-left: 50%;" src="telescope.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Reels"></a>
        <a href="trends"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="comet.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Trends"></a>
        <a href=""><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="bootes.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Groups"></a>
        <a href=""><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="earth.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Languages"></a>
        <a href="information"><img class="w-25 rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover"
                style="margin-left: 50%;" src="saturn.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="İnformation"></a>
    </div>
    <div class="position-absolute mt-4 w-25 text-center dropdown end-0" style="top:0;right:0;">
        <a href="profile.php" style="text-decoration:none;font-family:'Courier New', Courier, monospace;">
            <img <?php echo 'src="' . $profilePhoto . '"' ?> class=" border border-dark border-opacity-25 border-5"
                alt="123" style="border-radius:68%;width:34%;" />
            <p class="text-light text-center">
                <?php echo $username; ?>
            </p>
        </a>
        <a href="profile.php"><button class="btn btn-outline-light mt-2 dropdown-content"
                style="font-size:12.5px;width:32%;">Profile</button></a>
        <br>
        <button class="btn btn-outline-light mt-2 dropdown-content"
            style="font-size:12.5px;width:32%;">Settings</button>
        <br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content" style="font-size:12.5px;width:32%;">Logout</button>
        </form>

    </div>
    <div class="scrollable-container w-100 mt-1" style="overflow-y:auto;height:40rem;">
        <?php
        $servername = "sql203.infinityfree.com";
        $username = "if0_35435711";
        $password = "hrtPcoQHzpRSu";
        $dbname = "if0_35435711_users";

        try {
            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Post tablosundan veri çekme
            $postQuery = "SELECT username, photo, description, time FROM post  ORDER BY time DESC";
            $postStmt = $dbh->query($postQuery);

            if ($postStmt) {
                while ($row = $postStmt->fetch(PDO::FETCH_ASSOC)) {
                    // User tablosundan profilephoto sütununu çekme
                    $userQuery = "SELECT profilephoto FROM user WHERE username = '" . $row["username"] . "'";
                    $userStmt = $dbh->query($userQuery);
                    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

                    // HTML içeriğini oluştur
                    echo '
            <div class="w-25 post" style="margin-left:38%;">
                <div class="card post text-white">
                    <img src="data/posts/' . $row["photo"] . '" class="card-img-top" alt="...">
                    <div class="card-body" style="background-color:black;">
                        <a href=""><img src="' . $userRow["profilephoto"] . '" class="rounded-5 mx-1" style="width:15%;"></a>
                        <h5 class="card-title fs-4">' . $row["username"] . '</h5>
                        <p class="card-text">' . $row["description"] . '</p>
                        <br>
                        <p class="card-text"><small class="text-white-50">' . $row["time"] . '</small></p>
                        <input type="image" class="mt-2 imghover" style="width: 10%;" src="sun.png" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Like">
                        <input type="image" class="mt-2 mx-1 imghover" style="width: 10%;" src="mercury.png" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlike">
                    </div>
                </div>
            </div>
            <br>';
                }
            } else {
                echo "Veri bulunamadı";
            }
        } catch (PDOException $e) {
            echo "Bağlantı hatası: " . $e->getMessage();
        }
        ?>
        <br>
    </div>
    <div>
        <input type="image" class="top-100 end-0 translate-middle-y mx-3 imghover"
            style="width:5.5%;position:fixed;margin-top:-5%;opacity:75%;" src="bubble.png">

        <input id="formOpener" type="image" class="top-100 end-0 translate-middle-y mx-4 imghover"
            style="width:5%;position:fixed;margin-top:-10.5%;opacity:85%;" src="picture.png">
    </div>
    <div class="w-50 border bg-dark rounded-5 light border-dark position-absolute top-50 start-50 translate-middle text-center"
        id="hiddenForm" style="display: none;--bs-bg-opacity: .9;height:74%;">
        <p class="h1 text-light mt-5">Post</p>
        <form method="POST" action="https://egoistsky.free.nf/upload" id="myForm" class="mt-3"
            enctype="multipart/form-data">
            <input class="btn btn-outline-light w-75 mt-3" type="file" name="fileToUpload" required>
            <br>
            <textarea class="mt-4 w-75" name="description" placeholder="Description" id="description"
                style="border-radius: 3%; height: 5rem;"></textarea>
            <br>
            <input class="btn btn-success w-75 mt-4" name="share" type="submit" value="Share">
        </form>
        <button id="formCloser" class="w-25 mt-4 btn btn-danger">close</button>
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
    // Arama inputu ve sonuçların gösterileceği div
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    // Her harf girişinde arama fonksiyonu çalışacak
    searchInput.addEventListener('input', function () {
        const searchValue = this.value; // Arama değeri

        // Eğer arama değeri boşsa sonuçları temizle
        if (searchValue === '') {
            searchResults.innerHTML = '';
            return;
        }

        // Arama değeri ile kullanıcı adlarını ara
        fetch(`search.php?search_query=${searchValue}`)
            .then(response => response.text())
            .then(data => {
                searchResults.innerHTML = data; // Sonuçları göster
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
// connect.php dosyasını include edin
include 'connect.php';

// Arama sorgusu
if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // Veritabanında kullanıcıları adlarına göre arayın
    $query = "SELECT * FROM user WHERE username LIKE '%$search%'";
    $result = mysqli_query($connection, $query);

    // Sorgu başarılı mı kontrol edin
    if (!$result) {
        die("Sorgu hatası: " . mysqli_error($connection));
    }

    // Sonuçları ekrana yazdırın
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['username'] . "<br>";
            // Diğer kullanıcı bilgilerini de isterseniz burada gösterebilirsiniz
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}
?>