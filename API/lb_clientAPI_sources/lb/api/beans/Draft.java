package lb.api.beans;

import java.io.File;
import java.io.IOException;
import java.security.InvalidKeyException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.SignatureException;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.util.LinkedList;
import java.util.List;

import lb.api.connectors.ApplicationClient;
import lb.api.connectors.LetterAttachmentClient;
import lb.api.connectors.SessionClient;
import lb.api.crypto.CryptoUtil;
import lb.api.crypto.SignatureUtil;
import lb.api.exceptions.LetterException;
import lb.api.exceptions.RemoteException;

import org.apache.commons.codec.binary.Base64;
import org.json.JSONException;
import org.json.JSONObject;

public class Draft {

	protected String letterDeliveryTypeId;
	protected String title;
	protected String content;
	protected List<DraftRecipient> recipientList = new LinkedList<DraftRecipient>();
	protected List<DraftAttachment> attachmentList = new LinkedList<DraftAttachment>();
	protected String originLetterId;
	
	protected String letterId;

	public Draft() {}

	public Draft(String letterDeliveryTypeId, String title) {
		this.letterDeliveryTypeId = letterDeliveryTypeId;
		this.title = title;
	}

	public String getLetterDeliveryTypeId() {
		return letterDeliveryTypeId;
	}

	public String getTitle() {
		return title;
	}
	
	public String getContent() {
		return content;
	}

	public List<DraftRecipient> getRecipientList() {
		return recipientList;
	}
	
	public List<DraftAttachment> getAttachmentList() {
		return attachmentList;
	}
	
	public DraftAttachment getAttachmentByFilename(String attachmentFilename) {
		for (DraftAttachment attachment : attachmentList) {
			if (attachment.getFilename().equals(attachmentFilename)) {
				return attachment;
			}
		}
		return null;
	}
	
	public String getOriginLetterId() {
		return originLetterId;
	}
	
	public void setTitle(String title) {
		this.title = title;
	}
	
	public void setContent(String content) {
		this.content = content;
	}
	
	public void setLetterDeliveryTypeId(String letterDeliveryTypeId) {
		this.letterDeliveryTypeId = letterDeliveryTypeId;
	}

	public void addRecipient(DraftRecipient recipient) {
		recipientList.add(recipient);
	}
	
	public void addAttachment(DraftAttachment attachment) {
		attachmentList.add(attachment);
		attachment.setIndex(attachmentList.size());
	}
	
	public void setOriginLetterId(String originLetterId) {
		this.originLetterId = originLetterId;
	}

	public void send(SessionClient session, boolean toArchive) 
	throws JSONException, IOException, RemoteException, LetterException {
		ApplicationClient application = new ApplicationClient(session);
		send(application, toArchive);
	}
	
	public void send(ApplicationClient application, boolean toArchive) 
	throws JSONException, IOException, RemoteException, LetterException {
		this.letterId = application.createLetterRemote(this);
		
		LetterAttachmentClient attachmentClient = new LetterAttachmentClient(application.getSession());
		for (DraftAttachment attachment : attachmentList) {
			attachmentClient.uploadAttachment(letterId, attachment);
		}
		
		application.sendLetterRemote(letterId, toArchive);
	}
	
	public void signSealedLetter(SessionClient session, File keyStoreFile, String keyStorePassword) throws KeyStoreException, 
	NoSuchAlgorithmException, CertificateException, IOException, UnrecoverableKeyException, InvalidKeyException, 
	SignatureException, JSONException, RemoteException {
		
		KeyStore keyStore = CryptoUtil.openKeyStore(keyStoreFile, keyStorePassword);
		PrivateKey privateKey = CryptoUtil.getFirstPrivateKeyInKeyStore(keyStore, keyStorePassword);
		
		signSealedLetter(session, privateKey);
		
	}
	
	public void signSealedLetter(SessionClient session, PrivateKey privateKey) throws JSONException, 
	IOException, RemoteException, InvalidKeyException, NoSuchAlgorithmException, SignatureException {
		
		ApplicationClient application = new ApplicationClient(session);
		
		JSONObject parameters = new JSONObject();
		parameters.put("letterId", this.letterId);
		JSONObject response = application.executePost("getSealedLetterDigest", parameters);
		
		byte[] letterDigest = Base64.decodeBase64(response.getString("base64SealedLetterHash"));
		byte[] letterSignature = SignatureUtil.signData(letterDigest, "NoneWithRSA", privateKey);
		
		parameters = new JSONObject();
		parameters.put("letterId", this.letterId);
		parameters.put("base64SealedLetterSignature", Base64.encodeBase64String(letterSignature));
		application.executePost("setSealedLetterPartnerSignature", parameters);
		
	}

}
