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
	
	protected $countryCode;
	
	protected $zipCode;
	
	protected $town;
	
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

}
