<?php
require_once '../conf/dbInfo.php';
require_once './DataBroker.php';

$login = $_POST["username"];
$pass= $_POST["password"];

$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$broker = new DataBroker($servername, $username, $password, $dbname);
if($login == null || $pass == null){
    header("Location: http://$host$uri/../login.php");
}else{
    $broker->createNewUser($login,$pass);
    header("Location: http://$host$uri/../login.php");
}