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
namespace API\connectors;

use API\beans\Draft;

use API\APIResourcesManager;

class ApplicationClient
{
	const URL_APPLICATION = 'https://certified.legalbox.com/restful/application';
	
	private $_SessionClient;
	private $_FactoryBeans;
	
	public function __construct(SessionClient $SessionClient)
	{
		$this->_SessionClient = $SessionClient;
	}
	
	public function execute($request, $data = array(), $filename = null)
	{
		$datas = array();
		
		$datas['request'] = $request;
		$datas['data'] = $data;
		$datas['sessionId'] = $this->_SessionClient->sessionId;
		$datas['userId'] = $this->_SessionClient->userId;
		
		
		$response = $this->_SessionClient->execute(self::URL_APPLICATION, $datas, $filename);
		
		if($response['error'])
		{
			return false;
		}
		
		return $response['data'];
	}
	
	/**
	 * Etablir la liste des fuseaux horaires.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getTimeZoneList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getTimeZoneList', $datas);
	}
	
	/**
	 * Etablir la liste des pays.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getCountryList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getCountryList', $datas);
	}
	
	/**
	 * Récupérer le libellé d'un pays.
	 * @param Integer $countryId Id du pays
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getCountryLabel($countryId, $languageCode = null)
	{
		$datas = array(
			'countryId' => $countryId
		);
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getCountryLabel', $datas);
	}
	
	/**
	 * Etablir la liste des états d'un courrier.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getStatusTypeList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getStatusTypeList', $datas);
	}
	
	/**
	 * Etablir la liste des langues disponibles.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getLanguageList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getLanguageList', $datas);
	}
	
	/**
	 * Etablir la liste des dossiers (de courriers).
	 * @return Array
	 */
	public function getLetterFolderList()
	{
		$datas = array();
		
		return $this->execute('getLetterFolderList', $datas);
	}
	
	/**
	 * Etablir la liste des types d'entreprise.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getCompanyTypeList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getCompanyTypeList', $datas);
	}
	
	/**
	 * Etablir la liste des types des news.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getNewsItemTypeList($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getNewsItemTypeList', $datas);
	}
	
	/**
	 * Etablir la liste des modes de distribution et des types de courrier.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function getLetterDeliveryTypes($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('getLetterDeliveryTypes', $datas);
	}
	
	/**
	 * Lire dans le cache les évènements liés a la session courante.
	 * @param String $languageCode Code de la langue
	 * @return Array
	 */
	public function readCache($languageCode = null)
	{
		$datas = array();
		
		if($languageCode)
		{
			$datas['languageCode'] = $languageCode;
		}
		else
		{
			$datas['languageCode'] = $this->_SessionClient->languageCode;
		}
		
		return $this->execute('readCache', $datas);
	}
	
	/**
	 * Récupère toutes les informations associées à l'utilisateur courant.
	 * @return Array
	 */
	public function getUserInformations()
	{
		return $this->execute('getUserInformations');	
	}
	
	/**
	 * Récupère l'identifiant de l'utilisateur connecté.
	 * @return Array
	 */
	public function getUserIdentifier()
	{
		return $this->execute('getUserIdentifier');	
	}
	
	/**
	 * Modification du mot de passe de l'utilisateur connecté.
	 * Cette action provoque un envoie automatique de notification par e-mail vers l'utilisateur concerné.
	 * @param String $currentPassword
	 * @param String $newPassword
	 * @param String $mnemonicSentence
	 * @return Array
	 */
	public function updatePassword($currentPassword, $newPassword, $mnemonicSentence)
	{
		$datas = array(
			'currentPassword' => $currentPassword,
			'newPassword' => $newPassword,
			'mnemonicSentence' => $mnemonicSentence
		);
		
		return $this->execute('updatePassword', $datas);	
	}
	
	/**
	 * Récupère les crédits d'envoie et d'archivage disponible pour l'utilisateur de la session.
	 * @return Array
	 */
	public function getCreditBalance()
	{
		return $this->execute('getCreditBalance');
	}
	
	/**
	 * Vérifie si l'identifiant ou adresse e-mail renseignée correspond à un utilisateur LegalBox.
	 * @param String $identifierOrEmail Identifiant ou adresse e-mail à vérifier
	 * @return Array
	 */
	public function isEmailOrIdentifierRegistered($identifierOrEmail)
	{
		$datas = array(
			'identifierOrEmail' => $identifierOrEmail
		);
		
		return $this->execute('isEmailOrIdentifierRegistered', $datas);
	}
	
