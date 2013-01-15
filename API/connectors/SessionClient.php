<?php
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

		$parameters['sessionId'] = $this->sessionId;
		$parameters['userId'] = $this->userId;
		$HttpRequest->addPostFields(array( 
			'jsonParams' => json_encode($parameters)
		));
		
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
	
	public function closeSession()
	{
		
		$jsonParameters = array( 
			"request" => "closeSession", 
			"userId" => $this->userId, 
			"sessionId" => $this->sessionId
		);
		
		$responseJson = $this->execute(self::URL_SESSION, $jsonParameters);
	}
	
	/*
	public function executePost($jsonParameters)
	{
		return $this->executePostByUrl(APIResourcesManager::$urlSession, $jsonParameters);
	}
	
	public function executeApplicationPost($url, $jsonParameters)
	{
		$jsonParameters["sessionId"] = $this->sessionId;
		$jsonParameters["userId"] = $this->userId;
		return $this->executePostByUrl($url, $jsonParameters);
	}
	
	public function executePostByUrl($url, $jsonParameters)
	{
		$request = new \HttpRequest($url, \HttpRequest::METH_POST);
		
		$request->addPostFields(array( 
			'jsonParams' => json_encode($jsonParameters)
		));
		
		if(SessionClient::$debug)
		{
			echo "<div style='border: 1px #aaa solid; margin:5px; pading:5px;'><pre>\n";
			echo "SEND: " . json_encode($jsonParameters) . "<br/>\n";
		}
		$bodyResponse = $request->send()->getBody();
		if(SessionClient::$debug)
		{
			echo "RECEIVE: " . htmlentities($bodyResponse) . "<br/>\n";
			echo "</pre></div>\n";
		}
		$responseJson = json_decode($bodyResponse, true);
		return $responseJson;
	}
	
	public function executePostByUrlWithFile($url, $jsonParameters, $filename)
	{
		$request = new \HttpRequest($url, \HttpRequest::METH_POST);
		$request->addPostFields(array(
			'jsonParams' => json_encode($jsonParameters)
		));
		$return = $request->addPostFile(basename($filename), $filename);
		if(SessionClient::$debug)
		{
			echo "<div style='border: 1px #aaa solid; margin:5px; pading:5px;'><pre>\n";
			echo "SEND: " . json_encode($jsonParameters) . "<br/>\n";
		}
		$bodyResponse = $request->send()->getBody();
		if(SessionClient::$debug)
		{
			echo "RECEIVE: " . htmlentities($bodyResponse) . "<br/>\n";
			echo "</pre></div>\n";
		}
		$responseJson = json_decode($bodyResponse, true);
		return $responseJson;
	}
	
	public function createApplicationClient()
	{
		$applicationClient = new ApplicationClient();
		$applicationClient->session = $this;
		
		return $applicationClient;
	}
	*/
}