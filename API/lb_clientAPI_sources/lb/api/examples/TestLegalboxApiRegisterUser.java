package lb.api.examples;

import lb.api.APIResourcesManager;
import lb.api.beans.Address;
import lb.api.beans.Country;
import lb.api.beans.User;
import lb.api.connectors.SessionClient;
import lb.api.connectors.UserGroupBackofficeClient;

public class TestLegalboxApiRegisterUser {
	
	private static final String helpText = "Usage : TestLegalboxApiRegisterUser"
			+ " [admin userIdentifier or email]"
			+ " [admin password]"
			+ " [user email]"
			+ " [user identifier]"
			+ " [user first name]"
			+ " [user last name]"
			+ " [user password]"
			+ " [user mnemonic sentence]"
			;
	
	public static void main(String[] args) throws Exception {

		// initialize resources from the jar
		APIResourcesManager.initConfiguration();

		System.out.println("API VERSION : " + APIResourcesManager.getVersion());

		if (args.length < 7) {
			System.out.println(helpText);
			return;
		}

		SessionClient session = SessionClient.createSessionClient();

		try {
			String adminIdentifierOrEmail = args[0];
			String adminPassword = args[1];
			String userEmail = args[2];
			String userIdentifier = args[3];
			String userFirstName = args[4];
			String userLastName = args[5];
			String userPassword = args[6];
			String userMnemonicSentence = args[7];
			
			System.out.println("trying to open session for " + adminIdentifierOrEmail);
			session.openSession(adminIdentifierOrEmail, adminPassword);
			System.out.println("session opening succesful !");
			
			
			Country.populateList(session);
			
			User user = new User(); 
			user.setUserIdentifier(userIdentifier);
			user.setUserEmail(userEmail);
			user.setIsProfessional(false);

			user.setFirstName(userFirstName);
			user.setLastName(userLastName);
			
			Address address = user.getAddress();
			address.setAddress1("63bis rue de la tombe issoire");
			address.setCountryCode("FR");
			address.setZipCode("75014");
			address.setTown("PARIS");
			
			user.setMnemonicSentence(userMnemonicSentence);
						
			System.out.println("register user... ");
			UserGroupBackofficeClient backofficeUser = new UserGroupBackofficeClient(session);
			backofficeUser.createNewUserInGroup(user, userPassword);
			
			System.out.println("register user... done!");
			
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
