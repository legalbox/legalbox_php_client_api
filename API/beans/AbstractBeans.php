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

abstract class AbstractBeans
{
	protected $ApplicationClient;
	
	public function __construct(ApplicationClient $ApplicationClient)
	{
		$this->ApplicationClient = $ApplicationClient;
	}
	
	public function toArray()
	{
		$toArray = array();
		
		$refClass = new \ReflectionClass($this);
		
		foreach($refClass->getProperties() as $property)
		{
			$toArray[$property->getName()] = call_user_func(array($this, sprintf('get%s', ucfirst($property->getName()))));
		}

		return $toArray;
	}
	
	/**
	 * @return the $ApplicationClient
	 */
	public function getApplicationClient()
	{
		return $this->ApplicationClient;
	}

	/**
	 * @param field_type $ApplicationClient
	 */
	public function setApplicationClient($ApplicationClient)
	{
		$this->ApplicationClient = $ApplicationClient;
	}
}

