<?php
namespace API\beans;


class DraftRecipient
{
	protected $emailAddress;
	protected $isProfessional;
	protected $notificationLanguageCode;
	protected $isCarbonCopyRecipient = false;
	protected $isPrepayedRecipient = false;
	protected $signatureRequestList = array(); 
	
	public function getEmailAddress() {
		return $this->emailAddress;
	}
	
	public function isProfessional() {
		return $this->isProfessional;
	}
	
	public function isCarbonCopyRecipient() {
		return $this->isCarbonCopyRecipient;
	}
	
	public function getNotificationLanguageCode() {
		return $this->notificationLanguageCode;
	}
	
	public function getSignatureRequestList() {
		return $this->signatureRequestList;
	}
	
	public function setEmailAddress($emailAddress) {
		$this->emailAddress = $emailAddress;
	}
	
	public function setProfessional($isProfessional) {
		$this->isProfessional = $isProfessional;
	}
	
	public function setIsisCarbonCopyRecipient($isCarbonCopyRecipient) {
		$this->isCarbonCopyRecipient = $isCarbonCopyRecipient;
	}
	
	public function setNotificationLanguageCode($notificationLanguageCode) {
		$this->notificationLanguageCode = $notificationLanguageCode;
	}

	public function isPrepayedRecipient() {
		return $this->isPrepayedRecipient;
	}
	
	public function setPrepayedRecipient($isPrepayedRecipient) {
		$this->isPrepayedRecipient = $isPrepayedRecipient;
	}
	
	public function addSignatureRequest($attachment) {
		return array_push($this->signatureRequestList, $attachment);
	}
	
	public function getSignatureRequestIndexArray() {
		if (count($this->signatureRequestList) == 0) {
			return;
		}
		$indexArray = new JSONArray();
//		for (DraftAttachment attachment : signatureRequestList) {
//			indexArray.put(attachment.getIndex());
//		}
		return $indexArray;
	}
}

?>