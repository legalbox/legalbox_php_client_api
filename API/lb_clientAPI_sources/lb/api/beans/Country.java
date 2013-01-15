package lb.api.beans;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import lb.api.APIResourcesManager;
import lb.api.connectors.SessionClient;
import lb.api.exceptions.RemoteException;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class Country {
	
	protected String id;
	protected String code;
	protected String label;

	protected static List<Country> countryList = null;
	
	public String getId() {
		return id;
	}
	
	public String getCode() {
		return code;
	}
	
	public String getLabel() {
		return label;
	}
	
	public static List<Country> getCountryList() {
		return countryList;
	}

	public static void populateList(SessionClient session) throws JSONException, IOException, RemoteException{
		JSONObject jsonData = new JSONObject();
		jsonData.put("languageCode", session.getLanguageCode());
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("request", "getCountryList");
		jsonParameters.put("data", jsonData);
		JSONObject jsonReturned = session.getConnector().executePost(
				APIResourcesManager.getRegistrationRestfulURL(), 
				jsonParameters);
		populateList(jsonReturned.getJSONObject("data").getJSONArray("countryList"));
	}
	
	public static void populateList(JSONArray list) throws JSONException {
		countryList = new ArrayList<Country>();
		for (int i = 0; i < list.length(); i++) {
			JSONObject item = list.getJSONObject(i);
			Country country = new Country();
			country.id = item.getString("_id");
			country.code = item.getString("code");
			country.label = item.getString("label");
			countryList.add(country);
		}
	}
	
	public static Country getCountryByCode(String code) {
		for (Country country : countryList) {
			if (country.code.equals(code)) {
				return country;
			}
		}
		return null;
	}
}
