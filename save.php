<?php

include('mysql/connect.php');

//initialisation du tableau d'imputations.
$arrImputations = [];

//création des lignes du tableau
foreach($_POST as $index => $value) {

    //les champs projet-X
    $check = strpos($index,'projet-');
    if($check !== false) {
        //récupération du numéro de ligne
        $pieces = explode("-", $index);
        $id = $pieces[1];
        $arrImputations[$id] = array(); //création des ligne du tableau
    }
}

//remplissage de chaque lignes du tableau
$boolDataBdd = false;
foreach($_POST as $index => $value) {

    //si le champ est data_bdd
    if(strstr($index,'data_bdd')) {
        $boolDataBdd = true;
    }else{
        $boolDataBdd = false;
    }

    //tout les champs sauf imputation_date
    $check = strpos($index,'-');
    if($check !== false && !$boolDataBdd) {
        //récupération du numéro de ligne
        $pieces = explode("-", $index);
        $id = $pieces[1];
        $arrImputations[$id][$index] = $value; //remplissage du tableau
    }elseif($boolDataBdd){
        //si data_bdd, alors on ajoute la valeur
        $arrImputations[$id]['data_bdd'] = $value;
    }
}

//récupération des imputations déja en bdD
$bdDImputationsIds = getBdDImputationsIds($bdd);
//si il y à des imputations déja en bdD
$boolCommunImputationsIds = array();
if(count($bdDImputationsIds) > 0) {
    foreach($bdDImputationsIds as $bdDid) { //pour chaque imputation en bdD
        foreach($arrImputations as $imputation){ //pour chaque imputation du formulaire
            if(isset($imputation['data_bdd'])) { //si y'a un data_bdd
                if($bdDid == $imputation['data_bdd']) { //si data_bdd = imputation id en bdD
                    $boolCommunImputationsIds[] = $bdDid;
                }
            }
        }
    }
}

//récupération des imputations à supprimer
$arrImputationsIdsToDelete = getImputationsToDelete($bdDImputationsIds,$boolCommunImputationsIds);
deleteImputationInBdd($bdd,$arrImputationsIdsToDelete); //suppression

//traitement pour chaque imputation du tableau (modification, insertion)
foreach($arrImputations as $index => $details) {

    if(isset($arrImputations[$index]['data_bdd']) && !in_array($arrImputations[$index]['data_bdd'], $arrImputationsIdsToDelete)) {
        //modification des imputations présentes en bdD
        $boolEqual = compareWithBdDLine($bdd,$arrImputations,$index);
        //si c'est égal on ne fait rien, si c'est pas égal, on modifie
        if(!$boolEqual) {
            //modifier la ligne
            var_dump("update : ".$arrImputations[$index]['data_bdd']);
            updateLineInBdD($bdd,$details,$index,$arrImputations[$index]['data_bdd']);
        }
    }
    else {
        //insertion d'imputations supplémentaires en bdD
        var_dump("insert");
        insertLineInBdD($bdd,$details,$index);
    }
}

//récupère l'ID du projet avec son nom lower
function getProjectId($bdd,$strProjectName)
{
    $req = $bdd->query("SELECT id FROM projects WHERE lower = '".$strProjectName."'");
    while ($imputation = $req->fetch()) {
        return $imputation['id'];
    }
}

//récupère l'ID de l'état avec son nom lower
function getStateId($bdd,$strProjectName)
{
    $req = $bdd->query("SELECT id FROM states WHERE lower = '".$strProjectName."'");
    while ($state = $req->fetch()) {
        return $state['id'];
    }
}

