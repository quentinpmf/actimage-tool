<?php 
  try
  {
    //$bdd = new PDO('mysql:host=localhost;dbname=GestionProduction;charset=utf8', 'root', '');
    $bdd = new PDO('mysql:host=localhost;dbname=actimage-tool;charset=utf8', 'root', '');
    //$bdd = new PDO('mysql:host=localhost;dbname=u340604686_bdd;charset=utf8', 'u340604686_user', '5yV28K7yIPbXz3nzjz');
  }
  catch(Exception $e)
  {
    die('Erreur : '.$e->getMessage());
  }
?>