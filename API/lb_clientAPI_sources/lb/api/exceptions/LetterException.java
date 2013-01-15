package lb.api.exceptions;


public class LetterException extends Exception {

	private static final long serialVersionUID = 1L;
	protected String remoteClassName;
	
	public LetterException() {
	}
	
	public LetterException(String message) {
		super(message);
	}

}