//compare une ligne du tableau avec la ligne en bdd
function compareWithBdDLine($bdd,$data,$index)
{
    $boolEqual = false;
    $req = $bdd->query("SELECT * FROM imputations WHERE id = '".$data[$index]['data_bdd']."'");
    while ($imputation = $req->fetch()) {
        if( $imputation['projet_id'] == getProjectId($bdd,$data[$index]["projet-".$index])
            && $imputation['issue_number'] == $data[$index]["issue_number-".$index]
            && $imputation['conforme_redmine'] == (isset($data[$index]["conforme_redmine-".$index]) && $data[$index]["conforme_redmine-".$index] == "on" ? 1 : 0)
            && $imputation['passed_time'] == $data[$index]["passed_time-".$index]
            && $imputation['allocated_time'] == $data[$index]["allocated_time-".$index]
            && $imputation['state'] == getStateId($bdd,$data[$index]["state-".$index])
            && $imputation['imputation_date'] == $_POST['imputation_date']
            && $imputation['description'] == $data[$index]["description-".$index]
            && $imputation['remarque'] == $data[$index]["remarque-".$index]
        ){
            $boolEqual = true;
        }
        return $boolEqual;
    }
}

function getBdDImputationsIds($bdd)
{
    $bdDImputationsIds = array();
    $req = $bdd->query("SELECT * FROM imputations WHERE imputation_date = '".$_POST['imputation_date']."'");
    while ($imputation = $req->fetch()) {
        $bdDImputationsIds[] = $imputation['id'];
    }
    return $bdDImputationsIds;
}

//insère une ligne en BdD
function insertLineInBdD($bdd,$details,$index)
{
    $req=$bdd->prepare("INSERT INTO imputations(projet_id,issue_number,conforme_redmine,passed_time,allocated_time,state,imputation_date,description,remarque) VALUES (:projectId,:issueNumber,:conformeRedmine,:passedTime,:allocatedTime,:state,:imputationDate,:description,:remarque)");
    $req->execute(array(
        'projectId'=> getProjectId($bdd,$details["projet-".$index]),
        'issueNumber'=> $details["issue_number-".$index],
        'conformeRedmine'=> (isset($details["conforme_redmine-".$index]) && $details["conforme_redmine-".$index] == "on" ? 1 : 0),
        'passedTime'=>$details["passed_time-".$index],
        'allocatedTime'=>$details["allocated_time-".$index],
        'state'=> getStateId($bdd,$details["state-".$index]),
        'imputationDate'=>$_POST['imputation_date'],
        'description'=>$details["description-".$index],
        'remarque'=>$details["remarque-".$index],
    ));
}

//insère une ligne en BdD
function updateLineInBdD($bdd,$details,$index,$imputationId)
{
    $req=$bdd->prepare("UPDATE imputations SET projet_id=:projectId, issue_number=:issueNumber,
    conforme_redmine=:conformeRedmine,passed_time=:passedTime,allocated_time=:allocatedTime,state=:state,
    imputation_date=:imputationDate,description=:description,remarque=:remarque WHERE id=:imputationId");
    $req->execute(array(
        'projectId'=> getProjectId($bdd,$details["projet-".$index]),
        'issueNumber'=> $details["issue_number-".$index],
        'conformeRedmine'=> $details["conforme_redmine-".$index] == "on" ? 1 : 0,
        'passedTime'=>$details["passed_time-".$index],
        'allocatedTime'=>$details["allocated_time-".$index],
        'state'=> getStateId($bdd,$details["state-".$index]),
        'imputationDate'=>$_POST['imputation_date'],
        'description'=>$details["description-".$index],
        'remarque'=>$details["remarque-".$index],
        'imputationId'=>$imputationId
    ));
}

//fait la différence entre le tableau d'ids en bdD et le tableau d'ids du formulaire
function getImputationsToDelete($bdDImputationsIds,$boolCommunImputationsIds)
{
    return array_diff($bdDImputationsIds, $boolCommunImputationsIds);
}

//supprime les imputations dans la bdD avec les ID du tableau en paramètre
function deleteImputationInBdd($bdd, $arrImputationsIdsToDelete)
{
    foreach($arrImputationsIdsToDelete as $imputationIdToDelete) { //pour chaque imputation a supprimer
        $req=$bdd->prepare("DELETE FROM imputations WHERE id=:imputationIdToDelete");
        $req->execute(array(
            'imputationIdToDelete'=> $imputationIdToDelete
        ));
    }
}

?>