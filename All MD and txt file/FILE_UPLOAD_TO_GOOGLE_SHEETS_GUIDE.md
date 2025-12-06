# File Upload to Google Sheets Integration Guide

## Overview
This guide explains how the MRM CF7 Popup Widget handles file uploads and sends file URLs to Google Sheets.

## How It Works

### 1. File Upload Process
When a user submits a Contact Form 7 with file attachments:

1. **JavaScript intercepts the form submission**
   - Detects if there are any file input fields with files selected
   - Prevents default form submission temporarily

2. **Files are uploaded to WordPress Media Library**
   - Each file is uploaded via AJAX to the server
   - Files are validated for type and size
   - Uploaded files are stored in `/wp-content/uploads/` directory

3. **File URLs are generated**
   - WordPress creates a permanent URL for each uploaded file
   - Example: `https://yoursite.com/wp-content/uploads/2025/11/document.pdf`

4. **Form submission continues**
   - After all files are uploaded, the CF7 form is submitted normally
   - File URLs replace the file data in the form

5. **Data is sent to Google Sheets**
   - File URLs are included in the mapped data
   - Google Sheets receives the URL, not the actual file

## Configuration

### Step 1: Contact Form 7 Setup
Create a file upload field in your CF7 form:

```
<label> Adhar Card </label>
[file file-225 filetypes:audio/*|video/*|image/* limit:10mb]
```

### Step 2: Field Mapping
In the MRM CF7 Popup Widget settings, map the file field to a Google Sheet column:

```json
{
  "your-name": "Name",
  "your-email": "Email",
  "tel-835": "Phone",
  "file-225": "Adhar card",
  "your-message": "Description"
}
```

### Step 3: Google Sheets
In your Google Sheet, the file column will receive the URL:

| Name | Email | Phone | Adhar card | Description |
|------|-------|-------|------------|-------------|
| John | john@example.com | 1234567890 | https://yoursite.com/wp-content/uploads/2025/11/adhar.jpg | Sample |

## Supported File Types

### Images
- JPEG/JPG
- PNG
- GIF
- WebP

### Documents
- PDF
- Microsoft Word (.doc, .docx)
- Microsoft Excel (.xls, .xlsx)

### Audio
- MP3
- WAV
- OGG

### Video
- MP4
- MPEG
- MOV
- AVI

## File Size Limits
- **Maximum file size**: 10MB per file
- Can be adjusted in `cf7-popup-ajax-handler.php` line ~400

## Security Features

1. **File Type Validation**
   - Both MIME type and file extension are checked
   - Only allowed file types can be uploaded

2. **File Size Validation**
   - Files exceeding the limit are rejected

3. **Nonce Verification**
   - All AJAX requests are protected with WordPress nonces

4. **File Sanitization**
   - File names are sanitized before upload
   - Prevents directory traversal attacks

## Troubleshooting

### Issue: Files not uploading
**Solution**: Check browser console (F12) for error messages

### Issue: File URL not appearing in Google Sheets
**Solution**: 
1. Check field mapping - ensure file field name matches exactly
2. Check console for upload success messages
3. Verify file type is allowed

### Issue: Upload fails with "File type not allowed"
**Solution**: Add the file type to the allowed types list in `cf7-popup-ajax-handler.php`

### Issue: Form not submitting after file upload
**Solution**: Check console for JavaScript errors

## Technical Details

### File Upload Flow
```
User selects file → Form submit → JS intercepts → Upload to WP Media 
→ Get URL → Submit CF7 form → Send to Google Sheets
```

### Key Files
1. **cf7-popup-ajax-handler.php** - Server-side file upload handler
2. **cf7-popup-script.js** - Client-side file upload logic
3. **cf7-popup-widget.php** - Widget configuration

### AJAX Endpoints
- `mrm_cf7_popup_upload_file` - Handles file uploads
- `mrm_cf7_popup_google_sheets` - Sends data to Google Sheets

## Example Field Mapping with Multiple Files

```json
{
  "your-name": "Name",
  "your-email": "Email",
  "file-id-proof": "ID Proof",
  "file-photo": "Photograph",
  "file-resume": "Resume",
  "your-message": "Message"
}
```

## Best Practices

1. **Use descriptive field names** in CF7
2. **Set appropriate file size limits** in CF7 form
3. **Specify allowed file types** in CF7 field
4. **Test with different file types** before going live
5. **Monitor WordPress media library** storage space

## Support

For issues or questions:
1. Check browser console for errors
2. Check WordPress error log
3. Enable WordPress debugging: `define('WP_DEBUG', true);`
4. Check the logs at `/wp-content/debug.log`

---

**Last Updated**: December 2025
**Version**: 1.0
