package lb.api.beans;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import lb.api.connectors.SessionClient;
import lb.api.exceptions.RemoteException;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class LetterDeliveryType {
	
	protected String id;
	protected String code;
	protected String name;

	protected static List<LetterDeliveryType> letterDeliveryTypes = null;

	public final static String LRE_CODE = "LRE";
	public final static String CERTIFIED_LETTER_CODE = "CERTIFIED_LETTER";
	
	public static void populateList(SessionClient session) throws JSONException, IOException, RemoteException {
		JSONObject deliveryAndLetterTypes = session.createApplicationClient().getDeliveryAndLetterTypes();
		populateList(deliveryAndLetterTypes.getJSONArray("letterDeliveryTypes"));
	}
	
	public static void populateList(JSONArray list) throws JSONException {
		letterDeliveryTypes = new ArrayList<LetterDeliveryType>();
		for (int i = 0; i < list.length(); i++) {
			JSONObject item = list.getJSONObject(i);
			LetterDeliveryType type = new LetterDeliveryType();
			type.id = item.getString("_id");
			type.code = item.getString("code");
			type.name = item.getString("name");
			letterDeliveryTypes.add(type);
		}
	}
	
	public static LetterDeliveryType getLetterDeliveryTypeById(String id) {
		for (LetterDeliveryType type : letterDeliveryTypes) {
			if (type.id.equals(id)) {
				return type;
			}
		}
		return null;
	}
	
	public static LetterDeliveryType getLetterDeliveryTypeByCode(String code) {
		for (LetterDeliveryType type : letterDeliveryTypes) {
			if (type.code.equals(code)) {
				return type;
			}
		}
		return null;
	}
	
	public String getId() {
		return id;
	}
	
	public String getCode() {
		return code;
	}
	
	public String getName() {
		return name;
	}
}
