<?php
use API\connectors\UserGroupBackofficeClient;

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
use API\beans\User;
use API\beans\Address;
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
include (__DIR__ . '/../API/connectors/UserGroupBackofficeClient.php');
include (__DIR__ . '/../API/beans/AbstractBeans.php');
include (__DIR__ . '/../API/beans/User.php');
include (__DIR__ . '/../API/beans/Address.php');

try
{
	// INIT
	$identifierOrEmail = 'your@account.com';
	$password = 'YourPassword';
	
	$userEmailRegistration = 'email@registration.com';
	$identifierRegistration = 'identifier.registration';
	$firstNameRegistration = 'Firstname';
	$lastNameRegistration = 'Lastname';
	$publicNameRegistration = 'Public name';
	
	$address1Registration = 'Your address';
	$countryCodeRegistration = 'fr';
	$languageCodeRegistration = 'fr';
	$zipCodeRegistration = '59000';
	$townRegistration = 'Lille';
	
	// Enable or disable HTTP trace
	SessionClient::$debug = true;
	
	// Create new session
	$sessionClient = new SessionClient($identifierOrEmail, $password);

	// Create new registration connector
	$registrationClient = new RegistrationClient($sessionClient);
	
	// Create new application connector
	$applicationClient = new ApplicationClient($sessionClient);
	
	// Create new user group connector
	$userGroupBackofficeClient = new UserGroupBackofficeClient($sessionClient);
	
	//$applicationClient->getCountryList();
	
	$checkRemoteParams = $registrationClient->checkRemoteParams($userEmailRegistration, $identifierRegistration);
	
	if($checkRemoteParams['isEmailValid']
	&& $checkRemoteParams['isEmailUnregistered'] 
	&& $checkRemoteParams['isIdentifierAvailable'])
	{
		// Create user
		$user = new User($applicationClient);
		$user->setIdentifier($identifierRegistration);
		$user->setUserEmail($userEmailRegistration);
		$user->setIsProfessional(false);
		$user->setFirstName($firstNameRegistration);
		$user->setLastName($lastNameRegistration);
		$user->setPublicName($publicNameRegistration);
		$user->setLanguageCode($languageCodeRegistration);
		$user->setSponsorId($sessionClient->userId);
		
		// Create address
		$address = new Address($applicationClient);
		$address->setAddress1($address1Registration);
		$address->setCountryCode($countryCodeRegistration);
		$address->setZipCode($zipCodeRegistration);
		$address->setTown($townRegistration);
		
		// Assignment to user the address
		$user->setAddress($address);
		
		// Submit registration form
		$registrationClient->submitRegistrationForm($user);
		
		
		// false: registration step 1 
		// true: registration step 2 
		$validatePreregisteredInformationByUser = true;
		
		$userGroupBackofficeClient->sendEmailAddressVerificationEmail(
			$validatePreregisteredInformationByUser, 
			$languageCodeRegistration, 
			$userEmailRegistration);
	}
	
	// Close session
	$sessionClient->closeSession();

}
catch (HttpException $ex)
{
	echo $ex;
}
?>

	</body>
</html>
