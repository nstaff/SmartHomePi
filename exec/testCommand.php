<?php

/** 
 * Generates files from POST data for test run. Outputs results to browser for testing.
 * @author Nick Staffend <nicholas.a.staffend at gmail.com>
 * @depends exec/FileFactory.php
 * @depends lib/Commands.php
 */
require_once __DIR__ .'/../exec/FileFactory.php';
require_once __DIR__.'/../lib/Commands.php';
require_once __DIR__.'/../lib/VariableManager.php';

//turn errors on
error_reporting(E_ALL);
ini_set("display_errors", 1);
            
//pull from the VARS menu on the edit page for access to variables.
$storedValues = VariableManager::getTestValues();
//remaining values come in via POST
$json['initGeneration'] = FileFactory::generateInit($_POST[TestCommands::INIT]);
$json['runCommandGeneration'] = FileFactory::generateRunCommand($_POST[TestCommands::CMD]);
$returnValue = array("fileStatus" => $json);
//run the commands
include 'runCommand.php';

//$storedValues will not update

echo json_encode($json);