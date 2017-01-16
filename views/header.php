<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">

    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <nav class="navbar navbar-light bg-faded">
        <a class="navbar-brand" href="/">Twitter</a>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="?page=timeline">Your timeline</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=yourtweets">Your tweets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=publicprofiles">Public Profiles</a>
            </li>
        </ul>
        <div class="form-inline float-xs-right">

            <?php if ($_SESSION['id']) { ?>

            <a class="btn btn-outline-success" href="?function=logout">Logout</a>

            <?php } else { ?>
                <button class="btn btn-outline-success" data-toggle="modal" data-target="#myModal">Login/Signup</button>
            <?php } ?>
        </div>
    </nav>
