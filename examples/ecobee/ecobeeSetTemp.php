<?php include 'init.php';

        $result = $ecobee->SetHoldTemp($_GET['temp']);
        $json["status"] = $ecobee->getStateAsArray();
