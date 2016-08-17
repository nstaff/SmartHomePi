<?php
/**
 * @author Nick Staffend <nicholas.a.staffend at gmail.com>
 */
require_once __DIR__ . '/../../DeviceType.php';
require_once __DIR__ . '/../../Commands.php';

abstract class EcobeeFactory{
    const NAME = "NAME";
    const API_KEY = "API_KEY";
    const AUTHORIZATION_CODE = "AUTH_CODE";
    const ACCESS_TOKEN = "ACC_TOK";
    const REFRESH_TOKEN = "REF_TOK";
    
    /**
     * Factory method provided for adding "1-click" support of adding the Ecobee thermostat
     * @param type $apiKey APP key for the ecobee
     * @param type $authCode authorization code for the ecobee
     * @param type $accToken access token for the ecobee
     * @param type $refToken refresh token for the ecobee
     * @param type $name The name to be given to the device in the database. USed for display
     * @return type
     */
    public static function BuildEcobee($apiKey, $authCode, $accToken, $refToken, $name){
        include __DIR__ . '/../../../conf/dbInfo.php';
        require_once __DIR__ .'/../../DataBroker.php';
        
        $init = '<?php require_once __DIR__ .\'/../lib/Ecobee.php\'; '.
        '$ecobee = new Ecobee($storedValues["ACCESS_TOKEN"], $storedValues["REFRESH_TOKEN"], $storedValues["API_KEY"], $storedValues["AUTHORIZATION_CODE"], $storedValues["LastRefresh"]); ' .
        '$tokens = json_decode($ecobee->RefreshPin(), true); ' .
        'if($tokens != null){ '.
        '$storedValues["REFRESH_TOKEN"] = $ecobee->REFRESH_TOKEN; '.
        '$storedValues["ACCESS_TOKEN"] = $ecobee->ACCESS_TOKEN; '.
        '$storedValues["LastRefresh"] = $ecobee->lastRefresh; }';
        
        $varsArray = array("API_KEY" => $apiKey, "AUTHORIZATION_CODE" => $authCode, "REFRESH_TOKEN" => $refToken, "ACCESS_TOKEN" => $accToken, "LastRefresh" => null);
        $vars = json_encode($varsArray);
//'{"API_KEY":"'.$apiKey.'","AUTHORIZATION_CODE":"'.$authCode.'","REFRESH_TOKEN":"'.$refToken.'","ACCESS_TOKEN":"'.$accToken.'"LastRefresh":null}';
        
        $getState = '<?php include \'init.php\'; '.
        '$json["status"] = $ecobee->getStateAsArray();';
        
        $cool = '<?php include \'init.php\'; '.
                '$result = $ecobee->SetMode(\'cool\'); '.
                '$json["status"] = $ecobee->getStateAsArray();';
        
        $heat = '<?php include \'init.php\'; '.
                '$result = $ecobee->SetMode(\'heat\'); '.
                '$json["status"] = $ecobee->getStateAsArray();';
        
        $auto = '<?php include \'init.php\'; '.
                '$result = $ecobee->SetMode(\'auto\'); '.
                '$json["status"] = $ecobee->getStateAsArray();';
        
        $setTemp = '<?php include \'init.php\'; '.
                '$result = $ecobee->SetHoldTemp($_GET[\'temp\']); '.
                '$json["status"] = $ecobee->getStateAsArray();';
        
        $broker = new DataBroker($servername, $username, $password, $dbname);
        $idNum = $broker->initRecord(DeviceType::T, $name);
        $result = $broker->updateVarsById($idNum, $vars) && $broker->updateInitById($idNum, $init) &&
                $broker->updateThermostatCommand($idNum, ThermostatCommands::GET_TEMP, $getState) && 
                $broker->updateThermostatCommand($idNum, ThermostatCommands::COOL, $cool) &&
                $broker->updateThermostatCommand($idNum, ThermostatCommands::HEAT, $heat) &&
                $broker->updateThermostatCommand($idNum, ThermostatCommands::AUTO, $auto) && 
                $broker->updateThermostatCommand($idNum, ThermostatCommands::SET_TEMP, $setTemp);
        
        return ( $result ? $idNum : -1);
    }
}