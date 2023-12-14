<?php
include 'connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT password FROM user WHERE username='$username'";
        $result = $dbh->query($sql);

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $stored_hash = $row['password'];
            if (password_verify($password, $stored_hash)) {
                $_SESSION['username'] = $username;
                $sql_role = "SELECT role FROM roles WHERE id = (SELECT id FROM user WHERE username='$username')";
                $result_role = $dbh->query($sql_role);

                if ($result_role->rowCount() > 0) {
                    $row_role = $result_role->fetch(PDO::FETCH_ASSOC);
                    $user_role = $row_role['role'];
                    switch ($user_role) {
                        case 'user':
                            header("Location: https://egoistsky.free.nf/user");
                            break;
                        case 'admin':
                            header("Location: https://egoistsky.free.nf/admin");
                            break;
                        case 'moderator':
                            header("Location: https://egoistsky.free.nf/moderator");
                            break;
                        default:
                            echo "Invalid role";
                            break;
                    }
                    $login_time = date('Y-m-d H:i:s');
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    $sql_log = "INSERT INTO user_logs (username, login_time, ip_address, user_agent) VALUES ('$username', '$login_time', '$ip_address', '$user_agent')";
                    $dbh->exec($sql_log);

                    exit();
                } else {
                    echo "User role not found.";
                }
            } else {
                echo "Invalid username or password.";
            }

        } else {
            echo "User not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Screen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="astronom.ico">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-black">
    <a href="https://egoistsky.free.nf" class="start-50 top-25 mt-5 text-center position-absolute translate-middle"><img
            src="astronomy.png" class="border border-black rounded-circle border-3 mt-5" style="width:16%;"></a>
    <form class="w-25 text-white position-absolute top-50 start-50 translate-middle" method="post">
        <div class="mb-4">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="exampleInputEmail1"
                aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-outline-light w-100">Login</button>
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
<script>
</html >