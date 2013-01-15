package lb.api.beans;

import java.util.LinkedList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class LetterDetails extends LetterPreview {
	
	protected String htmlContent;
	protected List<LetterAttachment> attachmentList;

	public LetterDetails(JSONObject detailsObject) throws JSONException {
		super(detailsObject);
		this.htmlContent = detailsObject.getString("text");
		this.attachmentList = new LinkedList<LetterAttachment>();
		JSONArray attachmentArray = detailsObject.getJSONArray("letterAttachments");
		for (int i = 0; i < attachmentArray.length(); i++) {
			attachmentList.add(new LetterAttachment(attachmentArray.getJSONObject(i)));
		}
	}
	
	public String getHtmlContent() {
		return htmlContent;
	}
	
	public List<LetterAttachment> getAttachmentList() {
		return attachmentList;
	}

}
