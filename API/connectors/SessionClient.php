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
namespace API\connectors;

use API\APIResourcesManager;
use API\connectors\ApplicationClient;

class SessionClient
{
	const URL_SESSION = 'https://mail.legalbox.com/restful/session';
	const URL_REGISTRATION = 'https://mail.legalbox.com/restful/registration';
	const URL_APPLICATION = 'https://mail.legalbox.com/restful/application';
	const URL_USER_GROUP = 'https://mail.legalbox.com/restful/userGroupBackoffice';
	
	public static $debug = false;
	
	public $sessionId;
	public $userId;
	public $languageCode;
	
	public function __construct($identifierOrEmail, $password, $languageCode = 'fr')
	{
		$this->languageCode = $languageCode;
		
		$this->openSession($identifierOrEmail, $password);
	}
	
	public function execute($url, $parameters, $filename = null)
	{
		$HttpRequest = new \HttpRequest($url, \HttpRequest::METH_POST);

		
		$HttpRequest->addPostFields(array( 
			'jsonParams' => json_encode($parameters)
		));
		
		if($filename)
		{
			$HttpRequest->addPostFile(basename($filename), $filename);
		}
		
		if(self::$debug)
		{
			print "<div style='border: 1px #aaa solid; margin:5px; pading:5px;'><pre>\n";
			print "SEND: " . json_encode($parameters) . "<br />\n";
		}
		
		$bodyResponse = $HttpRequest->send()->getBody();
		
		if(self::$debug)
		{
			print "RECEIVE: " . htmlentities($bodyResponse) . "<br />\n";
			print "</pre></div>\n";
		}
		
		$responseJson = json_decode($bodyResponse, true);
		
		return $responseJson;
	
	}
	
	/**
	 * Ouvrir une session
	 * @param String $identifierOrEmail Identifiant ou Email
	 * @param String $password Mot de passe
	 */
	public function openSession($identifierOrEmail, $password)
	{
		$jsonParameters = array( 
			"request" => "openSession", 
			"identifierOrEmail" => $identifierOrEmail, 
			"password" => $password
		);
		
		$responseJson = $this->execute(self::URL_SESSION, $jsonParameters);
		
		if(!$responseJson['error'])
		{
			$this->userId = $responseJson['userId'];
			$this->sessionId = $responseJson['sessionId'];
			
			return $this;
		}
		
		return false;
	}
	
	/**
	 * Ferme une session
	 */
	public function closeSession()
	{
		
		$jsonParameters = array( 
			"request" => "closeSession", 
			"userId" => $this->userId, 
			"sessionId" => $this->sessionId
		);
		
		$responseJson = $this->execute(self::URL_SESSION, $jsonParameters);
	}
}