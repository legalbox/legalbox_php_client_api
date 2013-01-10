package lb.api.examples;

import lb.api.APIResourcesManager;
import lb.api.connectors.SessionClient;


public class TestLegalboxApiLogin {
	
	private static final String helpText = "Usage : TestLegalboxApiLogin"
			+ " [userIdentifier or email]"
			+ " [password]";
	
	public static void main(String[] args) throws Exception {

		// initialize resources from the jar
		APIResourcesManager.initConfiguration();
		
		System.out.println("API VERSION : " + APIResourcesManager.getVersion());

		if (args.length < 2) {
			System.out.println(helpText);
			return;
		}
		
		SessionClient session = SessionClient.createSessionClient();

		try {
			String identifierOrEmail = args[0];
			String password = args[1];
			System.out.println("trying to open session for " + identifierOrEmail);
			session.openSession(identifierOrEmail, password);
			System.out.println("session opening succesful !");
			
		} catch (Exception e) {
			e.printStackTrace();
		}

		try {
			session.closeSession();
			session = null;
			Runtime.getRuntime().gc();
			System.out.println("session closed");
		} catch (Exception e) {
		}
	}
}
