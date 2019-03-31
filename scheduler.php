<!DOCTYPE html>
<html lang="en">
<head>
    <title>Actimage</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstrap 4 Mobile App Template">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <?php include('includes.php') ?>
</head>

<?php
include('mysql/connect.php');
?>

<body>

    <section class="features-section py-5">
	    <div class="container py-lg-5">
			<form name="form" id="formGenerate" method="POST" action="generate.php">

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
                $req4 = $bdd->query("SELECT SUM(passed_time) as 'totalPassedTime',imputation_date FROM imputations GROUP BY imputation_date DESC");
                while ($timeData = $req4->fetch()) {
                    $arrTotalPassedTime[$timeData['imputation_date']] = $timeData['totalPassedTime'];
                }

                //récupération des imputations rangés par jour décroissante
                $req5 = $bdd->query("SELECT * FROM imputations ORDER BY imputation_date DESC");
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

                        //contenu du jour
                        echo('<h3 class="text-center font-weight-bold section-heading mt-3"><input type="checkbox" id="checkboxDates" name="'.$imputations['imputation_date'].'"><a class="black" href="index.php?imputation_date='.$imputations['imputation_date'].'"><i> '.$imputations['imputation_date'].' ('.$arrTotalPassedTime[$imputations['imputation_date']].' jh)'.'</i></a>');

                        if($arrTotalPassedTime[$imputations['imputation_date']] < 1) {
                            echo('<a href="index.php?imputation_date='.$imputations['imputation_date'].'"><i class="fa fa-plus" aria-hidden="true"></i></a>');
                        }

                        echo('</i></h3>');
                        echo('<div class="row">'); //ouverture div

                        ?>
                        <!-- en tête -->
                        <div class="col-12 col-md-6 col-xl-<?php echo($width); ?> pr-xl-3">
                            <div class="card rounded">
                                <div class="card-body">
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
                            <div class="card rounded">
                                <div class="card-body">
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

                <div class="pt-5 text-center">
                    <a class="btn btn-primary theme-btn theme-btn-ghost font-weight-bold" onclick="generate(); return false;" href="#">Générer le reporting hebdo</a>
                </div>

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

