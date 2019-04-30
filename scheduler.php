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

    <?php include('includes/top_page.php') ?>

    <style>
        .stripped{
            background: repeating-linear-gradient(
                    45deg,
                    #E6E1DC,
                    #E6E1DC 10px,
                    #F5F2EE 10px,
                    #F5F2EE 20px
            );
            color:black;
        }
    </style>
</head>

<?php
include('login/connectToBDD/conn.php');
session_start();
if(!isset($_SESSION['UserEmail']))
{
    header('location:login/login.php');
}

//affiche l'alert error
$boolError = false;
if(isset($_GET['error']) && $_GET['error'] == "noImputationsSelected") {
    $boolError = true;
}

?>

<body>

    <?php include('includes/navbar.php') ?>

    <div class="pt-5 text-center">
        <a class="btn btn-primary theme-btn theme-btn-ghost font-weight-bold" onclick="generate(); return false;" href="#">Générer le reporting hebdo</a>
    </div>

    <section class="features-section">
	    <div class="container py-lg-5">
			<form name="form" id="formGenerate" method="POST" action="generate.php">

                <!-- alert après enregistrement -->
                <?php if($boolError){
                    echo('<div class="alert alert-danger center" id="scheduler-danger-alert" role="alert">Veuillez sélectionner au moins une journée.</div><br>');
                } ?>

                <?php
                $currentDate = ""; //date courante
                $boolFirstDay = true; //true si premier jour, false sinon
                $boolLastDay = false; //true si dernier jour, false sinon
                $nbChangeDays = 0; //nombre de fois ou l'on à changé de jours
                $nbChangeImputations = 0; //nombre de fois ou l'on à changé d'imputations

                //récupération du nombre de jours différents
                $req1 = $bdd->query("SELECT COUNT(DISTINCT imputation_date) as 'nbDifferentDays' FROM imputations");
                $res1 = $req1->fetch();
                $nbDifferentDays = $res1['nbDifferentDays'];

                //récupération du nombre de jours différents
                $req2 = $bdd->query("SELECT COUNT(*) as 'nbDifferentImputations' FROM imputations");
                $res2 = $req2->fetch();
                $nbDifferentImputations = $res2['nbDifferentImputations'];

                //récupération des projets (ids,name,type)
                $arrProjects = array();
                $req3 = $bdd->query("SELECT * FROM projects");
                while ($project = $req3->fetch()) {
                    $arrProjects[$project['id']] = array('name' => $project['name'],'type' => $project['type'], 'logo' => $project['logo']);
                }

                //récupération du temps total passé par jour
                $arrTotalPassedTime = array();
                $req4 = $bdd->query("SELECT SUM(passed_time) as 'totalPassedTime',imputation_date FROM imputations WHERE user_id=".$_SESSION['UserId']." GROUP BY imputation_date DESC");
                while ($timeData = $req4->fetch()) {
                    $arrTotalPassedTime[$timeData['imputation_date']] = $timeData['totalPassedTime'];
                }

                //récupération des imputations rangés par jour décroissante
                $req5 = $bdd->query("SELECT * FROM imputations WHERE user_id=".$_SESSION['UserId']." ORDER BY imputation_date  DESC");
                while ($imputations = $req5->fetch())
                {
                    $marge = $imputations['allocated_time']-$imputations['passed_time'];
                    if($marge >= 0) {
                        $margeColor = 'green';
                    }else{
                        $margeColor = 'red';
                    }

                    //selon la durée de l'imputation on met une plus ou moins grande div
                    switch($imputations['passed_time'])
                    {
                        case '0.125':
                            $width = '15';
                        break;
                        case '0.25':
                            $width = '3';
                        break;
                        case '0.5':
                            $width = '6';
                        break;
                        case '0.75':
                            $width = '9';
                        break;
                        case '1':
                            $width = '12';
                        break;
                        default:
                            $width = '3';
                    }

                    //si la date de l'imputation est différente de la date courante
                    if($imputations['imputation_date'] != $currentDate)
                    {
                        //si ce n'est pas le premier jour : alors il y à eu une div ouverte avant, donc on la ferme
                        if(!$boolFirstDay) {
                            echo('</div>'); //fermeture <div class="row">
                        }


                        ?>

                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12 checkCheckbox">
                                <div class="card rounded">
                                    <div class="card-body p-6">
                                        <span class="alignleft">
                                            <b>
                                                <a style="color:#526b84" href="index.php?imputation_date=<?php echo($imputations['imputation_date']); ?>">
                                                    <?php echo($imputations['imputation_date'].' ('.$arrTotalPassedTime[$imputations['imputation_date']].' jh)'); ?></i>
                                                </a>
                                            </b>
                                        </span>
                                        <span class="alignright"><input type="checkbox" id="checkboxDates" name="<?php echo($imputations['imputation_date']); ?>"></span>

                                    </div>
                                </div><!--//card-->
                            </div>
                        </div>

                        <?php

                        echo('<div class="row">'); //ouverture div

                        ?>
                        <!-- en tête -->
                        <div class="col-12 col-md-6 col-xl-<?php echo($width); ?> pr-xl-3">
                            <div class="card rounded mt-2 mb-2">
                                <div class="card-body nocolor stripped">
                                    <?php echo('<b>'.$arrProjects[$imputations['projet_id']]['name'].' - '.$arrProjects[$imputations['projet_id']]['type'].' ('.$imputations['issue_number'].') </b></br>');
                                    echo('<span class="'.$margeColor.'">'.$imputations['passed_time'].'jh > '.$imputations['allocated_time'].'jh </span></br>');
                                    echo('<i>'.$imputations['description'].'</i>'); ?>
                                    <div class="avatar-container s40">
                                        <?php echo('<img alt="" class="img-circle" src="assets/images/logos/'.$arrProjects[$imputations['projet_id']]['logo'].'">'); ?>
                                    </div>
                                </div>
                            </div><!--//card-->
                        </div>

                        <?php

                        $currentDate = $imputations['imputation_date']; //on met la date courante à jour
                        $nbChangeDays = $nbChangeDays+1; //on met a jour le nombre de changements de jour
                    }
                    else
                    {
                        ?>
                        <!-- en tête -->
                        <div class="col-12 col-md-6 col-xl-<?php echo($width); ?> pr-xl-3">
                            <div class="card rounded mt-2 mb-2">
                                <div class="card-body nocolor stripped">
                                    <?php echo('<b>'.$arrProjects[$imputations['projet_id']]['name'].' - '.$arrProjects[$imputations['projet_id']]['type'].' ('.$imputations['issue_number'].') </b></br>');
                                    echo('<span class="'.$margeColor.'">'.$imputations['passed_time'].'jh > '.$imputations['allocated_time'].'jh </span></br>');
                                    echo('<i>'.$imputations['description'].'</i>'); ?>
                                    <div class="avatar-container s40">
                                        <?php echo('<img alt="" class="img-circle" src="assets/images/logos/'.$arrProjects[$imputations['projet_id']]['logo'].'">'); ?>
                                    </div>
                                </div>
                            </div><!--//card-->
                        </div>
                        <?php
                    }

                    $nbChangeImputations = $nbChangeImputations+1; //on met a jour le nombre de changements d'imputations
                    $boolFirstDay = false; //dès la premiere itération, on est plus dans le premier jour.

                    //si le nombre de changements de jours = le nombre différents de jours dans la bdD : alors c'est la fin et il faut fermer la div car il n'y aura pas de prochain tour.
                    if($nbChangeDays == $nbDifferentDays && $nbChangeImputations == $nbDifferentImputations) {
                        echo('</div>'); //fermeture <div class="row">
                    }
                }
                ?>


			</form>
	    </div><!--//container-->
    </section><!--//features-section-->

    <!-- Javascript -->
    <script type="text/javascript" src="assets/plugins/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/popper.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script type="text/javascript" src="assets/plugins/jquery-flipster/dist/jquery.flipster.min.js"></script>
    <script type="text/javascript" src="assets/js/flipster-custom.js"></script>


</body>
</html>

