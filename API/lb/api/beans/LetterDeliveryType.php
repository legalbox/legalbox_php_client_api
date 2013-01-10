<?php


class LetterDeliveryType
{
	protected $id;
	protected $code;
	protected $name;

	protected static $letterDeliveryTypes = array();

	public static $LRE_CODE = "LRE";
	public static $CERTIFIED_LETTER_CODE = "CERTIFIED_LETTER";
	
	public static function populateList($applicationClient) {
		$deliveryAndLetterTypes = $applicationClient->getDeliveryAndLetterTypes();
		LetterDeliveryType::populateListByDeliveryTypeList($deliveryAndLetterTypes["letterDeliveryTypes"]);
	}


	
	public static function populateListByDeliveryTypeList($list) {
		LetterDeliveryType::$letterDeliveryTypes = array();
		
		for ($i = 0; $i < count($list); $i++) {
			$item = $list[$i];
			$type = new LetterDeliveryType();
			$type->id = $item["_id"];
			$type->code = $item["code"];
			$type->name = $item["name"];
			array_push(LetterDeliveryType::$letterDeliveryTypes, $type);
		}
		
	}
	
	public static function getLetterDeliveryTypeById($id) {
		for ($i = 0; $i < count(LetterDeliveryType::$letterDeliveryTypes); $i++) {
			$type = LetterDeliveryType::$letterDeliveryTypess[$i];
			if ($type->_id == $id) {
				return $type;
			}
		}
	}
	
	public static function getLetterDeliveryTypeByCode($code) {
		for ($i = 0; $i < count(LetterDeliveryType::$letterDeliveryTypes); $i++) {
			$type = LetterDeliveryType::$letterDeliveryTypes[$i];
			if ($type->code == $code) {
				return $type;
			}
		}
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	public function getName() {
		return $this->name;
	}
}

?>