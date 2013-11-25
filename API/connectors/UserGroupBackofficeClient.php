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

class UserGroupBackofficeClient
{
	const URL_BACKOFFICE = 'https://mail.legalbox.com/restful/userGroupBackoffice';
	
	private $_SessionClient;

	public function __construct(SessionClient $SessionClient)
	{
		$this->_SessionClient = $SessionClient;
	}
	
	public function execute($request, $data = array())
	{
		//$data['languageCode'] = $this->_SessionClient->languageCode;
		$datas = array();
		
		$datas['request'] = $request;
		$datas['data'] = $data;
		$datas['sessionId'] = $this->_SessionClient->sessionId;
		$datas['userId'] = $this->_SessionClient->userId;

		$response = $this->_SessionClient->execute(self::URL_BACKOFFICE, $datas);
		
		if($response['error'])
		{
			return false;
		}
		
		return $response['data'];
	}
	
	/**
	 * Renvoie des information du groupe auquel appartient l'utilisateur connecté
	 * @return Array
	 */
	public function getGroupData()
	{
		return $this->execute('getGroupData');
	}
	
	/**
	 * Etablissement de la liste des utilisateurs du groupe et de leurs informations
	 * @return Array
	 */
	public function getGroupMembersList()
	{
		return $this->execute('getGroupMembersList');
	}
	
	/**
	 * Ajout d'un utilisateur dans un groupe
	 * @param $newMemberUserId ID de l'utilisateur à ajouter au groupe
	 * @return Array
	 */
	public function addMemberToGroup($newMemberUserId)
	{
		$datas = array(
			'newMemberUserId' => $newMemberUserId
		);
		
		return $this->execute('addMemberToGroup', $datas);
	}
	
	/**
	 * Suppression de tous les membres du groupe spécifiés dans la liste
	 */
	public function removeMemberListFromGroup($membersToRemoveUserIdList)
	{
		$datas = array(
			'membersToRemoveUserIdList' => implode(',', $membersToRemoveUserIdList)
		);
		
		return $this->execute('removeMemberListFromGroup', $datas);
	}
	
	/**
	 * Création de l'utilisateur avec finalisation de l'inscription.
	 * Recherche du groupe de l'utilisateur de la session.
	 * Ajout de l'utilisateur créé dans le groupe
	 * @return Array
	 */
	public function createNewUserInGroup($data)
	{
		$data['generatePassword'] = true;
		return $this->execute('createNewUserInGroup', $data);
	}
	
	/**
	 * Envoie une notification à l'email spécifié afin de valider la propriété de ce mail.
	 */
	public function sendEmailAddressVerificationEmail(
			$validatePreregisteredInformationByUser,
			$languageCode,
			$identifierOrEmail)
	{
		$datas = array(
				'validatePreregisteredInformationByUser' => $validatePreregisteredInformationByUser,
				'languageCode' => $languageCode,
				'identifierOrEmail' => $identifierOrEmail
		);
		
		return $this->execute('sendEmailAddressVerificationEmail', $datas);
		
	}
	
}
