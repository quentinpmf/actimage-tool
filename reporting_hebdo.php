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

if(isset($_POST) && !empty($_POST)) {
    var_dump($_POST);
}

?>

<body>

</body>
