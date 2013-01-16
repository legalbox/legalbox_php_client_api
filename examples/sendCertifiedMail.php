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
		<title>Test PHP Client API </title>
	</head>
	<body>

<?php

include (__DIR__.'/../API/connectors/SessionClient.php');
include (__DIR__.'/../API/connectors/ApplicationClient.php');
include (__DIR__.'/../API/connectors/LetterAttachmentClient.php');
include (__DIR__.'/../API/beans/AbstractBeans.php');
include (__DIR__.'/../API/beans/LetterDeliveryType.php');
include (__DIR__.'/../API/beans/Draft.php');
include (__DIR__.'/../API/beans/DraftAttachment.php');
include (__DIR__.'/../API/beans/DraftRecipient.php');

try {
	// INIT
	$identifierOrEmail = "johannbrocail@enaco.fr";
	$password = "63JGP5TU";
	$letterSubject = "Hello PHP";
	$recipientEmail = "ooskinzoo@hotmail.com";

	$text = "Have a good day PHP!!";
	$attachmentFile = "example.pdf";

	// enable or disable HTTP trace
	SessionClient::$debug = true;
	
	$SessionClient = new SessionClient($identifierOrEmail, $password);

    echo "sessionId=" . $SessionClient->sessionId . "<br/>\n";
    echo "userId=" . $SessionClient->userId . "<br/>\n";
    echo "languageCode=" . $SessionClient->languageCode . "<br/>\n";
    
    $ApplicationClient = new ApplicationClient($SessionClient);

  

	echo "<div>populate delivery type list</div>";
	//LetterDeliveryType::populateList($ApplicationClient);
	$LetterDeliveryTypes = $ApplicationClient->getDeliveryAndLetterTypes();
	
	

	echo "<div>sending letter...</div>";

	
	echo "<div>new Draft()</div>";
	$Draft = new Draft($ApplicationClient);

	foreach($LetterDeliveryTypes['letterDeliveryTypes'] as $letterDeliveryType)
	{
		if($letterDeliveryType['code'] == LetterDeliveryType::$CERTIFIED_LETTER_CODE)
		{
			$LetterDeliveryType = new LetterDeliveryType($ApplicationClient, $letterDeliveryType['_id']);
			$Draft->setLetterDeliveryType($LetterDeliveryType);
			//$Draft->setLetterDeliveryTypeId($letterDeliveryType['_id']);
		}	
	}
	
	
	
	
	//$Draft->setLetterDeliveryCode(LetterDeliveryType::$CERTIFIED_LETTER_CODE);
	$Draft->setTitle($letterSubject);
	$Draft->setText($text);
	
	echo "<div>new DraftRecipient()</div>";
	$Recipient = new DraftRecipient();
	$Recipient->setEmailAddress($recipientEmail);
	$Draft->addRecipient($Recipient);



	/**
	 * add attachment if any
	 */
	if(!empty($attachmentFile)) {
		echo "<br/>add attachment : " . $attachmentFile;
		$DraftAttachment = new DraftAttachment($ApplicationClient);
		$Draft->addAttachment($DraftAttachment);
	}


	echo "<div>... send...</div>";
	$Draft->send();


	echo "<div>closeSession</div>\n";
    $SessionClient->closeSession();
    
} catch (HttpException $ex) {
    echo $ex;
}
?>

	</body>
</html>
