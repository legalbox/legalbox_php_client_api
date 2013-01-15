package lb.api.exceptions;

import org.json.JSONException;
import org.json.JSONObject;

@SuppressWarnings("serial")
public class RemoteException extends Exception {

	protected String remoteExceptionClassName;
	protected String remoteErrorMessage;
	
	public RemoteException() {
		super();
	}
	
	public RemoteException(String message) {
		super(message);
	}
	
	public String getRemoteExceptionClassName() {
		return remoteExceptionClassName;
	}
	
	public String getRemoteErrorMessage() {
		return remoteErrorMessage;
	}

	public static RemoteException createRemoteException(JSONObject jsonResponse) {
		
		String remoteExceptionClassName = null;
		try {
			remoteExceptionClassName = jsonResponse.getString("errorClass");
		} catch (JSONException e) {}
		
		String remoteErrorMessage = null;
		try {
			remoteErrorMessage = jsonResponse.getString("errorMessage");
		} catch (JSONException e) {}

		String message = "";
		if (remoteExceptionClassName != null) {
			message += remoteExceptionClassName;
		}
		if (remoteErrorMessage != null) {
			message += " - " + remoteErrorMessage;
		}
		
		RemoteException remoteException = new RemoteException(message);
		remoteException.remoteExceptionClassName = remoteExceptionClassName;
		remoteException.remoteErrorMessage = remoteErrorMessage;
		
		return remoteException;
		
	}

}
