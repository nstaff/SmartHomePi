
<?php 
    require_once __DIR__ .'/../lib/Ecobee.php';
    $ecobee = new Ecobee($storedValues["ACCESS_TOKEN"], $storedValues["REFRESH_TOKEN"], $storedValues["API_KEY"], $storedValues["AUTHORIZATION_CODE"], $storedValues["LastRefresh"]);
    $tokens = json_decode($ecobee->RefreshPin(), true); 
    if($tokens != null){
        echo json_encode($tokens);
        $storedValues["REFRESH_TOKEN"] = $ecobee->REFRESH_TOKEN; 
        $storedValues["ACCESS_TOKEN"] = $ecobee->ACCESS_TOKEN; 
        $storedValues["LastRefresh"] = $ecobee->lastRefresh;
    }
    