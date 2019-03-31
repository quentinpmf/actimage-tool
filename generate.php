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

if(isset($_POST) && !empty($_POST))
{
    $arrImputationsToInclude = array();
    foreach($_POST as $imputation_date => $onOff)
    {
        $arrImputationsToInclude[] = $imputation_date;
    }

    $strImputationsToInclude = implode("','", $arrImputationsToInclude); //imputations en string

    //récupération des imputations
    $req = $bdd->query("SELECT * FROM imputations WHERE imputation_date IN('$strImputationsToInclude') ORDER BY 'project_id' DESC");
    while ($imputation = $req->fetch())
    {
        $req2 = $bdd->query("SELECT * FROM projects WHERE id='".$imputation['projet_id']."'");
        while ($project = $req2->fetch())
        {
            /*
             * - VNF (Portail Internet) :
                    o   Développement du module Y (Conforme Redmine / Pas de dépassement) : finalisé et en prod, grâce à l’usage de la libraire XX
                    o   Documentation Z (Conforme Redmine / 1h de dépassement) : en cours / décalé du fait d’une priorisation de la tâche B. Plus long que prévu car interdépendance avec un autre module non terminé
             */

            $conformeRedmine = ($imputation['conforme_redmine']) ? 'Conforme Redmine' : 'Non conforme Redmine';
            $depassement = $imputation['allocated_time']-$imputation['passed_time'];
            if($depassement < 0) {
                $valueDepassement = str_replace("-", "", $depassement);
                $strDepassement = 'Dépassement de '.($valueDepassement*8).'h';
            }else{
                $strDepassement = 'Pas de dépassement';
            }

            echo('- '.$project['name'].' ('.$project['type'].') :'.'</br>');
            echo('o '.$imputation['description'].' ('.$conformeRedmine.' / '.$strDepassement.') : ');
        }

        $req3 = $bdd->query("SELECT * FROM states WHERE id='".$imputation['state']."'");
        while ($state = $req3->fetch())
        {
            echo($state['libelle']);
        }

        $remarque = $imputation['remarque'];
        if($remarque != "") {
            $remarque = " / ".$remarque;
        }

        echo($state['libelle'].$remarque."</br>");
    }
}
else
{
    echo('veuillez sélectionner au moins un jour');
}

?>

<body>

<section class="features-section py-5">
    <div class="container py-lg-5">
        <form name="form" id="formGenerate" method="POST" action="generate_2.php">
            <h3 class="mb-3 text-center font-weight-bold section-heading">Données reporting hebdo</h3>

            <div id="rows">

                <!-- en tête -->
                <div class="row">
                    <div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Choix du projet</b>
                            </div>
                        </div><!--//card-->
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
