<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Test PHP Client API : open session</title>
	</head>
	<body>

<?php
include ("lb/api/connectors/SessionClient.php");
include ("lb/api/connectors/ApplicationClient.php");

try {
	$identifierOrEmail = "your@email.com";
	$password = "your-password";

	// enable or disable HTTP trace
	SessionClient::$debug = true;

	$sessionClient = new SessionClient();
	
	$sessionClient->openSession($identifierOrEmail, $password);

	echo "openSession<br/>\n";
    echo "sessionId=" . $sessionClient->sessionId . "<br/>\n";
    echo "userId=" . $sessionClient->userId . "<br/>\n";
    echo "languageCode=" . $sessionClient->languageCode . "<br/>\n";
    
	echo "closeSession<br/>\n";
    $sessionClient->closeSession();
    
} catch (HttpException $ex) {
    echo $ex;
}
?>

	</body>
</html>
