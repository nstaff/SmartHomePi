<?php
require_once 'init.php';

$client = new \Phue\Client($hueHost, $hueUsername);
$lights = $client->getLights();
$json["success"] = $lights[1]->isOn();