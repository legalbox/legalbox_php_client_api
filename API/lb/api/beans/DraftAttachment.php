<?php


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