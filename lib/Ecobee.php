<?php
    class Ecobee{
        var $API_KEY;
        var $AUTHORIZATION_CODE;
        var $ACCESS_TOKEN;
        var $REFRESH_TOKEN;
        var $holdTemp;
        var $farenTemp;
        var $celTemp;
        var $humid;
        var $mode;
        var $lastReponse;
        var $lastTokens;
        var $lastRefresh;
        
        public function __construct($accessToken, $refreshToken, $apiKey, $authCode, $lastRefresh) {
            $this->API_KEY = $apiKey;
            $this->AUTHORIZATION_CODE = $authCode;
            $this->REFRESH_TOKEN = $refreshToken;
            $this->ACCESS_TOKEN = $accessToken;
            //set to some arbitrary value that will need refreshing
            $this->lastRefresh = $lastRefresh;
            //$this->lastRefresh = strtotime('2010-04-28 17:25:01');
        }
        
        public function setLastRefresh($lastRefresh){
            $this->lastRefresh = $lastRefresh;
        }
        
        function getCurrentHoldTemp() {
            return $this->holdTemp;
        }

        function getCurrentTemp() {
            return $this->farenTemp;
        }

        function getCurrentHumid() {
            return $this->humid;
        }

        function getCurrentMode() {
            return $this->mode;
        }

        function getAPI_KEY() {
            return $this->API_KEY;
        }

        function getAUTHORIZATION_CODE() {
            return $this->AUTHORIZATION_CODE;
        }

        function getACCESS_TOKEN() {
            return $this->ACCESS_TOKEN;
        }

        function getREFRESH_TOKEN() {
            return $this->REFRESH_TOKEN;
        }


                // $var = 'auto', 'auxHeatOnly', 'cool', 'heat', 'off'.
        // return true if successful false otherwise
        public function SetMode($var){
            //global $ACCESS_TOKEN;
            
            $json = '{"selection": {
                    "selectionType":"registered",
                    "selectionMatch":""
                },
                "thermostat": {
                    "settings":{
                    "hvacMode":"cool"}
                }
            }';
            $result = json_decode($json);
            $result->thermostat->settings->hvacMode = $var;
            
            $data_string = json_encode($result);

            $url = 'https://api.ecobee.com/1/thermostat?format=json';
            $header = array('Content-Type: text/json', 'Authorization: Bearer ' . $this->ACCESS_TOKEN, 'Content-Length: ' . strlen($data_string));

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);   

            $response = curl_exec($ch);
            curl_close($ch);    
            $this->lastReponse = $response;
            if (json_decode($response)->status->code == 0)
            {
                $this->mode = $var;
                return true;
            }
            else
            {
                return false;
            }
        }
        
            public function Registration()
            {
                //global $API_KEY;

                $url = 'https://api.ecobee.com/authorize?response_type=ecobeePin&client_id='.$this->API_KEY.'&scope=smartWrite';
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($ch);
                curl_close($ch);

                return $response;
            }

            public function PinTokenRequest()
            {
                //global $AUTHORIZATION_CODE, $API_KEY;

                $url = 'https://api.ecobee.com/token?';
                $postFields = 'grant_type=ecobeePin&code='.$this->AUTHORIZATION_CODE.'&client_id='.$this->API_KEY;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);            
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($ch);
                curl_close($ch);

                return $response;
            }

            //Token needs to be refresh every hour, 
            //TODO need to store the two tokens in a database
            public  function RefreshPin()
            {
                if ( !(((strtotime('now') - $this->lastRefresh) > (60 * 30)) || ($this->lastRefresh == null)) ){
                    return;
                }
                //global $REFRESH_TOKEN, $API_KEY;
                $url = 'https://api.ecobee.com/token?grant_type=refresh_token&';
                $postFields = 'refresh_token='.$this->REFRESH_TOKEN.'&client_id='.$this->API_KEY;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);            
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($ch);
                curl_close($ch);

               // Get the updated info
                $jsonObject = json_decode($response);

                // These two data need to presist between runs, 

                $accessToken = $jsonObject->access_token;   // $ACCESS_TOKEN should be update to this value
                $refreshToken = $jsonObject->refresh_token; // $REFRESH_TOKEN should be updated to this value
                $this->lastTokens = $response;
