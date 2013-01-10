<?php


class LetterAttachmentClient
{
	public $application;

	
	public function uploadAttachment($letterId, $attachment) {
				
		$jsonParameters = array();
		$jsonParameters["letterId"] = $letterId;
		$jsonParameters["index"] = $attachment->getIndex();
		$jsonParameters["X-Progress-ID"] = $letterId . "_" . $attachment->getIndex();
		$jsonParameters["filename"] = $attachment->getFilename();

		$session = $this->application->session;
    	$jsonParameters["sessionId"] = $session->sessionId;
    	$jsonParameters["userId"] = $session->userId;
    	
		$uploadUrl = APIResourcesManager::$uploadUrl 
			. "?sessionId=" . $session->sessionId
			. "&userId=" . $session->userId
			. "&letterId=" . $jsonParameters["letterId"]
			. "&index=" . $jsonParameters["index"]
			. "&X-Progress-ID=" . $jsonParameters["X-Progress-ID"]
			. "&filename=" . $jsonParameters["filename"]
			;
    	
		$session->executePostByUrlWithFile($uploadUrl, $jsonParameters, $attachment->getPathFilename());
		
//		if (attachment.isSigned()) {
//			session.createApplicationClient()
//				.addAttachmentSignatureRemote(letterId, attachment.getIndex(), attachment.getBase64Signature());
//		}
		
	}
		
}
?>