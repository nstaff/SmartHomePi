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
        include __DIR__.'/factories/HueFactory.php';
        
        if(isset($_POST[HueFactory::HOST]) && !empty($_POST[HueFactory::HOST])){
            if(empty($_POST[HueFactory::USERNAME]) || empty($_POST[HueFactory::LIGHT_NUM]) || empty($_POST[HueFactory::NAME])){
                echo "<script>window.alert(\"Some values not set.\nPlease make sure all values are provided.\");</script>";
            }else{
                $hueHost = $_POST[HueFactory::HOST]; //filter_input(INPUT_POST, HueFactory::HOST, FILTER_VALIDATE_IP);
                $hueUsername = $_POST[HueFactory::USERNAME]; //filter_input(INPUT_POST, HueFactory::USERNAME, FILTER_SANITIZE_STRING);
                $name = $_POST[HueFactory::NAME]; //filter_input(INPUT_POST, HueFactory::NAME, FILTER_SANITIZE_STIRNG);
                $lightId = $_POST[HueFactory::LIGHT_NUM]; //filter_input(INPUT_POST, HueFactory::LIGHT_NUM, FILTER_VALIDATE_INT);
                if($_POST['type'] == 'color'){
                    $success = HueFactory::buildColorHue($hueHost, $hueUsername, $lightId, $name);
                }else{
                    $success = HueFactory::buildDimmingHue($hueHost, $hueUsername, $lightId, $name);
                }
                if($success > 0){
                    echo "<script>alert(\"$name added to database\");</script>";
                }
            }
        }else{
            echo "<script>window.alert(\"No hostname provided.\nPlease make sure all values are provided.\");</script>";
        }
    ?>
    <h2>Add Philips Hue</h2>
    <p>For documentation on how to get the values below, please visit the 
        <a href="http://www.developers.meethue.com/documentation/getting-started">meetHue website</a>
        for instructions.</p>
    <form action="addHue.php" method="post">
        Name: <input type="text" name="<?php echo HueFactory::NAME ?>"><br />
        Device Type: <select id="type" name="type">
            <option value="normal">White</option>
            <option value="color">Color</option>
        </select>
        The following values are provided by the Hue API:<br>
        Hue IP address: <input type="text" name="<?php echo HueFactory::HOST ?>"><br />
        Hue username: <input type="text" name="<?php echo HueFactory::USERNAME ?>"><br />
        Hue light ID number: <input type="text" name="<?php echo HueFactory::LIGHT_NUM ?>"><br />
        <input type="submit" name="Create Hue">
    </form>
</div>
</body>
</html>
