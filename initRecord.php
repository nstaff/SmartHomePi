<?php 
        include 'conf/dbInfo.php';
        include 'lib/DataBroker.php';
	require_once 'lib/DeviceType.php';
	
	$broker = new DataBroker($servername, $username, $password, $dbname);
	
        
	$type = $_POST['type'];
        $name = $_POST['name'];
        $newRecordId = $broker->initRecord($type, $name);
	
	header("Location: edit.php?id=".$newRecordId."&type=".$type);