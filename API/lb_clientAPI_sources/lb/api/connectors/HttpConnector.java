package lb.api.connectors;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import lb.api.APIResourcesManager;
import lb.api.exceptions.RemoteException;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.CookieStore;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.protocol.ClientContext;
import org.apache.http.client.utils.URIBuilder;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.ContentBody;
import org.apache.http.impl.client.BasicCookieStore;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.cookie.BasicClientCookie;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.BasicHttpContext;
import org.apache.http.protocol.HttpContext;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

public class HttpConnector {
	
	private static Log log = LogFactory.getLog(HttpConnector.class);
	
	private DefaultHttpClient httpClient;

	public HttpConnector(DefaultHttpClient httpClient) {
		this.httpClient = httpClient;
	}
	
	/**
	 * Performs an AJAX RESTful request using JSON only.
	 * @param relativeUrl : the relative URL to request
	 * @param jsonParameters : the JSON Object to send
	 * @return the response JSON.
	 */
	public JSONObject executePost(String relativeUrl, JSONObject jsonParameters) throws JSONException, 
	RemoteException, ClientProtocolException, IOException {
		
		log.debug("run executePost(relativeUrl, jsonParameters)");
		log.debug("relativeUrl: " + relativeUrl);
		log.debug("jsonParams: " + jsonParameters.toString());
		
		HttpPost httppost = new HttpPost(buildURI(relativeUrl));
		
		List<NameValuePair> parametersList = new ArrayList<NameValuePair>();
		parametersList.add(new BasicNameValuePair("jsonParams", jsonParameters.toString()));
		httppost.setEntity(new UrlEncodedFormEntity(parametersList, "UTF-8"));

		ResponseHandler<String> handler = new ResponseHandler<String>() {
		    public String handleResponse(HttpResponse response) throws ClientProtocolException, IOException {
		        HttpEntity entity = response.getEntity();
		        if (entity != null) {
		            return EntityUtils.toString(entity);
		        } else {
		            return null;
		        }
		    }
		};
		
		String response = httpClient.execute(httppost, handler);
		response = response.replaceAll("\n", "");
		response = response.replaceAll("\r", "");
		
		JSONObject jsonReturned = new JSONObject(response);	
		log.debug("jsonReturned" + jsonReturned.toString());
		
		if (jsonReturned.getBoolean("error")) {
			throw RemoteException.createRemoteException(jsonReturned);
		}
		
		return jsonReturned;
		
	}
	
	/**
	 * Performs a JSON request with the given parameters and content.
	 * @param relativeUrl : the relative URL to request
	 * @param jsonParameters : the parameters that will be passed to this request
	 * @param contentBody : the request body that will be passed
	 * @return the raw response.
	 */
	public String executePost(String relativeUrl, JSONObject jsonParameters, ContentBody contentBody) throws IOException, 
	JSONException, RemoteException {
		
		log.debug("run executePost(relativeUrl, jsonParameters, contentBody)");
		log.debug("relativeUrl: " + relativeUrl);
		log.debug("jsonParams: " + jsonParameters.toString());
		
		URI uri = buildURI(relativeUrl);
		URIBuilder builder = new URIBuilder(uri);
		for (@SuppressWarnings("unchecked")
		Iterator<String> iterator = jsonParameters.keys(); iterator.hasNext();) {
			String key = iterator.next();
			builder.setParameter(key, jsonParameters.getString(key));
		}	
		try {
			uri = builder.build();
		} catch (URISyntaxException e) {
			log.error("Cannot build URI, parameters ignored !", e);
		}
		log.debug("URI built: " + uri.toString());
		
		HttpPost httppost = new HttpPost(uri);
		
		MultipartEntity reqEntity = new MultipartEntity();
		reqEntity.addPart("content", contentBody);
		
		httppost.setEntity(reqEntity);
		
		ResponseHandler<String> handler = new ResponseHandler<String>() {
		    public String handleResponse(HttpResponse response) throws ClientProtocolException, IOException {
		        HttpEntity entity = response.getEntity();
		        if (entity != null) {
		            return EntityUtils.toString(entity);
		        } else {
		            return null;
		        }
		    }
		};
		
		String response = httpClient.execute(httppost, handler);
		log.debug("response: " + response);

		return response;

	}
	
	public byte[] executePost(String relativeUrl, List<NameValuePair> requestParameters) throws ClientProtocolException, IOException {
		return executePostWithCookies(relativeUrl, requestParameters, null);
	}
	
	/**
	 * Performs a POST request with the given parameters and cookies.
	 * @param relativeUrl : the relative URL to request
	 * @param requestParameters : the parameters that will be passed to this request
	 * @param cookiesNameValuePairs : the name-value pairs put in cookies that will be added to this request
	 * @return the raw response.
	 */
	public byte[] executePostWithCookies(String relativeUrl, 
			List<NameValuePair> requestParameters,
			Map<String, String> cookiesNameValuePairs) throws ClientProtocolException, IOException {
		
		HttpPost httppost = new HttpPost(buildURI(relativeUrl));
		
		httppost.setEntity(new UrlEncodedFormEntity(requestParameters, "UTF-8"));
		
		HttpContext localContext = new BasicHttpContext();
		if(cookiesNameValuePairs != null) {
			localContext.setAttribute(ClientContext.COOKIE_STORE, createCookieStoreFromMap(cookiesNameValuePairs));
		}
		
		ResponseHandler<byte[]> handler = new ResponseHandler<byte[]>() {
		    public byte[] handleResponse(HttpResponse response) throws ClientProtocolException, IOException {
		        HttpEntity entity = response.getEntity();
		        if (entity != null) {
		            return EntityUtils.toByteArray(entity);
		        } else {
		            return null;
		        }
		    }
		};
		
		return httpClient.execute(httppost, handler, localContext);
		
	}
	
	private static CookieStore createCookieStoreFromMap(Map<String, String> cookiesNameValuePairs) {
		CookieStore cookieStore = new BasicCookieStore();
		for (String key : cookiesNameValuePairs.keySet()) {
			BasicClientCookie cookie = new BasicClientCookie(key, cookiesNameValuePairs.get(key));
			cookie.setDomain(".legalbox.com");
			cookie.setPath("/");
			cookieStore.addCookie(cookie);
		}
		return cookieStore;
	}
	
	private static URI buildURI(String relativeURL) {
		
		URIBuilder builder = new URIBuilder();
		
		builder.setScheme(APIResourcesManager.getRestfulScheme())
			.setHost(APIResourcesManager.getRestfulHostname())
			.setPort(APIResourcesManager.getRestfulPort())
			.setPath(relativeURL);
		
		URI uri = null;
		try {
			uri =  builder.build();
		} catch (URISyntaxException e) {
			log.error("Cannot build URI !", e);
		}
		
		return uri;
		
	}
	
}
