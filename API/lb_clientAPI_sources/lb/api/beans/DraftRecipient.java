package lb.api.beans;

import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;

public class DraftRecipient {
	
	protected String emailAddress;
	protected boolean isProfessional;
	protected String notificationLanguageCode;
	protected boolean isCarbonCopyRecipient = false;
	protected boolean isPrepayedRecipient = false;
	protected List<DraftAttachment> signatureRequestList = new ArrayList<DraftAttachment>(); 
	
	public DraftRecipient(String emailAddress) {
		this.emailAddress = emailAddress;
	}
	
	public DraftRecipient(String emailAddress, boolean isCarbonCopyRecipient) {
		this.emailAddress = emailAddress;
		this.isCarbonCopyRecipient = isCarbonCopyRecipient;
	}
	
	public String getEmailAddress() {
		return emailAddress;
	}
	
	public boolean isProfessional() {
		return isProfessional;
	}
	
	public boolean isCarbonCopyRecipient() {
		return isCarbonCopyRecipient;
	}
	
	public String getNotificationLanguageCode() {
		return notificationLanguageCode;
	}
	
	public List<DraftAttachment> getSignatureRequestList() {
		return signatureRequestList;
	}
	
	public void setEmailAddress(String emailAddress) {
		this.emailAddress = emailAddress;
	}
	
	public void setProfessional(boolean isProfessional) {
		this.isProfessional = isProfessional;
	}
	
	public void setIsisCarbonCopyRecipient(boolean isCarbonCopyRecipient) {
		this.isCarbonCopyRecipient = isCarbonCopyRecipient;
	}
	
	public void setNotificationLanguageCode(String notificationLanguageCode) {
		this.notificationLanguageCode = notificationLanguageCode;
	}

	public boolean isPrepayedRecipient() {
		return isPrepayedRecipient;
	}
	
	public void setPrepayedRecipient(boolean isPrepayedRecipient) {
		this.isPrepayedRecipient = isPrepayedRecipient;
	}
	
	public void addSignatureRequest(DraftAttachment attachment) {
		this.signatureRequestList.add(attachment);
	}
	
	public JSONArray getSignatureRequestIndexArray() {
		if (signatureRequestList.size() == 0) {
			return null;
		}
		JSONArray indexArray = new JSONArray();
		for (DraftAttachment attachment : signatureRequestList) {
			indexArray.put(attachment.getIndex());
		}
		return indexArray;
	}
	
}
