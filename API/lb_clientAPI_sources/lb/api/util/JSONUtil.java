package lb.api.util;

import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.TimeZone;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class JSONUtil {
	
	public static JSONObject convertDateToJSON(Date date) throws JSONException {
		if (date == null) return null;
		
		GregorianCalendar calendar = new GregorianCalendar();
		calendar.setTime(date);
		// Switch to UTC time
		calendar.add(Calendar.MILLISECOND, -calendar.get(Calendar.ZONE_OFFSET));
		calendar.add(Calendar.MILLISECOND, -calendar.get(Calendar.DST_OFFSET));
		JSONObject dateInJSON = new JSONObject();
		dateInJSON.put("year", calendar.get(Calendar.YEAR));
		dateInJSON.put("month", calendar.get(Calendar.MONTH));
		dateInJSON.put("day", calendar.get(Calendar.DAY_OF_MONTH));
		dateInJSON.put("hour", calendar.get(Calendar.HOUR_OF_DAY));
		dateInJSON.put("min", calendar.get(Calendar.MINUTE));
		dateInJSON.put("sec", calendar.get(Calendar.SECOND));
		return dateInJSON;
	}
	
	public static Date convertDateFromJSON(JSONObject dateInJSON) throws JSONException {
		if (dateInJSON == null) return null;
		GregorianCalendar calendar = new GregorianCalendar(
				dateInJSON.getInt("year"),
				dateInJSON.getInt("month"),
				dateInJSON.getInt("day"),
				dateInJSON.getInt("hour"),
				dateInJSON.getInt("min"),
				dateInJSON.getInt("sec"));
		calendar.setTimeZone(TimeZone.getTimeZone("UTC"));
		return calendar.getTime();
	}
	
	public static void forcePutInJSON(JSONObject object, String key, Object value) throws JSONException {
		if (value == null) {
			object.put(key, JSONObject.NULL);
		} else {
			object.put(key, value);
		}	
	}
	
	/**
	 * Converts a hash into an array, keeping the hash values and ignoring the keys.
	 * The ordering of the resulting array is undefined.
	 */
	public static JSONArray convertJSONObjectToJSONArray(JSONObject jsonObject) throws JSONException {
		JSONArray array = jsonObject.toJSONArray(jsonObject.names());
		if (array == null) {
			array = new JSONArray();
		}
		return array;
	}
	
	/**
	 * Converts a JSON Array containing only Integers into a Java List.
	 * @param jsonArray : the JSON Array to convert
	 * @return the resulting Map
	 * @throws JSONException 
	 */
	public static List<Integer> convertJSONArrayToIntegerList(JSONArray jsonArray) throws JSONException {
		List<Integer> list = new LinkedList<Integer>();
		for (int i = 0; i < jsonArray.length(); i++) {
			list.add(jsonArray.getInt(i));
		}
		return list;
	}
	
	/**
	 * Converts a JSON Array containing only Strings into a Java List.
	 * @param jsonArray : the JSON Array to convert
	 * @return the resulting Map
	 * @throws JSONException 
	 */
	public static List<String> convertJSONArrayToStringList(JSONArray jsonArray) throws JSONException {
		List<String> list = new LinkedList<String>();
		for (int i = 0; i < jsonArray.length(); i++) {
			list.add(jsonArray.getString(i));
		}
		return list;
	}
	
	/**
	 * Converts a JSON Object containing only Strings into a Java Map.
	 * @param jsonObject : the JSON Object to convert
	 * @return the resulting Map
	 * @throws JSONException 
	 */
	public static Map<String, String> convertJSONObjectToMap(JSONObject jsonObject) throws JSONException {
		Map<String, String> map = new HashMap<String, String>();
		String[] names = JSONObject.getNames(jsonObject);
		for (int i = 0; i < names.length; i++) {
			map.put(names[i], jsonObject.getString(names[i]));
		}
		return map;
	}
	
	public static JSONArray convertListToJSONArray(List<?> list) {
		JSONArray array = new JSONArray();
		for (Object object : list) {
			array.put(object);
		}
		return array;
	}
	
	public static JSONObject parseJSONLineSafe(String inputString) throws JSONException {
		inputString = inputString.replaceAll("\n", "");
		inputString = inputString.replaceAll("\r", "");
		return new JSONObject(inputString);
	}
	
}
