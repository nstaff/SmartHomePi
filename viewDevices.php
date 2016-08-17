<!DOCTYPE html>
    <html>
<body>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php include 'top-nav.php';?>

    <?php include 'sidebar.php';?>
        <?php
            //Get classes for use and database info    
            require_once 'conf/dbInfo.php';
            require_once 'lib/DataBroker.php';
            require_once 'lib/Device.php';
            require_once 'lib/HTMLParser.php';
            require_once 'lib/authentication.php';
            
            $broker = new DataBroker($servername, $username, $password, $dbname);
            $URL = "edit.php"
        //print rows and add edit option
        ?>
<div>
    <div id="main" class="col-xs-6">
    <h1>Devices</h1>
    <h2>Lights</h2>
    <?php
        //iterate through light array and build hyperlink
    echo HTMLParser::printDevices($broker->getAllLights(), $URL)    
    ?>
    <h2>Dimming Lights</h2>
    <?php
        //iterate through light array and build hyperlink
    echo HTMLParser::printDevices($broker->getAllDimmingLights(), $URL)    
    ?>
    <h2>Colored Lights</h2>
    <?php
        //iterate through light array and build hyperlink
    echo HTMLParser::printDevices($broker->getAllColoredLights(), $URL)    
    ?>
    <h2>Switches</h2>
    <?php
    echo HTMLParser::printDevices($broker->getAllSwitches(), $URL)    
    ?>
    <h2>Thermostats</h2>
    <?php
    echo HTMLParser::printDevices($broker->getAllThermostats(), $URL)    
    ?>

</div>
</div>
</body>
</html>
