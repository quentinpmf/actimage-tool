<!DOCTYPE html>
<html lang="en">
<head>
    <title>ActiTool</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Actitool">
    <meta name="author" content="Quentin Boudinot">

    <?php include('../includes/admin/top_page.php') ?>

    <link type="text/css" rel="stylesheet" href="../assets/featherlight-1.7.13/release/featherlight.min.css" />
    <script src="../assets/featherlight-1.7.13/assets/javascripts/jquery-1.7.0.min.js"></script>
    <script src="../assets/featherlight-1.7.13/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
    <link type="text/css" rel="stylesheet" href="../assets/featherlight-1.7.13/release/featherlight.min.css" />
    <style type="text/css">
        @media all {
            .lightbox { display: none; }
            .fl-page h1,
            .fl-page h3,
            .fl-page h4 {
                font-family: 'HelveticaNeue-UltraLight', 'Helvetica Neue UltraLight', 'Helvetica Neue', Arial, Helvetica, sans-serif;
                font-weight: 100;
                letter-spacing: 1px;
            }
            .fl-page h1 { font-size: 110px; margin-bottom: 0.5em; }
            .fl-page h1 i { font-style: normal; color: #ddd; }
            .fl-page h1 span { font-size: 30px; color: #333;}
            .fl-page h3 { text-align: right; }
            .fl-page h3 { font-size: 15px; }
            .fl-page h4 { font-size: 2em; }
            .fl-page .jumbotron { margin-top: 2em; }
            .fl-page .doc { margin: 2em 0;}
            .fl-page .btn-download { float: right; }
            .fl-page .btn-default { vertical-align: bottom; }

            .fl-page .btn-lg span { font-size: 0.7em; }
            .fl-page .footer { margin-top: 3em; color: #aaa; font-size: 0.9em;}
            .fl-page .footer a { color: #999; text-decoration: none; margin-right: 0.75em;}
            .fl-page .github { margin: 2em 0; }
            .fl-page .github a { vertical-align: top; }
            .fl-page .marketing a { color: #999; }

            /* override default feather style... */
            .fixwidth {
                background: rgba(256,256,256, 0.8);
            }
            .fixwidth .featherlight-content {
                width: 500px;
                padding: 25px;
                color: #fff;
                background: #111;
            }
            .fixwidth .featherlight-close {
                color: #fff;
                background: #333;
            }

        }
        @media(max-width: 768px){
            .fl-page h1 span { display: block; }
            .fl-page .btn-download { float: none; margin-bottom: 1em; }
        }
    </style>

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

            <!-- spinner appel AJAX -->
            <div id="loading" style="display:none">Loading&#8230;</div>

            <div id="rows">
                <!-- en tête -->

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                    </div>

                    <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Nom</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Type</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body center p-6">
                                <b>[Modifier.]</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body center p-6">
                                <b>Suppr.</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                    </div>
                </div>

                <div class="row">
                    <?php

                    //récupération des utilisateurs
                    $req = $bdd->query("SELECT * FROM projects ORDER BY name ASC");
                    while ($projects = $req->fetch()) {
                        ?>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                        </div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                            <?php
                            if(!empty($projects['logo'])) {
                                echo('<img class="img-circle" src="../assets/images/logos/'.$projects["logo"].'">');
                            }
                            ?>
                        </div>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body nocolor p-6">
                                    <?php echo($projects['name']); ?>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body nocolor p-6">
                                    <?php echo($projects['type']); ?>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body center nocolor p-6">
                                    <a href="editProject.php?projectId=<?php echo($projects['id']); ?>">
                                        <img src="../assets/images/admin/edit.png">
                                    </a>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body center nocolor p-6">
                                    <a href="deleteProject.php?projectId=<?php echo($projects['id']); ?>">
                                        <img src="https://img.icons8.com/metro/26/000000/trash.png">
                                    </a>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                        </div>

                    <?php
                    }
                    ?>
                </div>

                <div class="pt-5 text-center">
                    <a class="btn btn-success theme-btn theme-btn-ghost-green font-weight-bold" href="#" data-featherlight="#addProject">Ajouter un nouveau projet</a>
                </div>

            </div>

	    </div><!--//container-->

    </section><!--//features-section-->

    <div class="lightbox" id="addProject">
        <h2>Créer un nouveau projet</h2>
        <form method="POST" action="addProject.php" enctype="multipart/form-data">
            <label for="projectName">Nom du projet</label>
            <input type="text" id="projectName" name="projectName" maxlength="30" placeholder="April" required/><br>
            <label for="projectType">Type du projet</label>
            <input type="text" id="projectType" name="projectType" maxlength="30" placeholder="TMA"/><br>
            <label for="projectLogo">Logo du projet</label>
            <input type="file" id="projectLogo" name="projectLogo"><br>
            <input type="submit" value="Valider">
        </form>
    </div>

    <!-- Javascript -->
    <script type="text/javascript" src="../assets/plugins/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../assets/plugins/popper.min.js"></script>
    <script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script type="text/javascript" src="../assets/plugins/jquery-flipster/dist/jquery.flipster.min.js"></script>
    <script type="text/javascript" src="../assets/js/flipster-custom.js"></script>


</body>
</html>

