package lb.api.beans;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;

import org.apache.commons.io.IOUtils;
import org.apache.http.entity.mime.content.ByteArrayBody;
import org.apache.http.entity.mime.content.ContentBody;

public class DraftAttachment {
	
	private byte[] attachmentBytes;
	private int index = -1;
	private String filename;
	private String base64Signature = null;
	
	private DraftAttachment() {}
	
	public static DraftAttachment createAttachment(byte[] attachmentBytes, String fileName) throws IOException {
		DraftAttachment attachment = new DraftAttachment();
		attachment.attachmentBytes = attachmentBytes;
		attachment.filename = fileName;
		return attachment;
	}
	
	public static DraftAttachment createAttachment(InputStream inputStream, String fileName) throws IOException {
		return createAttachment(IOUtils.toByteArray(inputStream), fileName);
	}
	
	public static DraftAttachment createAttachment(File path) throws IOException {
		return createAttachment(
				IOUtils.toByteArray(new FileInputStream(path)), 
				path.getName());
	}
	
	public byte[] getAttachmentBytes() {
		return attachmentBytes;
	}
	
	public String getBase64Signature() {
		return base64Signature;
	}
	
	public int getIndex() {
		return index;
	}
	
	public String getFilename() {
		return filename;
	}
	
	public ContentBody getHttpBody() {
		return new ByteArrayBody(attachmentBytes, filename);
	}

	public boolean isSigned() {
		return base64Signature != null;
	}
	
	public void setIndex(int index) {
		this.index = index;
	}
	
	public void setFilename(String filename) {
		this.filename = filename;
	}
	
	public void setBase64Signature(String base64Signature) {
		this.base64Signature = base64Signature;
	}
	

}
