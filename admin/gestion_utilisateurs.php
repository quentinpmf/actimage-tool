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
                                <b>Prénom</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Email</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Rôle</b>
                            </div>
                        </div><!--//card-->
                    </div>

                    <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body center p-6">
                                <b>[Passwd.]</b>
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
                                <b>[Suppr.]</b>
                            </div>
                        </div><!--//card-->
                    </div>
                </div>

                <div class="row">
                    <?php

                    //récupération des utilisateurs
                    $req = $bdd->query("SELECT * FROM users ORDER BY id ASC");
                    while ($imputations = $req->fetch()) {
                        ?>
                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body nocolor p-6">
                                    <?php echo($imputations['nom']); ?>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body nocolor p-6">
                                    <?php echo($imputations['prenom']); ?>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body nocolor p-6">
                                    <?php echo($imputations['email']); ?>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-2 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body nocolor p-6">
                                    <select onchange="changerUserRole($(this))" name="user_role-<?php echo($imputations['id']) ?>"> <!-- récupération des roles depuis la BdD -->
                                        <?php
                                        $req2 = $bdd->query("SELECT * FROM roles");
                                        while ($roles = $req2->fetch())
                                        {
                                            //selection du projet dans la liste
                                            if($imputations['role'] == $roles['id']) {
                                                $selected = "selected";
                                            }
                                            else{
                                                $selected = "";
                                            }

                                            echo('<option value="'.$roles['id'].'" '.$selected.'>'.$roles['libelle'].'</option>');
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body center nocolor p-6">
                                    <a href="editUser.php?id=<?php echo($imputations['id']); ?>">
                                        <img src="../assets/images/admin/refresh.png">
                                    </a>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body center nocolor p-6">
                                    <a href="newPassordUser.php?id=<?php echo($imputations['id']); ?>">
                                        <img src="../assets/images/admin/edit.png">
                                    </a>
                                </div>
                            </div><!--//card-->
                        </div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-1 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body center nocolor p-6">
                                    <a href="changeUserRole.php?id=<?php echo($imputations['id']); ?>">
                                        <img src="https://img.icons8.com/metro/26/000000/trash.png">
                                    </a>
                                </div>
                            </div><!--//card-->
                        </div>
                    <?php
                    }
                    ?>
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

