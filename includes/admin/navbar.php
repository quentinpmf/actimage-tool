<nav class="navbar navbar-expand-md" style="padding:0px;background-color: #FF8029;color:white">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <a class="navbar-brand" href="../index.php">
                <img src="../assets/images/logo_alpha_actimage.png" alt="Logo" style="width:40px;margin-left:5px">
            </a>
        </ul>
    </div>
    <div class="mx-auto order-0">
        <span class="navbar-brand mx-auto"><?php echo("[Espace Administration] ".$_SESSION['UserPrenom']." ".$_SESSION['UserNom']) ?></span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <?php
                    if($_SESSION['UserRole'] == 2 || $_SESSION['UserRole'] == 3)
                    {
                        echo('<a style="color:white" href="../admin/index.php"><img src="../assets/images/user.png" alt="Administration"></a>&nbsp;');
                    }
                ?>

                <a style="color:white" href="../login/logout.php"><img src="../assets/images/logout.png" alt="Déconnexion"></a>
                &nbsp;
            </li>
        </ul>
    </div>
</nav>