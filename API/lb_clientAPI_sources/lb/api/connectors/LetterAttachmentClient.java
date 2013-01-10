package lb.api.connectors;

import java.io.IOException;

import lb.api.APIResourcesManager;
import lb.api.beans.DraftAttachment;
import lb.api.exceptions.RemoteException;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.json.JSONException;
import org.json.JSONObject;

public class LetterAttachmentClient {
	
	@SuppressWarnings("unused")
	private static Log log = LogFactory.getLog(LetterAttachmentClient.class);
	
	public static String uploadUrl = APIResourcesManager.getFileUploadRestfulURL();
	
	protected SessionClient session;

	public LetterAttachmentClient(SessionClient session) {
		this.session = session;
	}
	
	public void uploadAttachment(String letterId, DraftAttachment attachment) throws IOException, 
	JSONException, RemoteException {
		
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("letterId", letterId);
		jsonParameters.put("index", "" + attachment.getIndex());
		jsonParameters.put("X-Progress-ID", letterId + "_" + attachment.getIndex());
		jsonParameters.put("filename", attachment.getFilename());

		session.executeApplicationPost(uploadUrl, jsonParameters, attachment.getHttpBody());
		
		if (attachment.isSigned()) {
			session.createApplicationClient()
				.addAttachmentSignatureRemote(letterId, attachment.getIndex(), attachment.getBase64Signature());
		}
		
	}
	
}
