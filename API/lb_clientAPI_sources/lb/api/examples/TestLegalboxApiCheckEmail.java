package lb.api.examples;

import java.io.IOException;

import lb.api.APIResourcesManager;
import lb.api.util.EmailAddressChecker;

public class TestLegalboxApiCheckEmail {
	
	public static void main(String[] args) throws IOException {
		// initialize resources from the jar
		APIResourcesManager.initConfiguration();

		System.out.println("API VERSION : " + APIResourcesManager.getVersion());
		String email = "contact@legalbox.com";

		verifyEmail(email);
	}
	
	public static void verifyEmail(String email) {
		int status = EmailAddressChecker.checkEmailValidity(email);
	
		switch (status) {
		case EmailAddressChecker.ADDRESS_INVALID:
			System.out.println("invalid: " + email);
			break;

		case EmailAddressChecker.ADDRESS_STATUS_UNKNOWN:
			System.out.println("unknown: " + email);
			break;

		case EmailAddressChecker.ADDRESS_VALID:
			System.out.println("valid: " + email);
			
			break;

		default:
			break;
		}		
	}
	
}
