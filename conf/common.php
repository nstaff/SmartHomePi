<?php
if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    #echo 'first file success';
} else {
    #echo 'first file null';
    require_once __DIR__ . '/../../../autoload.php';
}

#Need to update this with your Hue Bridge IP address
$hueHost = "192.168.1.64";
#Need to update this with your Hue user name
$hueUsername = "nwil2SqWVF0ZB-NQk82EYE4DDr67P798NF-AwYkC";
