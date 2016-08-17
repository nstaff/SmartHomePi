<?php
require_once 'DataBroker.php';

/**
 * Class for managing loading variables in and out of the database. Wrapper class 
 * for the basic DataBroker.
 */
class VariableManager {
    var $broker;
    var $values;
    var $id;
    var $type;

    /**
     * Constructor
     * @param type $id ID of the device in the database
     * @param type $type DeviceType enumeration of the device
     */
    function __construct($id, $type){
            include __DIR__ .'/../conf/dbInfo.php';
            $this->broker = new DataBroker($servername, $username, $password, $dbname);
            $this->id = $id;
            $this->type = $type;
    }

    /**
     * Gets the variables from storage
     * @return array array of variables in key => value pair format.
     */
    public function getVariables(){
            $values = json_decode($this->broker->getVars($this->id), true);
            return $values;
    }

    /**
     * Stores the array of variables to the corresponding ID
     * @param array $array an array of variables to store in key => value pair format
     */
    public function storeVariables($array){
            $this->broker->updateVarsById($this->id, json_encode($array));
    }

    /**
     * pull the values from POST. This would be used during test while adding commands
     * @return array An array of stored values based on their JSON storage
     */
    public static function getTestValues(){
        $storedValues = json_decode($_POST[TestCommands::VARS], true);
        return $storedValues;
    }
    
}

