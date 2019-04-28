<?php
    include('../login/connectToBDD/conn.php');

    if(isset($_POST['userId']) && !empty($_POST['userId']) && isset($_POST['newRole']) && !empty($_POST['newRole'])){
        $req=$bdd->prepare("UPDATE users SET role=:newRole WHERE id=:userId");
        $req->execute(array(
            'newRole'=> $_POST['newRole'],
            'userId'=> $_POST['userId']
        ));
    }
    echo(json_encode('test'));
?>