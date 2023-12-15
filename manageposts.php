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


// Kullanıcının rolünü almak için kullanıcı ID'sini belirle
$userId = $row['id'];

$queryUserRole = "SELECT role FROM roles WHERE id = :id";
$stmtUserRole = $dbh->prepare($queryUserRole);
$stmtUserRole->bindParam(':id', $userId);
$stmtUserRole->execute();
$userRole = $stmtUserRole->fetch(PDO::FETCH_ASSOC)['role'];

// Eğer kullanıcı admin değilse, başka bir sayfaya yönlendir
if ($userRole !== 'admin') {
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
                Admin Name :
                <?php echo $username; ?>
            </p>
        </a>
        <a href="profile.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Profile</button></a>

        <br>
        <a href="admin.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Admin
                Page</button></a><br>
        <button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Settings</button>
        <br>
        <a href="manageposts.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Posts</button></a><br>
        <a href="login_logs.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Login Logs</button></a><br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Logout</button>
        </form>

    </div>
    <div class="scrollable-container w-100 mt-1 responsiveposter" style="overflow-y:auto;height:40rem;">
        <?php
        $servername = "sql203.infinityfree.com";
        $username = "if0_35435711";
        $password = "hrtPcoQHzpRSu";
        $dbname = "if0_35435711_users";

        try {
            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $postQuery = "SELECT username, photo, description, time FROM post  ORDER BY time DESC";
            $postStmt = $dbh->query($postQuery);

            if ($postStmt) {
                while ($row = $postStmt->fetch(PDO::FETCH_ASSOC)) {
                    $userQuery = "SELECT profilephoto FROM user WHERE username = '" . $row["username"] . "'";
                    $userStmt = $dbh->query($userQuery);
                    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);
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
                        <button type="submit" name="deleteButton" class="btn btn-outline-danger mt-1 w-100">Delete Post</button>
                        </form>
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