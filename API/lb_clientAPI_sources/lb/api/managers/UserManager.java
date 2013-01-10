package lb.api.managers;

import java.io.IOException;

import org.json.JSONException;
import org.json.JSONObject;

import lb.api.connectors.SessionClient;
import lb.api.exceptions.RemoteException;

// TODO A compléter avec le reste des données de "getUserInformations" ...
public class UserManager {
	
	private SessionClient sessionClient;
	
	private String userIdentifier;
	private String mainEmail;
	
	public UserManager(SessionClient sessionClient) {
		this.sessionClient = sessionClient;
	}
	
	public String getUserIdentifier() {
		return userIdentifier;
	}
	
	public String getMainEmail() {
		return mainEmail;
	}
	
	public void fetchUserInformations() throws IOException, JSONException, RemoteException {
		JSONObject informationsObject = sessionClient.createApplicationClient().getUserInformations();
		userIdentifier = informationsObject.getString("userIdentifier");
		mainEmail = informationsObject.getString("userEmail");
	}

}
