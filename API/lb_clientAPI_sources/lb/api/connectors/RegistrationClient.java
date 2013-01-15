package lb.api.connectors;

import java.io.IOException;

import lb.api.APIResourcesManager;
import lb.api.beans.User;
import lb.api.exceptions.RemoteException;

import org.json.JSONException;
import org.json.JSONObject;

public class RegistrationClient {
	
	protected static String url = APIResourcesManager.getRegistrationRestfulURL();

	SessionClient session;
	
	public RegistrationClient(
			SessionClient session) {
		this.session = session;
	}
	
	
	public JSONObject getCountryList() throws JSONException, IOException, RemoteException 
	{
		JSONObject jsonReturned = executePost("getCountryList");
		return jsonReturned.getJSONObject("data");
	}
	
	
	public JSONObject submitRegistrationForm(
			User user) 
	throws JSONException, IOException, RemoteException
	{
		JSONObject data = user.exportToJSON();
		JSONObject jsonReturned = executePost("submitRegistrationForm", data);
		return jsonReturned;
	}
	
	
	public JSONObject sendEmailAddressVerificationEmail(
			String identifierOrEmail,
			Boolean asynchronous)
	throws JSONException, IOException, RemoteException {
		JSONObject data = new JSONObject();
		data.put("identifierOrEmail", identifierOrEmail);
		data.put("asynchronous", asynchronous);
		JSONObject jsonReturned = executePost("sendEmailAddressVerificationEmail", data, true);
		return jsonReturned;
	}
	
	public JSONObject executePost(
			String request) 
	throws IOException, JSONException, RemoteException {
		return executePost(request, new JSONObject());
	}

	public JSONObject executePost(
			String request,
			JSONObject jsonData) 
	throws IOException, JSONException, RemoteException {
		return executePost(request, jsonData, false);
	}
	
	public JSONObject executePost(
			String request,
			JSONObject jsonData,
			boolean mergeJson) 
	throws IOException, JSONException, RemoteException {
		jsonData.put("languageCode", session.getLanguageCode());
		JSONObject jsonParameters = null;
		if(mergeJson) {
			jsonParameters = jsonData;
			jsonParameters.put("request", request);
		} else {
			jsonParameters = new JSONObject();
			jsonParameters.put("request", request);
			jsonParameters.put("data", jsonData);
		}
		return session.getConnector().executePost(url, jsonParameters);
	}
	
}
