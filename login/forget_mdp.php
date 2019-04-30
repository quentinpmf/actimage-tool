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

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">


    <title>ActiTool</title>

    <!-- CSS login -->
    <link href="../assets/css/login.css" rel="stylesheet" type="text/css">
</head>

<body>
<!-- Page Content -->
<div class="container">
    <div class="login-page">
        <div class="form">

            <h2>Mot de passe oublié</h2>

            <form class="login-form" method="post" action="connectToBDD/doForgetMdp.php" >
                <input style="border-bottom: 1px solid black;" type="email" id="email" name="login_email" placeholder="Email" value="" maxlength="60" />
                <button>Envoyer un email</button>
                <p class="message">Déja enregistré? <a href="login.php">Connectez-vous</a></p>
                <p class="message2">Pas enregistré? <a href="register.php">Créer un compte</a></p>
            </form>

        </div>
    </div>
</div>
</body>

</html>