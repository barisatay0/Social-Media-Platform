<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Successful!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        i {
            font-size: 15rem;
        }
    </style>
</head>

<body>
    <i class="fa-solid fa-circle-check d-flex justify-content-center mt-4" style="color: #000000;"></i>
    <div class="alert alert-dark mx-auto w-50 text-center mt-4" role="alert">
        <h4>Successful!</h4>
    </div>
    <div class="alert alert-dark mx-auto w-25 text-center" role="alert">
        <div class="spinner-border text-dark mx-auto" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h6 class="mt-2">You are redirected to the previous page!</h6>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                window.history.back();
            }, 3000);
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>