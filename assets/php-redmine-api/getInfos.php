<?php

require_once 'src/autoload.php';
$client = new Redmine\Client('https://redmine-web.actimage-ext.net', '86465e7b572453f8045c8f8d3b469a4fe9af20d8');

$array = array();

if(isset($_POST['issueId']) && $_POST['issueId'] != "")
{
    $issueId = $_POST['issueId'];
    $issueDetails = $client->issue->show($issueId);

    if(isset($issueDetails['issue']['total_estimated_hours']) && $issueDetails['issue']['total_estimated_hours'] != 0){
        $total_estimated_hours = $issueDetails['issue']['total_estimated_hours'] / 8;
    }else{
        $total_estimated_hours = "";
    }
    $subject = $issueDetails['issue']['subject'];

    $array = array(
        "total_estimated_hours" => $total_estimated_hours,
        "subject" => $subject,
    );
}

echo json_encode($array);

?>