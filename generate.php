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
    <script src="assets/ckeditor/ckeditor.js"></script>

    <?php include('includes/top_page.php') ?>
</head>

<?php
include('login/connectToBDD/conn.php');

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

    $oldIssueNumber = "";
    $oldDepassement = "";

    //récupération des imputations
    $req = $bdd->query("SELECT * FROM imputations WHERE imputation_date IN('$strImputationsToInclude') AND user_id=".$_SESSION['UserId']." ORDER BY `projet_id` ASC");
    while ($imputation = $req->fetch())
    {
        $depassement = "";
        if(isset($oldIssueNumber) && $oldIssueNumber != "" && $oldIssueNumber == $imputation['issue_number'] && isset($imputation['issue_number']) && $imputation['issue_number'] != "#")
        {
            $depassement = $imputation['allocated_time']-$imputation['passed_time'] + $oldDepassement;
        }
        else{
            $oldIssueNumber = "";
            $oldDepassement = "";
        }

        $req2 = $bdd->query("SELECT * FROM projects WHERE id='".$imputation['projet_id']."'");
        while ($project = $req2->fetch())
        {
            /*
             * - VNF (Portail Internet) :
                    o   Développement du module Y (Conforme Redmine / Pas de dépassement) : finalisé et en prod, grâce à l’usage de la libraire XX
                    o   Documentation Z (Conforme Redmine / 1h de dépassement) : en cours / décalé du fait d’une priorisation de la tâche B. Plus long que prévu car interdépendance avec un autre module non terminé
             */

            $conformeRedmine = ($imputation['conforme_redmine']) ? 'Conforme Redmine' : 'Non conforme Redmine';

            $issueId = $imputation['issue_number'];

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

        $oldIssueNumber = $imputation['issue_number'];
        if(isset($imputation['allocated_time']) && $imputation['allocated_time'] != ""){
            $oldDepassement = $imputation['allocated_time']-$imputation['passed_time'];
        }else{
            $oldDepassement = 0;
        }
    }
}
else
{
    header('location:scheduler.php?error=noImputationsSelected');
}

$txtAnalyseQualitative = "- Nécessité de monter en compétences sur la technologie SharePoint 2016
- Client dépassant les durées prévues pour les points quinzomadaires
- Nécessité de revoir le mode organisationnel concernant la validation des livrables de recette";

//Récupération des jours fériés dans la semaine
function getHolidays($year = null)
{
        if ($year === null)
        {
                $year = intval(strftime('%Y'));
        }

        $easterDate = easter_date($year);
        $easterDay = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear = date('Y', $easterDate);

        $holidays = array(
                // Jours feries fixes
                mktime(0, 0, 0, 1, 1, $year),// 1er janvier
                mktime(0, 0, 0, 5, 1, $year),// Fete du travail
                mktime(0, 0, 0, 5, 8, $year),// Victoire des allies
                mktime(0, 0, 0, 7, 14, $year),// Fete nationale
                mktime(0, 0, 0, 8, 15, $year),// Assomption
                mktime(0, 0, 0, 11, 1, $year),// Toussaint
                mktime(0, 0, 0, 11, 11, $year),// Armistice
                mktime(0, 0, 0, 12, 25, $year),// Noel

                // Jour feries qui dependent de paques
                mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear),// Lundi de paques
                mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),// Ascension
                mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear), // Pentecote
        );

        sort($holidays);

        return $holidays;
}
function getHolidaysInThisWeek(){
    $date = date('Y-m-d');
    $week = date("W", strtotime($date));
    $year = date("Y", strtotime($date));

    $week_array = array();
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    for($i = 0;$i < 5;$i++){
        $week_array[$i] = strtotime($dto->format('Y-m-d'));
        $dto->modify('+1 days');
    }

    $arrHolidaysInThisWeek = array();
    $holidays = getHolidays(2019);

    foreach($week_array as $day){
        foreach($holidays as $holidayDay){
            if($day == $holidayDay)
            {
                $arrHolidaysInThisWeek[] = date('Y-m-d', $day);
            }
        }
    }
    return $arrHolidaysInThisWeek;
}

$arrHolidaysInThisWeek = getHolidaysInThisWeek();

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
                                <textarea required name="analyse_qualitative" rows="4" cols="190" placeholder="<?php echo($txtAnalyseQualitative); ?>"></textarea>
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
                            <?php
                            if(!empty($arrHolidaysInThisWeek)){
                                $i = 0;
                                foreach($arrHolidaysInThisWeek as $index=>$date){
                                    echo('
                                            <div class="card-body nocolor congesFeriesLine">
                                                <input onfocusout="datepickerReporting(event);" value="'.$date.'" name="conges_feries-'.$i.'" type="date"/>
                                                <input type="text" name="justificatif-conges-'.$i.'" name="justificatif-conges-'.$i.'" value="Férié" size="50"/>
                                             </div>
                                        ');
                                    $i++;
                                }
                            }else{
                                echo('
                                        <div class="card-body nocolor congesFeriesLine">
                                            <input onfocusout="datepickerReporting(event);" name="conges_feries-0" type="date"/>
                                            <input type="text" name="justificatif-conges-0" name="justificatif-conges-0" placeholder="Justificatif" size="50"/> 
                                        </div>
                                    ');
                            }
                            ?>
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
    CKEDITOR.config.height = 200;
</script>