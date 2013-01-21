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
use API\beans\DraftAttachment;
use API\beans\DraftRecipient;
use API\beans\Draft;
use API\beans\LetterDeliveryType;
use API\connectors\ApplicationClient;
use API\connectors\SessionClient;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Test PHP Client API</title>
	</head>
	<body>

<?php

include (__DIR__ . '/../API/connectors/SessionClient.php');
include (__DIR__ . '/../API/connectors/ApplicationClient.php');
include (__DIR__ . '/../API/connectors/LetterAttachmentClient.php');
include (__DIR__ . '/../API/beans/AbstractBeans.php');
include (__DIR__ . '/../API/beans/LetterDeliveryType.php');
include (__DIR__ . '/../API/beans/Draft.php');
include (__DIR__ . '/../API/beans/DraftAttachment.php');
include (__DIR__ . '/../API/beans/DraftRecipient.php');

try
{
	// INIT
	$identifierOrEmail = "johannbrocail@enaco.fr";
	$password = "63JGP5TU";
	$letterSubject = "Hello PHP";
	$recipientEmail = "ooskinzoo@hotmail.com";
	
	$text = "<b>test</b>";
	$attachmentFile = "example.pdf";
	
	// Enable or disable HTTP trace
	SessionClient::$debug = true;
	
	// Create new session
	$SessionClient = new SessionClient($identifierOrEmail, $password);
	
	// Create new application connector
	$ApplicationClient = new ApplicationClient($SessionClient);
	
	// Recovery of different types of letter available
	$LetterDeliveryTypes = $ApplicationClient->getDeliveryAndLetterTypes();
	
	// Create new draft
	$Draft = new Draft($ApplicationClient);
	
	foreach($LetterDeliveryTypes['letterDeliveryTypes'] as $letterDeliveryType)
	{
		if($letterDeliveryType['code'] == LetterDeliveryType::$CERTIFIED_LETTER_CODE)
		{
			$LetterDeliveryType = new LetterDeliveryType($ApplicationClient, $letterDeliveryType['_id']);
			// Assignment to draft the type of letter
			$Draft->setLetterDeliveryType($LetterDeliveryType);
		}
	}
	
	// Set the title
	$Draft->setTitle($letterSubject);
	// Set the content
	$Draft->setText($text);
	
	// Create new recipient
	$Recipient = new DraftRecipient();
	// Set the e-mail
	$Recipient->setEmailAddress($recipientEmail);
	// Assignment to draft the recipient
	$Draft->addRecipient($Recipient);
	
	if(!empty($attachmentFile))
	{
		// Create new Attachment
		$DraftAttachment = new DraftAttachment($ApplicationClient);
		// Set filename
		$DraftAttachment->setFilename($attachmentFile);
		// Assignment to draft the attachment
		$Draft->addAttachment($DraftAttachment);
	}
	
	// Save the draft
	$ApplicationClient->setLetter($Draft);
	// Send the draft
	$ApplicationClient->sendLetter($Draft);
	
	// Close session
	$SessionClient->closeSession();

}
catch (HttpException $ex)
{
	echo $ex;
}
?>

	</body>
</html>
