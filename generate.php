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
    <script src="assets/ckeditor/ckeditor.js"></script>

    <?php include('includes.php') ?>
</head>

<?php
include('mysql/connect.php');

session_start();
if(!isset($_SESSION['UserEmail']))
{
    header('location:login/login.php');
}

include('includes/navbar.php');

$txt = "<p><u>Projets :</u></p>"
    ."<ul>";
$strLastProject = "";
$boolULopen = false;

if(isset($_POST) && !empty($_POST))
{
    $arrImputationsToInclude = array();
    foreach($_POST as $imputation_date => $onOff)
    {
        $arrImputationsToInclude[] = $imputation_date;
    }

    $strImputationsToInclude = implode("','", $arrImputationsToInclude); //imputations en string

    //récupération des imputations
    $req = $bdd->query("SELECT * FROM imputations WHERE imputation_date IN('$strImputationsToInclude') AND user_id=".$_SESSION['UserId']." ORDER BY `projet_id` ASC");
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

            $fullProjectName = $project['name'].' ('.$project['type'].')';
            if($strLastProject != $fullProjectName){
                if($boolULopen)
                {
                    $txt .= '</ul>';
                }
                $txt .= '<li><b>'.$fullProjectName.' : </b>'
                    .'<ul>';
                $boolULopen = true;
            }
            $txt .= '<li> '.$imputation['description'].' ('.$conformeRedmine.' / '.$strDepassement.') : ';

            $strLastProject = $fullProjectName;
        }

        $req3 = $bdd->query("SELECT * FROM states WHERE id='".$imputation['state']."'");
        while ($state = $req3->fetch())
        {
            $txt .= $state['libelle'];
        }

        $remarque = $imputation['remarque'];
        if($remarque != "") {
            $remarque = " / ".$remarque;
        }

        $txt .= $state['libelle'].$remarque.'</li>';
    }
}
else
{
    header('location:scheduler?error=noImputationsSelected');
}

$txtAnalyseQualitative = "- Nécessité de monter en compétences sur la technologie SharePoint 2016
- Client dépassant les durées prévues pour les points quinzomadaires
- Nécessité de revoir le mode organisationnel concernant la validation des livrables de recette";

?>

<body>

<section class="features-section py-5">
    <div class="container py-lg-5">
        <form name="form" id="formGenerate" method="POST" action="reporting_hebdo.php">
            <h3 class="mb-3 text-center font-weight-bold section-heading">Données reporting hebdo</h3>

            <div id="rows">

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Projection / Analyse qualitative</b>
                            </div>
                        </div><!--//card-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                            <div class="card-body nocolor">
                                <textarea name="analyse_qualitative" rows="4" cols="190" placeholder="<?php echo($txtAnalyseQualitative); ?>"></textarea>
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Projection / Analyse quantitative</b>
                            </div>
                        </div><!--//card-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card-body nocolor">

                            <!--
                            <ul>
                                <li>&nbsp;April (TMA) :
                                    <ul>
                                        <li>1 (Conforme Redmine / Pas de d&eacute;passement) : En d&eacute;veloppement / 1</li>
                                        <li>test2</li>
                                    </ul>
                                </li>
                                <li>April (EVOLUTIONS) :&nbsp;
                                    <ul>
                                        <li>test1</li>
                                        <li>test2</li>
                                    </ul>
                                </li>
                            </ul>
                            -->

                            <textarea id="editor1" name="analyse_quantitative" rows="10" cols="190" placeholder="Description"><?php echo($txt); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <b>Consulting</b>
                            </div>
                        </div><!--//card-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card-body nocolor">
                            <span class="alignleft"><b>Commercial</b></span>
                            <span class="alignright"><textarea name="consulting_commercial" rows="1" cols="170">RAS</textarea></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card-body nocolor">
                            <span class="alignleft"><b>RH</b></span>
                            <span class="alignright"><textarea name="consulting_rh" rows="1" cols="170">RAS</textarea></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card-body nocolor">
                            <span class="alignleft"><b>Déplacement(s)</b></span>
                            <span class="alignright"><textarea name="consulting_deplacements" rows="1" cols="170">RAS</textarea></span>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div class="card rounded">
                            <div class="card-body p-6">
                                <span class="alignleft"><b>Congés / Fériés</b></span>
                                <span class="alignright"><a onclick="addDatePickerReporting(event);"><i class="fa fa-plus" aria-hidden="true"></i></a></span>
                            </div>
                        </div><!--//card-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                        <div id="conges_feries">
                            <div class="card-body nocolor congesFeriesLine">
                                <input onfocusout="datepickerReporting(event);" name="conges_feries-0" type="date"/>
                                <input type="text" name="justificatif-conges-0" name="justificatif-conges-0" placeholder="Justificatif" size="50"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 text-center">
                    <input type="submit" value="Générer le reporting hebdo" class="btn btn-primary theme-btn theme-btn-ghost font-weight-bold">
                </div>

            </div>
        </form>
    </div>
</section>

</body>

<script>
    CKEDITOR.replace( 'editor1' );
</script>