	/**
	 * Création d'un nouveau contact pour l'utilisateur de la session.
	 */
	public function createContact()
	{
		
	}
	
	/**
	 * Modification du contact.
	 */
	public function updateContact()
	{
		
	}
	
	/**
	 * Supprime un contact.
	 */
	public function deleteContact()
	{
		
	}
	
	/**
	 * Imporation d'un ou plusieurs contacts.
	 * Si l'identifiant de l'utilisateur à rajouter aux contacts est vien celui d'un utilisateur valide 
	 * et qu'il ne s'agit pas d'un contact déjà enregistré ou de soi-même.
	 * Pour chaque contact à importer, un nouveau contact est enregistrer pour l'utilisateur de la session.
	 */
	public function importMultipleContacts()
	{
		
	}
	
	/**
	 * Inviter qu'lqu'un via son e-mail
	 * @param String $email Email de la personne que l'on veut inviter à s'inscrire au sevice LegalBox
	 * @param Boolean $isMyVisitingCardShared True si je veux rendre visible ma carte de visite pour ce futur contact
	 * @param Integer $languageId Id en BDD de la langue
	 */
	public function sendInvitation($email, $isMyVisitingCardShared, $languageId)
	{
		$datas = array(
			'email' => $email,
			'isMyVisitingCardShared' => $isMyVisitingCardShared,
			'languageId' => $languageId
		);

		return $this->execute('sendInvitation', $datas);
	}
	
	/**
	 * Envoie d'une notification par email à la personne concernée et mise à jour de la date d'invitation.
	 * Si la personne notifiée s'inscrit au service LegalBox, cette invitation sera supprimée et un nouveau contact sera créé.
	 * @param $pendingInvitationId Id en BDD de l'invitation
	 * @param $isMyVisitingCardShared True si je veux rendre visible ma carte de visite pour ce futur contact
	 * @param $languageId Id en BDD de la langue
	 * @param String $customMessage Message personnalisé qui sera ajouté dans la notification envoyée
	 */
	public function sendInvitationAgain($pendingInvitationId, $isMyVisitingCardShared, $languageId, $customMessage)
	{
		$datas = array(
			'pendingInvitationId' => $pendingInvitationId,
			'isMyVisitingCardShared' => $isMyVisitingCardShared,
			'languageId' => $languageId, 
			'customMessage' => $customMessage
		);

		return $this->execute('sendInvitationAgain', $datas);
	}
	
	/**
	 * Mettre à jour une invitation
	 * @param $pendingInvitationId Id en BDD de l'invitation
	 * @param $isMyVisitingCardShared True si je veux rendre visible ma carte de visite pour ce futur contact
	 */
	public function updatePendingInvitation($pendingInvitationId, $isMyVisitingCardShared)
	{
		$datas = array(
			'pendingInvitationId' => $pendingInvitationId,
			'isMyVisitingCardShared' => $isMyVisitingCardShared
		);

		return $this->execute('updatePendingInvitation', $datas);
	}
	
	/**
	 * Supprime une invitation
	 * @param $pendingInvitationId Id en BDD de l'invitation
	 */
	public function deletePendingInvitation($pendingInvitationId)
	{
		$datas = array(
			'pendingInvitationId' => $pendingInvitationId
		);

		return $this->execute('deletePendingInvitation', $datas);
	}
	
	/**
	 * Envoyer plusieurs invitations en même temps
	 */
	public function sendMultipleInvitations()
	{
		
	}
	
	/**
	 * Relancer plusieurs invitations en même temps
	 */
	public function sendMultipleInvitationsAgain()
	{
		
	}
	
	/**
	 * Supprimer plusieurs invitations en même temps
	 */
	public function deleteMultipleInvitation()
	{
		
	}
	
