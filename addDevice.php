
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
            require_once 'lib/DeviceType.php';
            require_once 'lib/Commands.php';
            ?>
    <div id="main" class="col-xs-6" >

    <h1>Choose your device type.</h1>


        <form id="commandInput" action="initRecord.php" method="post">
          Device Name: <input type="text" name="name"><br><br>
          Device Type: <select id="type" name="type" onchange="deviceChange()">
          <?php
            echo "<option value=".DeviceType::L.">Light</option>";
            echo "<option value=".DeviceType::D_L.">Dimming Light</option>";
            echo "<option value=".DeviceType::C_L.">Colored Light</option>";
            echo "<option value=".DeviceType::T.">Thermostat</option>";
            echo "<option value=".DeviceType::S.">Switch</option>";
          ?>
          </select><br>

       <br>
       <button value="Reset" type="reset" onclick="cancelDevice()">Cancel</button>
       <button type="submit" value="Submit">Create Device</button>
       
     </form>
        <br>
        Or, you can instantly add one of these supported devices:
        <br>
        <button onclick="addHue()">Philips Hue</button>
        <br>
        <button onclick="addEco()">Ecobee</button>
    
        <script>    
        function cancelDevice(){
	window.location="index.html";
        }
        
        function addHue(){
            window.location="lib/support/addHue.php";
        }
        function addEco(){
            window.location="lib/support/addEcobee.php";
        }
        </script>
        </div>
    </body>
</html>
