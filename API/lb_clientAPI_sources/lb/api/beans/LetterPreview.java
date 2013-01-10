package lb.api.beans;

import java.util.Date;
import java.util.LinkedList;
import java.util.List;

import lb.api.util.JSONUtil;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class LetterPreview {
	
	protected String letterId;
	protected String actorId;
	protected String subject;
	protected Date sendingDate;
	protected LetterActor sender;
	protected List<LetterActor> recipientList;
	protected boolean hasAttachments;
	protected LetterDeliveryType letterDeliveryType;
	
	public LetterPreview(JSONObject previewObject) throws JSONException {
		this.letterId = previewObject.getString("_id");
		this.actorId = previewObject.getString("actorId");
		if (!previewObject.isNull("subject")) {
			this.subject = previewObject.getString("subject");
		}
		this.sendingDate = JSONUtil.convertDateFromJSON(previewObject.getJSONObject("date"));
		if (!previewObject.isNull("sender")) {
			this.sender = new LetterActor(previewObject.getJSONObject("sender"));
		}
		this.recipientList = new LinkedList<LetterActor>();
		JSONArray recipientsArray = previewObject.getJSONArray("recipientList");
		for (int i = 0; i < recipientsArray.length(); i++) {
			recipientList.add(new LetterActor(recipientsArray.getJSONObject(i)));
		}
		this.letterDeliveryType = LetterDeliveryType.getLetterDeliveryTypeById(
				previewObject.getString("letterDeliveryTypeId"));
	}

	public String getLetterId() {
		return letterId;
	}

	public String getActorId() {
		return actorId;
	}

	public String getSubject() {
		return subject;
	}

	public Date getSendingDate() {
		return sendingDate;
	}

	public LetterActor getSender() {
		return sender;
	}

	public List<LetterActor> getRecipientList() {
		return recipientList;
	}

	public boolean hasAttachments() {
		return hasAttachments;
	}
	
	public LetterDeliveryType getLetterDeliveryType() {
		return letterDeliveryType;
	}

}
