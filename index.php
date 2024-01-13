<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: user.php");
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
        .responsivepagelogos {
            width: 25%;
        }

        .logoa {
            margin-left: 12%;
        }

        .logoimg {
            width: 6%;
        }

        .signbutton {
            margin-right: 5rem;
        }

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

        .post {
            margin-left: 31%;
            width: 90%;
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

        @media only screen and (max-width: 420px) {
            .responsivepagelogos {
                margin-left: 12%;
                width: 60%;
            }

            .loginbutton {
                width: 15%;
                font-size: 11px;
                height: 5%;
            }

            .signbutton {
                width: 17%;
                font-size: 11px;
                margin-right: 4rem;
                height: 5%;
            }

            .logoimg {
                width: 16%;
            }

            .logoa {
                margin-left: 3%;
            }

            .post {
                width: 295%;
                margin-left: 19%;
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
                width: 95%;
                margin-left: -30%;
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

<body class="bg-black" ; background-size: cover;">
    <a href="" class="mx-3 mt-2"></a>
    <div><a href="https://egoistsky.free.nf/"
            class=" link-light link-underline-opacity-0 text-uppercase fst-italic fw-bolder logoa"><img
                class="border border-black border-3 rounded-circle logoimg" style="" src="astronomy.png" alt="logo"></a>
    </div>
    <div class="position-absolute top-0 start-50 translate-middle mt-4" style="width:33%;">
        <form name="searcher" method="post" action="notsignsearch.php">
            <input type="search" id="searchInput" name="search" placeholder="Search..."
                class="form-control responsivesearch">
        </form>
        <div id="searchResults"></div>
    </div>
    <a href="login"><button type="button" class="btn btn-outline-light  top-0 end-0 translate-middle mt-4 loginbutton"
            style="position: fixed;">Log In</button></a>
    <a href="signup"><button type="button" class="btn btn-outline-light top-0 end-0 translate-middle mt-4 signbutton"
            style="position: fixed;">Sign Up</button></a>
    <div class="top-50 start-0 translate-middle-y mx-1" style="width:24%;margin-top:1%;position: fixed;">
        <a href="Explore"><img
                class=" rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos " style=""
                src="telescope.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Explore"></a>
        <a href="Random"><img
                class=" rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="comet.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Random Match"></a>
        <a href="following.php"><img
                class=" rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="bootes.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Following"></a>
        <a href="world.php"><img
                class=" rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="earth.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="World"></a>
        <a href="information"><img
                class=" rounded-circle d-block mb-3 mt-3 border-2 border-dark imghover responsivepagelogos" style=""
                src="saturn.png" alt="" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="İnformation"></a>
    </div>
    <div class="scrollable-container w-100 mt-1" style="overflow-y:auto;height:40rem;">
        <?php
        include 'connect.php';

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
            <div class="w-25 post">
            
                <div class="card post border border-dark text-white">
               
                
            
                    <img src="data/posts/' . $row["photo"] . '" class="card-img-top" alt="..." style="height:24rem;">
                    <div class="card-body border border-dark" style="background-color:black;">
 <div class="mt-2 mx-2">
                <a class="text-light h4" style="text-decoration:none;" href="https://egoistsky.free.nf/egoist?username=' . $row["username"] . '"><img src="' . $userRow["profilephoto"] . '" class="rounded-circle mx-1" style="width:4rem;height:4rem;">' . $row["username"] . '</a>
                </div>
                <br>
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
                echo "Data not found";
            }
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        ?>
        <br>
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
                console.error('Searcher Error:', error);
            });
    });
</script>

</html>