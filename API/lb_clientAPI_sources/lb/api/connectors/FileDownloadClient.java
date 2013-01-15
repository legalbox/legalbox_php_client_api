package lb.api.connectors;

import java.io.IOException;
import java.util.LinkedList;
import java.util.List;

import lb.api.APIResourcesManager;
import lb.api.exceptions.RemoteException;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;

public class FileDownloadClient {
	
	public static String url = APIResourcesManager.getFileUploadRestfulURL();
	protected SessionClient session;

	public FileDownloadClient(SessionClient session) {
		this.session = session;
	}
	
	public byte[] getAttachment(String attachmentId)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("attachmentId", attachmentId));
		return executePost("getAttachment", parametersList);
	}
	
	public byte[] getAttachmentSignatureFile(String attachmentSignatureId)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("attachmentSignatureId", attachmentSignatureId));
		return executePost("getAttachmentSignatureFile", parametersList);
	}
	
	
	public byte[] getAttachmentSignatureCertificate(String attachmentSignatureId)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("attachmentSignatureId", attachmentSignatureId));
		return executePost("getAttachmentSignatureCertificate", parametersList);
	}
	
	
	/**
	 * 
	 * @param action: getData, getSignature, getCertificate
	 */
	public byte[] getEvent(
			String eventId,
			String action)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("eventId", eventId));
		parametersList.add(new BasicNameValuePair("action", action));
		return executePost("getEvent", parametersList);
	}
	

	/**
	 * 
	 * @param action: getData, getLegalboxSignature, getSenderSignature
	 */
	public byte[] getLetterSeal(
			String letterId,
			String action)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("letterId", letterId));
		parametersList.add(new BasicNameValuePair("action", action));
		return executePost("getLetterSeal", parametersList);
	}
	
	/**
	 * 
	 * @param action: getData, getSignature
	 */
	public byte[] getLetterReceipt(
			String letterId,
			String recipientUserId,
			String action)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("letterId", letterId));
		parametersList.add(new BasicNameValuePair("recipientUserId", recipientUserId));
		parametersList.add(new BasicNameValuePair("action", action));
		return executePost("getLetterReceipt", parametersList);
	}
	
	/**
	 * 
	 * @param action: getData, getSignature
	 */
	public byte[] getLetterAcceptationReceipt(
			String letterId,
			String recipientUserId,
			String action)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("letterId", letterId));
		parametersList.add(new BasicNameValuePair("recipientUserId", recipientUserId));
		parametersList.add(new BasicNameValuePair("action", action));
		return executePost("getLetterAcceptationReceipt", parametersList);
	}
	
	public byte[] getUserCertificate(
			String userId)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		return executePost("getUserCertificate", parametersList);
	}
	
	public byte[] getUserIdentificationCertificate(
			String userIdentificationId)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("userIdentificationId", userIdentificationId));
		return executePost("getUserIdentificationCertificate", parametersList);
	}
	
	public byte[] generateBackupCertificateLevel1(
			String userIdentificationId)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("userIdentificationId", userIdentificationId));
		return executePost("generateBackupCertificateLevel1", parametersList);
	}
	

	public byte[] getUserGroupConsumptionCSV(
			String userIdentificationId,
			String startDate,
			String endDate)
	throws IOException, JSONException, RemoteException {
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("userIdentificationId", userIdentificationId));
		parametersList.add(new BasicNameValuePair("startDate", startDate));
		parametersList.add(new BasicNameValuePair("endDate", endDate));
		return executePost("getUserGroupConsumptionCSV", parametersList);
	}
	
	
	

	public byte[] executePost(String request, List<NameValuePair> parametersList) 
	throws IOException, JSONException, RemoteException {
		parametersList.add(new BasicNameValuePair("request", request));
		parametersList.add(new BasicNameValuePair("sessionId", session.sessionId));
		parametersList.add(new BasicNameValuePair("userId", session.userId));
		return session.getConnector().executePost(
				APIResourcesManager.getFileDownloadRestfulURL(), parametersList);
	}
}
