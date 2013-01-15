package lb.api.beans;

import org.json.JSONException;
import org.json.JSONObject;

public class User {
	
	protected String sponsorId;
	protected String userEmail;
	protected String userIdentifier;
	protected Boolean isProfessional;
	protected String publicName;
	protected String firstName;
	protected String lastName;
	protected String languageId;
	protected String phone;
	protected String mnemonicSentence;
	protected Address address;
	protected CompanyData companyData;
	
	public User() {
		this.companyData = new CompanyData();
		this.address = new Address();
	}


	public String getMnemonicSentence() {
		return mnemonicSentence;
	}
	
	public void setMnemonicSentence(String mnemonicSentence) {
		this.mnemonicSentence = mnemonicSentence;
	}
	
	public Address getAddress() {
		return address;
	}
	
	public void setAddress(Address address) {
		this.address = address;
	}
	
	public CompanyData getCompanyData() {
		return companyData;
	}
	
	public void setCompanyData(CompanyData companyData) {
		this.companyData = companyData;
	}
	
	public boolean isProfessional() {
		return isProfessional;
	}
	
	public String getAccountType() {
		if(isProfessional) {
			return "professional";
		} else {
			return "personal";
		}
	}
	
	
	public void setIsProfessional(Boolean isProfessional) {
		this.isProfessional = isProfessional;
	}
	
	public String getUserEmail() {
		return userEmail;
	}
	
	public void setUserEmail(String userEmail) {
		this.userEmail = userEmail;
	}
	
	public String getSponsorId() {
		return sponsorId;
	}
	
	public void setSponsorId(String sponsorId) {
		this.sponsorId = sponsorId;
	}
	
	public String getUserIdentifier() {
		return userIdentifier;
	}
	
	public void setUserIdentifier(String userIdentifier) {
		this.userIdentifier = userIdentifier;
	}
	
	public String getPublicName() {
		return publicName;
	}
	
	public void setPublicName(String publicName) {
		this.publicName = publicName;
	}
	
	public String getFirstName() {
		return firstName;
	}
	
	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}
	
	public String getLastName() {
		return lastName;
	}
	
	public void setLastName(String lastName) {
		this.lastName = lastName;
	}
	
	public String getPhone() {
		return phone;
	}
	
	public void setPhone(String phone) {
		this.phone = phone;
	}
	
	public String getLanguageId() {
		return languageId;
	}
	
	public void setLanguageId(String languageId) {
		this.languageId = languageId;
	}


	public JSONObject exportToJSON() throws JSONException {

		JSONObject jsonObject = new JSONObject();
		jsonObject.put("userEmail", this.getUserEmail());
		jsonObject.put("identifier", this.getUserIdentifier());
		jsonObject.put("sponsorId", this.getSponsorId());
		jsonObject.put("accountType", this.getAccountType());

		jsonObject.put("isProfessional", this.isProfessional());
		jsonObject.put("publicName", this.getPublicName());
		jsonObject.put("firstName", this.getFirstName());
		jsonObject.put("lastName", this.getLastName());
		jsonObject.put("phone", this.getPhone());
		jsonObject.put("address", this.getAddress().exportToJSON());
		
		if(this.isProfessional()) {
			// TODO nicely one day ...
			//jsonObject.put("companyData", this.getCompanyData().exportToJSON());
			this.getCompanyData().exportToJSON(jsonObject);
		}
		
		jsonObject.put("mnemonicSentence",  this.getMnemonicSentence());

		return jsonObject;
	}

	
}
