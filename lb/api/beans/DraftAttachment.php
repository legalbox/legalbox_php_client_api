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
 */
class DraftAttachment
{
//	private $attachmentBytes;
	private $pathFilename;
	private $index = -1;
	private $filename;
	private $base64Signature = null;
	
	
//	public static function createAttachment($attachmentBytes, $fileName) {
//		$attachment = new DraftAttachment();
//		$attachment->attachmentBytes = $attachmentBytes;
//		$attachment->filename = $fileName;
//		return $attachment;
//	}
//	
//	public static function createAttachment($inputStream, $fileName) {
//		return DraftAttachment::createAttachment(IOUtils.toByteArray(inputStream), fileName);
//	}
	
	public static function createAttachment($filename)  {
		$attachment = new DraftAttachment();
//		$attachment->attachmentBytes = fread(fopen($filename, "r"), filesize($filename));
		$attachment->pathFilename = $filename;
		$attachment->filename = basename($filename);
		return $attachment;
	}
	
	public function getBase64Signature() {
		return $this->base64Signature;
	}
	
	public function getIndex() {
		return $this->index;
	}
	
	public function getPathFilename() {
		return $this->pathFilename;
	}
	
	public function getFilename() {
		return $this->filename;
	}
	
	public function isSigned() {
		return !empty($this->base64Signature);
	}
	
	public function setIndex($index) {
		$this->index = $index;
	}
	
	public function setFilename($filename) {
		$this->pathFilename = $filename;
		$this->filename = basename($filename);
	}
	
	public function setBase64Signature($base64Signature) {
		$this->base64Signature = $base64Signature;
	}
}

?>