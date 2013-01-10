package lb.api.crypto;

import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.IOException;
import java.security.Key;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.util.Enumeration;

import org.apache.commons.io.FileUtils;

public class CryptoUtil {
	
	public static KeyStore openKeyStore(File keyStoreFile, String keyStorePassword) throws KeyStoreException, 
	NoSuchAlgorithmException, CertificateException, IOException {
		KeyStore keyStore = KeyStore.getInstance("PKCS12");
		ByteArrayInputStream inputStream = new ByteArrayInputStream(
				FileUtils.readFileToByteArray(keyStoreFile));
		keyStore.load(inputStream, keyStorePassword.toCharArray());
		return keyStore;
	}
	
	public static PrivateKey getFirstPrivateKeyInKeyStore(KeyStore keyStore, String keyPassword) throws KeyStoreException, 
	UnrecoverableKeyException, NoSuchAlgorithmException {
		Enumeration<String> aliases = keyStore.aliases();
		while (aliases.hasMoreElements()) {
			String alias = aliases.nextElement();
			Key key = keyStore.getKey(alias, keyPassword.toCharArray());
			if (key != null) {
				return (PrivateKey) key;
			}
		}
		return null;
	}

}
