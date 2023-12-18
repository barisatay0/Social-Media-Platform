<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if (isset($_POST['submit'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmNewPassword = $_POST['confirm_new_password'];

        try {
            $query = "SELECT * FROM user WHERE username = :username";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $storedHashedPassword = $row['password'];
                if (password_verify($currentPassword, $storedHashedPassword)) {
                    if ($newPassword === $confirmNewPassword) {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $updateQuery = "UPDATE user SET password = :password WHERE username = :username";
                        $updateStmt = $dbh->prepare($updateQuery);
                        $updateStmt->bindParam(':password', $hashedPassword);
                        $updateStmt->bindParam(':username', $username);
                        $updateStmt->execute();

                        echo '<p class="text-success h1 position-absolute">Şifre başarıyla güncellendi!</p>';
                    } else {
                        echo '<p class="text-danger h1">Yeni şifreler eşleşmiyor!</p>';
                    }
                } else {
                    echo '<p class="text-danger h1">Mevcut şifre yanlış!</p>';
                }
            } else {
                echo '<p class="text-light h1">Kullanıcı bulunamadı veya Connection Error!</p>';
            }
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }
} else {
    header("Location: login");
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
        .searcher {
            width: 33%;
        }

        .formander {
            width: 25%;
        }

        .logo {
            width: 6%;
        }

        .return {
            margin-top: 12%
        }

        @media only screen and (max-width: 400px) {
            .formander {
                width: 60%;
            }

            .searcher {
                width: 60%;
            }

            .logo {
                width: 15%;
                margin-left: -10%;
            }

            .return {
                margin-top: 50%
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
    <div class="position-absolute top-0 start-50 translate-middle mt-4 searcher" style="">
        <form name="searcher" method="post" action="search.php">
            <input type="search" id="searchInput" name="search" placeholder="Search..." class="form-control">
        </form>
        <div id="searchResults"></div>
    </div>
    <form class="formander text-white position-absolute top-50 start-50 translate-middle" action="" method="post">
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="current_password" class="form-control" id="current_password" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" id="new_password" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">New Password Again</label>
            <input type="password" name="confirm_new_password" class="form-control" id="confirm_new_password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-success w-100">Save Password</button>
    </form>
    <a href="edit.php"><button class="btn btn-light position-absolute top-50 start-50 translate-middle return"
            style=";">Return To Edit Page</button></a>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
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

</html>