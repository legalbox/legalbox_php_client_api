package lb.api.exceptions;


public class AuthenticationException extends Exception {

	private static final long serialVersionUID = 1L;
	protected String remoteClassName;
	
	public AuthenticationException() {
	}
	
	public AuthenticationException(String message) {
		super(message);
	}

}
