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


class DraftRecipient
{
	protected $emailAddress;
	protected $isProfessional = false;
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
