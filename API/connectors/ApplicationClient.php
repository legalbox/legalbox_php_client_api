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

use API\beans\Draft;

use API\APIResourcesManager;

class ApplicationClient
{
	const URL_APPLICATION = 'https://mail.legalbox.com/restful/application';
	
	private $_SessionClient;
	private $_FactoryBeans;
	
	public function __construct(SessionClient $SessionClient)
	{
		$this->_SessionClient = $SessionClient;
	}
	
	public function execute($request, $data, $filename = null)
	{
		$datas = array();
		
		$datas['request'] = $request;
		$datas['data'] = $data;
		$datas['sessionId'] = $this->_SessionClient->sessionId;
		$datas['userId'] = $this->_SessionClient->userId;
		
		
		$response = $this->_SessionClient->execute(self::URL_APPLICATION, $datas, $filename);
		
		if($response['error'])
		{
			return false;
		}
		
		return $response['data'];
	}
	
	/**
	 * Etablir la liste des fuseaux horaires
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getTimeZoneList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getTimeZoneList', $datas);
	}
	
	/**
	 * Etablir la liste des pays
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getCountryList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getCountryList', $datas);
	}
	
	/**
	 * Récupérer le libellé d'un pays
	 * @param Integer $countryId Id du pays
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getCountryLabel($countryId, $languageCode = null)
	{
		$datas = array(
			'countryId' => $countryId
		);
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getCountryLabel', $datas);
	}
	
	/**
	 * Etablir la liste des états d'un courrier
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getStatusTypeList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getStatusTypeList', $datas);
	}
	
	/**
	 * Etablir la liste des langues disponibles
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getLanguageList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getLanguageList', $datas);
	}
	
	/**
	 * Etablir la liste des dossiers (de courriers)
	 * @return Array
	 */
	public function getLetterFolderList()
	{
		$datas = array();
		
		return $this->execute('getLetterFolderList', $datas);
	}
	
	/**
	 * Etablir la liste des types d'entreprise
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getCompanyTypeList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getCompanyTypeList', $datas);
	}
	
	/**
	 * Etablir la liste des types des news
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getNewsItemTypeList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getNewsItemTypeList', $datas);
	}
	
	/**
	 * Etablir la liste des modes de distribution et des types de courrier
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getDeliveryAndLetterTypes($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getDeliveryAndLetterTypes', $datas);
	}
	
	/**
	 * Préparer un courrier / créer un brouillon
	 * @param Draft $Draft
	 */
	public function setLetter(Draft $Draft)
	{
		$datas = array(
			'letterId' => null,
			'letterDeliveryTypeId' => $Draft->getLetterDeliveryType()->getId(),
			'letterTypeId' => null, 
			'title' => $Draft->getTitle(),
			'text' => $Draft->getText()
		);
		

		
		$recipientArray = array();
		$recipientList = $Draft->getRecipientList();
		
		$nonSubscribedRecipient = array();
		for($i = 0; $i < count($recipientList); $i++)
		{
			$recipient = $recipientList[$i];
			
			$response = $this->isEmailOrIdentifierRegistered($recipient->getEmailAddress());
			
			if($response["isRegistered"])
			{
				$userId = $response["userId"];
			}
			
			if(empty($userId))
			{
				echo "<br/>set nonSubscribedRecipient";
				$nonSubscribedRecipient["isProfessional"] = $recipient->isProfessional();
				$nonSubscribedRecipient["prepayeResponse"] = $recipient->isPrepayedRecipient();
				$nonSubscribedRecipient["emailAddress"] = $recipient->getEmailAddress();
				$nonSubscribedRecipient["notificationLanguageCode"] = $recipient->getNotificationLanguageCode();
				$nonSubscribedRecipient["attachmentSignatureRequestList"] = $recipient->getSignatureRequestIndexArray();
			}
			else
			{
				echo "<br/>set recipientObject";
				$recipientObject = array();
				$recipientObject["userId"] = $userId;
				$recipientObject["prepayeResponse"] = $recipient->isPrepayedRecipient();
				$recipientObject["notificationLanguageCode"] = $recipient->getNotificationLanguageCode();
				$recipientObject["isCC"] = $recipient->isCarbonCopyRecipient();
				//				$recipientObject["attachmentSignatureRequestList"] = $recipient->getSignatureRequestIndexArray();
				array_push($recipientArray, $recipientObject);
			}
		
		}
	
		$datas["recipientList"] = $recipientArray;
		
		if (!empty($nonSubscribedRecipient)) {
			$datas["nonSubscribedRecipient"] = $nonSubscribedRecipient;
		}

		$returnObj = $this->execute('setLetter', $datas);
		return $Draft->setLetterId($returnObj["letterId"]);
	}
	
