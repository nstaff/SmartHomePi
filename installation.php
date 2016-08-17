<!DOCTYPE html>
<html>
<body>
<?php include 'top-nav.php';?>
<?php include 'sidebar.php';?>
<div>

    <div id="main" class="col-xs-6">
        <h1>Smarthome Quick Start Guide</h1><br>
        <h3>Installation</h3><p>To perform a complete installation on a brand new Raspbian image:<br></p>
        <ol>
            <li>Download the piSetup.sh script from https://bitbucket.org/nstaffend/smarthomepi/src/.  </li>
            <li>Ensure that your Raspberry Pi has a valid internet connection.</li>
            <li>Open the terminal window</li>
            <li>Navigate to piSetup.sh</li>
            <li>Type: sudo chmod +x piSetup.sh</li>
            <li>Type: sudo ./piSetup.sh</li>
            <li>The Raspberry Pi will update, install the Apache, MySQL, and PHP server functionality, as well as some helper libraries such as cURL for PHP.&nbsp;</li>
            <li>During the script's execution you will be prompted 3 times for your MySQL root password. One of these times is to store the value into a temporary location to execute the database execution scripts.</li>
        </ol>
    </div>
</div>
</body>
</html>