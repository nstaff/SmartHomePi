<!DOCTYPE html>
<html>
<body>
<?php include 'top-nav.php';?>
<?php include 'sidebar.php';?>
<style>
.sidebar {
height: 147%;
}
</style>
<div>

    <div id="main" class="col-xs-6">
        <p></p><h3>Device Setup</h3><p><br></p>
        Each device maybe added by going to the smart home web interface accessible via http://yourraspberrypiIPaddress/smarthome/index.html<br>
        From here click on Add Device where you will be presented with a menu to select the device type. Selecting the device type creates the database record for the device and allows you to input the PHP code associated with each device action.<br>
        Two additional fields are provided to assist the developer. <br>
        <h4>Return values</h4><p>The commands will return a JSON object that is parsed from an array called $json. In order to properly return the status of execution of a command the user needs to include:</p>
        $json["success"] = $yourSuccessCondition;somewhere in their PHP code. Otherwise the return value will always be false.&nbsp;
        <h4>Device Initialization (INIT)</h4><p>The INIT field is where you want to put any calls that get made repetitively at the beginning of accessing a device. For example, a Philips Hue needs the Bridge IP address and user name to make any calls. Putting the following code into the INIT field:</p>
        <p>&lt;?php&nbsp;</p>
        $hueBridge = "192.168.1.100"; <br>
        $userName= "myHueUserName ";<br>
        Will allow the user to have these variables or constructs as necessary. In order to utilize the information stored in the INIT field the user needs to include the 'init.php' file in their command code:<p>include 'init.php';</p>
        This include statement will trigger generation and execution of the initialization. Make sure to include this line in the commands where you need access to these variables.<br>
        <h4>Persistent Variables (VARS)</h4><p>The vars field addresses use of variables that may need to be persistent between runs of PHP code. For example, saving a token that changes from run to run. The information is stored as a JSON object in the database and is loaded automatically at runtime of any PHP code. Storing your variables in the VARS field will provide array style access throughout the run of your code. For example, if the user needs to store an API_KEY value to interface with the device the user may store it in the VARS field as follows:<br></p><p><font face="Segoe UI">{"API_KEY":"myApiKey"}</font></p><p><font face="Segoe UI">At runtime the values from the VARS field are loaded into a Key-Value pair array called $storedValues which is available during the execution of any commands run on the platform. To access the value shown above at runtime, call:</font></p><p><font face="Segoe UI">$storedValues["API_KEY"]</font></p><p><font face="Segoe UI">These values will be updated in JSON format in the device's VARS field after any command completion.</font><font face="Segoe UI">NOTE: When utilizing the Test functionality on the device's Edit page, the variables will not be updated at the end of the run.</font>
        </div>
    </div>
</body>
</html>
