<!DOCTYPE html>
<html lang="en">
<head>
    <title>Actimage</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Actitool">
    <meta name="author" content="Quentin Boudinot">

    <?php include('../includes/admin/top_page.php') ?>
</head>

<?php
include('../login/connectToBDD/conn.php');

session_start();
if(!isset($_SESSION['UserEmail']))
{
    header('location:../login/login.php');
}

?>

<body>

    <?php include('../includes/admin/navbar.php') ?>

    <section class="features-section py-5">
	    <div class="container py-lg-5">

            <div id="rows">
                <!-- en tête -->
                <div class="row">

                    <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">&nbsp;</div>

                    <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                        <a href="gestion_utilisateurs.php">
                            <div class="card rounded">
                                <div class="card-body p-6 imgcenter">
                                    <img src="../assets/images/admin/gestion_utilisateurs.png">
                                </div>
                            </div><!--//card-->
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                        <a href="gestion_projets.php">
                            <div class="card rounded">
                                <div class="card-body p-6 imgcenter">
                                    <img src="../assets/images/admin/gestion_projets.png">
                                </div>
                            </div><!--//card-->
                        </a>
                    </div>

                </div>
            </div>

	    </div><!--//container-->

    </section><!--//features-section-->

    <!-- Javascript -->
    <script type="text/javascript" src="../assets/plugins/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../assets/plugins/popper.min.js"></script>
    <script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script type="text/javascript" src="../assets/plugins/jquery-flipster/dist/jquery.flipster.min.js"></script>
    <script type="text/javascript" src="../assets/js/flipster-custom.js"></script>


</body>
</html>

