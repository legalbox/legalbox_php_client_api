package lb.api;

import java.io.IOException;
import java.io.InputStream;
import java.util.Enumeration;
import java.util.Properties;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;

public class APIResourcesManager {
	
	private static Log log = LogFactory.getLog(APIResourcesManager.class);
	
	private static final String VERSION = "1.0.0";
	
	private static final String EMBEDDED_CONFIG_FILE_PATH = "/resources/config/clientAPIconfiguration.conf";
	
	private static Properties configProperties = null;

	public static String getVersion() {
		return VERSION;
	}
	
	public static InputStream getResourceAsStream(String path) {
		return APIResourcesManager.class.getResourceAsStream(path);
	}
	
	public static String getConfigurationProperty(String key) {
		String value = configProperties.getProperty(key);
		if (value == null) {
			log.error("Property not found : " + key);
		}
		return value;
	}
	
	/**
	 * Initializes the configuration using the default embedded configuration file.
	 */
	public static void initConfiguration() throws IOException {
		configProperties = new Properties();		
		configProperties.load(getResourceAsStream(EMBEDDED_CONFIG_FILE_PATH));	
	}
	
	/**
	 * Initializes the configuration using the given resource configuration file.
	 * If a value is not present in the given configuration, the default value will be used.
	 * @param configFilePath : the path of the resource configuration file to use
	 */
	public static void initConfiguration(Properties customConfig) throws IOException {
		initConfiguration();
		@SuppressWarnings("unchecked")
		Enumeration<String> customConfigNames = (Enumeration<String>) customConfig.propertyNames();
		String key;
		while (customConfigNames.hasMoreElements()) {
			key = customConfigNames.nextElement();
			configProperties.setProperty(key, customConfig.getProperty(key));
		}
	}
	
	public static String getTrustStorePassword() {
		return getConfigurationProperty("trustStorePassword");
	}
	
	public static String getRestfulScheme() {
		return getConfigurationProperty("protocol");
	}
	
	public static String getRestfulHostname() {
		return getConfigurationProperty("hostname");
	}
	
	public static int getRestfulPort() {
		return Integer.parseInt(getConfigurationProperty("port"));
	}
	
	public static String getSessionRestfulURL() {
		return getConfigurationProperty("sessionRestfulURL");
	}
	
	public static String getApplicationRestfulURL() {
		return getConfigurationProperty("applicationRestfulURL");
	}

	public static String getRegistrationRestfulURL() {
		return getConfigurationProperty("registrationRestfulURL");
	}

	public static String getUserGroupRestfulURL() {
		return getConfigurationProperty("userGroupRestfulURL");
	}
	
	public static String getFileUploadRestfulURL() {
		return getConfigurationProperty("fileUploadRestfulURL");
	}
	
	public static String getFileDownloadRestfulURL() {
		return getConfigurationProperty("fileDownloadRestfulURL");
	}
	
	public static InputStream getSSLTrustStoreInputStream() {
		return getResourceAsStream(getConfigurationProperty("trustStoreFilePath"));
	}
	
	public static boolean isHTTPSprotocol() {
		return "https".equals(getRestfulScheme());
	}

}
