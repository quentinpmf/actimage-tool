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
        <link rel="icon" href="../img/favicon.png">

        <title>Drag'n Resume</title>
        <!-- Custom styles for this template -->

        <style>
            .login-page {
                width: 360px;
                padding: 8% 0 0;
                margin: auto;
            }
            .form {
                position: relative;
                z-index: 1;
                background: #FFFFFF;
                max-width: 360px;
                margin: 0 auto 100px;
                padding: 45px;
                text-align: center;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input {
                outline: 0;
                background: #f2f2f2;
                width: 100%;
                border: 0;
                margin: 0 0 15px;
                padding: 15px;
                box-sizing: border-box;
                font-size: 14px;
            }
            .form button {
                text-transform: uppercase;
                outline: 0;
                background: #ff632c;
                width: 100%;
                border: 0;
                padding: 15px;
                color: #FFFFFF;
                font-size: 14px;
                cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
                background: #f71037;
            }
            .form .message {
                margin: 15px 0 0;
                color: #b3b3b3;
                font-size: 12px;
            }
            .form .message a {
                color: #ff632c;
                text-decoration: none;
            }
            .form .register-form {
                display: none;
            }

            .form .message2 {
                margin: 0px 0 0;
                color: #b3b3b3;
                font-size: 12px;
            }

            .container:before, .container:after {
                content: "";
                display: block;
                clear: both;
            }
            .container .info {
                margin: 50px auto;
                text-align: center;
            }
            .container .info h1 {
                margin: 0 0 15px;
                padding: 0;
                font-size: 36px;
                font-weight: 300;
                color: #1a1a1a;
            }
            .container .info span {
                color: #4d4d4d;
                font-size: 12px;
            }
            .container .info span a {
                color: #000000;
                text-decoration: none;
            }
            .container .info span .fa {
                color: #EF3B3A;
            }
            body {
                background: 	#f71037; /* fallback for old browsers */
                background: -webkit-linear-gradient(right, #f71037, #ff632c);
                background: -moz-linear-gradient(right, #f71037, #ff632c);
                background: -o-linear-gradient(right, #f71037, #ff632c);
                background: linear-gradient(to left, #f71037, #ff632c);
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .succes{
                color:green;
            }

            .erreur{
                color:red;
            }

            input[type=text] {
                box-sizing: border-box;
            }

            /*!
             * Start Bootstrap - Portfolio Item (https://startbootstrap.com/template-overviews/portfolio-item)
             * Copyright 2013-2017 Start Bootstrap
             * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-portfolio-item/blob/master/LICENSE)
             */

            body {
                padding-top: 54px;
            }

            .formation_info
            {
                border-left: 5px solid white;
                background-color: lightgrey;
            }

            .formation_info a
            {
                color:black;
            }

            .formation_info a:hover
            {
                text-decoration: none;
            }

            .RbtnMargin{
                margin-left: 5px;
            }

            .sessions_list{
                margin-bottom: 25px;
            }

            .divExportPDF
            {
                margin-left: -20px;
            }

            .divCreateFormationButton
            {
                margin-bottom: 100px;
            }

            .white{
                color:white;
            }

            .btn-secondary_ :hover{
                text-decoration: none;
            }

            @media (min-width: 992px) {
                body {
                    padding-top: 56px;
                }
            }
        </style>
    </head>

    <body>
        <!-- Page Content -->
        <div class="container">
            <div class="login-page">
                <div class="form">

                    <h2>Connexion</h2>

                    <?php
                        if(isset($_GET['error']))
                        {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                Mauvais Identifiants :(
                            </div>
                        <?php
                        }
                    ?>

                    <form class="login-form" method="post" action="connectToBDD/doLogin.php" >
                        <input style="border-bottom: 1px solid black;" type="email" id="email" name="login_email" value="" placeholder="Email" maxlength="60" />
                        <input style="border-bottom: 1px solid black;" type="password" id="motdepasse" name="login_password" value="" placeholder="Mot de passe" maxlength="20" />
                        <button>Connexion</button>
                        <p class="message">Pas enregistré? <a href="register.php">Créer un compte</a></p>
                        <p class="message2">Vous avez oublié votre mot de passe? <a href="forget_mdp.php">Cliquez ici</a></p>
                    </form>

                </div>
            </div>
        </div>
    </body>

</html>