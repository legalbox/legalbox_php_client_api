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
namespace API\connectors;

use API\beans\User;

class RegistrationClient
{
	const URL_REGISTRATION = 'https://mail.legalbox.com/restful/registration';
	
	private $_SessionClient;

	public function __construct(SessionClient $SessionClient)
	{
		$this->_SessionClient = $SessionClient;
	}
	
	public function execute($datas)
	{

		$response = $this->_SessionClient->execute(self::URL_REGISTRATION, $datas);
		
		if($response['error'])
		{
			return false;
		}
		
		return $response['data'];
	}
	
	/**
	 * Verifie que l'email et l'identifiant requis sont disponible et que l'email est bien formaté.
	 * @param String $email E-mail à vérifier
	 * @param String $identifier Identifiant à vérifier
	 * @return Array
	 */
	public function checkRemoteParams($email, $identifier)
	{
		$datas = array(
			'request' => 'checkRemoteParams', 
			'email' => $email,
			'identifier' => $identifier
		);
		
		return $this->execute($datas);
	}
	
	/**
	 * Enregistre la demande d'inscription.
	 * L'inscription sera définitive quand l'utilisateur aura choisi et validé son mot de passe personnel
	 */
	public function submitRegistrationForm(User $User)
	{
		$datas = array(
			'request' => 'submitRegistrationForm', 
			'data' => array(
				'accountType' => 'private',
				'firstName' => $User->getFirstName(),
				'lastName' => $User->getLastName(),
				'userEmail' => $User->getUserEmail(), 
				'identifier' => $User->getIdentifier(), 
				'publicName' => $User->getPublicName(), 
				'languageCode' => $User->getLanguageCode(), 
				'address' => $User->getAddress()->toArray(),
				'phone' => '',
				'compagnyName' => '',
				'tradeName' => '', 
				'identificationNumber' => '',
				'position' => '', 
				'unit' => '',
				'department' => '', 
				'website' => '', 
				'organizationTypeSelect' => ''
			)
		);
		
		return $this->execute($datas);
	}
	
	/**
	 * Envoie une notification à l'email spécifié afin de valider la propriété de ce mail.
	 */
	public function sendEmailAddressVerificationEmail()
	{
		
	}
	
	/**
	 * Enregistre les informations fournies.
	 * Elaboration d'un fichier lbkp qui permettra à l'utilisateur de réinitialiser son mot de passe.
	 * Envoie d'une notification accompagné du fichier lbkp en pièce jointe.
	 */
	public function submitValidationForm()
	{
		
	}

}
