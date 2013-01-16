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

use API\connectors\SessionClient;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Test PHP Client API : open session</title>
	</head>
	<body>

<?php

require_once (__DIR__.'/../API/connectors/SessionClient.php');

try {
	$identifierOrEmail = "your@email.com";
	$password = "your-password";

	// enable or disable HTTP trace
	SessionClient::$debug = true;

	$SessionClient = new SessionClient($identifierOrEmail, $password);

    echo "sessionId=" . $SessionClient->sessionId . "<br/>\n";
    echo "userId=" . $SessionClient->userId . "<br/>\n";
    echo "languageCode=" . $SessionClient->languageCode . "<br/>\n";
    
	echo "closeSession<br/>\n";
    $SessionClient->closeSession();
    
} catch (HttpException $ex) {
    echo $ex;
}
?>

	</body>
</html>
