<?php
    require_once 'Exceptions.php';
    require_once 'Commands.php';
    require_once 'DeviceType.php';
    require_once 'Device.php';
	//Data manager class for handling SQL inputs/queries
	class DataBroker {
		var $servername;
		var $username;
		var $password;
		var $dbname;

                
                /**
		 * Constructor
		 * @param type $server - the server URL/IP address
                 * @param type $user - the mysql username
                 * @param type $pass - user password
                 * @param type $db - the database name
                 * 
                 */

		function __construct($servername, $username, $password, $dbname){
			$this->servername = $servername;
			$this->username = $username;
			$this->password = $password; 
			$this->dbname = $dbname;
		}

                
                /**
                 * returns a multidimensional array in JSON format of all devices in the database
                 * organized by type
                 * @return JSON JSON array
                 */
		public function getAllDevicesMultiDimensions(){
                    $allDevices = array();
                    $allDevices["lights"] = $this->getAllLights();
                    $allDevices["switches"] = $this->getAllSwitches();
                    $allDevices["thermostats"] = $this->getAllThermostats();
                    return $allDevices;
		}
                

                
                /**
		 * Returns an single dimensional array of all devices with ID number and type
                 * associated in each object
                 * @return JSON JSON array
                 */
                public function getAllDevices(){
                    $allDevices = array();
                    $allDevices = $this->getAllLights();
                    //array_merge($allDevices, $this->getAllLights());
                    $allDevices = array_merge($allDevices, $this->getAllDimmingLights());
                    $allDevices = array_merge($allDevices, $this->getAllColoredLights());
                    $allDevices = array_merge($allDevices, $this->getAllSwitches());
                    $allDevices = array_merge($allDevices, $this->getAllThermostats());
                    return $allDevices;
		}



                /**
		 * Returns an array of all lights as Device objects in the database
                 */
		public function getAllLights(){
			//return select * from lights
			$conn = $this->getDbConn();
			$results = $conn->query("SELECT * FROM DEVICES WHERE TYPE='". DeviceType::L . "';");
			$lights = array();
                        while($row = $results->fetch_assoc()){
                            $device = new Device ($row['ID'], $row['NAME'], DeviceType::L);
                            array_push($lights, $device);
                        }
                        $conn->close();
                        return $lights;
		}
                
                /**
		 * Returns an array of all lights as Device objects in the database
                 */
		public function getAllDimmingLights(){
			//return select * from lights
			$conn = $this->getDbConn();
			$results = $conn->query("SELECT * FROM DEVICES WHERE TYPE='". DeviceType::D_L . "';");
			$lights = array();
                        while($row = $results->fetch_assoc()){
                            $device = new Device ($row['ID'], $row['NAME'], DeviceType::D_L);
                            array_push($lights, $device);
                        }
                        $conn->close();
                        return $lights;
		}
                
                /**
		 * Returns an array of all lights as Device objects in the database
                 */
		public function getAllColoredLights(){
			//return select * from lights
			$conn = $this->getDbConn();
			$results = $conn->query("SELECT * FROM DEVICES WHERE TYPE='". DeviceType::C_L . "';");
			$lights = array();
                        while($row = $results->fetch_assoc()){
                            $device = new Device ($row['ID'], $row['NAME'], DeviceType::C_L);
                            array_push($lights, $device);
                        }
                        $conn->close();
                        return $lights;
		}
                
                /**
		 * Returns an array of all switches as Device objects in the database
                 */
		public function getAllSwitches(){
			$conn = $this->getDbConn();
			$results = $conn->query("SELECT * FROM DEVICES WHERE TYPE='". DeviceType::S . "';");
			$lights = array();
                        while($row = $results->fetch_assoc()){
                            $device = new Device ($row['ID'], $row['NAME'], DeviceType::S);
                            array_push($lights, $device);
                        }
                        $conn->close();
                        return $lights;
		}
                
                /**
                 * Gets the raw JSON data from the associated database VARS field 
                 * for the specified device
                 * @param type $id The devices database ID number
                 * @param DeviceType $type The devices enumerated type from DeviceType
                 * @return boolean false if no valuse, JSON if values existed
                 */
                public function getVars($id){
                    $sql = "SELECT VARS FROM DEVICES WHERE ID=".$id.";";
                    $conn=$this->getDbConn();
                    /*$stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->bind_result($results);
                    $stmt->fetch();*/
                    $results = $conn->query($sql);
                    while($row = $results->fetch_assoc()){
                        $json = $row['VARS'];
                    }
                    return $json;
                }

                /**
		 * Returns an array of all thermostats as Device objects in the database
                 */
		public function getAllThermostats(){
			//return select * from switches
			$conn = $this->getDbConn();
			$results = $conn->query("SELECT * FROM DEVICES WHERE TYPE='". DeviceType::T . "';");
			$thermostats = array();
                        while($row = $results->fetch_assoc()){
                            $therm = new Device ($row['ID'], $row['NAME'], DeviceType::T);
                            array_push($thermostats, $therm);
                        }
                        $conn->close();
                        return $thermostats;
		}	


                /**
		 * Initialize a database record and return the ID number. 
		 * TODO: Parameterize queries to make resilient to SQL injection
		 * @param type $type - Enumerated type from DeviceTypes
                 * @param type $name - (optional) the name of the device.
                 * @return integer - returns -1 for an invalid provided type. Otherwise returns database IS number of item added.
                 */
                public function initRecord($type, $name){
                    $table = $this->getTableNameFromType($type);
                    if($table == FALSE){
                        return -1;
                    } else {
                        $sql = "INSERT INTO DEVICES (NAME, TYPE) VALUES (?,?);";
                        $conn = $this->getDbConn();
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $name, $type);
                        $stmt->execute();
                        $idNum = $stmt->insert_id;
                        $stmt->close();
                        //$conn->query($sql);
                        //$idNum = $conn->insert_id;
                        $sql2 = "INSERT INTO ".$table." (ID, TYPE) VALUES (?, ?);";
                        $stmt = $conn->prepare($sql2);
                        $stmt->bind_param("is", $idNum, $type);
                        $stmt->execute();
                        $stmt->close();
                        //$conn->query($sql2);
                        $conn->close();
                        return $idNum;
                    }
                }
                
                
                /**
                 * Update the specified field in the light table
                 * @param string $idNum  the id number of the light
                 * @param DeviceType $commandType  should be enumerated command type for applicable device
                 * @param string $command  PHP code for the updated command
                 * @return boolean If the update was successful
                 */
                public function updateDimmingLightCommand($idNum, $commandType, $command){
                    if(!DimmingLightCommands::isValidValue($commandType)){
                        return false;
                    } else{
                        return $this->updateTableCommand("DIMMING_LIGHTS", $idNum, $commandType, $command);
                    }
                }
                
                /**
                 * 
                 * @param type $tableName the table name to update
                 * @param type $idNum id number of the device
                 * @param type $commandType the enumerated command type
                 * @param type $command the command
                 * @return boolean
                 */
                private function updateTableCommand($tableName, $idNum, $commandType, $command){
                    $sql = "UPDATE ".$tableName." SET ".$commandType."=? WHERE ID=?;";
                    $conn = $this->getDbConn();
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $command, $idNum);
                    $result = $stmt->execute();
                    $stmt->close();
                    $conn->close();
                    return $result;
                }
                
                /**
                 * Update the specified field in the light table
                 * @param string $idNum  the id number of the light
                 * @param DeviceType $commandType  should be enumerated command type for applicable device
                 * @param string $command  PHP code for the updated command
                 * @return boolean If the update was successful
                 */
                public function updateColoredLightCommand($idNum, $commandType, $command){
                    if(!ColoredLightCommands::isValidValue($commandType)){
                        return false;
                    }
                    return $this->updateTableCommand("COLORED_LIGHTS", $idNum, $commandType, $command);
                }

                
                /**
                 * Update the specified field in the light table
                 * @param string $idNum  the id number of the light
                 * @param DeviceType $commandType  should be enumerated command type for applicable device
                 * @param string $command  PHP code for the updated command
                 * @return boolean If the update was successful
                 */
                public function updateLightCommand($idNum, $commandType, $command){
                    //echo "Databroker: ".$commandType;
                    if(!LightCommands::isValidValue($commandType)){
                        return false;
                    } 
                    return $this->updateTableCommand("LIGHTS", $idNum, $commandType, $command);
                }
                
                /**
                 * Update the specified field in the switch table
                 * @param string $idNum  the id number of the switch
                 * @param DeviceType $commandType  should be enumerated command type for applicable device
                 * @param string $command  PHP code for the updated command
                 * @return boolean If the update was successful
                 */
                public function updateSwitchCommand($idNum, $commandType, $command){
                    if(!SwitchCommands::isValidValue($commandType)){
                        return false;
                    }return $this->updateTableCommand("SWITCHES", $idNum, $commandType, $command);
                }
                
                /**
                 * Update the specified field in the thermostat table
                 * @param string $idNum  the id number of the thermostat
                 * @param DeviceType $commandType  should be enumerated command type for applicable device
                 * @param string $command  PHP code for the updated command
                 * @return boolean If the update was successful
                 */
                public function updateThermostatCommand($idNum, $commandType, $command){
                    if(!ThermostatCommands::isValidValue($commandType)){
                        return false;
                    }return $this->updateTableCommand("THERMOSTATS", $idNum, $commandType, $command);
                }
                
                /**
                 * Updates the VARS field for the specified variable. This can be
                 * updated via update<DeviceType>command method, however this method
                 * is provided for easier access by the VariableManager class.
                 * @param string/int $id - database ID number of the device
                 * @param DeviceType $type DeviceType of the device
                 * @param JSON $newVars JSON array on new values to store
                 * @return type
                 */
                public function updateVarsById($id, $newVars){
                    //$sql = "UPDATE DEVICES SET VARS='".addslashes($newVars)."' WHERE ID=".$id.";";
                    //return $this->executeNoResultQuery($sql);
                    return $this->updateTableCommand("DEVICES", $id, "VARS", $newVars);
                }
                
                /**
                 * Updates the INIT field for the specified variable. This can be
                 * updated via update<DeviceType>command method, however this method
                 * is provided for easier plain-language access while running the 
                 * executeCommand script.
                 * @param string/int $id - database ID number of the device
                 * @param DeviceType $type DeviceType of the device
                 * @param JSON $newInit PHP code for update
                 * @return type
                 */
                public function updateInitById($id, $newInit){
                    return $this->updateTableCommand("DEVICES", $id, "INIT", $newInit);
                }

                
                /**
                 * Update the name of the device. Provided as a convenience method.
                 * The same result can be achieved using the update<DeviceType>command()
                 * method.
                 * @param type $id Database ID number of the device
                 * @param type $type DeviceType of the device
                 * @param type $newName new nape to provide to the database
                 * @return type if successful update
                 */
                public function updateNameById($id, $newName){
                    return $this->updateTableCommand("DEVICES", $id, CommonCommands::NAME, $newName);
                }
                
                /**
                 * Deletes a device from the database.
                 * @param type $idNum The ID number of the device to be deleted
                 * @return boolean if the deletion was successful
                 */
                public function removeDevice($idNum){
                    $conn = $this->getDbConn();
                    if($stmt = $conn->prepare("DELETE FROM DEVICES WHERE ID=?")){
                        $stmt->bind_param("i", $idNum);
                        $result = $stmt->execute();
                        $stmt->close();
                    }else{
                        $result = FALSE;
                    }
                    $conn->close();
                    return $result;
                }
                

                /**
                 * Get a key-value pair based array of all fields with the associated
                 * record
                 * @param type $id the devices ID number
                 * @param type $type the type of device
                 * @return array of key-values 
                 */
                public function getAllFieldsAsKeyValue($id){
                    //$type = "invalid";
                    $sql = "SELECT TYPE FROM DEVICES WHERE ID=?";
                    $conn = $this->getDbConn();
                    if($stmt = $conn->prepare($sql)){
                        //get the type from the database
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $stmt->bind_result($type);
                        $stmt->fetch();
                        
                        if (DeviceType::isValidValue($type)) {
                            $tableName = $this->getTableNameFromType($type);
                            if($tableName == -1){
                                die("invalid type. Ensure URL provides the correct type argument.");
                            }
                            $stmt->close();
                            $sql = "SELECT * FROM DEVICES INNER JOIN " . $tableName . " ON DEVICES.ID=".$tableName.".ID WHERE DEVICES.ID=".$id.";";
                            //$stmt = $conn->prepare($sql);
                            //$stmt->bind_param("i", $id);
                            //$stmt->execute();
                            //$result = array();
                            //$stmt->bind_result($result);
                            //$stmt->fetch();
                            $result = $conn->query($sql);

                            while($row = $result->fetch_assoc()){
                                //iterate through each row of the recordset and echo the field for editing
                                foreach($row as $key => $value){
                                    $resultSet[$key] = $value;
                                }
                            }
                            //$stmt->close();
                            $conn->close();

                            return $resultSet;
                        } else {
                            return "WIGLY";
                        }
                    }else{
                        die("invalid type");
                    }
                }
                
                
                
                
                /**
                 * Gets the name of the table based on the associated Enumberated
                 * device type
                 * @param DeviceType $type Device Type enumeration
                 * @return boolean|string false if not a valid type
                 */
                private function getTableNameFromType($type){
                    if(!DeviceType::isValidValue($type)){
                        return false;
                    } else{
                        switch($type){
                            case DeviceType::L:
                                $table = "LIGHTS";
                                break;
                            case DeviceType::S:
                                $table = "SWITCHES";
                                break;
                            case DeviceType::T:
                                $table = "THERMOSTATS";
                                break;
                            case DeviceType::C_L:
                                $table = "COLORED_LIGHTS";
                                break;
                            case DeviceType::D_L:
                                $table = "DIMMING_LIGHTS";
                                break;
                        }
                        return $table;
                    }
                }
                
                /**
                 * Execute a query that will not expect results
                 * @param type $sql The SQL string to execute
                 * @return boolean if the query was successful
                 */
		private function executeNoResultQuery($sql){
			$conn = $this->getDbConn();
			if($conn->query($sql) === TRUE){
				$result = true;
			} else {
				$result = false;
			}
			$conn->close();
			return $result;
		}

                /**
                 * get a mysqli database connection for use by this class
                 * @return \mysqli
                 */
		private function getDbConn(){
			// Create connection
                        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
                        // Check connection
                        if ($conn->connect_error) {
               			die("Connection failed: " . $conn->connect_error);
            		}
			return $conn;
		}

        public function updateUser($userId, $password){
            $sql = "UPDATE USERS SET PASSWORD=? WHERE ID=?";
            $conn = $this->getDbConn();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $password, $userId);
            $result = $stmt->execute();
            $stmt->close();
            $conn->close();
            return $result;

        }

        public function loginByUsernameAndPassword($username, $password){
            $conn = $this->getDbConn();
            if($stmt = $conn->prepare("SELECT ID FROM USERS WHERE USERNAME=? AND PASSWORD=SHA1(?)")){
                $stmt->bind_param("ss", $username,$password);
                $stmt->execute();
                $stmt->bind_result($userId);
                $stmt->fetch();
                $stmt->close();
            }else{
                return FALSE;
            }
            $conn->close();
            error_log($userId);
            if($userId > 0) {
                return $this->createNewSession($userId);
            }else{
                return FALSE;
            }
        }

        public function createNewSession($userId){
            $sessionKey = substr(sha1(rand()), 0, 10);
            $sql = "UPDATE USERS SET SESSION_KEY=? WHERE ID=?";
            $conn = $this->getDbConn();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $sessionKey, $userId);
            $result = $stmt->execute();
            $stmt->close();
            $conn->close();
            setcookie("SMART_HOME_SESSION", $sessionKey, time() + (86400 * 30), "/");
            return $result;

        }


        public function getUserBySessionKey($sessionKey){
            $conn = $this->getDbConn();
            if($stmt = $conn->prepare("SELECT SESSION_KEY FROM USERS WHERE SESSION_KEY=?")){
                $stmt->bind_param("s", $sessionKey);
                $result = $stmt->execute();
                $stmt->bind_result($result);
                $stmt->close();
                
                //while($row = $result->fetch_assoc()){
                //    $result = $row['SESSION_KEY'];
                //}
            }else{
                $result = FALSE;
            }
            $conn->close();
            return $sessionKey;
        }

        public function createNewUser($username, $password){
            $conn = $this->getDbConn();
            if($stmt = $conn->prepare("INSERT INTO USERS(USERNAME,PASSWORD) VALUES(?,SHA1(?))")){
                $stmt->bind_param("ss", $username,$password);
                $result = $stmt->execute();
                $stmt->close();
            }else{
                $result = FALSE;
            }
            $conn->close();
            return $result;

        }
	}