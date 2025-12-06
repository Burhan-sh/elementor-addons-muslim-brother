# Google Sheets JSON Key Upload - Issue Fixed

## Problem Summary

You were experiencing the following error when uploading a Service Account JSON file:

```
Service Account credentials not found. Please upload a JSON key file or paste the JSON content.
```

**Root Cause:** The uploaded JSON file credentials were not being properly retrieved from WordPress media library when form submissions occurred.

## What Was Fixed

### 1. **AJAX Handler Update** (`includes/cf7-popup-ajax-handler.php`)
- Fixed the credential retrieval logic to properly handle uploaded JSON files
- Added proper file ID and widget ID validation
- Improved error messages with debug information
- Added comprehensive logging for troubleshooting

### 2. **JavaScript Console Logging** (`assets/js/cf7-popup-script.js`)
- Added debug logging to show when file data is being sent
- Added logging to display Google Sheets configuration
- Better error reporting in browser console

### 3. **Widget Instructions** (`widgets/cf7-popup-widget.php`)
- Improved setup instructions with direct links
- Added important warnings and tips
- Removed non-working upload button
- Clearer guidance on file upload process

## How to Test the Fix

### Step 1: Upload Your JSON File

1. **Open Elementor Editor** and edit your CF7 Popup widget
2. Go to **Google Sheets Integration** section
3. Enable **Google Sheets** toggle
4. Select **Service Account** as authentication method
5. Keep **Upload JSON File (Recommended)** selected
6. Click **Choose File** button
7. Select your JSON file: `my-cf7-integration-31e3099c308f.json`
8. Click **Upload Files** in WordPress media library
9. Click **Update** to save the widget settings

### Step 2: Verify Service Account Email

Your service account email from the JSON file:
```
cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com
```

### Step 3: Share Your Google Sheet

1. Open your Google Sheet
2. Click the **Share** button
3. Enter: `cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com`
4. Select **Editor** permission
5. Uncheck "Notify people" (since it's a service account)
6. Click **Share**

### Step 4: Configure Field Mapping

Add this JSON in the **Field Mapping** textarea:
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "your-phone": "Phone",
  "your-message": "Message"
}
```

Replace the left side (`your-name`, `your-email`, etc.) with your actual CF7 field names.

### Step 5: Test Submission

1. **View your page** (not in Elementor editor)
2. Open **browser console** (press F12, go to Console tab)
3. Fill out and submit the contact form
4. Watch the console for these messages:
   - ✅ "Data sent to Google Sheets successfully"
   - Or error messages with details

## Debugging Tips

### Check Browser Console (F12)

After form submission, you should see:
```
📊 Google Sheets Data: {enabled: true, authMethod: "service_account", ...}
📤 Sending uploaded file data: {fileId: 123, widgetId: "abc123"}
✅ Data sent to Google Sheets successfully
```

### Check WordPress Error Logs

Look for these log entries in `wp-content/debug.log`:
```
MRM CF7 Popup - Google Sheets Request:
Auth Method: service_account
Sheet ID: 1OtbFHlzlUFGlPEFCUEKskaaVMv4ZoGrvEcLOUS4amE8
Widget ID: abc123
File ID: 123
```

### Common Issues and Solutions

#### Issue 1: "Service Account credentials not found"

**Solutions:**
- Make sure you clicked **Update** after uploading the JSON file
- Try re-uploading the JSON file
- Check that the JSON file is valid (open it in a text editor)
- Verify the file was actually uploaded to WordPress media library

#### Issue 2: "Invalid Service Account credentials format"

**Solutions:**
- Ensure the JSON file contains all required fields:
  - `type`: should be "service_account"
  - `private_key`: RSA private key
  - `client_email`: service account email
  - `token_uri`: OAuth token URL
- Download a fresh JSON key from Google Cloud Console

#### Issue 3: "Failed to obtain access token"

**Solutions:**
- Check that Google Sheets API is enabled in your Google Cloud project
- Verify the service account has not been deleted
- Ensure the private key in JSON is not corrupted

#### Issue 4: "API request failed with status code: 403"

**Solutions:**
- **You haven't shared the Google Sheet** with the service account email
- Share the sheet with: `cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com`
- Give **Editor** permission (not Viewer)

#### Issue 5: "API request failed with status code: 404"

**Solutions:**
- Check that the **Sheet ID** is correct
- Verify the **Sheet Name/Tab** exists (default is "Sheet1")
- Make sure the Google Sheet hasn't been deleted

## File Upload Button Issue

**Original Problem:** The "Upload New File" button wasn't working.

**Why:** It was trying to click an element that doesn't exist in Elementor's dynamic interface.

**Solution:** Removed the non-functional button. Instead:
- Just click the **Choose File** button that Elementor provides
- This is the proper way to upload files in Elementor

## Security Notes

✅ **Your JSON file is stored securely:**
- Stored in `/wp-content/uploads/mrm-cf7-private/` directory
- Protected with `.htaccess` to prevent direct access
- Files are cleaned up after 30 days if no longer in use

✅ **Credentials are never exposed:**
- File is only read server-side during form submission
- Private keys never sent to browser/client
- AJAX requests are nonce-protected

## Next Steps: Changing Service Accounts

When you're ready to switch from your test account to production:

1. Create your production service account in Google Cloud Console
2. Download the new JSON key file
3. In Elementor widget, click **Choose File** again
4. Upload the new JSON file
5. Update your Google Sheet sharing:
   - Remove the old service account email
   - Share with the new service account email
6. Click **Update** to save changes

The old credentials will be automatically replaced.

## Support Resources

- [Google Cloud Service Account Setup](https://console.cloud.google.com/iam-admin/serviceaccounts)
- [Google Sheets API Documentation](https://developers.google.com/sheets/api)
- Check `wp-content/debug.log` for error details

## Testing Checklist

- [ ] JSON file uploaded successfully in Elementor
- [ ] Widget settings saved with Update button
- [ ] Google Sheet shared with service account email
- [ ] Service account has Editor permission
- [ ] Field mapping JSON is valid
- [ ] Sheet ID is correct
- [ ] Sheet Name/Tab exists
- [ ] Browser console shows no errors
- [ ] Form submission appears in Google Sheet
- [ ] Timestamp column is populated

---

**Date Fixed:** December 6, 2025
**Version:** 2.2.0
**Status:** ✅ Issue Resolved
