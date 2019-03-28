<?php

//Identifiants
include('vars.php');

if(isset($_POST['issueId']) && $_POST['issueId'] != "") {

    $issueId = $_POST['issueId'];

    $handle = fopen("https://$redmine_id:$redmine_password@$redmine_url/$issueId", "r");
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);

    $hoursLine = strstr($contents, '<div class="estimated-hours attribute">');

    if ($hoursLine != false) {
        $hoursLineDiv = strstr($hoursLine, '<div class="label">');
        $hoursLineDiv = strstr($hoursLineDiv, 'Temps estimé:</div><div class="value">');
        $hoursLineDiv = strstr($hoursLineDiv, '<div class="value">');
        $hoursLineDiv = explode('</div>', $hoursLineDiv);
        $hoursLine_explode = explode('>', $hoursLineDiv[0]);
        $hours = str_replace(" h", "", $hoursLine_explode[1]);
        $hours = str_replace(".00", "", $hours);

        $hoursFormat = $hours / 8;
    } else {
        $hoursFormat = "";
    }
    echo json_encode($hoursFormat);
}

?>