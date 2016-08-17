<?php include 'init.php'; 
$result = $ecobee->SetMode('cool'); 
$ecobee->GetStatus();  
$json["status"] = $ecobee->getStateAsArray();