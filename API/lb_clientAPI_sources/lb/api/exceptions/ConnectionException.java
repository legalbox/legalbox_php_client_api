package lb.api.exceptions;


public class ConnectionException extends Exception {

	private static final long serialVersionUID = 1L;
	protected String remoteClassName;
	
	public ConnectionException() {
	}
	
	public ConnectionException(String message) {
		super(message);
	}

}
