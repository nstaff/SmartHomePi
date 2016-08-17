#put this code in for turning a light off
<?php
require_once 'init.php';

$client = new \Phue\Client($hueHost, $hueUsername);
$lights = $client->getLights();
$lights[1]->setOn(FALSE);
$json["success"] = !$lights[1]->isOn();