	public function sendLetter(Draft $Draft, $toArchive = false)
	{
		$datas = array();
		$datas["letterId"] = $Draft->getLetterId();
		$datas["toArchive"] = $toArchive;
		return $this->execute('sendLetter', $datas);
	}
	
	public function getContactAndPendingInvitationLists()
	{
		$datas = array( 
			'languageCode' => $this->_SessionClient->languageCode
		);
		
		return $this->execute('getContactAndPendingInvitationLists', $datas);
	}
	
	public function getUserInformationDependingOnEmailOrIdentifierStatus($identifierOrEmail)
	{
		$datas = array( 
			'request' => 'getUserInformationDependingOnEmailOrIdentifierStatus', 
			'identifierOrEmail' => $identifierOrEmail
		);
		
		return $this->execute($datas);
	}
	
	public function isEmailOrIdentifierRegistered($identifierOrEmail)
	{
		$datas = array(
			'identifierOrEmail' => $identifierOrEmail
		);
		
		return $this->execute('isEmailOrIdentifierRegistered', $datas);
	}

	public function getUserInformations()
	{
		$datas = array( 
			'request' => 'getUserInformations'
		);
		
		return $this->execute($datas);
	}
	
	public function getCreditBalance()
	{
		$datas = array( 
			'request' => 'getCreditBalance'
		);
		
		return $this->execute($datas);
	}
	
	public function createLetterRemote(Draft $Draft)
	{
		return $this->setLetterRemote($Draft, null);
	}
	
	public function setLetterRemote(Draft $Draft, $letterId)
	{
		$datas = array();
		$datas["letterId"] = $letterId;
		$datas["letterDeliveryTypeId"] = $Draft->getLetterDeliveryType()->getId();
		$datas["text"] = $Draft->getText();
		$datas["title"] = $Draft->getTitle();
		$datas["originLetterId"] = $Draft->getOriginLetterId();
		
		$recipientArray = array();
		$recipientList = $Draft->getRecipientList();
		
		$nonSubscribedRecipient = array();
		for($i = 0; $i < count($recipientList); $i++)
		{
			$recipient = $recipientList[$i];
			
			$response = $this->isEmailOrIdentifierRegistered($recipient->getEmailAddress());
			
			if($response["isRegistered"])
			{
				$userId = $response["userId"];
			}
			
			if(empty($userId))
			{
				echo "<br/>set nonSubscribedRecipient";
				$nonSubscribedRecipient["isProfessional"] = $recipient->isProfessional();
				$nonSubscribedRecipient["prepayeResponse"] = $recipient->isPrepayedRecipient();
				$nonSubscribedRecipient["emailAddress"] = $recipient->getEmailAddress();
				$nonSubscribedRecipient["notificationLanguageCode"] = $recipient->getNotificationLanguageCode();
				$nonSubscribedRecipient["attachmentSignatureRequestList"] = $recipient->getSignatureRequestIndexArray();
			}
			else
			{
				echo "<br/>set recipientObject";
				$recipientObject = array();
				$recipientObject["userId"] = $userId;
				$recipientObject["prepayeResponse"] = $recipient->isPrepayedRecipient();
				$recipientObject["notificationLanguageCode"] = $recipient->getNotificationLanguageCode();
				$recipientObject["isCC"] = $recipient->isCarbonCopyRecipient();
				//				$recipientObject["attachmentSignatureRequestList"] = $recipient->getSignatureRequestIndexArray();
				array_push($recipientArray, $recipientObject);
			}
		
		}
	
		$datas["recipientList"] = $recipientArray;
		
		if (!empty($nonSubscribedRecipient)) {
			$datas["nonSubscribedRecipient"] = $nonSubscribedRecipient;
		}
		
		//$datas["recipientList"] = $recipientArray;
		//$datas["nonSubscribedRecipient"] = $nonSubscribedRecipient;
		
		$returnObj = $this->execute('setLetter', $datas);
		return $returnObj["letterId"];
	
	}
	
	public function addAttachmentSignatureRemote($letterId, $attachmentIndex, $base64Signature)
	{
		$datas = array();
		$datas['request'] = 'addAttachmentSignature';
		$datas["letterId"] = $letterId;
		$datas["index"] = $attachmentIndex;
		$datas["base64Signature"] = $base64Signature;
		$this->execute($datas);
	}
	
	public function sendLetterRemote($letterId, $toArchive)
	{
		$datas = array();
		$datas["letterId"] = $letterId;
		$datas["toArchive"] = $toArchive;
		$this->execute('sendLetter', $datas);
	}
}
