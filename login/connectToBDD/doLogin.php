<?php
    session_start();
        include "classes.php";
        $user = new user();
        $user->setUserEmail($_POST['login_email']);
        $user->setUserPassword(sha1($_POST['login_password']));
        if($user->Userlogin()==true)
        {
            $_SESSION['UserId']=$user->getUserId();
            $_SESSION['UserEmail']=$user->getUserEmail();
			$_SESSION['UserPassword']=$user->getUserPassword();
            $_SESSION['UserNom']=$user->getUserNom();
            $_SESSION['UserPrenom']=$user->getUserPrenom();
            $_SESSION['UserRole']=$user->getUserRole();
        }

?>