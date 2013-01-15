package lb.api.examples;

import java.io.File;

import lb.api.APIResourcesManager;
import lb.api.beans.Draft;
import lb.api.beans.DraftAttachment;
import lb.api.beans.DraftRecipient;
import lb.api.beans.LetterDeliveryType;
import lb.api.connectors.SessionClient;


public class TestLegalboxApiSendLetter {
	
	private static final String helpText = "Usage : TestLegalboxApiSendLetter"
			+ " [userIdentifier or email]"
			+ " [password]"
			+ " [recipient email]"
			+ " [letter title]"
			+ " [letter text]"
			+ " [attachment path]"
			;
	
	public static void main(String[] args) throws Exception {

		// initialize resources from the jar
		APIResourcesManager.initConfiguration();

		System.out.println("API VERSION : " + APIResourcesManager.getVersion());

		if (args.length < 5) {
			System.out.println(helpText);
			return;
		}

		SessionClient session = SessionClient.createSessionClient();

		

		try {
			String identifierOrEmail = args[0];
			String password = args[1];
			String recipientEmail = args[2];
			String title = args[3];
			String text = args[4];
			
			System.out.println("trying to open session for " + identifierOrEmail);
			session.openSession(identifierOrEmail, password);
			System.out.println("session opening succesful !");

			System.out.println("populate delivery type list");
			LetterDeliveryType.populateList(session);
			
			System.out.print("sending letter...");
		
			LetterDeliveryType deliveryType 
				= LetterDeliveryType.getLetterDeliveryTypeByCode(LetterDeliveryType.CERTIFIED_LETTER_CODE);
			
			Draft draft = new Draft(deliveryType.getId(), title);
			DraftRecipient recipient = new DraftRecipient(recipientEmail);
			draft.setContent(text);
			draft.addRecipient(recipient);

			/**
			 * add attachment if any
			 */
			if(args.length == 6) {
				String path = args[5];
				DraftAttachment attachment = DraftAttachment.createAttachment(new File(path));
				draft.addAttachment(attachment);
			}

			draft.send(session, false);
			
			System.out.println("sending letter... done!");
			
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
