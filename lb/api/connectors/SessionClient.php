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
 */
class SessionClient
{
	public static $debug = false;

    public $sessionId = '';
    public $userId = '';
	public $languageCode = "en";
	public $mainEmail = '';
 
    public function openSession($identifierOrEmail, $password) {
    	
    	$jsonParameters = array(
			"request" => "openSession",
			"identifierOrEmail" => $identifierOrEmail, 
			"password" => $password);
    	
        $responseJson = $this->executePost($jsonParameters);
        
        $this->userId = $responseJson['userId'];
        $this->mainEmail = $responseJson['mainEmail'];
        $this->sessionId = $responseJson['sessionId'];
    }

    public function closeSession() {
    	
    	$jsonParameters = array(
			"request" => "closeSession",
			"userId" => $this->userId, 
			"sessionId" => $this->sessionId);
    	
        $responseJson = $this->executePost($jsonParameters);
     }
     
    public function executePost($jsonParameters) {
    	return $this->executePostByUrl(APIResourcesManager::$urlSession, $jsonParameters);
	}
	
	 public function executeApplicationPost($url, $jsonParameters) {
    	$jsonParameters["sessionId"] = $this->sessionId;
    	$jsonParameters["userId"] = $this->userId;
   		return $this->executePostByUrl($url, $jsonParameters);
	}
   
    public function executePostByUrl($url, $jsonParameters) {
    	$request = new HttpRequest($url, HttpRequest::METH_POST);
		$request->addPostFields(array('jsonParams' => json_encode($jsonParameters)));

		if (SessionClient::$debug) {
			echo "<div style='border: 1px #aaa solid; margin:5px; pading:5px;'><pre>\n";
			echo "SEND: " . json_encode($jsonParameters) . "<br/>\n";
		}
		$bodyResponse = $request->send()->getBody();
		if (SessionClient::$debug) {
			echo "RECEIVE: " . htmlentities($bodyResponse). "<br/>\n";
			echo "</pre></div>\n";
		}
        $responseJson = json_decode($bodyResponse, true);
		return $responseJson;
	}

	public function executePostByUrlWithFile($url, $jsonParameters, $filename) {
		$request = new HttpRequest($url, HttpRequest::METH_POST);
		$request->addPostFields(array('jsonParams' => json_encode($jsonParameters)));
		$return = $request->addPostFile(basename($filename), $filename);
		if (SessionClient::$debug) {
			echo "<div style='border: 1px #aaa solid; margin:5px; pading:5px;'><pre>\n";
			echo "SEND: " . json_encode($jsonParameters) . "<br/>\n";
		}
		$bodyResponse = $request->send()->getBody();
		if (SessionClient::$debug) {
			echo "RECEIVE: " . htmlentities($bodyResponse). "<br/>\n";
			echo "</pre></div>\n";
		}
        $responseJson = json_decode($bodyResponse, true);
		return $responseJson;
	}
   
      
     

    
    public function createApplicationClient() {
		$applicationClient = new ApplicationClient();
		$applicationClient->session = $this;
		
		return $applicationClient;
	}
}


?>