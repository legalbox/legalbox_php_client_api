package lb.api.beans;

import org.json.JSONException;
import org.json.JSONObject;

public class CompanyData {
	protected String companyTypeId;
	protected String companyName;
	protected String tradeName;
	protected String position;
	protected String unit;
	protected String department;
	protected String identificationNumber;
	protected String website;

	public CompanyData() {
		this.companyTypeId = "";
		this.companyName = "";
		this.tradeName = "";
		this.position = "";
		this.unit = "";
		this.department = "";
		this.identificationNumber = "";
		this.website = "";
	}
	
	public void setCompanyName(String companyName) {
		this.companyName = companyName;
	}
	
	public String getCompanyName(){
		return companyName;
	}
	
	public String getIdentificationNumber() {
		return identificationNumber;
	}
	
	public void setIdentificationNumber(String identificationNumber) {
		this.identificationNumber = identificationNumber;
	}
	
	public void setCompanyTypeId(String companyTypeId) {
		this.companyTypeId = companyTypeId;
	}

	public JSONObject exportToJSON() throws JSONException {
		JSONObject jsonObject = new JSONObject();
		return exportToJSON(jsonObject);
	}
	
	public JSONObject exportToJSON(JSONObject jsonObject) throws JSONException {
		 
		jsonObject.put("companyTypeId", companyTypeId);
		jsonObject.put("companyName", companyName);
		jsonObject.put("tradeName", tradeName);
		jsonObject.put("position", position);
		jsonObject.put("unit", unit);
		jsonObject.put("department", department);
		jsonObject.put("identificationNumber", identificationNumber);
		jsonObject.put("website", website);

		return jsonObject;
	}
}
