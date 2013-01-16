<?php
/*
 * This file is part of LegalBox PHP Client API.
 *
 * Copyright 2013 LegalBox SA <contact@legalbox.com>
 * 
 * LegalBox PHP Client API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * LegalBox PHP Client API is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with LegalBox PHP Client API.  If not, see <http://www.gnu.org/licenses/lgpl.txt>.
 */

/**
 * @author David Keller <david.keller@legalbox.com>
 * @author Johann Brocail <johannbrocail@enaco.fr>
 */

namespace API\beans;

use API\connectors\ApplicationClient;

class LetterDeliveryType extends AbstractBeans
{
	protected $id;
	protected $code;
	protected $name;

	protected static $letterDeliveryTypes = array();

	public static $LRE_CODE = "LRE";
	public static $CERTIFIED_LETTER_CODE = "CERTIFIED_LETTER";
	
	
	public function __construct(ApplicationClient $ApplicationClient, $letterDeliveryTypeId = null)
	{
		parent::__construct($ApplicationClient);
		
		if($letterDeliveryTypeId)
		{
			$datas = array(
				'letterDeliveryTypeId' => $letterDeliveryTypeId
			);
			
			//$this->getApplicationClient()->execute('getLetterDeliveryTypeDetails', $datas);
			$this->id = $letterDeliveryTypeId;
		}
	}
	
	public static function populateList(ApplicationClient $ApplicationClient) {
		$deliveryAndLetterTypes = $ApplicationClient->getDeliveryAndLetterTypes();
		self::populateListByDeliveryTypeList($deliveryAndLetterTypes["letterDeliveryTypes"]);
	}


	
	public static function populateListByDeliveryTypeList($list) {
		LetterDeliveryType::$letterDeliveryTypes = array();
		
		for ($i = 0; $i < count($list); $i++) {
			$item = $list[$i];
			$type = new self();
			$type->id = $item["_id"];
			$type->code = $item["code"];
			$type->name = $item["name"];
			array_push(self::$letterDeliveryTypes, $type);
		}
		
	}
	
	public static function getLetterDeliveryTypeById($id) {
		for ($i = 0; $i < count(self::$letterDeliveryTypes); $i++) {
			$type = self::$letterDeliveryTypess[$i];
			if ($type->_id == $id) {
				return $type;
			}
		}
	}
	
	public static function getLetterDeliveryTypeByCode($code) {
		for ($i = 0; $i < count(self::$letterDeliveryTypes); $i++) {
			$type = self::$letterDeliveryTypes[$i];
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
