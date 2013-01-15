package lb.api.managers;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import lb.api.beans.Contact;
import lb.api.connectors.SessionClient;
import lb.api.exceptions.RemoteException;

public class ContactsManager {
	
	private SessionClient sessionClient;
	private List<Contact> contactList = new ArrayList<Contact>();
	
	public ContactsManager(SessionClient sessionClient) {
		this.sessionClient = sessionClient;
	}
	
	public List<Contact> getContactList() {
		return contactList;
	}
	
	public void populateContactList() throws JSONException, IOException, RemoteException {
		JSONArray contactArray = sessionClient.createApplicationClient()
				.getContactAndPendingInvitationLists()
				.getJSONArray("contactList");
		for (int i = 0; i < contactArray.length(); i++) {
			JSONObject contactObject = contactArray.getJSONObject(i);
			contactList.add(Contact.createContactFromJSON(contactObject));
		}
	}
	
	public Contact getContactByEmail(String email) {
		for (Contact contact : contactList) {
			if (email.equals(contact.getEmail())) {
				return contact;
			}
		}
		return null;
	}

}
