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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Test PHP Client API </title>
	</head>
	<body>

<?php

include ("lb/api/APIResourcesManager.php");
include ("lb/api/connectors/SessionClient.php");
include ("lb/api/connectors/ApplicationClient.php");
include ("lb/api/connectors/LetterAttachmentClient.php");
include ("lb/api/beans/LetterDeliveryType.php");
include ("lb/api/beans/Draft.php");
include ("lb/api/beans/DraftAttachment.php");
include ("lb/api/beans/DraftRecipient.php");

try {
	// INIT
	$identifierOrEmail = "your@email.com";
	$password = "your-password";
	$letterSubject = "Hello PHP";
	$recipientEmail = "recipient@email.com";
	$text = "Have a good day PHP!!";
	$attachmentFile = "tests/doc_test.pdf";

	// enable or disable HTTP trace
	SessionClient::$debug = true;
	
	$sessionClient = new SessionClient();
	
	$sessionClient->openSession($identifierOrEmail, $password);

	echo "openSession<br/>\n";
    echo "sessionId=" . $sessionClient->sessionId . "<br/>\n";
    echo "userId=" . $sessionClient->userId . "<br/>\n";
    echo "languageCode=" . $sessionClient->languageCode . "<br/>\n";
    
    $applicationClient = new ApplicationClient();
    $applicationClient->session = $sessionClient;


    $applicationClient = $sessionClient->createApplicationClient();

	echo "<div>populate delivery type list</div>";
	LetterDeliveryType::populateList($applicationClient);
	
	echo "<div>sending letter...</div>";

	$draft = new Draft();
	echo "<div>new Draft()</div>";
	$draft->setLetterDeliveryCode(LetterDeliveryType::$CERTIFIED_LETTER_CODE);
	$draft->setTitle($letterSubject);
	$draft->setContent($text);
	
	echo "<div>new DraftRecipient()</div>";
	$recipient = new DraftRecipient();
	$recipient->setEmailAddress($recipientEmail);
	$draft->addRecipient($recipient);




	/**
	 * add attachment if any
	 */
	if(!empty($attachmentFile)) {
		echo "<br/>add attachment : " . $attachmentFile;
		$attachment = DraftAttachment::createAttachment($attachmentFile);
		$draft->addAttachment($attachment);
	}


	echo "<div>... send...</div>";
	$draft->send($applicationClient, false);


	echo "<div>closeSession</div>\n";
    $sessionClient->closeSession();
    
} catch (HttpException $ex) {
    echo $ex;
}
?>

	</body>
</html>