	/**
	 * Etablir la liste des contacts et des invitations en cours
	 */
	public function getContactAndPendingInvitationList()
	{
		
	}
	/**
	 * Préparer un courrier / créer un brouillon.
	 * @param Draft $Draft
	 */
	public function setLetter(Draft $Draft)
	{
		$datas = array(
			'letterId' => null,
			'letterDeliveryTypeId' => $Draft->getLetterDeliveryType()->getId(),
			'letterTypeId' => null, 
			'title' => $Draft->getTitle(),
			'text' => $Draft->getText()
		);
		
		$recipientArray = array();
		$nonSubscribedRecipient = array();
		
		foreach($Draft->getRecipientList() as $k => $Recipient)
		{
			
			$response = $this->isEmailOrIdentifierRegistered($Recipient->getEmailAddress());
			
			if($response["isRegistered"])
			{
				$userId = $response["userId"];
			}
			
			if(empty($userId))
			{
				echo "<br/>set nonSubscribedRecipient";
				$nonSubscribedRecipient["isProfessional"] = $Recipient->isProfessional();
				$nonSubscribedRecipient["prepayeResponse"] = $Recipient->isPrepayedRecipient();
				$nonSubscribedRecipient["emailAddress"] = $Recipient->getEmailAddress();
				$nonSubscribedRecipient["notificationLanguageCode"] = $Recipient->getNotificationLanguageCode();
				$nonSubscribedRecipient["attachmentSignatureRequestList"] = $Recipient->getSignatureRequestIndexArray();
			}
			else
			{
				echo "<br/>set recipientObject";
				$recipientObject = array();
				$recipientObject["userId"] = $userId;
				$recipientObject["prepayeResponse"] = $Recipient->isPrepayedRecipient();
				$recipientObject["notificationLanguageCode"] = $Recipient->getNotificationLanguageCode();
				$recipientObject["isCC"] = $Recipient->isCarbonCopyRecipient();
				$recipientObject["attachmentSignatureRequestList"] = $Recipient->getSignatureRequestIndexArray();
				array_push($recipientArray, $recipientObject);
			}
		
		}
	
		$datas["recipientList"] = $recipientArray;
		
		if (!empty($nonSubscribedRecipient)) {
			$datas["nonSubscribedRecipient"] = $nonSubscribedRecipient;
		}

		$returnObj = $this->execute('setLetter', $datas);
		return $Draft->setLetterId($returnObj["letterId"]);
	}
	
	/**
	 * Récupérer l'identiiant d'attachement d'une pièce jointe
	 */
	public function getAttachmentIdFromIndexAndLetterId()
	{
		
	}
	
	/**
	 * Signer une pièce
	 */
	public function createAttachmentSignature()
	{
		
	}
	
	/**
	 * Récupérer un brouillon
	 */
	public function getDraftDetails()
	{
			
	}
	
	/**
	 * Envoyer un courrier
	 * @param Draft $Draft Brouillon
	 * @param Boolean $toArchive True pour demande l'archivage du courrier
	 * @param Boolean $notifyByEmail True to send a notification by email
	 */
	public function sendLetter(Draft $Draft, $toArchive = false, $notifyByEmail = true)
	{
		$datas = array(
			'letterId' => $Draft->getLetterId(),
			'toArchive' => $toArchive,
			'notifyByEmail' => $notifyByEmail
		);

		if(count($Draft->getAttachmentList()))
		{
			$AttachmentClient = new LetterAttachmentClient($this->_SessionClient);
			$AttachmentClient->application = $this;
			
			foreach($Draft->getAttachmentList() as $k => $Attachment)
			{
				$AttachmentClient->uploadAttachment($Draft, $Attachment);
			}
		}
		
		return $this->execute('sendLetter', $datas);
	}
	
	/**
	 * Récupère la liste des courriers de l'utilisateur
	 * @return Array
	 */
	public function getLetterList()
	{
		return $this->execute('getLetterList');
	}
	
	/**
	 * Récupère la liste des brouillons de l'utilisateur
	 * @return Array
	 */
	public function getDraftList()
	{
		return $this->execute('getDraftList');
	}
	
	/**
	 * Récupère le contenu d'un courrier
	 */
	public function getLetterDetails($actorId)
	{
		$datas = array(
            'actorId' => $actorId
        );

        return $this->execute('getLetterDetails', $datas);
	}
	
	/**
	 * Accepter / refuser une LRE (Lettre Recommandée Electronique)
	 */
	public function decideLRE()
	{
		
	}
	
	public function getContactAndPendingInvitationLists()
	{
		$datas = array( 
			'languageCode' => $this->_SessionClient->languageCode
		);
		
		return $this->execute('getContactAndPendingInvitationLists', $datas);
	}
	
	public function getUserInformationDependingOnEmailOrIdentifierStatus($identifierOrEmail)
	{
		$datas = array( 
			'request' => 'getUserInformationDependingOnEmailOrIdentifierStatus', 
			'identifierOrEmail' => $identifierOrEmail
		);
		
		return $this->execute($datas);
	}
}
