package lb.api.connectors;

import java.io.IOException;
import java.io.InputStream;
import java.security.KeyManagementException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import lb.api.APIResourcesManager;
import lb.api.exceptions.AuthenticationException;
import lb.api.exceptions.ConnectionException;
import lb.api.exceptions.RemoteException;

import org.apache.http.NameValuePair;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.ssl.SSLSocketFactory;
import org.apache.http.entity.mime.content.ContentBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

public class SessionClient {
	
	protected static String url = APIResourcesManager.getSessionRestfulURL();
	
	private static Scheme clientHttpsScheme = null;
	
	protected HttpConnector connector;
	protected String sessionId;
	protected String userId;
	protected String languageCode = "en";
	
	private SessionClient() {}
	
	public String getSessionId() {
		return sessionId;
	}
	
	public String getUserId() {
		return userId;
	}
	
	public String getLanguageCode() {
		return languageCode;
	}
	
	public void setLanguageCode(String languageCode) {
		this.languageCode = languageCode;
	}
	
	public HttpConnector getConnector() {
		return connector;
	}
	
	protected static Scheme getClientHttpsScheme() throws NoSuchAlgorithmException, CertificateException, 
	IOException, KeyStoreException, KeyManagementException, UnrecoverableKeyException {
		if (clientHttpsScheme == null) {
			KeyStore trustStore  = KeyStore.getInstance(KeyStore.getDefaultType());
			InputStream instream = null;
			try {
				instream = APIResourcesManager.getSSLTrustStoreInputStream();
                trustStore.load(instream, APIResourcesManager.getTrustStorePassword().toCharArray());
            } finally {
            	if(instream != null) instream.close();
            }
			SSLSocketFactory socketFactory = new SSLSocketFactory(trustStore);
			clientHttpsScheme = new Scheme("https", 443, socketFactory);
		}
		return clientHttpsScheme;
	}
	
	public static SessionClient createSessionClient() throws ConnectionException {
		SessionClient sessionClient = new SessionClient();
		DefaultHttpClient client = new DefaultHttpClient();
		try {
            client.getConnectionManager().getSchemeRegistry().register(getClientHttpsScheme());
			sessionClient.connector = new HttpConnector(client);
		} catch (Exception e) {
			throw new ConnectionException(e.getMessage());
		}
		return sessionClient;
	}
	
	public static SessionClient createSessionClientFromExistingSession(String userId, String sessionId) 
	throws ConnectionException, AuthenticationException {
		SessionClient sessionClient = createSessionClient();
		sessionClient.sessionId = sessionId;
		sessionClient.userId = userId;
		try {
			sessionClient.checkSession();
		} catch (RemoteException remoteException) {
			throw new AuthenticationException(remoteException.getMessage());
		} catch (IOException exception) {
			throw new ConnectionException(exception.getMessage());
		} catch (JSONException exception) {
			throw new ConnectionException(exception.getMessage());
		}
		return sessionClient;
	}
	
	public void openSession(String identifierOrEmail, String password) 
	throws JSONException, IOException, AuthenticationException {
		
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("request", "openSession");
		jsonParameters.put("identifierOrEmail", identifierOrEmail);
		jsonParameters.put("password", password);

		try {
			JSONObject jsonReturned = executePost(jsonParameters);
			sessionId = jsonReturned.getString("sessionId");
			userId = jsonReturned.getString("userId");
		} catch (RemoteException e) {
			throw new AuthenticationException(e.getMessage());
		}
		
	}
	
	public void setSessionInformations(String userId, String sessionId) {
		this.userId = userId;
		this.sessionId = sessionId;
	}

	public JSONObject executeApplicationPost(String url, JSONObject jsonParameters) 
	throws IOException, JSONException, RemoteException {
		jsonParameters.put("sessionId", this.sessionId);
		jsonParameters.put("userId", this.userId);
		return connector.executePost(url, jsonParameters);
	}
	
	public void executeApplicationPost(
			String url,
			JSONObject jsonParameters,
			ContentBody contentBody) 
	throws IOException, JSONException, RemoteException {
		jsonParameters.put("sessionId", this.sessionId);
		jsonParameters.put("userId", this.userId);
		connector.executePost(url, jsonParameters, contentBody);
	}
	
	public JSONObject executePost(JSONObject jsonParameters) 
	throws IOException, JSONException, RemoteException {
		return connector.executePost(url, jsonParameters);
	}
	
	public byte[] getAttachmentRemote(String attachmentId) throws IOException {
		Map<String, String> cookiesMap = new HashMap<String, String>();
		cookiesMap.put("userId", userId);
		cookiesMap.put("sessionId", sessionId);
		List<NameValuePair> parametersList = new LinkedList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("request", "getAttachment"));
		parametersList.add(new BasicNameValuePair("attachmentId", attachmentId));
		return connector.executePostWithCookies(
				APIResourcesManager.getFileDownloadRestfulURL(), parametersList, cookiesMap);
	}

	public void closeSession() 
	throws JSONException, IOException, RemoteException {
		if (this.sessionId == null) {
			return;
		}
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("request", "closeSession");
		jsonParameters.put("sessionId", this.sessionId);
		executePost(jsonParameters);
		this.sessionId = null;
	}

	public void checkSession() 
	throws JSONException, IOException, RemoteException {
		JSONObject jsonParameters = new JSONObject();
		jsonParameters.put("request", "checkSession");
		jsonParameters.put("userId", this.userId);
		jsonParameters.put("sessionId", this.sessionId);
		executePost(jsonParameters);
	}
	
	public ApplicationClient createApplicationClient() {
		return new ApplicationClient(this);
	}

	@Override
	protected void finalize() throws Throwable {
		closeSession();
		super.finalize();
	}
	
}
