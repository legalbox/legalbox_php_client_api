package lb.api.connectors;

import java.io.IOException;

import lb.api.APIResourcesManager;
import lb.api.beans.User;
import lb.api.exceptions.ConnectionException;
import lb.api.exceptions.RemoteException;

import org.json.JSONException;
import org.json.JSONObject;

public class UserGroupBackofficeClient {
	
	protected static String url = APIResourcesManager.getUserGroupRestfulURL();

	protected SessionClient session;
	
	public UserGroupBackofficeClient(SessionClient session)
	throws JSONException, IOException, RemoteException {
		this.session = session;
	}
	
	
	public JSONObject getGroupData() 
	throws JSONException, IOException, RemoteException, ConnectionException 
	{
		return executePost("getGroupData");
	}
	
	public JSONObject getGroupMembersList() 
	throws JSONException, IOException, RemoteException, ConnectionException 
	{
		return executePost("getGroupMembersList");
	}		

	public JSONObject addMemberToGroup(
			String newMemberUserId) 
	throws JSONException, IOException, RemoteException, ConnectionException 
	{
		JSONObject data = new JSONObject();
		data.put("newMemberUserId", newMemberUserId);
		JSONObject jsonReturned = executePost("addMemberToGroup");
		return jsonReturned;
	}
		
	public JSONObject removeMemberFromGroup(
			String memberUserId) 
	throws JSONException, IOException, RemoteException, ConnectionException 
	{
		JSONObject data = new JSONObject();
		data.put("memberUserId", memberUserId);
		JSONObject jsonReturned = executePost("removeMemberFromGroup");
		return jsonReturned;
	}
		
	public JSONObject createNewUserInGroup(
			User user,
			String password) 
	throws JSONException, IOException, RemoteException, ConnectionException 
	{
		JSONObject data = user.exportToJSON();
		data.put("password",  password);
		
		JSONObject jsonReturned = executePost("createNewUserInGroup", data);
		return jsonReturned;
	}
		
	
	public JSONObject executePost(
			String request) 
	throws IOException, JSONException, RemoteException, ConnectionException {
		return executePost(request, new JSONObject());
	}
	
	public JSONObject executePost(
			String request,
			JSONObject jsonData) 
	throws IOException, JSONException, RemoteException, ConnectionException {
		jsonData.put("languageCode", session.getLanguageCode());
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("request", request);
		jsonParameters.put("data", jsonData);
		return session.executeApplicationPost(url, jsonParameters).getJSONObject("data");
	}
}
