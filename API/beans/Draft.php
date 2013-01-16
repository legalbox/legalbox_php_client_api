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

namespace API\beans;

use API\connectors\ApplicationClient;

use API\connectors\LetterAttachmentClient;

class Draft extends AbstractBeans
{
	protected $letterId;
	
	protected $LetterDeliveryType;
	
	protected $LetterType;
	
	protected $title;
	protected $text;
	
	protected $recipientList;
	protected $attachmentList = array();
	
	protected $originLetterId;
	
	
	public function __construct(ApplicationClient $ApplicationClient, $letterId = null)
	{
		parent::__construct($ApplicationClient);
		
		$this->recipientList = new \ArrayIterator();
		$this->attachmentList = new \ArrayIterator();
		
		if($letterId)
		{
			$datas = array(
				'letterId' => $letterId
			);
			
			$this->getApplicationClient()->execute('getDraftDetails', $datas);
		}


	}
	
	/**
	 * @return the $letterId
	 */
	public function getLetterId()
	{
		return $this->letterId;
	}

	/**
	 * @return the $LetterDeliveryType
	 */
	public function getLetterDeliveryType()
	{
		return $this->LetterDeliveryType;
	}

	/**
	 * @return the $LetterType
	 */
	public function getLetterType()
	{
		return $this->LetterType;
	}

	/**
	 * @return the $title
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return the $text
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @return the $recipientList
	 */
	public function getRecipientList()
	{
		return $this->recipientList;
	}

	/**
	 * @return the $attachmentList
	 */
	public function getAttachmentList()
	{
		return $this->attachmentList;
	}

	/**
	 * @param field_type $letterId
	 */
	public function setLetterId($letterId)
	{
		$this->letterId = $letterId;
	}

	/**
	 * @param field_type $LetterDeliveryType
	 */
	public function setLetterDeliveryType($LetterDeliveryType)
	{
		$this->LetterDeliveryType = $LetterDeliveryType;
	}

	/**
	 * @param field_type $LetterType
	 */
	public function setLetterType($LetterType)
	{
		$this->LetterType = $LetterType;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @param field_type $text
	 */
	public function setText($text)
	{
		$this->text = $text;
	}

	/**
	 * @param field_type $recipientList
	 */
	public function setRecipientList($recipientList)
	{
		$this->recipientList = $recipientList;
	}

	/**
	 * @param field_type $attachmentList
	 */
	public function setAttachmentList($attachmentList)
	{
		$this->attachmentList = $attachmentList;
	}

	/**
	 * @return the $originLetterId
	 */
	public function getOriginLetterId()
	{
		return $this->originLetterId;
	}

	/**
	 * @param field_type $originLetterId
	 */
	public function setOriginLetterId($originLetterId)
	{
		$this->originLetterId = $originLetterId;
	}
	
	

	public function addRecipient(DraftRecipient $Recipent)
	{
		return $this->recipientList->append($Recipent);
	}
	
	public function addAttachment(DraftAttachment $Attachment)
	{
		return $this->attachmentList->append($Attachment);
	}
	
	public function getAttachmentByFilename($attachmentFilename)
	{
		//		for (DraftAttachment attachment : attachmentList) {
	//			if (attachment.getFilename().equals(attachmentFilename)) {
	//				return attachment;
	//			}
	//		}
	}
	
	
	public function send($toArchive = false)
	{
		$this->letterId = $this->getApplicationClient()->setLetter($this);
		
		if(count($this->attachmentList))
		{
			$attachmentClient = new LetterAttachmentClient();
			$attachmentClient->application = $this->getApplicationClient();
			
			foreach($this->attachmentList as $k => $Attachment)
			{
				$attachmentClient->uploadAttachment($this->letterId, $Attachment);
			}
		}
		
		$this->getApplicationClient()->sendLetterRemote($this->letterId, $toArchive);
	}
}
