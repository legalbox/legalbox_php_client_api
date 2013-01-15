package lb.api.beans;

import org.json.JSONException;
import org.json.JSONObject;

public class Address {
	protected String address1;
	protected String address2;
	protected String address3;
	protected String zipCode;
	protected String town;
	protected String cedex;
	protected String stateProvince;
	protected String countryId;

	protected Address() {
		this.address1 = "";
		this.address2 = "";
		this.address3 = "";
		this.zipCode = "";
		this.town = "";
		this.cedex = "";
		this.stateProvince = "";
		this.countryId = "";
	}

	public String getAddress1() {
		return address1;
	}

	public void setAddress1(String address1) {
		this.address1 = address1;
	}

	public String getAddress2() {
		return address2;
	}

	public void setAddress2(String address2) {
		this.address2 = address2;
	}

	public String getAddress3() {
		return address3;
	}

	public void setAddress3(String address3) {
		this.address3 = address3;
	}

	public String getZipCode() {
		return zipCode;
	}

	public void setZipCode(String zipCode) {
		this.zipCode = zipCode;
	}

	public String getTown() {
		return town;
	}

	public void setTown(String town) {
		this.town = town;
	}

	public String getCountryId() {
		return countryId;
	}

	public void setCountryId(String countryId) {
		this.countryId = countryId;
	}

	public void setCountryCode(String countryCode) {
		Country item = Country.getCountryByCode(countryCode);
		this.countryId = item.getId();
	}

	public String getStateProvince() {
		return stateProvince;
	}

	public void setStateProvince(String stateProvince) {
		this.stateProvince = stateProvince;
	}

	public String getCedex() {
		return cedex;
	}

	public void setCedex(String cedex) {
		this.cedex = cedex;
	}

	public static Address createEmptyAddress() {
		return new Address();
	}

	public JSONObject exportToJSON() throws JSONException {

		JSONObject jsonObject = new JSONObject();

		jsonObject.put("address1", address1);
		jsonObject.put("address2", address2);
		jsonObject.put("address3", address3);
		jsonObject.put("zipCode", zipCode);
		jsonObject.put("town", town);
		jsonObject.put("cedex", cedex);
		jsonObject.put("stateProvince", stateProvince);
		jsonObject.put("countryId", countryId);

		return jsonObject;
	}


}
