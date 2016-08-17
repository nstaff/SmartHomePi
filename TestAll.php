<?php
    #includes/Libraries
    require_once 'conf/common.php';
    use PHPUnit\Framework\TestCase;
    #include 'lib/DeviceType.php';
    #require_once 'lib/DeviceType.php';
    require_once 'lib/Device.php';
    require_once 'lib/DataBroker.php';
    require_once 'lib/Commands.php';
    
    #Test run
    class TestAll extends TestCase{
        
        public function testEnum(){
            $this->assertTrue(DeviceType::isValidValue(DeviceType::L));
            $this->assertTrue(DeviceType::isValidValue(DeviceType::S));
            $this->assertTrue(DeviceType::isValidValue(DeviceType::T));
            $this->assertFalse(DeviceType::isValidValue("notlisted"));
        }
        
        public function testDevice(){
            $light = new Device(1, "lightTest", DeviceType::L);
            $this->assertEquals($light->getID(), 1);
            $this->assertEquals($light->getName(), "lightTest");
            $this->assertEquals($light->getType(), DeviceType::L);
            
            $json = array('name' => 'lightTest', 'type' => "LIGHT", 'id' => 1);
            $json = json_encode($json);
            $this->assertEquals($json, $light->getJSON());
            
        }
        
        public function testBroker(){
            include 'conf/dbInfo.php';
            $broker = new DataBroker($servername, $username, $password, $dbname);
            #test db adds
            $testName = "namynam";
            
            $lightId = $broker->initRecord(DeviceType::L, $testName);
            $colLightId = $broker->initRecord(DeviceType::C_L, $testName);
            $dimLightId = $broker->initRecord(DeviceType::D_L, $testName);
            $switchId = $broker->initRecord(DeviceType::S, $testName);
            $thermId = $broker->initRecord(DeviceType::T, $testName);

            //test return of ID numbers
            $this->assertEquals($lightId + 1, $colLightId);
            $this->assertEquals($lightId + 2, $dimLightId);
            $this->assertEquals($lightId + 3, $switchId);
            $this->assertEquals($lightId + 4, $thermId);

            
            //used for testing later. Single declaration.
            $newName = "name-insky";
            
            
            $lights = $broker->getAllLights();
            $preCount = count($lights);
            foreach($lights as $light){
                $this->assertEquals("Device", get_class($light));
                $this->assertEquals($light->getType(), DeviceType::L);

                if($light->getID() == $lightId){
                    #test update methods
                    $this->assertTrue($broker->updateLightCommand($light->getId(), LightCommands::ON_CMD, "a new command"));
                    $this->assertTrue($broker->updateLightCommand($light->getId(), LightCommands::OFF_CMD, "a new command"));
                    $this->assertTrue($broker->updateLightCommand($light->getId(), LightCommands::GET_STATE, "a new command"));
                    #test update name
                    $this->assertTrue($broker->updateNameById($light->getId(), $light->getType(), $newName));
                    
                    #test update field failures
                    
                    $this->assertFalse($broker->updateLightCommand($light->getId(), ThermostatCommands::FAN_OFF, "a bad command"));
                    #test deletion
                    $this->assertTrue($broker->removeDevice($light->getId()));
                }
            }
            $this->assertTrue(($preCount - 1) == count($broker->getAllLights()));
            
            
            $lights = $broker->getAllColoredLights();
            $preCount = count($lights);
            foreach($lights as $light){
                $this->assertEquals("Device", get_class($light));
                $this->assertEquals($light->getType(), DeviceType::C_L);
                if($light->getID() == $colLightId){
                    #test update methods
                    $this->assertTrue($broker->updateColoredLightCommand($light->getId(), LightCommands::ON_CMD, "a new command"));
                    $this->assertTrue($broker->updateColoredLightCommand($light->getId(), LightCommands::OFF_CMD, "a new command"));
                    $this->assertTrue($broker->updateColoredLightCommand($light->getId(), LightCommands::GET_STATE, "a new command"));
                    #test update name
                    $this->assertTrue($broker->updateNameById($light->getId(), $light->getType(), $newName));
                    
                    #test update field failures
                    
                    $this->assertFalse($broker->updateColoredLightCommand($light->getId(), ThermostatCommands::FAN_OFF, "a bad command"));
                    #test deletion
                    $this->assertTrue($broker->removeDevice($light->getId()));
                }
            }
            $this->assertTrue(($preCount - 1) == count($broker->getAllColoredLights()));
            
            
            $lights = $broker->getAllDimmingLights();
            $preCount = count($lights);
            foreach($lights as $light){
                $this->assertEquals("Device", get_class($light));
                $this->assertEquals($light->getType(), DeviceType::D_L);
                if($light->getID() == $dimLightId){
                    #test update methods
                    $this->assertTrue($broker->updateDimmingLightCommand($light->getId(), LightCommands::ON_CMD, "a new command"));
                    $this->assertTrue($broker->updateDimmingLightCommand($light->getId(), LightCommands::OFF_CMD, "a new command"));
                    $this->assertTrue($broker->updateDimmingLightCommand($light->getId(), LightCommands::GET_STATE, "a new command"));
                    #test update name
                    $this->assertTrue($broker->updateNameById($light->getId(), $light->getType(), $newName));
                    
                    #test update field failures
                    
                    $this->assertFalse($broker->updateDimmingLightCommand($light->getId(), ThermostatCommands::FAN_OFF, "a bad command"));
                    #test deletion
                    $this->assertTrue($broker->removeDevice($light->getId()));
                }
            }
            //$this->assertTrue(($preCount - 1) == count($broker->getAllDimmingLights()));
            
            
            
            $switches = $broker->getAllSwitches();
            $preCount = count($switches);
            foreach($switches as $switch){
                $this->assertEquals("Device", get_class($switch));
                $this->assertEquals($switch->getType(), DeviceType::S);
                if($switch->getName() == $testName){
                    #test update methods
                    $this->assertTrue($broker->updateSwitchCommand($switch->getId(), SwitchCommands::ON_CMD, "a new command"));
                    $this->assertTrue($broker->updateSwitchCommand($switch->getId(), SwitchCommands::OFF_CMD, "a new command"));
                    $this->assertTrue($broker->updateSwitchCommand($switch->getId(), SwitchCommands::GET_STATE, "a new command"));
                    #test update name
                    $this->assertTrue($broker->updateNameById($switch->getId(), $switch->getType(), $newName));
                    
                    #test update field failures
                    $this->assertFalse($broker->updateSwitchCommand($switch->getId(), ThermostatCommands::FAN_OFF, "a bad command"));
                    #test deletion
                    $this->assertTrue($broker->removeDevice($switch->getId()));
                }
            }
            //$this->assertTrue(($preCount - 1) == count($broker->getAllSwitches()));
            
            
            $thermostats = $broker->getAllThermostats();
            $preCount = count($thermostats);
            foreach($thermostats as $therm){
                $this->assertEquals("Device", get_class($therm));
                $this->assertEquals($therm->getType(), DeviceType::T);
                if($therm->getName() == $testName){
                    #test update methods
                    $this->assertTrue($broker->updateThermostatCommand($therm->getId(), ThermostatCommands::FAN_OFF, "a new command"));
                    $this->assertTrue($broker->updateThermostatCommand($therm->getId(), ThermostatCommands::FAN_ON, "a new command"));
                    $this->assertTrue($broker->updateThermostatCommand($therm->getId(), ThermostatCommands::GET_TEMP, "a new command"));
                    $this->assertTrue($broker->updateThermostatCommand($therm->getId(), ThermostatCommands::SET_TEMP, "a new command"));
                    #test update name
                    $this->assertTrue($broker->updateNameById($therm->getId(), $therm->getType(), $newName));
                    
                    #test update field failures
                    $this->assertFalse($broker->updateThermostatCommand($therm->getId(), SwitchCommands::OFF_CMD, "a bad command"));
                    #test deletion
                    $this->assertTrue($broker->removeDevice($therm->getId()));
                }
            }
            //$this->assertTrue(($preCount - 1) == count($broker->getAllThermostats()));
        }
        
        public function testVarManager(){
            include 'conf/dbInfo.php';
            include 'lib/VariableManager.php';
            $broker = new DataBroker($servername, $username, $password, $dbname);
            $id = $broker->initRecord(DeviceType::L, "test var manager");
            $jsonOut = array("a" => "alpha", "b" => "2");
            $broker->updateVarsById($id, json_encode($jsonOut));
            
            $varManager = new VariableManager($id, DeviceType::L);
            
            $values = $varManager->getVariables();
            $this->assertTrue($values == $jsonOut);
            $values["a"] = "zulu";
            $varManager->storeVariables($values);
            $jsonOut["a"] = "zulu";
            
            $this->assertTrue($values == $jsonOut);
            $broker->removeDevice($id);
            
        }
        
        public function testHueFactory(){
            require_once 'lib/support/factories/HueFactory.php';
            include 'examples/LightInit.php';
            include 'conf/dbInfo.php';
            $idNum = HueFactory::buildHue($hueHost, $hueUsername, 1, "LivingRoomFactoryTest");
            $this->assertNotEquals($idNum, -1);
            $this->assertTrue($idNum > 0);
            $idNum2 = HueFactory::buildColorHue($hueHost, $hueUsername, 1, "color from factory");
            $this->assertNotEquals($idNum2, -1);
            $this->assertTrue($idNum2 > 0);
            $broker = new DataBroker($servername, $username, $password, $dbname);
            $broker->removeDevice($idNum);
            $broker->removeDevice($idNum2);
        }
        
        public function testEcobeeFactory(){
            require_once 'lib/support/factories/EcobeeFactory.php';
            include 'conf/dbInfo.php';
            $name="factory test";
            $apiKey = "b1B9A8NIec8CxHA49fVOSGVs5lJnexxX";
            $authCode="zckheMNpoomQdv1pqwINaYeEUrTJIoXt";
            $refToken = "uLwSCtosLPl6cFEc3aijbmBghyVi83rp";
            $accToken ="WVdGhzk8wp1urTqayQ5Y9ynn5xJMCuQX";
            $idNum = EcobeeFactory::BuildEcobee($apiKey, $authCode, $accToken, $refToken, $name);
            $this->assertTrue($idNum != -1);
            $broker = new DataBroker($servername, $username, $password, $dbname);
            //$broker->removeDevice($idNum);
        }
    }