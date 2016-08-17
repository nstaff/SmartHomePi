<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <?php include 'top-nav.php';?>
        <?php include 'sidebar.php';?>
        <?php
            //get necessary objects
            require_once 'conf/dbInfo.php';
            require_once 'lib/DataBroker.php';
            require_once 'lib/Device.php';
            require_once 'lib/HTMLParser.php';
            //Declare a broker to do the heavy lifting
            $broker = new DataBroker($servername, $username, $password, $dbname);
            
            //If something is passed in $_GET, handle the deletion, then redirect
            //back to this page to clear the URL (avoids reload errors in an 
            //imperfect way)
            if(!empty($_GET)){
                $idNum = $_GET['id'];
                $broker->removeDevice($idNum);
                header("Location: delete.php"); 
                exit();
            }
            
            
        
        //print rows and add delete hyperlink info
        ?>
<div id="main">
    <h1>Devices</h1>
    <p>Click a device below to delete it. This CANNOT be undone.</p>
    <h2>Lights</h2>
    <?php
        //build lights and display
        echo HTMLParser::printDevices($broker->getAllLights(), "delete.php");    
    ?>
    <h2>Dimming Lights</h2>
    <?php
        //build dimming lights and display
        echo HTMLParser::printDevices($broker->getAllDimmingLights(), "delete.php");    
    ?>
    <h2>Colored Lights</h2>
    <?php
        //build colored lights and display
        echo HTMLParser::printDevices($broker->getAllColoredLights(), "delete.php");    
    ?>
    <h2>Switches</h2>
    <?php
        //build switches
        echo HTMLParser::printDevices($broker->getAllSwitches(), "delete.php");    
    ?>
    <h2>Thermostats</h2>
    <?php
        //build thermostats
        echo HTMLParser::printDevices($broker->getAllThermostats(), "delete.php"); 
    ?>
</div>
    </body>
</html>
