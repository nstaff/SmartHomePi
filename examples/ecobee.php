

<?php

echo '<p>Hello World</p>';

        //Do not change

//$REFRESH_TOKEN = 'W6YQff9DzxiBlcTIHiagEoswfTK3lNee';
        //$ACCESS_TOKEN = 'Dc0BoFOHnbEooMA3Xy2pXhxrIAuwj6Gl';

        class Ecobee{
            
            
            var $API_KEY = 'b1B9A8NIec8CxHA49fVOSGVs5lJnexxX';
            var $AUTHORIZATION_CODE = 'ggqVCBDhA7P03B4HHdI6H6YJYzuZtfa1';
            
            var $ACCESS_TOKEN = "mNEXQ64EYvtZLvPiaLI4TQoHXqQgKhfL";
            var $REFRESH_TOKEN = "qXJln05itAw80cOHyIAo9tLOj6L2ZfYK";
            
            public function __construct($accessToken, $refreshToken, $apiKey, $authCode) {
                $this->API_KEY = $apiKey;
                $this->AUTHORIZATION_CODE = $authCode;
                $this->REFRESH_TOKEN = $refreshToken;
                $this->ACCESS_TOKEN = $accessToken;
            }
            
            public function getFieldsAsJSON(){
                $json["API_KEY"] = $this->API_KEY;
                $json["AUTHORIZATION_CODE"] = $this->AUTHORIZATION_CODE;
                $json["ACCESS_TOKEN"] = $this->ACCESS_TOKEN;
                $json["REFRESH_TOKEN"] = $this->REFRESH_TOKEN;
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

                echo '<p>ACCESS_TOKEN = "' . $accessToken .'"</p>';
                echo '<p>REFRESH_TOKEN = "' . $refreshToken . '"</p>';
                $this->ACCESS_TOKEN = $accessToken;
                $this->REFRESH_TOKEN = $refreshToken;
                return $response;
            }

            public function GetStatus()
            {
                //global $ACCESS_TOKEN;

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

                return $response;
            }

        }

// Only need to refresh pin once an hour
// 
        
$ecobee = new Ecobee($storedValues["ACCESS_TOKEN"], $storedValues["REFRESH_TOKEN"], $storedValues["API_KEY"], $storedValues["AUTHORIZATION_CODE"]);
$tokens = json_decode($ecobee->RefreshPin());

$storedValues["REFRESH_TOKEN"] = $ecobee->REFRESH_TOKEN;
$storedValues["ACCESS_TOKEN"] = $ecobee->ACCESS_TOKEN;

//what he had
$jsonObject = json_decode($ecobee->GetStatus());
$thermostat = $jsonObject->thermostatList[0]->runtime;
echo json_decode($jsonObject, true);
echo '<p> Temperature ' . ($thermostat->actualTemperature / 10.0) .' F' . '</p>';
echo '<p>Humidity ' . $thermostat->actualHumidity .'%' . '</p>';

//what I have
//include 'init.php';
$ecobee = new Ecobee($storedValues["ACCESS_TOKEN"], $storedValues["REFRESH_TOKEN"], $storedValues["API_KEY"], $storedValues["AUTHORIZATION_CODE"]);
$tokens = json_decode($ecobee->RefreshPin(), true);
echo "TOKENS::";
var_dump($tokens);
echo "<br>";
//update the stored values
$storedValues["REFRESH_TOKEN"] = $ecobee->REFRESH_TOKEN;
$storedValues["ACCESS_TOKEN"] = $ecobee->ACCESS_TOKEN;
$status = json_decode($ecobee->GetStatus());
echo "STATUS::";
//echo json_encode($status);
$jsonObject = json_decode($ecobee->GetStatus());
$thermostat = $jsonObject->thermostatList[0]->runtime;
echo json_encode($thermostat);
echo '<p> Temperature ' . ($thermostat->actualTemperature / 10.0) .' F' . '</p>';
        echo '<p>Humidity ' . $thermostat->actualHumidity .'%' . '</p>';