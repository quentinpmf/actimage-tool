<?php

//Identifiants
include('vars.php');

if(isset($_POST['issueId']) && $_POST['issueId'] != "")
{
    $issueId = $_POST['issueId'];

    $handle = fopen("https://$redmine_id:$redmine_password@$redmine_url/$issueId", "r");
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);

    $subjectLine = strstr($contents, '<div class="subject">');

    if($subjectLine != false) {
        $subjectLineDiv = strstr($subjectLine, '<div>');
        $subjectLineDiv = strstr($subjectLineDiv, '<h3>');

        $subjectLineDiv = explode("<h3>", $subjectLineDiv);
        $hoursLine_explode = explode("</h3>", $subjectLineDiv[1]);

        $subject = $hoursLine_explode[0];
    }
    else{
        $subject = "";
    }
    echo json_encode($subject);
}

?>