package lb.api.connectors;

import java.io.IOException;
import java.util.List;

import lb.api.APIResourcesManager;
import lb.api.beans.Draft;
import lb.api.beans.DraftRecipient;
import lb.api.exceptions.LetterException;
import lb.api.exceptions.RemoteException;
import lb.api.util.JSONUtil;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class ApplicationClient {
	
	protected static String url = APIResourcesManager.getApplicationRestfulURL();

	protected SessionClient session;
	
	public ApplicationClient(SessionClient session) {
		this.session = session;
	}
	
	public SessionClient getSession() {
		return session;
	}

	public JSONObject getContactAndPendingInvitationLists() throws JSONException, IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("languageCode", session.getLanguageCode());
		JSONObject jsonReturned = executePost("getContactAndPendingInvitationLists", data);
		return jsonReturned;
	}

	public JSONObject createContact(String newContactUserId, JSONObject contactAttributes) throws JSONException, 
	IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("newContactUserId", newContactUserId);
		data.put("contactAttributes", contactAttributes);
		JSONObject jsonReturned = executePost("createContact", data);
		return jsonReturned;
	}
	
	public JSONObject updateContact(String contactId, JSONObject contactAttributes) throws JSONException, 
	IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("contactId", contactId);
		data.put("contactAttributes", contactAttributes);
		JSONObject jsonReturned = executePost("updateContact", data);
		return jsonReturned;
	}
	
	public JSONObject deleteContact(String contactId) throws JSONException, IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("contactId", contactId);
		JSONObject jsonReturned = executePost("deleteContact", data);
		return jsonReturned;
	}

	public JSONObject getUserInformationDependingOnEmailOrIdentifierStatus(String identifierOrEmail) throws JSONException, 
	IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("identifierOrEmail", identifierOrEmail);
		JSONObject jsonReturned = executePost("getUserInformationDependingOnEmailOrIdentifierStatus", data);
		return jsonReturned;
	}

	public JSONObject isEmailOrIdentifierRegistered(String identifierOrEmail) throws JSONException, 
	IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("identifierOrEmail", identifierOrEmail);
		JSONObject jsonReturned = executePost("isEmailOrIdentifierRegistered", data);
		return jsonReturned;
	}
	
	public JSONObject getDeliveryAndLetterTypes() throws JSONException, IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("languageCode", session.getLanguageCode());
		JSONObject jsonReturned = executePost("getDeliveryAndLetterTypes", data);
		return jsonReturned;
	}
	
	public JSONObject getUserInformations() throws IOException, JSONException, RemoteException {
		JSONObject jsonReturned = executePost("getUserInformations");
		return jsonReturned.getJSONObject("userInformations");
	}
	
	public JSONObject getCreditBalance() throws IOException, JSONException, RemoteException {
		JSONObject jsonReturned = executePost("getCreditBalance");
		return jsonReturned.getJSONObject("credits");
	}
	
	public String createLetterRemote(Draft draft) throws JSONException, IOException, RemoteException, LetterException {
		return setLetterRemote(draft, null);
	}
	
	public String setLetterRemote(Draft draft, String letterId) throws JSONException, IOException, RemoteException, 
	LetterException {
		
		JSONObject data = new JSONObject();
		data.put("letterId", letterId);
		data.put("letterDeliveryTypeId", draft.getLetterDeliveryTypeId());
		data.put("text", draft.getContent());
		data.put("title", draft.getTitle());
		data.put("originLetterId", draft.getOriginLetterId());
		
		JSONArray recipientArray = new JSONArray();
		List<DraftRecipient> recipientList = draft.getRecipientList();
		
		JSONObject nonSubscribedRecipient = new JSONObject();
		for (DraftRecipient recipient : recipientList) {
			JSONObject recipientObject = new JSONObject();
			JSONObject response = isEmailOrIdentifierRegistered(recipient.getEmailAddress());
			String userId = null;
			if (response.getBoolean("isRegistered")) {
				userId = response.getString("userId");
			}
			if (userId == null) {
				if (recipientList.size() > 1) {
					throw new LetterException("Cannot send a letter with more than one recipient in case of a non subscribed recipient");
				}
				nonSubscribedRecipient.put("isProfessional", recipient.isProfessional());
				nonSubscribedRecipient.put("prepayeResponse", recipient.isPrepayedRecipient());
				nonSubscribedRecipient.put("emailAddress", recipient.getEmailAddress());
				nonSubscribedRecipient.put("notificationLanguageCode", recipient.getNotificationLanguageCode());
				nonSubscribedRecipient.put("attachmentSignatureRequestList", recipient.getSignatureRequestIndexArray());
			} else {
				recipientObject.put("userId", userId);
				recipientObject.put("prepayeResponse", recipient.isPrepayedRecipient());
				recipientObject.put("notificationLanguageCode", recipient.getNotificationLanguageCode());
				recipientObject.put("isCC", recipient.isCarbonCopyRecipient());
				recipientObject.put("attachmentSignatureRequestList", recipient.getSignatureRequestIndexArray());
				recipientArray.put(recipientObject);
			}
			
		}
		
		if (nonSubscribedRecipient.length() > 0) {
			data.put("nonSubscribedRecipient", nonSubscribedRecipient);
		} else {
			data.put("recipientList", recipientArray);
		}
		
		JSONObject returnObj = executePost("setLetter", data);
		return returnObj.getString("letterId");
		
	}
	
	public void moveLetterToBin(List<String> actorIdList) throws IOException, JSONException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("folderId", "BIN");
		data.put("actorIdArray", JSONUtil.convertListToJSONArray(actorIdList));
		executePost("moveLettersToFolder", data);
	}
	
	public void addAttachmentSignatureRemote(String letterId, 
			int attachmentIndex, 
			String base64Signature) throws IOException, JSONException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("letterId", letterId);
		data.put("index", attachmentIndex);
		data.put("base64Signature", base64Signature);
		executePost("addAttachmentSignature", data);
	}

	public void sendLetterRemote(String letterId, boolean toArchive) throws IOException, RemoteException, JSONException {
		JSONObject data = new JSONObject();
		data.put("letterId", letterId);
		data.put("toArchive", toArchive);
		executePost("sendLetter", data);
	}
	
	public JSONObject executePost(String request) throws IOException, JSONException, RemoteException {
		return executePost(request, new JSONObject());
	}
	
	public JSONObject executePost(String request, JSONObject jsonData) throws IOException, JSONException, RemoteException {
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("request", request);
		jsonParameters.put("data", jsonData);
		return session.executeApplicationPost(url, jsonParameters).getJSONObject("data");
	}
}
