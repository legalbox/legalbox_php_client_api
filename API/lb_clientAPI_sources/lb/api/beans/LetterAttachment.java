package lb.api.beans;

import org.json.JSONException;
import org.json.JSONObject;

public class LetterAttachment {
	
	protected String id;
	protected int size;
	protected String fileName;
	
	public LetterAttachment(JSONObject attachmentObject) throws JSONException {
		this.id = attachmentObject.getString("_id");
		this.size = attachmentObject.getInt("size");
		this.fileName = attachmentObject.getString("name");
	}
	
	public String getId() {
		return id;
	}
	
	public String getFileName() {
		return fileName;
	}

}
