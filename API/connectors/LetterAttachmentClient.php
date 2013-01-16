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

use API\beans\DraftAttachment;

class LetterAttachmentClient
{
	const URL_UPLOAD = 'https://mail.legalbox.com/restful/fileUpload';
	const URL_DOWNLOAD = 'https://mail.legalbox.com/restful/fileDownload';
	
	private $_SessionClient;

	public function __construct(SessionClient $SessionClient)
	{
		$this->_SessionClient = $SessionClient;	
	}
	
	public function execute($url, $datas, $filename = null)
	{
		$datas['sessionId'] = $this->_SessionClient->sessionId;
		$datas['userId'] = $this->_SessionClient->userId;
		
		$url .= '?sessionId=' . $this->_SessionClient->sessionId
			. '&userId=' . $this->_SessionClient->userId
			. '&letterId=' . $datas["letterId"]
			. '&index=' . $datas["index"]
			. '&X-Progress-ID=' . $datas["X-Progress-ID"]
			. '&filename=' . $datas["filename"];
			
		$response = $this->_SessionClient->execute($url, $datas, $filename);
		
		
	}
	
	public function uploadAttachment(Draft $Draft, DraftAttachment $Attachment)
	{
		$datas = array(
			'letterId' => $Draft->getLetterId(),
			'index' => $Attachment->getIndex(), 
			'X-Progress-ID' => $Draft->getLetterId().'_'.$Attachment->getIndex(),
			'filename' => $Attachment->getFilename()
		);
		
		return $this->execute(self::URL_UPLOAD, $datas, $Attachment->getPathFilename());
	}
	
	/*
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
		
	}*/
		
}
