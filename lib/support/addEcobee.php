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
        <?php include __DIR__ .'/../../top-nav.php';?>

        <?php include __DIR__ .'/../../sidebar.php';?>
        <div id="main" class="col-xs-6">
            <?php
                include __DIR__ . '/factories/EcobeeFactory.php';
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        ////&& isset($_POST[EcobeeFactory::ACCESS_TOKEN])
                        //&& isset($_POST[EcobeeFactory::REFRESH_TOKEN]) && isset($_POST[EcobeeFactory::AUTHORIZATION_CODE]) 
                        //&& isset($_POST[EcobeeFactory::API_KEY])){
                    $success = EcobeeFactory::BuildEcobee($_POST[EcobeeFactory::API_KEY], 
                            $_POST[EcobeeFactory::AUTHORIZATION_CODE], 
                            $_POST[EcobeeFactory::ACCESS_TOKEN], 
                            $_POST[EcobeeFactory::REFRESH_TOKEN], 
                            $_POST[EcobeeFactory::NAME]);
                    if(!($success == -1)){
                        echo "<script>alert(\"".$_POST[EcobeeFactory::NAME]." added to database\");</script>";
                    }else{
                        echo "<script>alert(\"something went wrong\");</script>";
                    }
                }
            ?>
            <h2>Add Ecobee</h2>
            <p>For documentation on how to get the values below, please visit the Ecobee website for instructions</p>
            <p>Once the Ecobee is added, it will automatically update the tokens as necessary. These are only needed to establish an initial connection with the Ecobee servers.</p>
            <form action="addEcobee.php" method="post">
                Name: <input type="text" name="<?php echo EcobeeFactory::NAME ?>"><br>
                API Key: <input type="text" name="<?php echo EcobeeFactory::API_KEY ?>"><br>
                Authorization Code: <input type="text" name="<?php echo EcobeeFactory::AUTHORIZATION_CODE ?>"><br>
                Refresh Token: <input type="text" name="<?php echo EcobeeFactory::REFRESH_TOKEN ?>"><br>
                Access Token: <input type="text" name="<?php echo EcobeeFactory::ACCESS_TOKEN ?>"><br>
                <input type="submit" name="Create_Ecobee">
            </form>
        </div>
    </body>
</html>