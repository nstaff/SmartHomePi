<?php
/**
 * @author Nick Staffend <nicholas.a.staffend at gmail.com>
 */
require_once __DIR__ . '/../../DeviceType.php';
require_once __DIR__ . '/../../Commands.php';


abstract class HueFactory{
    const HOST = "HUE_HOST";
    const USERNAME = "HUE_USER";
    const LIGHT_NUM = "LIGHT_NUMBER";
    const NAME = "NAME";
    /**
     * Factory method provided for "1-click" support of adding Philips Hue to the
     * smarthome framework.
     * @param type $hueHost The IP address of the Hue Bridge
     * @param type $hueUsername The username provided to the user by the Philips Hue Bridge
     * @param type $hueLightIdNumber The ID number of the light provided by the Philips Hue Bridge
     * @param type $name User provided identifier of the light
     * @return boolean if successful
     */
    public static function buildHue($hueHost, $hueUsername, $hueLightIdNumber, $name){
        include __DIR__ . '/../../../conf/dbInfo.php';
        require_once __DIR__ .'/../../DataBroker.php';
        $broker = new DataBroker($servername, $username, $password, $dbname);
        //Declare the commands required to operate the Hue
        
        $init = "<?php include 'runAutoloader.php';";
        $lightOn = '<?php require_once \'init.php\'; $client = new \Phue\Client(\''.$hueHost.'\', \''.$hueUsername.
                '\'); $lights = $client->getLights(); $lights['.$hueLightIdNumber.
                ']->setOn(); $json["state"] = $lights['.$hueLightIdNumber.']->isOn();';
        $lightOff = '<?php require_once \'init.php\'; $client = new \Phue\Client(\''.$hueHost.'\', \''.$hueUsername.
                '\'); $lights = $client->getLights(); $lights['.$hueLightIdNumber.
                ']->setOn(FALSE); $json["state"] = !$lights['.$hueLightIdNumber.']->isOn();';
        $getState = '<?php require_once \'init.php\'; $client = new \Phue\Client(\''.$hueHost.'\', \''.$hueUsername.
                '\'); $lights = $client->getLights(); '.
                '$json["state"] = $lights['.$hueLightIdNumber.']->isOn();';
        
        //get the smarthome database ID number of the device and add initialize the device
        $idNum = $broker->initRecord(DeviceType::L, $name);
        
        //update the commands to operate the Hue
        $result = $broker->updateInitById($idNum, $init) &&
        $broker->updateLightCommand($idNum, LightCommands::ON_CMD, $lightOn) &&
        $broker->updateLightCommand($idNum, LightCommands::OFF_CMD, $lightOff) &&
        $broker->updateLightCommand($idNum, LightCommands::GET_STATE, $getState);
        
        return ( $result ? $idNum : -1 );
    }
    
    /**
     * Factory method provided for "1-click" support of adding Philips Hue Color to the
     * smarthome framework.
     * @param type $hueHost The IP address of the Hue Bridge
     * @param type $hueUsername The username provided to the user by the Philips Hue Bridge
     * @param type $hueLightIdNumber The ID number of the light provided by the Philips Hue Bridge
     * @param type $name User provided identifier of the light
     * @return boolean if successful
     */
    public static function buildDimmingHue($hueHost, $hueUsername, $hueLightIdNumber, $name){
        $init = '<?php include \'runAutoloader.php\'; '
                . '$client = new \Phue\Client($storedValues["hueHost"], $storedValues["hueUsername"]); '
                . '$lights = $client->getLights(); $light = $lights['.$hueLightIdNumber.'];';
        
        $lightOn = '<?php include \'init.php\'; $light->setOn(); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $lightOff = '<?php include \'init.php\';  $light->setOn(FALSE); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $getState = '<?php include \'init.php\'; $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $setBri = '<?php include \'init.php\'; $light->setBrightness(($_GET[\'BRI\'] * 255/100)); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $vars = '{"hueHost":"'.$hueHost.'","hueUsername":"'.$hueUsername.'"}';
        //get the smarthome database ID number of the device and add initialize the device
        include __DIR__ . '/../../../conf/dbInfo.php';
        require_once __DIR__ .'/../../DataBroker.php';
        $broker = new DataBroker($servername, $username, $password, $dbname);
        $idNum = $broker->initRecord(DeviceType::D_L, $name);
        
        
        include __DIR__ . '/../../../conf/dbInfo.php';
        require_once __DIR__ .'/../../DataBroker.php';
        $broker = new DataBroker($servername, $username, $password, $dbname);
        //update the commands to operate the Hue
        $result = $broker->updateInitById($idNum, $init) &&
                $broker->updateVarsById($idNum, $vars);
                $broker->updateDimmingLightCommand($idNum, LightCommands::ON_CMD, $lightOn) &&
                $broker->updateDimmingLightCommand($idNum, LightCommands::OFF_CMD, $lightOff) &&
                $broker->updateDimmingLightCommand($idNum, LightCommands::GET_STATE, $getState) &&
                $broker->updateDimmingLightCommand($idNum, DimmingLightCommands::SET_BRIGHTNESS, $setBri);
        return ( $result ? $idNum : -1 );
    }
    
