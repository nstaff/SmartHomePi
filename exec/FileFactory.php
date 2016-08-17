<?php
/**
 * Static class for providing factory methods for outputting execution commands
 * @author Nick Staffend <nicholas.a.staffend at gmail.com>
 */
abstract class FileFactory {
    
    /**
     * Pulls command from the database via the DataBroker.
     * Will die with JSON return "errors":"error description" if and data method
     * is unsuccessful
     * @return array JSON encoded array of error messages for success/failure
     * @param string $idNum
     * @param string $type
     * @param string $commandName
     */
    public static function generateFromDatabase($idNum, $type, $commandName){
        include __DIR__.'/../conf/dbInfo.php';
        require_once __DIR__.'/../lib/DeviceType.php';
        require_once __DIR__.'/../lib/DataBroker.php';
        require_once __DIR__.'/../lib/Commands.php';
        
        //Declare broker and get commands from database
        $broker = new DataBroker($servername, $username, $password, $dbname);
        $commands = $broker->getAllFieldsAsKeyValue($idNum);
        //Check that results exist
        if($commands == FALSE){
            $json = array('errors' => 'No results returned from database');
            die(json_encode($json));
        } 
        //Check that the command exists
        if ($commands[$commandName] == NULL || $commands[$commandName] == ""){
            $json = array('errors' => 'No '.$commandName.' or field is null in database for '.$type.' with ID='.$idNum);
            die(json_encode($json));
        }

        //try to write the files
        $json["initFileOutput"] = FileFactory::generateInit($commands[CommonCommands::INIT])["initFileOutput"];
        $json["runCommandFileOutput"] = FileFactory::generateRunCommand($commands[$commandName])["runCommandFileOutput"];
        
        return $json;
    }
    
    public static function generateInit($initCode){
        try{
            $initResult = file_put_contents('init.php', $initCode);
            $jsonFileOutputMessage = 'initFileOutput';
            if($initResult){
                $json = array($jsonFileOutputMessage => 'true');
            }else{
                $json = array($jsonFileOutputMessage => 'false');
            }
        } catch (Exception $ex) {
            $json['exception'] =  "File Access Exception. Cannot generate init.php. Check permissions";
            die(json_encode($json));
        }
        return $json;
    }
    
    public static function generateRunCommand($cmdCode){
        try{
            $runResult = file_put_contents('runCommand.php', $cmdCode);
            $jsonFileOutputMessage = 'runCommandFileOutput';
            if($runResult){
                $json = array($jsonFileOutputMessage => 'true');
            }else{
                $json = array($jsonFileOutputMessage => 'false');
            }
        } catch (Exception $ex) {
            $json['exception'] =  "File Access Error. Cannot generate runCommand.php. Check permissions";
            die(json_encode($json));
        }
        return $json;
    }
}
