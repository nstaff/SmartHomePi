<?php include 'init.php'; 
$jsonObject = json_decode($ecobee->GetStatus()); 
$json["status"] = $ecobee->getStateAsArray();