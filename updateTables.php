<?php
//initialize username vars
require_once 'conf/dbInfo.php';
require_once 'lib/DataBroker.php';
require_once 'lib/DeviceType.php';
require_once 'lib/Commands.php';


#Chose type of device then generate SQL
    $id = addslashes($_POST['ID']);
    $newName = addslashes($_POST['NAME']);
    $type = $_POST['TYPE'];
    
    #create broker
    $broker = new DataBroker($servername, $username, $password, $dbname);
    #insert a light
    if ($type == DeviceType::L){
        $result = $broker->updateLightCommand($id, LightCommands::GET_STATE, $_POST[LightCommands::GET_STATE]) &&
                $broker->updateLightCommand($id, LightCommands::ON_CMD, $_POST[LightCommands::ON_CMD]) &&
                $broker->updateLightCommand($id, LightCommands::OFF_CMD, $_POST[LightCommands::OFF_CMD]) &&
                $broker->updateNameById($id, $newName) &&
                $broker->updateInitById($id, $_POST[CommonCommands::INIT]) &&
                $broker->updateVarsById($id, $_POST[CommonCommands::VARS]);
        
    } 
    #Insert a switch
    else if ($type == DeviceType::S){
        $result = $broker->updateSwitchCommand($id, SwitchCommands::GET_STATE, $_POST[SwitchCommands::GET_STATE]) &&
                $broker->updateSwitchCommand($id, SwitchCommands::ON_CMD, $_POST[SwitchCommands::ON_CMD]) &&
                $broker->updateSwitchCommand($id, SwitchCommands::OFF_CMD, $_POST[SwitchCommands::OFF_CMD]) &&
                $broker->updateNameById($id, $newName) &&
                $broker->updateInitById($id, $_POST[CommonCommands::INIT]) &&
                $broker->updateVarsById($id, $_POST[CommonCommands::VARS]);

    } 
    #insert a thermostat
    else if ($type== DeviceType::T){
        $result = $broker->updateThermostatCommand($id, ThermostatCommands::FAN_OFF, $_POST[ThermostatCommands::FAN_OFF]) &&
                $broker->updateThermostatCommand($id, ThermostatCommands::FAN_ON, $_POST[ThermostatCommands::FAN_ON]) &&
                $broker->updateThermostatCommand($id, ThermostatCommands::GET_TEMP, $_POST[ThermostatCommands::GET_TEMP]) &&
                $broker->updateThermostatCommand($id, ThermostatCommands::SET_TEMP, $_POST[ThermostatCommands::SET_TEMP]) &&
                $broker->updateThermostatCommand($id, ThermostatCommands::COOL, $_POST[ThermostatCommands::COOL]) &&
                $broker->updateThermostatCommand($id, ThermostatCommands::HEAT, $_POST[ThermostatCommands::HEAT]) &&
                $broker->updateThermostatCommand($id, ThermostatCommands::AUTO, $_POST[ThermostatCommands::AUTO]) &&
                $broker->updateNameById($id, $newName) &&
                $broker->updateInitById($id, $_POST[CommonCommands::INIT]) &&
                $broker->updateVarsById($id, $_POST[CommonCommands::VARS]);
    } else if ($type== DeviceType::C_L){
        $result = $broker->updateNameById($id, $newName) &&
                $broker->updateInitById($id, $_POST[CommonCommands::INIT]) &&
                $broker->updateVarsById($id, $_POST[CommonCommands::VARS]) &&
                $broker->updateColoredLightCommand($id, ColoredLightCommands::GET_STATE, $_POST[ColoredLightCommands::GET_STATE]) &&
                $broker->updateColoredLightCommand($id, ColoredLightCommands::ON_CMD, $_POST[ColoredLightCommands::ON_CMD]) &&
                $broker->updateColoredLightCommand($id, ColoredLightCommands::OFF_CMD, $_POST[ColoredLightCommands::OFF_CMD]) &&
                $broker->updateColoredLightCommand($id, ColoredLightCommands::SET_RGB, $_POST[ColoredLightCommands::SET_RGB]) &&
                $broker->updateColoredLightCommand($id, ColoredLightCommands::SET_BRIGHTNESS, $_POST[ColoredLightCommands::SET_BRIGHTNESS]);
    }else if ($type == DeviceType::D_L){
        $result = $broker->updateDimmingLightCommand($id, DimmingLightCommands::GET_STATE, $_POST[DimmingLightCommands::GET_STATE]) &&
                $broker->updateDimmingLightCommand($id, DimmingLightCommands::ON_CMD, $_POST[DimmingLightCommands::ON_CMD]) &&
                $broker->updateDimmingLightCommand($id, DimmingLightCommands::OFF_CMD, $_POST[DimmingLightCommands::OFF_CMD]) &&
                $broker->updateDimmingLightCommand($id, DimmingLightCommands::SET_BRIGHTNESS, $_POST[DimmingLightCommands::SET_BRIGHTNESS]) &&
                $broker->updateNameById($id, $newName) &&
                $broker->updateInitById($id, $_POST[CommonCommands::INIT]) &&
                $broker->updateVarsById($id, $_POST[CommonCommands::VARS]);
    }
    
    #interpret results. Squelch if in test mode.
    if($result === TRUE){
            $message = "Successfully updated " . $device;
        } else{
            $message = "There was an error with the SQL update statement.";
    }
    #var_dump($_POST);

    #Let the user know of success/failure
    echo "<script type='text/javascript'>alert('$message');</script>";
    #redirect back to edit.
//garbage    
//echo "<script>window.open(\"executeCommand.php?mode=test&id=".$id."&type=".$type."&command=\");</script>";
    echo "<script>window.location =\"edit.php?id=".$id."\";</script>";
    #echo "<a href=\"index.html\">Go Home</a>";
