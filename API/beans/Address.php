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

class Address extends AbstractBeans
{
	protected $addressId;
	
	protected $address1;
	
	protected $address2;
	
	protected $address3;
	
	protected $countryCode;
	
	protected $zipCode;
	
	protected $town;
	
	protected $cedex;
	
	protected $stateProvince;
	
	
	public function __construct(ApplicationClient $ApplicationClient, $addressId = null)
	{
		parent::__construct($ApplicationClient);
	
		if($addressId)
		{
			$datas = array(
					'addressId' => $addressId
			);
				
			//$this->getApplicationClient()->execute('getAddressDetails', $datas);
		}
	}
	
	/**
	 * @return the $addressId
	 */
	public function getAddressId() {
		return $this->addressId;
	}

	/**
	 * @return the $address1
	 */
	public function getAddress1() {
		return $this->address1;
	}

	/**
	 * @return the $countryCode
	 */
	public function getCountryCode() {
		return $this->countryCode;
	}

	/**
	 * @return the $zipCode
	 */
	public function getZipCode() {
		return $this->zipCode;
	}

	/**
	 * @return the $town
	 */
	public function getTown() {
		return $this->town;
	}

	/**
	 * @param field_type $addressId
	 */
	public function setAddressId($addressId) {
		$this->addressId = $addressId;
	}

	/**
	 * @param field_type $address1
	 */
	public function setAddress1($address1) {
		$this->address1 = $address1;
	}

	/**
	 * @param field_type $countryCode
	 */
	public function setCountryCode($countryCode) {
		$this->countryCode = $countryCode;
	}

	/**
	 * @param field_type $zipCode
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}

	/**
	 * @param field_type $town
	 */
	public function setTown($town) {
		$this->town = $town;
	}
	
	/**
	 * @return the $address2
	 */
	public function getAddress2() {
		return $this->address2;
	}
	
	/**
	 * @return the $address3
	 */
	public function getAddress3() {
		return $this->address3;
	}
	
	/**
	 * @return the $cedex
	 */
	public function getCedex() {
		return $this->cedex;
	}
	
	/**
	 * @return the $stateProvince
	 */
	public function getStateProvince() {
		return $this->stateProvince;
	}
	
	/**
	 * @param field_type $address2
	 */
	public function setAddress2($address2) {
		$this->address2 = $address2;
	}
	
	/**
	 * @param field_type $address3
	 */
	public function setAddress3($address3) {
		$this->address3 = $address3;
	}
	
	/**
	 * @param field_type $cedex
	 */
	public function setCedex($cedex) {
		$this->cedex = $cedex;
	}
	
	/**
	 * @param field_type $stateProvince
	 */
	public function setStateProvince($stateProvince) {
		$this->stateProvince = $stateProvince;
	}
	

}
