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

if(isset($_POST['projectName']) && !empty($_POST['projectName']) && isset($_POST['projectType']) )
{
    $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
    $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
    $lower_name = str_replace($search, $replace, $_POST['projectName']);
    $lower_name = strtolower($lower_name);
    $lower_name = str_replace(" ", "-", $lower_name);
    $lower_name = str_replace("---", "--", $lower_name);
    $lower_name = str_replace("--", "-", $lower_name);

    $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
    $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
    $lower_type = str_replace($search, $replace, $_POST['projectType']);
    $lower_type = strtolower($lower_type);
    $lower_type = str_replace(" ", "-", $lower_type);
    $lower_type = str_replace("---", "--", $lower_type);
    $lower_type = str_replace("--", "-", $lower_type);

    //ajout du type de projet si il y en a un
    if($lower_type != ""){
        $lower_name = $lower_name."_".$lower_type;
    }

    //logo
    if(isset($_FILES['projectLogo']['name']) && !empty($_FILES['projectLogo']['name'])){
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
        //1. strrchr renvoie l'extension avec le point (« . »).
        //2. substr(chaine,1) ignore le premier caractère de chaine.
        //3. strtolower met l'extension en minuscules.
        $extension_upload = strtolower(  substr(  strrchr($_FILES['projectLogo']['name'], '.')  ,1)  );
        if ( in_array($extension_upload,$extensions_valides) ) echo "Extension correcte";

        $nom = "../assets/images/logos/".$lower_name.".".$extension_upload;
        $resultat = move_uploaded_file($_FILES['projectLogo']['tmp_name'],$nom);
        if (!$resultat) {
            echo "Transfert fail";
            die;
        }
    }

    $req=$bdd->prepare("INSERT INTO projects(name,type,lower,logo) VALUES (:projectName,:projectType,:projectLower,:projectLogo)");
    $req->execute(array(
        'projectName'=>$_POST['projectName'],
        'projectType'=>$_POST['projectType'],
        'projectLower'=>$lower_name,
        'projectLogo'=>$extension_upload ? $lower_name.".".$extension_upload : ""
    ));

    header('location:gestion_projets.php');
}

?>

