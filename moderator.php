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
        <a href="manageexplore.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons"
                style="">Manage Explore</button></a>
        <br>
        <a href="posttable.php"><button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Post
                Table</button></a>
        <br>
        <button class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Settings</button>
        <br>
        <form method="post" action=""><button type="submit" name="logout"
                class="btn btn-outline-light mt-2 dropdown-content profilebuttons" style="">Logout</button>
        </form>

    </div>
    <div class="text-center" style="margin-left:10%;width:67%;">
        <br>
        <h5 class="h3 text-white">Manage Users</h5>
        <table class="table table-hover" style="">
            <tr>
                <th>id</th>
                <th>Username</th>
                <th>Email</th>
                <th>firstname</th>
                <th>lastname</th>
                <th>Role</th>
                <th>Ban</th>
            </tr>
            <tr>
                <?php foreach ($usersData as $user):
                    $rolesQuery = "SELECT role FROM roles WHERE id = :id";
                    $stmtRoles = $dbh->prepare($rolesQuery);
                    $stmtRoles->bindParam(':id', $user['id']);
                    $stmtRoles->execute();
                    $userRole = $stmtRoles->fetch(PDO::FETCH_ASSOC)['role'];
                    ?>

                <tr>
                    <td>
                        <?php echo $user['id']; ?>
                    </td>
                    <td><a href="https://egoistsky.free.nf/egoist?username=<?php echo $user['username']; ?>">
                            <?php echo $user['username']; ?>
                        </a></td>
                    <td>
                        <?php echo $user['email']; ?>
                    </td>
                    <td>
                        <?php echo $user['firstname']; ?>
                    </td>
                    <td>
                        <?php echo $user['lastname']; ?>
                    </td>
                    <td>
                        <div class="input-group mt-1">
                            <input type="text" class="form-control" value="<?php echo $userRole ?>" readonly>

                        </div>
                    </td>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <td><button type="submit" name="Ban" class="btn btn-outline-danger">Ban</button>
                            <button type="submit" name="UnBan" class="btn btn-outline-primary">Un Ban</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
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
<?php
if (isset($_POST['delete'])) {
    $userId = $_POST['id'];
    $deleteUserRoleQuery = "DELETE FROM roles WHERE id = :id";
    $stmtDeleteUserRole = $dbh->prepare($deleteUserRoleQuery);
    $stmtDeleteUserRole->bindParam(':id', $userId);
    $stmtDeleteUserRole->execute();

    $deleteUserQuery = "DELETE FROM user WHERE id = :id";
    $stmtDeleteUser = $dbh->prepare($deleteUserQuery);
    $stmtDeleteUser->bindParam(':id', $userId);
    $stmtDeleteUser->execute();

    header("Location: current_page.php");
    exit();
}
if (isset($_POST['editRole'])) {
    $userId = $_POST['id'];
    $newRole = $_POST['newRole'];

    $updateRoleQuery = "UPDATE roles SET role = :newRole WHERE id = :id";
    $stmtUpdateRole = $dbh->prepare($updateRoleQuery);
    $stmtUpdateRole->bindParam(':newRole', $newRole);
    $stmtUpdateRole->bindParam(':id', $userId);
    $stmtUpdateRole->execute();

    header("Location: current_page.php");
    exit();
}
if (isset($_POST['Ban'])) {
    $userId = $_POST['id'];

    $banUserQuery = "UPDATE user SET banned = true WHERE id = :id";
    $stmtBanUser = $dbh->prepare($banUserQuery);
    $stmtBanUser->bindParam(':id', $userId);
    $stmtBanUser->execute();

} elseif (isset($_POST['UnBan'])) {
    $userId = $_POST['id'];

    $unbanUserQuery = "UPDATE user SET banned = false WHERE id = :id";
    $stmtUnbanUser = $dbh->prepare($unbanUserQuery);
    $stmtUnbanUser->bindParam(':id', $userId);
    $stmtUnbanUser->execute();

    header("Location: current_page.php");
    exit();
}

?>