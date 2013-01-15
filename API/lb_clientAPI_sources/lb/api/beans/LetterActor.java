package lb.api.beans;

import org.json.JSONException;
import org.json.JSONObject;

public class LetterActor {
	
	protected String userId;
	protected boolean isSubscribed;
	protected String emailAddress;
	protected String userIdentifier;
	protected String publicName;
	
	public LetterActor(JSONObject actorObject) throws JSONException {
		this.emailAddress = actorObject.getString("email");
		if (!actorObject.isNull("userId")) {
			this.isSubscribed = true;
			this.userId = actorObject.getString("userId");
			this.publicName = actorObject.getString("publicName");
			this.userIdentifier = actorObject.getString("identifier");
		} else {
			this.isSubscribed = false;
		}
	}

	public String getUserId() {
		return userId;
	}

	public String getEmailAddress() {
		return emailAddress;
	}

	public String getUserIdentifier() {
		return userIdentifier;
	}

	public String getPublicName() {
		return publicName;
	}
	
}
