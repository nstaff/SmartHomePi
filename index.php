<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html><head>
        <title>Smarthome Data Manager</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles/bootstrap.css" rel="stylesheet" />
        <link href="styles/styles.css" rel="stylesheet" />
        <link href="styles/font-awesome.css" rel="stylesheet" />
</head>
    <body>
        <?php include 'top-nav.php';?>
        
            <?php include 'sidebar.php';?>
            <style>
		.sidebar{
			height:135%;
		}
	   </style>
		<div id="main" class="col-xs-6">
                <h1>Welcome to the SWENG 500 SmartHome data manager.</h1>

                <h3>Devices</h3>
                <p>Welcome to the SmartPi open smart home server!</p>
                <p>This web front end is where you can add devices, users, and configure your smart home server for running in your home. 
                We support two devices at this time. The Philips Hue (both color, and non-color versions) as well as the Ecobee thermostat. These devices
                may be easily added to your configuration with only the most basic setup procedures from the manufacturer's web site.</p>
                
                <p>Additionally the SmartPi smart home currently supports 3 generic device types: Light (regular, dimming, colored), Switch, and Thermostat. Each device has its own unique database record. Each of the devices support various commands:</p>

                <h4>Light Commands</h4>
                <ol>
                    <li>On</li> 
                    <li>Off</li>
                    <li>Get State</li>
                    <li>Set Brightness</li>
                    <li>Set RGB</li>
                </ol>
                <h4>Switch Commands</h4>
                <ol>
                    <li>On</li> 
                    <li>Off</li>
                    <li>Get State</li>
                </ol>
                <h4>Thermostat Commands</h4>
                <ol>
                    <li>Get Temp</li>
                    <li>Set Temp</li>
                    <li>Cool</li>
                    <li>Heat</li>
                    <li>Auto</li>
                    <li>Fan On</li>
                </ol>

                <p>Access commands using a URL with query params like this:</p>
                http://www.raspiurl.com/smarthome/executeCommand.php?id=3&amp;command=ON_CMD<br>
                <p>However, this only matters for developers who want to use their own interface. When using the SmartHome Android App, the App takes care of all of this.
                <h3>Supported Devices</h3>
                <p>This app currently supports the Philips Hue and Ecobee by default. To get started adding devices, click on Add Device in the header menu</p>
                    
            </div>
        </div>
    </body>
</html>
