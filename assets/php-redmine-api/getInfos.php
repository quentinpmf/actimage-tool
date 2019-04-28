<?php

require_once '../assets/php-redmine-api/src/autoload.php';
$client = new Redmine\Client('https://redmine-web.actimage-ext.net', '86465e7b572453f8045c8f8d3b469a4fe9af20d8');

$issueId = '25524';

$issueDetails = $client->issue->show($issueId);

$total_estimated_hours = $issueDetails['issue']['total_estimated_hours'];
$subject = $issueDetails['issue']['subject'];

$array = array(
    "total_estimated_hours" => $total_estimated_hours / 8,
    "subject" => $subject,
);

echo json_encode($array);

?>