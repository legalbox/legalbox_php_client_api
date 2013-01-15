package lb.api.crypto;

import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.Signature;
import java.security.SignatureException;

public class SignatureUtil {
	
	public static byte[] signData(byte[] data, String algorithm, PrivateKey privateKey) throws InvalidKeyException, 
	NoSuchAlgorithmException, SignatureException {
		
		Signature signature = Signature.getInstance(algorithm);
		signature.initSign(privateKey);
		signature.update(data);
		return signature.sign();
		
	}

}
