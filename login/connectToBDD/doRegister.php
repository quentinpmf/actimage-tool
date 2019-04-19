<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
    include "conn.php";
    include "classes.php";

    $user = new user();

    $nom = $_POST["inscription_nom"]; //stockage de la valeur tap�e par l'utilisateur dans la variable a
    $prenom = $_POST["inscription_prenom"]; //stockage de la valeur tap�e par l'utilisateur dans la variable b
	$email = $_POST["inscription_email"]; //stockage de la valeur tap�e par l'utilisateur dans la variable a
	$mdp = $_POST["inscription_mdp"]; //stockage de la valeur tap�e par l'utilisateur dans la variable a
	$mdp2 = $_POST["inscription_mdp2"]; //stockage de la valeur tap�e par l'utilisateur dans la variable a

	$champs_verif = "false";

	if(empty($nom) OR empty($prenom) OR empty($email) OR empty($mdp) OR empty($mdp2) )
	{
        header("location: ../register.php?error=champs_manquant");exit();
	}
	else
	{
	    if($mdp !== $mdp2)
        {
            header("location: ../register.php?error=mdp");exit();
        }
        else
        {
           $champs_verif = "true" ;
        }
	}

	$reqmail = $bdd->query("SELECT email FROM users WHERE email='$email'");
	while($donneesmail=$reqmail->fetch(PDO::FETCH_OBJ))
	{
		$emailbdd=$donneesmail->email;
	}

	if(empty($emailbdd))
	{
		$passmail="true";
	}
	else
	{
		if($email==$emailbdd)
		{
			$passmail="false";
            header("location: ../register.php?error=email_deja_present");exit();
		}
		else
		{
			$passmail="true";
		}
	}

	//bloquer l'inscription si email d�ja enregistr�
	if($passmail == "true" And $champs_verif == "true")
	{
        //création utilisateur
	    $user->setUserEmail($_POST['inscription_email']);
		$user->setUserPassword(sha1($_POST['inscription_email']));
		$user->setUserNom($_POST['inscription_nom']);
		$user->setUserPrenom($_POST['inscription_prenom']);
        $user->setUserPassword(sha1($_POST['inscription_mdp']));
        $user->InsertUser();
        header("location: ../register.php?inscription=ok");
    }
    else
    {
        header("location: ../register.php?inscription=nok");
    }

