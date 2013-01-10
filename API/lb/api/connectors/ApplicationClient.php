<?php


class ApplicationClient
{

    public $session;

	public function getContactAndPendingInvitationLists() {
    	$data = array(
			"languageCode" => $this->session->languageCode);

		return $this->executePost("getContactAndPendingInvitationLists", $data);
	}
	
	public function getUserInformationDependingOnEmailOrIdentifierStatus($identifierOrEmail) {
    	$data = array(
			"identifierOrEmail" => $identifierOrEmail);

		return $this->executePost("getUserInformationDependingOnEmailOrIdentifierStatus", $data);
	}
		

   public function isEmailOrIdentifierRegistered($identifierOrEmail) {
    	$data = array(
			"identifierOrEmail" => $identifierOrEmail);

		$jsonReturned = $this->executePost("isEmailOrIdentifierRegistered", $data);
		return $jsonReturned;
	}
    
    
    public function getDeliveryAndLetterTypes() {
    	$data = array(
			"languageCode" => $this->session->languageCode);

		return $this->executePost("getDeliveryAndLetterTypes", $data);
	}
	
	public function getUserInformations() {
		return $this->executePost("getUserInformations");
	}
	
	public function getCreditBalance() {
		return $this->executePost("getCreditBalance");
	}
	
	public function createLetterRemote($draft) {
		return $this->setLetterRemote($draft, null);
	}
	
	public function setLetterRemote($draft, $letterId) {
		$data = array();
		$data["letterId"] = $letterId;
		$data["letterDeliveryTypeId"] = $draft->getLetterDeliveryTypeId();
		$data["text"] = $draft->getContent();
		$data["title"] = $draft->getTitle();
		$data["originLetterId"] = $draft->getOriginLetterId();
		
		$recipientArray = array();
		$recipientList = $draft->getRecipientList();
		
		$nonSubscribedRecipient = array();
		for ($i = 0; $i < count($recipientList); $i++) {
			$recipient = $recipientList[$i];
			$response = $this->isEmailOrIdentifierRegistered($recipient->getEmailAddress());
			
			if ($response["isRegistered"]) {
				$userId = $response["userId"];
			}
			
			if (empty($userId)) {
				echo "<br/>set nonSubscribedRecipient";
				$nonSubscribedRecipient["isProfessional"] = $recipient->isProfessional();
				$nonSubscribedRecipient["prepayeResponse"] = $recipient->isPrepayedRecipient();
				$nonSubscribedRecipient["emailAddress"] = $recipient->getEmailAddress();
				$nonSubscribedRecipient["notificationLanguageCode"] = $recipient->getNotificationLanguageCode();
				$nonSubscribedRecipient["attachmentSignatureRequestList"] = $recipient->getSignatureRequestIndexArray();
			} else {
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
	
		if (empty($nonSubscribedRecipient)) {
			$data["recipientList"] = $recipientArray;
		} else {
			$data["nonSubscribedRecipient"] = $nonSubscribedRecipient;
		}
			
		$returnObj = $this->executePost("setLetter", $data);
		return $returnObj["letterId"];
		
	}
	
	public function addAttachmentSignatureRemote(
			$letterId, 
			$attachmentIndex, 
			$base64Signature) {
		$data = array();
		$data["letterId"] = $letterId;
		$data["index"] = $attachmentIndex;
		$data["base64Signature"] = $base64Signature;
		$this->executePost("addAttachmentSignature", $data);
	}

	public function sendLetterRemote($letterId, $toArchive) {
		$data = array();
		$data["letterId"] = $letterId;
		$data["toArchive"] = $toArchive;
		$this->executePost("sendLetter", $data);
	}
    
    
    public function executePost($request, $jsonData) {
		$jsonParameters["request"] = $request;

		if (is_null($jsonData)) {
			$empty['empty'] = "void";
			$jsonParameters["data"] = $empty;
		} else {
			$jsonParameters["data"] = $jsonData;
		}

 		$responseJson = $this->session->executeApplicationPost(APIResourcesManager::$urlApplication, $jsonParameters);
		
		return $responseJson['data'];
	}
    
}


?>