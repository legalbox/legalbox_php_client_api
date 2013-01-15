<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Test PHP Client API : open session</title>
	</head>
	<body>

<?php

use API\connectors\SessionClient;
use API\connectors\ApplicationClient;
use API\beans\LetterDeliveryType;
use API\APIResourcesManager;
use API\beans\Draft;
use API\beans\DraftRecipient;

ini_set('display_errors', 'on');

require_once 'autoload.php';


try {
	// INIT
	$identifierOrEmail = "johannbrocail@enaco.fr";
	$password = "63JGP5TU";
	$letterSubject = "Test email legalbox";
	$recipientEmail = "zimzim62000@gmail.com";
	$text = "Have a good day PHP!!";
	$attachmentFile = "";

	// enable or disable HTTP trace
	SessionClient::$debug = true;
	
	$SessionClient = new SessionClient($identifierOrEmail, $password);
	

	echo "openSession<br/>\n";
    echo "sessionId=" . $SessionClient->sessionId . "<br/>\n";
    echo "userId=" . $SessionClient->userId . "<br/>\n";
    echo "languageCode=" . $SessionClient->languageCode . "<br/>\n";
    
    $ApplicationClient = new ApplicationClient($SessionClient);


   
	echo "<div>populate delivery type list</div>";
	LetterDeliveryType::populateList($ApplicationClient);
	
	echo "<div>sending letter...</div>";

	$Draft = new Draft();
	echo "<div>new Draft()</div>";
	$Draft->setLetterDeliveryCode(LetterDeliveryType::$CERTIFIED_LETTER_CODE);
	$Draft->setTitle($letterSubject);
	$Draft->setContent($text);
	
	
	echo "<div>new DraftRecipient()</div>";
	$Recipient = new DraftRecipient();
	$Recipient->setEmailAddress($recipientEmail);
	$Draft->addRecipient($Recipient);
	
	


	/**
	 * add attachment if any
	 */
	if(!empty($attachmentFile)) {
		echo "<br/>add attachment : " . $attachmentFile;
		$Attachment = DraftAttachment::createAttachment($attachmentFile);
		$Draft->addAttachment($Attachment);
	}


	echo "<div>... send...</div>";
	$Draft->send($ApplicationClient, false);


	echo "<div>closeSession</div>\n";
    $SessionClient->closeSession();
    
} catch (HttpException $ex) {
    echo $ex;
}
?>
	</body>
</html>
