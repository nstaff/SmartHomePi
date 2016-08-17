#Put this code in for turning a light on
<?php
require_once 'init.php';

$client = new \Phue\Client($hueHost, $hueUsername);
$lights = $client->getLights();
$lights[1]->setOn();
$json["success"] = $lights[1]->isOn();


include 'init.php'; $light->setBrightness(($_GET['BRI'] * 255/100)); 
$json["state"] = ($light->isOn() ? "on" : "off"); 
$json["bri"] = ceil($light->getBrightness() / 255 * 100); 