    /**
     * Factory method provided for "1-click" support of adding Philips Hue Color to the
     * smarthome framework.
     * @param type $hueHost The IP address of the Hue Bridge
     * @param type $hueUsername The username provided to the user by the Philips Hue Bridge
     * @param type $hueLightIdNumber The ID number of the light provided by the Philips Hue Bridge
     * @param type $name User provided identifier of the light
     * @return boolean if successful
     */
    public static function buildColorHue($hueHost, $hueUsername, $hueLightIdNumber, $name){
        $init = '<?php include \'runAutoloader.php\'; '
                . '$client = new \Phue\Client($storedValues["hueHost"], $storedValues["hueUsername"]); '
                . '$lights = $client->getLights(); $light = $lights['.$hueLightIdNumber.'];';
        
        $lightOn = '<?php include \'init.php\'; $light->setOn(); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = $light->getBrightness(); $json["rgb"] = $light->getRGB();';
        $lightOff = '<?php include \'init.php\';  $light->setOn(FALSE); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = $light->getBrightness(); $json["rgb"] = $light->getRGB();';
        $getState = '<?php include \'init.php\'; $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $setRGB = '<?php include \'init.php\'; $light->setRGB($_GET[\'R\'],$_GET[\'G\'],$_GET[\'B\']); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $setBri = '<?php include \'init.php\'; $light->setBrightness(($_GET[\'BRI\'] * 255/100)); $json["state"] = ($light->isOn() ? "on" : "off"); $json["bri"] = ceil($light->getBrightness() / 255 * 100); $json["rgb"] = $light->getRGB();';
        $vars = '{"hueHost":"'.$hueHost.'","hueUsername":"'.$hueUsername.'"}';
        //get the smarthome database ID number of the device and add initialize the device
        include __DIR__ . '/../../../conf/dbInfo.php';
        require_once __DIR__ .'/../../DataBroker.php';
        $broker = new DataBroker($servername, $username, $password, $dbname);
        $idNum = $broker->initRecord(DeviceType::C_L, $name);
        
        
        include __DIR__ . '/../../../conf/dbInfo.php';
        require_once __DIR__ .'/../../DataBroker.php';
        $broker = new DataBroker($servername, $username, $password, $dbname);
        //update the commands to operate the Hue
        $result = $broker->updateInitById($idNum, $init) &&
                $broker->updateVarsById($idNum, $vars);
                $broker->updateColoredLightCommand($idNum, LightCommands::ON_CMD, $lightOn) &&
                $broker->updateColoredLightCommand($idNum, LightCommands::OFF_CMD, $lightOff) &&
                $broker->updateColoredLightCommand($idNum, LightCommands::GET_STATE, $getState) &&
                $broker->updateColoredLightCommand($idNum, ColoredLightCommands::SET_BRIGHTNESS, $setBri) &&
                $broker->updateColoredLightCommand($idNum, ColoredLightCommands::SET_RGB, $setRGB);
        return ( $result ? $idNum : -1 );
    }
}