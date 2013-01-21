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
 * @author Johann Brocail <johannbrocail@enaco.fr>
 */

namespace API\beans;

use API\connectors\ApplicationClient;

class User extends AbstractBeans
{
	protected $userId;
	
	protected $accountType;
	
	protected $firstName;
	
	protected $lastName;
	
	protected $userEmail;
	
	protected $identifier;
	
	protected $publicName;
	
	protected $languageCode;
	
	protected $isProfessional;
	
	protected $Address;

	public function __construct(ApplicationClient $ApplicationClient, $userId = null)
	{
		parent::__construct($ApplicationClient);
		
		if($userId)
		{
			$datas = array(
				'userId' => $userId
			);
			
			//$this->getApplicationClient()->execute('getUserDetails', $datas);
		}
	}
	
	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}
	
	/**
	 * @return the $accountType
	 */
	public function getAccountType() {
		return $this->accountType;
	}
	
	/**
	 * @return the $firstName
	 */
	public function getFirstName() {
		return $this->firstName;
	}
	
	/**
	 * @return the $lastName
	 */
	public function getLastName() {
		return $this->lastName;
	}
	
	/**
	 * @return the $userEmail
	 */
	public function getUserEmail() {
		return $this->userEmail;
	}
	
	/**
	 * @return the $identifier
	 */
	public function getIdentifier() {
		return $this->identifier;
	}
	
	/**
	 * @return the $publicName
	 */
	public function getPublicName() {
		return $this->publicName;
	}
	
	/**
	 * @return the $languageCode
	 */
	public function getLanguageCode() {
		return $this->languageCode;
	}
	
	/**
	 * @return the $Address
	 */
	public function getAddress() {
		return $this->Address;
	}
	
	/**
	 * @param field_type $userId
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}
	
	/**
	 * @param field_type $accountType
	 */
	public function setAccountType($accountType) {
		$this->accountType = $accountType;
	}
	
	/**
	 * @param field_type $firstName
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}
	
	/**
	 * @param field_type $lastName
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}
	
	/**
	 * @param field_type $userEmail
	 */
	public function setUserEmail($userEmail) {
		$this->userEmail = $userEmail;
	}
	
	/**
	 * @param field_type $identifier
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}
	
	/**
	 * @param field_type $publicName
	 */
	public function setPublicName($publicName) {
		$this->publicName = $publicName;
	}
	
	/**
	 * @param field_type $languageCode
	 */
	public function setLanguageCode($languageCode) {
		$this->languageCode = $languageCode;
	}
	
	/**
	 * @return the $isProfessional
	 */
	public function getIsProfessional() {
		return $this->isProfessional;
	}
	
	/**
	 * @param field_type $isProfessional
	 */
	public function setIsProfessional($isProfessional) {
		$this->isProfessional = $isProfessional;
	}
	
	/**
	 * @param field_type $Address
	 */
	public function setAddress($Address) {
		$this->Address = $Address;
	}
	
}
