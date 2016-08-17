<?php

    require_once 'Commands.php';
    require_once 'DeviceType.php';
    require_once 'Exceptions.php';

    class Device {
        var $id;		
        var $name;
        var $type;
	

	#constructor.  Should not be used unless exceptions are handled elsewhere.
	public function __construct($idNum, $name, $type){
		if(!DeviceType::isValidValue($type)){
			throw new InvalidTypeException();
		}
		else{
			$this->id = $idNum;
			$this->name = $name;
			$this->type = $type;
		}
	}	

	#static factory method for creation validation
	public static function build($id, $name, $type){
		try{
			return new simpleDevice($id, $name, $type);
		} catch(InvalidTypeException $ex){
			return null;
		}
	}	

	public function getJSON(){
		$fields = array('name' => $this->name, 'type' => $this->type, 'id' => $this->id);
		return json_encode($fields);
	}

	#Name Getter
	public function getName(){
		return $this->name;
	}

	#type Getter
	public function getType(){
		return $this->type;
	}

	#ID Getter
	public function getID(){
		return $this->id;
	}

	private function getFieldName($value){
		return key($value);
	}
    }
?>