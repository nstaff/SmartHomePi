<?php
    //load required libraries and variables
    require_once __DIR__ .'/../exec/FileFactory.php';
    include __DIR__.'/../lib/VariableManager.php';
    //Get variables
    //$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
    $idNum = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $commandName = filter_input(INPUT_GET, 'command', FILTER_SANITIZE_STRING);

    /**
     * DELETE THIS BEFORE PRODUCTION
     */
    //error_reporting(E_ALL);
    //ini_set("display_errors", 1);

    $type = "not-used";
    //get any stored variables that exist from the data layer for use by the user
    $varManager = new VariableManager($idNum, $type);
    $storedValues = $varManager->getVariables();
    $json = array("fileStatus" => FileFactory::generateFromDatabase($idNum, $type, $commandName));


    include 'runCommand.php';

    //update stored variables in case there were changes made by the user
    $varManager->storeVariables($storedValues);
    echo json_encode($json);
