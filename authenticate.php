<?php
include 'conf/dbInfo.php';
require_once 'lib/DataBroker.php';
$broker = new DataBroker($servername, $username, $password, $dbname);

$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$login = $_POST["username"];
$pass = $_POST["password"];
error_log($login);
error_log($pass);

$result = $broker->loginByUsernameAndPassword($login,$pass);

if(!$result){
    header("Location: http://$host$uri/login.php");

}else{
    header("Location: http://$host$uri/viewDevices.php");
}
?>