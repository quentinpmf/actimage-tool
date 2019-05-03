<!DOCTYPE html>
<html lang="en">
<head>
    <title>ActiTool</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Actitool">
    <meta name="author" content="Quentin Boudinot">

    <?php include('includes/top_page.php') ?>
</head>

<?php
include('login/connectToBDD/conn.php');

//enleve les accents dans la chaine donnée et met en minuscule
function stripAccentsAndLower($str) {
    return strtolower(strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'));
}

//on fixe la date par défaut ou date URL
$imputation_date = date('Y-m-d'); //par défaut
if(isset($_GET['imputation_date']) && $_GET['imputation_date'] != "") {
    $imputation_date = $_GET['imputation_date'];
}else{
    if(isset($_GET['isSaved'])) { //si isSaved dans l'URL, on le garde
        $plus = '&isSaved=1';
        header('location:index.php?imputation_date='.$imputation_date.$plus);
    }
    else{
        header('location:index.php?imputation_date='.$imputation_date);
    }
}

//affiche l'alert success
$boolSaved = false;
if(isset($_GET['isSaved']) && $_GET['isSaved'] == 1) {
    $boolSaved = true;
}

session_start();
if(!isset($_SESSION['UserEmail']))
{
    header('location:login/login.php');
}

?>

<body>

    <?php include('includes/navbar.php') ?>

    <section class="features-section py-5">
	    <div class="container py-lg-5">
			<form name="form" id="formGenerate" method="POST" action="test.php">
				<h3 class="mb-3 text-center font-weight-bold section-heading">Entrez informations pour <input onchange="datepickerOnChange(event);" name="imputation_date" type="date" value="<?php echo($imputation_date); ?>"/></h3>

				<div id="rows">

                    <!-- caché / affiché lorsqu'échec de sauvegarde -->
                    <div class="alert alert-danger center" id="danger-alert" style="text-align: center" role="alert">Le total du temps passé dépasse 1jh.</div>

                    <!-- alert après enregistrement -->
                    <?php if($boolSaved){
                        echo('<div class="alert alert-success center" id="success-alert" role="alert">Les imputations ont bien été sauvegardées.</div>');
                    } ?>

                    <!-- en tête -->
					<div class="row">
						<div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
							<div class="card rounded">
								<div class="card-body p-6">
                                    <b>Choix du projet</b>
								</div>
							</div><!--//card-->
						</div>

						<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
							<div class="card rounded">
								<div class="card-body p-6">
                                    <b>N° ticket</b>
								</div>
							</div><!--//card-->
						</div>

                        <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body p-2">
                                    <b>Conforme Redmine</b>
                                </div>
                            </div><!--//card-->
                        </div>

						<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
							<div class="card rounded">
								<div class="card-body p-2">
                                    <b>Temps passé (jh)</b>
								</div>
							</div><!--//card-->
						</div>

						<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
							<div class="card rounded">
								<div class="card-body p-2">
                                    <b>Temps prévu (jh)</b>
								</div>
							</div><!--//card-->
						</div>

                        <div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
                            <div class="card rounded">
                                <div class="card-body p-6">
                                    <b>Etat</b>
                                </div>
                            </div><!--//card-->
                        </div>

						<div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
							<div class="card rounded">
								<div class="card-body p-6">
                                    <b>Description du travail & Remarque(s)</b>
								</div>
							</div><!--//card-->
						</div>

						<div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
							<div class="card rounded">
								<div class="card-body p-6">
                                    <b>Suppr.</b>
								</div>
							</div><!--//card-->
						</div>
					</div>

                    <!-- spinner appel AJAX -->
                    <div id="loading" style="display:none">Loading&#8230;</div>

                    <?php

                    //récupération des imputations du jour
                    $req = $bdd->query("SELECT COUNT(*) as 'nbImputationsForThisDay' FROM imputations WHERE imputation_date = '".$imputation_date."' AND user_id='".$_SESSION['UserId']."'");
                    $res = $req->fetch();
                    $nbImputationsForThisDay = $res['nbImputationsForThisDay'];

                    $i = 0; //nb d'imputations par jour
                    //récupération des imputations pour le jour en cours, si il y en à
                    if($nbImputationsForThisDay > 0) {
                        //récupération des imputations pour le jour en cours, si il y en à
                        $req = $bdd->query("SELECT * FROM imputations WHERE imputation_date = '".$imputation_date."' AND user_id='".$_SESSION['UserId']."' ORDER BY id ASC");
                        while ($imputations = $req->fetch())
                        {
                            $req2 = $bdd->query("SELECT lower FROM projects WHERE id = '".$imputations['projet_id']."'");
                            while ($proj = $req2->fetch())
                            {
                                $projectName = $proj['lower'];
                            }

                            ?>
                            <div class="row infoRow number-<?php echo($i) ?>" id="<?php echo($i) ?>">
                                <div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body">
                                            <select onchange="changeProject(this);" name="projet-<?php echo($i) ?>" style="max-width: 210px;"> <!-- récupération des projets depuis la BdD -->
                                                <?php

                                                $req2 = $bdd->query("SELECT * FROM projects ORDER BY name ASC");
                                                while ($projects = $req2->fetch())
                                                {
                                                    //selection du projet dans la liste
                                                    if($imputations['projet_id'] == $projects['id']) {
                                                        $selected = "selected";
                                                    }
                                                    else{
                                                        $selected = "";
                                                    }

                                                    //récupération conforme_redmine
                                                    if($imputations['conforme_redmine'] == 1) {
                                                        $checked = "checked";
                                                    }
                                                    else{
                                                        $checked = "";
                                                    }

                                                    echo('<option value="'.stripAccentsAndLower($projects['lower']).'" '.$selected.'>'.$projects['name'].' - '.$projects['type'].'</option>');
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body center">
                                            <input <?php if($projectName == "equipe_point" || $projectName == "multi-projets_point"){echo("style='display:none'");} ?> type="text" data-id="<?php echo($i) ?>" id="issue_number-<?php echo($i) ?>" name="issue_number-<?php echo($i) ?>" size="5" value="<?php echo($imputations['issue_number']) ?>" onchange="getIssueSubject($(this))"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <?php ?>
                                            <input <?php if($projectName == "equipe_point" || $projectName == "multi-projets_point"){echo("style='display:none'");} ?> type="checkbox" class="conforme_redmine" id="conforme_redmine-<?php echo($i) ?>" name="conforme_redmine-<?php echo($i) ?>" size="5" <?php echo($checked) ?>/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <input type="text" class="passed_time" name="passed_time-<?php echo($i) ?>" size="5" value="<?php echo($imputations['passed_time']) ?>"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <input <?php if($projectName == "equipe_point" || $projectName == "multi-projets_point"){echo("style='display:none'");} ?> type="text" class="allocated_time" id="allocated_time-<?php echo($i) ?>" name="allocated_time-<?php echo($i) ?>" size="5" value="<?php echo($imputations['allocated_time']) ?>"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <select <?php if($projectName == "equipe_point" || $projectName == "multi-projets_point"){echo("style='display:none'");} ?>
                                                    name="state-<?php echo($i) ?>" id="state-<?php echo($i) ?>" style="max-width: 210px;"> <!-- état -->
                                                <?php
                                                $j = 1; //nb d'états dans la liste

                                                $req3 = $bdd->query("SELECT * FROM states ORDER BY id asc");
                                                while ($states = $req3->fetch())
                                                {
                                                    //selection du projet dans la liste
                                                    if($imputations['state'] == $j) {
                                                        $selected = "selected";
                                                    }
                                                    else{
                                                        $selected = "";
                                                    }

                                                    echo('<option value="'.stripAccentsAndLower($states['lower']).'" '.$selected.'>'.$states['libelle'].'</option>');
                                                    $j++; //nb de projets dans la liste ++
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6">
                                            <textarea name="description-<?php echo($i) ?>" rows="2" cols="40" placeholder="Description" style="max-width: 350px;"><?php echo($imputations['description']) ?></textarea>
                                            <input type="text" class="remarques" name="remarque-<?php echo($i) ?>" size="40" placeholder="Remarques" value="<?php echo($imputations['remarque']) ?>" style="max-width: 350px;"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <a href="" onclick="remove($(this)); return false;"><img src="https://img.icons8.com/metro/26/000000/trash.png"></a>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <input type="hidden" name="data_bdd-<?php echo($i) ?>" value="<?php echo($imputations['id']) ?>"/>
                            </div>
                            <?php
                            $i++;
                        }
                    }
                    else{
                        //pas de données en bdD donc on met une ligne vide
                        ?>
                            <!-- premiere ligne -->
                            <div class="row infoRow number-0">
                                <div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body">
                                            <select onchange="changeProject(this);" name="projet-0" style="max-width: 210px;"> <!-- récupération des projets depuis la BdD -->
                                                <?php
                                                $req = $bdd->query("SELECT * FROM projects ORDER BY name ASC");
                                                while ($projects = $req->fetch())
                                                {
                                                    echo('<option value="'.stripAccentsAndLower($projects['lower']).'">'.$projects['name'].' - '.$projects['type'].'</option>');
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body center">
                                            <input type="text" data-id="0" id="issue_number-0" name="issue_number-0" size="5" value="#" onchange="getIssueSubject($(this))"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <input type="checkbox" class="conforme_redmine" name="conforme_redmine-0" id="conforme_redmine-0" size="5" checked/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <input type="text" class="passed_time" name="passed_time-0" size="5"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <input type="text" class="allocated_time" name="allocated_time-0" id="allocated_time-0" size="5"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-2 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6 center">
                                            <select name="state-0" id="state-0" style="max-width: 210px;"> <!-- état -->
                                                <?php
                                                $req = $bdd->query("SELECT * FROM states ORDER BY id ASC");
                                                while ($states = $req->fetch())
                                                {
                                                    echo('<option value="'.stripAccentsAndLower($states['lower']).'">'.$states['libelle'].'</option>');
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6">
                                            <textarea name="description-0" rows="2" cols="40" placeholder="Description" style="max-width: 350px;"></textarea>
                                            <input type="text" class="remarques" name="remarque-0" size="40" placeholder="Remarques" style="max-width: 350px;"/>
                                        </div>
                                    </div><!--//card-->
                                </div>
                                <div class="col-12 col-md-6 col-xl-1 pr-xl-3 pt-md-3">
                                    <div class="card rounded">
                                        <div class="card-body p-6">
                                            <a href="">&nbsp;</a>
                                        </div>
                                    </div><!--//card-->
                                </div>
                            </div>
                        <?php
                    }



                    ?>
				</div>

                <!-- div caché pour récupérer les projets lors du bouton : Ajouter ligne -->
                <div id="hidden_projects">
                    <?php
                    $req = $bdd->query("SELECT * FROM projects ORDER BY name ASC");
                    while ($projects = $req->fetch())
                    {
                        echo('<option value="'.stripAccentsAndLower($projects['lower']).'">'.$projects['name'].' - '.$projects['type'].'</option>');
                    }
                    ?>
                </div>

				<div class="pt-5 text-center">
					<a class="btn btn-success theme-btn theme-btn-ghost-green font-weight-bold" onclick="addRow(); return false;" id="addRow" href="">Ajouter une ligne</a>
                    <a class="btn btn-warning theme-btn theme-btn-ghost-orange font-weight-bold" onclick="save(); return false;" href="">Sauvegarder</a>
                    <a class="btn btn-info theme-btn theme-btn-ghost font-weight-bold" href="scheduler.php">Mode semaine</a>
				</div>

			</form>
	    </div><!--//container-->

    </section><!--//features-section-->

    <!-- Javascript -->
    <script type="text/javascript" src="assets/plugins/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/popper.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script type="text/javascript" src="assets/plugins/jquery-flipster/dist/jquery.flipster.min.js"></script>
    <script type="text/javascript" src="assets/js/flipster-custom.js"></script>


</body>
</html>

