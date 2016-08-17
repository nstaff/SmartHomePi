<?php
require_once 'conf/dbInfo.php';
require_once 'lib/DataBroker.php';

$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$broker = new DataBroker($servername, $username, $password, $dbname);
if(isset($_COOKIE["SMART_HOME_SESSION"])){
    $session = $_COOKIE["SMART_HOME_SESSION"];
    if(!$broker->getUserBySessionKey($session)){
        header("Location: http://$host$uri/login.php");
        die();
    }
}else{
    header("Location: http://$host$uri/login.php");
    die();
}
?>
