package lb.api.beans;

import org.json.JSONException;
import org.json.JSONObject;

public class Contact {
	
	protected String id;
	protected Boolean isFreeRecipient;
	protected String email;
	protected String userStatus;
	protected String userId;
	protected Boolean isProfessional;
	protected String userIdentifier;
	
	public Contact(String id) {
		this.id = id;
	}
	
	public String getId() {
		return id;
	}
	
	public String getUserId() {
		return userId;
	}
	
	public String getEmail() {
		return email;
	}

	public static Contact createContactFromJSON(JSONObject contactObject) throws JSONException {
		Contact contact = new Contact(contactObject.getString("_id"));
		contact.isFreeRecipient = contactObject.getBoolean("isFreeRecipient");
		contact.email = contactObject.getString("email");
		contact.userStatus = contactObject.getString("userStatus");
		contact.userId = contactObject.getString("userId");
		contact.isProfessional = contactObject.getBoolean("isProfessional");
		contact.userIdentifier = contactObject.getString("userIdentifier");
		return contact;
	}
	
}
