<?php
namespace API\connectors;

use API\beans\Draft;

use API\APIResourcesManager;

class ApplicationClient
{
	
	private $_session;
	
	public function __construct(SessionClient $SessionClient)
	{
		$this->_session = $SessionClient;
	}
	
	public function getContactAndPendingInvitationLists()
	{
		$data = array( 
			'request' => 'getContactAndPendingInvitationLists',
			'languageCode' => $this->session->languageCode
		);
		
		return $this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
	
	public function getUserInformationDependingOnEmailOrIdentifierStatus($identifierOrEmail)
	{
		$data = array( 
			'request' => 'getUserInformationDependingOnEmailOrIdentifierStatus', 
			'identifierOrEmail' => $identifierOrEmail
		);
		
		return $this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
	
	public function isEmailOrIdentifierRegistered($identifierOrEmail)
	{
		$data = array( 
			'request' => 'isEmailOrIdentifierRegistered', 
			'identifierOrEmail' => $identifierOrEmail
		);
		
		$jsonReturned = $this->_session->execute(SessionClient::URL_APPLICATION, $data);
		
		return $jsonReturned;
	}
	
	public function getDeliveryAndLetterTypes()
	{
		$data = array( 
			'request' => 'getDeliveryAndLetterTypes', 
			"languageCode" => $this->_session->languageCode
		);
		
		return $this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
	
	public function getUserInformations()
	{
		$data = array( 
			'request' => 'getUserInformations'
		);
		
		return $this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
	
	public function getCreditBalance()
	{
		$data = array( 
			'request' => 'getCreditBalance'
		);
		
		return $this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
	
	public function createLetterRemote(Draft $Draft)
	{
		return $this->setLetterRemote($Draft, null);
	}
	
	public function setLetterRemote(Draft $Draft, $letterId)
	{
		$data = array();
		$data['request'] = 'setLetter';
		$data["letterId"] = $letterId;
		$data["letterDeliveryTypeId"] = $Draft->getLetterDeliveryTypeId();
		$data["text"] = $Draft->getContent();
		$data["title"] = $Draft->getTitle();
		$data["originLetterId"] = $Draft->getOriginLetterId();
		
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
		
		$data["recipientList"] = $recipientArray;
		$data["nonSubscribedRecipient"] = $nonSubscribedRecipient;
		
		$returnObj = $this->_session->execute(SessionClient::URL_APPLICATION, $data);
		return $returnObj["letterId"];
	
	}
	
	public function addAttachmentSignatureRemote($letterId, $attachmentIndex, $base64Signature)
	{
		$data = array();
		$data['request'] = 'addAttachmentSignature';
		$data["letterId"] = $letterId;
		$data["index"] = $attachmentIndex;
		$data["base64Signature"] = $base64Signature;
		$this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
	
	public function sendLetterRemote($letterId, $toArchive)
	{
		$data = array();
		$data['request'] = 'sendLetter';
		$data["letterId"] = $letterId;
		$data["toArchive"] = $toArchive;
		$this->_session->execute(SessionClient::URL_APPLICATION, $data);
	}
}
