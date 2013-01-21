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
 * @author Johann Brocail <johannbrocail@enaco.fr>
 */
use API\connectors\RegistrationClient;
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
		<title>Test PHP Client API - Registration methods</title>
	</head>
	<body>

<?php

include (__DIR__ . '/../API/connectors/SessionClient.php');
include (__DIR__ . '/../API/connectors/RegistrationClient.php');
include (__DIR__ . '/../API/connectors/ApplicationClient.php');
include (__DIR__ . '/../API/beans/AbstractBeans.php');
include (__DIR__ . '/../API/beans/User.php');

try
{
	// INIT
	$identifierOrEmail = 'johannbrocail@enaco.fr';
	$password = '63JGP5TU';
	
	$emailRegistration = 'johannbrocail2@enaco.fr';
	$identifierRegistration = 'johann.brocail';
	$firstNameRegistration = 'firstNameRegistration';
	$lastNameRegistration = 'lastNameRegistration';
	
	$address1Registration = '165 Av. de Bretagne';
	
	// Enable or disable HTTP trace
	SessionClient::$debug = true;
	
	// Create new session
	$SessionClient = new SessionClient($identifierOrEmail, $password);
	
	// Create new registration connector
	$RegistrationClient = new RegistrationClient($SessionClient);
	
	// Create new application connector
	$ApplicationClient = new ApplicationClient($SessionClient);
	
	$checkRemoteParams = $RegistrationClient->checkRemoteParams($emailRegistration, $identifierRegistration);
	
	if($checkRemoteParams['isEmailValid'] && $checkRemoteParams['isEmailUnregistered'] &&  $checkRemoteParams['isIdentifierAvailable'])
	{
		// Create new empty user
		$User = new User($ApplicationClient);
		
		// Set identifier
		$User->setIdentifier($identifierRegistration);
		
		// Set email
		$User->setEmail($emailRegistration);
		
		// Set isProfessional false
		$User->setIsProfessional(false);
		
		// Set firstName
		$User->setFirstName($firstNameRegistration);
		
		// Set lastName
		$User->setLastName($lastNameRegistration);
		
		
		// Create new empty address
		$Address = new Address($ApplicationClient);
		
		$Address->setAddress1($address1Registration);
	}
	
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
