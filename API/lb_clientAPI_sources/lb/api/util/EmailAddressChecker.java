package lb.api.util;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.InetSocketAddress;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Hashtable;

import javax.naming.NamingEnumeration;
import javax.naming.NamingException;
import javax.naming.directory.Attribute;
import javax.naming.directory.Attributes;
import javax.naming.directory.DirContext;
import javax.naming.directory.InitialDirContext;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;


public class EmailAddressChecker {
	
	private static Log log = LogFactory.getLog(EmailAddressChecker.class);
	
	public static final int ADDRESS_VALID = 0;
	public static final int ADDRESS_STATUS_UNKNOWN = 1;
	public static final int ADDRESS_INVALID = 2;
	
	private static final int SOCKET_TIMEOUT = 2000;
	
	private String emailAddress;
	
	private EmailAddressChecker() {}
	
	/**
	 * Checks if the given email address domain has a registered MX server.
	 */
	public static boolean hasDomainMX(String emailAddress) {
		int pos = emailAddress.indexOf('@');
		if (pos == -1) {
			throw new IllegalArgumentException();
		}
		// Isolate the domain/machine name and get a list of mail exchangers
		String domain = emailAddress.substring(pos + 1);
		ArrayList<String> mxList = null;
		try {
			mxList = getMXList(domain);
		} catch (NamingException ex) {
			return false;
		}
		if (mxList.size() == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * Checks if this email address is a non-valid recipient email address.
	 * @param emailAddress : the address to check
	 * @return <code>true</code> if this address has been successfully denied.
	 */
	public static boolean isEmailInvalid(String emailAddress) {
		return checkEmailValidity(emailAddress) == ADDRESS_INVALID;
	}
	
	/**
	 * Checks if this email address is a valid recipient email address.
	 * @param emailAddress : the address to check
	 * @return the status of the address, one of the following :
	 * <ul>
	 * 	<li>{@link EmailAddressChecker#ADDRESS_VALID} if the address has been successfully checked;</li>
	 * 	<li>{@link EmailAddressChecker#ADDRESS_STATUS_UNKNOWN} if the address could not be checked;</li>
	 * 	<li>{@link EmailAddressChecker#ADDRESS_INVALID} if the address has been successfully denied.</li>
	 * </ul>
	 */
	public static int checkEmailValidity(String emailAddress) {
		EmailAddressChecker checker = new EmailAddressChecker();
		checker.emailAddress = emailAddress;
		return checker.checkValidity();
	}
	
	private int checkValidity() {

		int pos = emailAddress.indexOf('@');
		if (pos == -1) {
			throw new IllegalArgumentException();
		}

		// Isolate the domain/machine name and get a list of mail exchangers
		String domain = emailAddress.substring(pos + 1);
		ArrayList<String> mxList = null;
		try {
			mxList = getMXList(domain);
		} catch (NamingException ex) {
			return ADDRESS_INVALID;
		}

		if (mxList.size() == 0) {
			return ADDRESS_INVALID;
		}

		for (String mxAddress : mxList) {
			int status = ADDRESS_STATUS_UNKNOWN;
			try {
				Socket socket = new Socket();
				socket.connect(new InetSocketAddress(mxAddress, 25), SOCKET_TIMEOUT);
				SMTPProcessor processor = new SMTPProcessor(socket);
				status = processor.getAddressStatus();
			} catch (IOException e) {
				log.error("Could not connect to mx : " + mxAddress, e);
			}
			if (status == ADDRESS_VALID) {
				return ADDRESS_VALID;
			} else if (status == ADDRESS_INVALID) {
				return ADDRESS_INVALID;
			}
		}

		return ADDRESS_STATUS_UNKNOWN;

	}
	
	private static ArrayList<String> getMXList(String hostName) throws NamingException {
		
		// Perform a DNS lookup for MX records in the domain
		Hashtable<String, String> env = new Hashtable<String, String>();
		env.put("java.naming.factory.initial", "com.sun.jndi.dns.DnsContextFactory");
		DirContext ictx = new InitialDirContext( env );
		Attributes attrs = ictx.getAttributes( hostName, new String[] { "MX" });
		Attribute attr = attrs.get( "MX" );

		// if we don't have an MX record, try the machine itself
		if (( attr == null ) || ( attr.size() == 0 )) {
			attrs = ictx.getAttributes( hostName, new String[] { "A" });
			attr = attrs.get( "A" );
			if( attr == null ) 
				throw new NamingException("No match for name '" + hostName + "'");
		}

		ArrayList<String> mxList = new ArrayList<String>();
		
		boolean hasMX = "MX".equals(attr.getID());
		
		@SuppressWarnings("rawtypes")
		NamingEnumeration en = attr.getAll();
		while (en.hasMore()) {
			String x = (String) en.next();
			String[] f = x.split(" ");
			String mailhost;
			if (hasMX) {
				mailhost = f[1];
			} else {
				mailhost = f[0];
			}
			if (mailhost.length() > 0 && mailhost.endsWith(".")) {
				mailhost = mailhost.substring(0, mailhost.length() - 1);
			}
			mxList.add(mailhost);
		}

		return mxList;
	}
	
	
	private class SMTPProcessor {
		
		private Socket socket;
		private BufferedReader reader;
		private PrintWriter writer;
		
		public SMTPProcessor(Socket socket) {
			this.socket = socket;
		}
		
		private String readLine() throws IOException {
			String inputLine = reader.readLine();
			return inputLine;
		}
		
		private int readCode() throws IOException {
			String line = readLine();
			int code = -1;
			while (line != null) {
				try {
					code = Integer.valueOf(line.substring(0, 3));
				} catch (NumberFormatException e) {
					code = -1;
				}
				if (line.charAt(3) != '-') {
					break;
				}
				line = readLine();
			}
			return code;
		}
		
		private void write(String message) {
			writer.println(message);
			writer.flush();
		}
		
		private int processTransaction() throws IOException, SMTPProcessingException {
			
			int code;
			
			code = readCode();
			if (code != 220) {	
				if (code == 553 || code == 550) {
					return ADDRESS_STATUS_UNKNOWN;
				} else if (code == 421) {
					return ADDRESS_INVALID;	
				} else {
					throw new SMTPProcessingException("Server welcomed with code " + code);
				}
			}
			
			write("EHLO legalbox.com");
			code = readCode();
			if (code != 250 ) {
				write("EHLO legalbox.com");
				code = readCode();
				if (code != 250 ) {
					throw new SMTPProcessingException("Server responded to EHLO with code " + code);
				}
			}
			
			write("MAIL FROM: <contact@legalbox.com>");
			code = readCode();
			if (code != 250 ) {
				if (code == 550) {
					return ADDRESS_STATUS_UNKNOWN;
				} else {
					throw new SMTPProcessingException("Server responded to MAIL FROM with code " + code);
				}
			}
			
			write("RCPT TO: <" + emailAddress + ">");
			code = readCode();

			// Quit nicely
			write("RSET"); 
			readCode();
			write("QUIT");
			readCode();
			
			if (code == 250) {
				return ADDRESS_VALID;
			} else {
				return ADDRESS_INVALID;
			}
			
		}

		public int getAddressStatus() {
			
			try {
				socket.setSoTimeout(SOCKET_TIMEOUT);
				reader = new BufferedReader(new InputStreamReader(socket.getInputStream()));
				writer = new PrintWriter(socket.getOutputStream());	
				return processTransaction();
			} catch (Exception e) {
				log.warn("Error occured during SMTP transaction, cannot check email address validity: " + emailAddress, e);
				return ADDRESS_STATUS_UNKNOWN;
			} finally {
				try {
					if (reader != null) {
						reader.close();
					}
					if (writer != null) {
						writer.close();
					}
					socket.close();
				} catch (IOException e) {
					log.error("Failed to close connection !", e);
				}
			}

		}
		
	}

}
