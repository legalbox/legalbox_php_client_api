<?php


class Draft
{
	protected $letterDeliveryTypeId;
	protected $title;
	protected $content;
	protected $recipientList = array();
	protected $attachmentList = array();
	protected $originLetterId;
	
	protected $letterId;

	public function getLetterDeliveryTypeId() {
		return $this->letterDeliveryTypeId;
	}

	public function getTitle() {
		return $this->title;
	}
	
	public function getContent() {
		return $this->content;
	}

	public function getRecipientList() {
		return $this->recipientList;
	}
	
	public function getAttachmentList() {
		return $this->attachmentList;
	}
	
	public function getAttachmentByFilename($attachmentFilename) {
//		for (DraftAttachment attachment : attachmentList) {
//			if (attachment.getFilename().equals(attachmentFilename)) {
//				return attachment;
//			}
//		}
	}
	
	public function getOriginLetterId() {
		return $this->originLetterId;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
	
	public function setLetterDeliveryTypeId($letterDeliveryTypeId) {
		$this->letterDeliveryTypeId = $letterDeliveryTypeId;
	}
	
	public function setLetterDeliveryCode($letterDeliveryCode) {
		$deliveryType = LetterDeliveryType::getLetterDeliveryTypeByCode($letterDeliveryCode);
		$this->letterDeliveryTypeId = $deliveryType->getId();
	}

	public function addRecipient($recipient) {
		array_push($this->recipientList, $recipient);
	}
	
	public function addAttachment($attachment) {
		array_push($this->attachmentList, $attachment);
		$attachment->setIndex(count($this->attachmentList));
	}
	
	public function setOriginLetterId($originLetterId) {
		$this->originLetterId = $originLetterId;
	}
	
	public function send($application, $toArchive) {
		$this->letterId = $application->createLetterRemote($this);
		
		if(!empty($this->attachmentList)) {
			$attachmentClient = new LetterAttachmentClient();
			$attachmentClient->application = $application;

			for ($i = 0; $i < count($this->attachmentList); $i++) {
				$attachment = $this->attachmentList[$i];
				$attachmentClient->uploadAttachment($this->letterId, $attachment);
			}
		}
				
		$application->sendLetterRemote($this->letterId, $toArchive);
	}
}

?>