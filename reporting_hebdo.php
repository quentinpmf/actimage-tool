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

if(isset($_POST) && !empty($_POST)) {

    $formatted_date = str_replace(" ", "\n", $_POST['consulting_commercial']);

    $txt = "<p><b>==> Projection / Analyse qualitative <==</b></p>";
    $txt .= "<p>".$_POST['analyse_qualitative']."</p>";
    $txt .= "<p><b>==> Projection / Analyse quantitative <==</b></p>";
    $txt .= $_POST['analyse_quantitative'];

    $consulting = "<p><u>Consulting :</u></p>"
        ."<ul>";
    $consulting .= "<li><b>Commercial</b></li>";
    $consulting .= "<ul>".$_POST['consulting_commercial']."</ul>";
    $consulting .= "<li><b>RH</b></li>";
    $consulting .= "<ul>".$_POST['consulting_rh']."</ul>";
    $consulting .= "<li><b>Déplacement(s)</b></li>";
    $consulting .= "<ul>".$_POST['consulting_deplacements']."</ul>";
    $consulting .= "</ul>";

    $txt .= $consulting;

    $initConges = "<p><u>Congés / Fériés :</u></p>";
    $conges_feries = $initConges;

    for($i=0;$i<7;$i++)
    {
        $conges = "<li>RAS</li>";
        $libelle_conges = "conges_feries-".$i;
        $libelle_justificatif = "justificatif-conges-".$i;

        if(isset($_POST[$libelle_conges]) && $_POST[$libelle_conges] != ""){
            $timestamp = strtotime($_POST[$libelle_conges]);
            $formatted_date = date("d-m-Y", $timestamp);
            $formatted_date = str_replace("-", "/", $formatted_date);

            $conges = "<li>".$formatted_date;

            if(isset($_POST[$libelle_justificatif]) && $_POST[$libelle_justificatif] != ""){
                $conges .= " : ".$_POST[$libelle_justificatif];
            }

            $conges .= "</li>";
        }else{
            if(!isset($_POST[$libelle_conges]) || $_POST[$libelle_conges] == "" ){
                if($conges_feries == $initConges){
                    $conges_feries .= $conges;
                }
                break;
            }
        }

        $conges_feries .= $conges;
    }

    $txt .= $conges_feries;
}

?>

<body>

<section class="features-section py-5">
    <div class="container py-lg-5">

        <h3 class="mb-3 text-center font-weight-bold section-heading">Reporting hebdo généré</h3>

        <div class="row">
            <div class="col-12 col-md-12 col-xl-12 pr-xl-12 pt-md-12">
                <div class="card-body nocolor">
                    <textarea id="editor2" name="reporting_hebdo" placeholder="Description"><?php echo($txt); ?></textarea>
                </div>
            </div>
        </div>

    </div>
</section>

</body>

<script>
    CKEDITOR.replace( 'editor2' );
    CKEDITOR.config.height = 600;
</script>