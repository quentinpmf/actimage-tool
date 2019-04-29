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

if(isset($_GET['projectId']) && !empty($_GET['projectId']))
{
    $reqLogo=$bdd->prepare("SELECT logo FROM projects WHERE id=:projectId");
    $reqLogo->execute(array(
        'projectId'=>$_GET['projectId']
    ));
    if($reqLogo->rowCount() > 0){
        while($data=$reqLogo->fetch()){
            $pathFile = $data['logo'];
        }
        unlink ("../assets/images/logos/".$pathFile); //on supprime le fichier sur le serveur
    }

    $req=$bdd->prepare("DELETE FROM projects WHERE id=:projectId");
    $req->execute(array(
        'projectId'=> $_GET['projectId']
    ));
}

header('location:gestion_projets.php');

?>

