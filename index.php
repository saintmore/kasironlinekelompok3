<?php 
session_start();
// cek apakah sudah login
if($_SESSION['status'] != 'login'){
    header("location:login.php?pesan=gagal");
}

if (isset($_GET['logout'])) {
    $_SESSION['status'] = 'not login';
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Mabruk Cell</title>
        <link rel="icon" type="image/x-icon" href="image/mabrukIcon.png" />
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light">Dashboard Menu</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="index.php?logout=a">Logout</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="masterData.php">Master Data</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="pra_kasir.php">Kasir</a>
                </div>
            </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper" style="background-image: url('image/welcome.jpg');">
                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <!-- <button class="btn btn-primary" id="sidebarToggle" align="left">Toggle Menu</button> -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0" align="right">
                                <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="#!">Link</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#!">Action</a>
                                        <a class="dropdown-item" href="#!">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#!">Something else here</a>
                                    </div>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </nav>
                <!-- Page content-->
                <div class="container-fluid">
                <div class="container">
                <!-- <?php //if (@$_GET['menu']) {
                       //include $_GET['menu'];
                   //} else { ?>
                    <h1> Welcome To Mabruk Cell :)</h1>
                   <?php //} ?> -->
                </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