//                echo '<p>ACCESS_TOKEN = "' . $accessToken .'"</p>';
 //               echo '<p>REFRESH_TOKEN = "' . $refreshToken . '"</p>';
                $this->ACCESS_TOKEN = $accessToken;
                $this->REFRESH_TOKEN = $refreshToken;
                $this->lastRefresh = strtotime('now');
                return $response;
            }

            public function GetStatus()
            {

                $url = 'https://api.ecobee.com/1/thermostat?format=json&body={"selection":{"selectionType":"registered","selectionMatch":"","includeRuntime":true}}';
                $header = array ('Content-Type: text/json', 'Authorization: Bearer '.$this->ACCESS_TOKEN);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($ch);
                curl_close($ch);    
                
                $jsonObject = json_decode($response); 
                $this->thermostat = $jsonObject->thermostatList[0]->runtime; 
                $this->farenTemp = ($this->thermostat->actualTemperature / 10.0); 
                $this->celTemp = $this->farToCel($this->farenTemp);
                $this->humid = $this->thermostat->actualHumidity .'%'; 
                $this->holdTemp = ($this->thermostat->desiredCool / 10.0);
                if($this->mode == null){
                    $this->mode = $this->GetMode();
                }
        
                return $response;
            }
            
            //$var in tenth of degrees, i.e 
            //75.0 F degrees -> $var = 750
            //84.5 F degrees -> $var = 845 
            //return true if successful false otherwise
            public function SetHoldTemp($var) {

                $json = '{
                "selection": {
                  "selectionType":"registered",
                  "selectionMatch":""
                },
                "functions": [
                  {
                    "type":"setHold",
                    "params":{
                      "holdType":"nextTransition",
                      "heatHoldTemp":700,
                      "coolHoldTemp":700
                    }
                  }
                ]
              }';
                $result = json_decode($json);
                $result->functions[0]->params->heatHoldTemp = $var;
                $result->functions[0]->params->coolHoldTemp = $var;

                $data_string = json_encode($result);

                $url = 'https://api.ecobee.com/1/thermostat?format=json';
                $header = array('Content-Type: text/json', 'Authorization: Bearer ' . $this->ACCESS_TOKEN, 'Content-Length: ' . strlen($data_string));

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);   

                $response = curl_exec($ch);
                curl_close($ch);

                if (json_decode($response)->status->code == 0)
                {
                    $this->holdTemp = $var / 10.0;
                    return true;
                }
                else
                {
                    return false;
                }
            }
            
            
            // return true 'auto', 'auxHeatOnly', 'cool', 'heat', 'off'.
            public function GetMode()
            {

                $url = 'https://api.ecobee.com/1/thermostat?format=json&body={"selection":{"selectionType":"registered","selectionMatch":"","includeSettings":true}}';
                $header = array('Content-Type: text/json', 'Authorization: Bearer ' . $this->ACCESS_TOKEN);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($response);
                return $json->thermostatList[0]->settings->hvacMode;
            }
            
            // Return cold Temp hold in tenth of degrees, i.e 
            //75.0 F degrees -> return = 750
            //84.5 F degrees -> return = 845 
            public function getColdHoldTemp() {
                return $this->holdTemp;
                }

            // Return heat Temp hold in tenth of degrees, i.e 
            //75.0 F degrees -> return = 750
            //84.5 F degrees -> return = 845 
            public function getHeatHoldTemp() {
                $status = json_decode($this->GetStatus());
                return $status->thermostatList[0]->runtime->desiredHeat;
            }

            // Return current Temp of house in tenth of degrees, i.e 
            //75.0 F degrees -> return = 750
            //84.5 F degrees -> return = 845 
            public function getActualTemp()
            {
                return $this->farenTemp;
            }
            
            public function getHumidity(){
                return $this->humid;
            }
            
            /**
             * Generates JSON state of this thermostat from its variables
             */
            public function getStateAsArray(){
                $this->GetStatus();
                return array("temperature" => array("fahrenheit" => $this->farenTemp, 
                    "celsius" => $this->celTemp), "humidity" => $this->humid, 
                    "hvacMode" => $this->mode, "holdTemp" => $this->holdTemp);
            }

            /**
             * Convert fahrenheit to celsius
             * @param type $faren the degrees in farenheit
             * @return type celsius degres
             */
            private function farToCel($faren){
                return round((($faren - 32) * 5 / 9), 1);
            }
        }