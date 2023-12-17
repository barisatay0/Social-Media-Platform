<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egoistsky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="astronom.ico">
    <style>
        #kacanResim {
            position: absolute;
            top: 50px;
            left: 50px;
            transition: 0.5s;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-black">
    <div class="text-white h1 text-center w-100"><span>You cant go back if you cant hit the earth!</span></div>
    <a href="index.php"><img style="width:10%;" id="kacanResim" src="earth.png" alt="Kaçan Resim"></a>


    <script>
        const kacanResim = document.getElementById('kacanResim');

        kacanResim.addEventListener('mouseover', () => {
            const maxX = window.innerWidth - kacanResim.offsetWidth;
            const maxY = window.innerHeight - kacanResim.offsetHeight;

            const newX = Math.random() * maxX;
            const newY = Math.random() * maxY;

            kacanResim.style.left = `${newX}px`;
            kacanResim.style.top = `${newY}px`;
        });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>

</html>