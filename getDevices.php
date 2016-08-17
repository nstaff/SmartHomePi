<?php
include 'conf/dbInfo.php';
require_once 'lib/DataBroker.php';
$broker = new DataBroker($servername, $username, $password, $dbname);

echo json_encode($broker->getAllDevices());

