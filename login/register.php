<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Actitool">
    <meta name="author" content="Quentin Boudinot">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <title>ActiTool</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- CSS login -->
    <link href="../assets/css/login.css" rel="stylesheet" type="text/css">

</head>

<?php
//config
include "connectToBDD/conn.php";
?>

<body>
    <!-- Page Content -->
    <div class="container">
        <div class="login-page">
            <div class="form">

                <section class="section-gap-other-pages">
                    <div class="title text-center">

                        <h2>Inscription</h2>

                        <?php if(isset($_GET['error'])){ ?>
                            <div class="alert alert-danger" role="alert">
                                <?php
                                switch($_GET['error'])
                                {
                                    case 'champs_manquant':
                                        echo 'Veuillez remplir tous les champs';
                                        break;
                                    case 'caractere_mdp':
                                        echo 'Le mot de passe doit faire au moins 4 caractères';
                                        break;
                                    case 'verification_mdp':
                                        echo 'Les deux mots de passe doivent etre identiques';
                                        break;
                                    case 'email_deja_present':
                                        echo 'Cet email est déja enregistré';
                                        break;
                                }
                                ?>
                            </div>
                        <?php }elseif(isset($_GET['inscription']) && $_GET['inscription'] == 'ok'){
                            ?>
                            <div class="alert alert-success" role="alert">
                                Inscription effectuée.
                            </div>
                            <?php
                        }elseif(isset($_GET['inscription']) && $_GET['inscription'] == 'nok'){
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Veuillez vérifier tous les champs.
                            </div>
                            <?php
                        }?>

                        <form class="login-form" method="post" action="connectToBDD/doRegister.php" enctype="multipart/form-data">
                            <input style="border-bottom: 1px solid black;" type="text" id="prenom" name="inscription_prenom" placeholder="Prénom" value="" maxlength="50" />
                            <input style="border-bottom: 1px solid black;" type="text" id="nom" name="inscription_nom" placeholder="Nom" value="" maxlength="50" />
                            <input style="border-bottom: 1px solid black;" type="email" id="email" name="inscription_email" placeholder="Email" value="" maxlength="50" />
                            <input style="border-bottom: 1px solid black;" type="password" id="mdp" name="inscription_mdp" placeholder="Mot de passe" value="" maxlength="50" />
                            <input style="border-bottom: 1px solid black;" type="password" id="mdp2" name="inscription_mdp2" placeholder="Confirmation mot de passe" value="" maxlength="50" />
                            <button>S'inscrire</button>
                            <p class="message">Déja inscrit? <a href="login.php">Se connecter</a></p>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>
</body>

</html>