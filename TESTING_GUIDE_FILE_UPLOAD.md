# Testing Guide - File Upload to Google Sheets

## 🧪 Pre-Test Checklist

Before testing, make sure:
- ✅ Contact Form 7 plugin is installed and activated
- ✅ Your Contact Form has a file upload field
- ✅ Google Sheets integration is configured in widget settings
- ✅ Field mapping includes the file field

## 📝 Step-by-Step Testing

### Step 1: Prepare Contact Form 7

Create or edit a form with file upload:

```
[text* your-name placeholder "Your Name"]
[email* your-email placeholder "Your Email"]
[file your-file limit:10mb filetypes:jpg|png|pdf|doc|docx]
[submit "Submit"]
```

### Step 2: Configure Widget

1. Go to Elementor Editor
2. Add/Edit MRM CF7 Popup Widget
3. **Google Sheets Tab:**
   - Enable Google Sheets: **Yes**
   - Authentication Method: Choose your method (Service Account recommended)
   - Sheet ID: Your Google Sheet ID
   - Sheet Name: Sheet1 (or your sheet name)

4. **Field Mapping:**
   ```
   CF7 Field Name → Google Sheet Column
   ────────────────────────────────────
   your-name      → Name
   your-email     → Email
   your-file      → File URL
   ```

### Step 3: Open Browser Console

1. Open the page with your form
2. Press **F12** (or right-click → Inspect)
3. Go to **Console** tab
4. Keep it open to see logs

### Step 4: Submit Form with File

1. Fill in the form fields
2. Select a file (image/document)
3. Click Submit
4. Watch the console for logs

### Expected Console Output:

```javascript
📤 Starting file uploads...
📁 Uploading file: test-document.pdf for field: your-file
✅ File uploaded: test-document.pdf → http://yoursite.com/wp-content/uploads/2025/12/test-document.pdf
📨 Submitting form after file uploads...
📊 Google Sheets Data: {enabled: true, authMethod: "service_account", ...}
📁 Uploaded Files: {your-file: "http://yoursite.com/wp-content/uploads/2025/12/test-document.pdf"}
📋 Form Data from CF7: [{name: "your-name", value: "John"}, ...]
📎 Using uploaded file URL for your-file : http://yoursite.com/wp-content/uploads/2025/12/test-document.pdf
📝 Using form value for your-name : John
📝 Using form value for your-email : john@example.com
📤 Sending to Google Sheets: {action: "mrm_cf7_popup_google_sheets", ...}
✅ Data sent to Google Sheets successfully
Response: {spreadsheetId: "...", updates: {...}}
```

### Step 5: Verify Results

#### A. WordPress Media Library
1. Go to **Media** → **Library** in WordPress admin
2. You should see the uploaded file
3. Click on it to verify URL

#### B. Google Sheets
1. Open your Google Sheet
2. Check the latest row
3. You should see:
   - Name: John
   - Email: john@example.com
   - File URL: http://yoursite.com/wp-content/uploads/2025/12/test-document.pdf
   - Timestamp: 2025-12-06T...

#### C. Click File URL in Google Sheets
1. Click the file URL in Google Sheets
2. It should open the uploaded file
3. Verify it's the correct file

## ✅ Success Criteria

Your test is successful if:
- ✅ No errors in browser console
- ✅ File appears in WordPress Media Library
- ✅ Form data appears in Google Sheets
- ✅ File URL is clickable and working in Google Sheets
- ✅ All form fields are correctly mapped
- ✅ Timestamp is recorded

## ❌ Common Issues & Solutions

### Issue 1: "Illegal invocation" Error
**Symptom:** Console shows jQuery error  
**Solution:** Make sure you're using the updated code (this fix!)

### Issue 2: File uploads but no Google Sheets data
**Symptom:** File in media library, but Google Sheets empty  
**Solutions:**
- Check Google Sheet ID is correct
- Verify Service Account has access
- Check field mapping is correct
- Look for errors in console

### Issue 3: "Security check failed"
**Symptom:** AJAX returns nonce error  
**Solutions:**
- Clear browser cache
- Refresh the page
- Check if you're logged in (if using wp_ajax)

### Issue 4: File doesn't upload
**Symptom:** Upload fails before Google Sheets  
**Solutions:**
- Check file type is allowed
- Verify file size is under 10MB
- Check WordPress upload permissions
- Look at Network tab in DevTools

### Issue 5: Google Sheets shows empty File URL
**Symptom:** Other fields OK, but File URL empty  
**Solutions:**
- Verify field name in mapping matches CF7 field name exactly
- Check console logs for "Using uploaded file URL" message
- Make sure file actually uploaded (check media library)

## 🔍 Debug Mode

If you need more detailed logs:

### 1. Enable WordPress Debug
Edit `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### 2. Check Error Log
Location: `/wp-content/debug.log`

Look for entries like:
```
MRM CF7 Popup - File uploaded successfully: http://...
MRM CF7 Popup - Google Sheets Request:
Auth Method: service_account
Sheet ID: 1ABC...XYZ
Data received: Array ( [Name] => John [Email] => john@example.com [File URL] => http://... )
```

### 3. Network Tab
In DevTools:
1. Go to **Network** tab
2. Filter: **XHR**
3. Look for requests to:
   - `mrm_cf7_popup_upload_file`
   - `wpcf7_submit`
   - `mrm_cf7_popup_google_sheets`

4. Click each request and check:
   - Status: Should be 200
   - Response: Should show success

## 📊 Test Scenarios

### Scenario 1: Single File Upload
- Upload: 1 image (JPG)
- Expected: Image URL in Google Sheets

### Scenario 2: PDF Document
- Upload: 1 PDF document
- Expected: PDF URL in Google Sheets

### Scenario 3: Multiple Fields
- Upload: 1 file + name + email + message
- Expected: All fields in Google Sheets including file URL

### Scenario 4: Without File
- Don't select any file
- Fill other fields
- Expected: All fields in Google Sheets, file column empty

### Scenario 5: Large File (Within Limit)
- Upload: 8MB file
- Expected: Success

### Scenario 6: Large File (Over Limit)
- Upload: 12MB file
- Expected: Error message "File size exceeds 10MB limit"

## 🎯 Performance Check

Monitor these metrics:
- ⏱️ File upload time: < 5 seconds for typical files
- ⏱️ Form submission: < 2 seconds
- ⏱️ Google Sheets update: < 3 seconds
- 📊 Total time: < 10 seconds

## 🔒 Security Testing

### Test 1: Invalid File Type
- Try uploading .exe or .php file
- Expected: Error "File type not allowed"

### Test 2: Malicious File Name
- Upload file with name: `../../../test.jpg`
- Expected: Sanitized filename in media library

### Test 3: Large File Attack
- Try uploading 50MB file
- Expected: Error "File size exceeds limit"

## 📱 Cross-Browser Testing

Test on:
- ✅ Chrome (Desktop)
- ✅ Firefox (Desktop)
- ✅ Safari (Mac)
- ✅ Edge (Windows)
- ✅ Chrome (Mobile)
- ✅ Safari (iOS)

## 🎉 Final Verification

Before considering test complete:
1. ✅ Test at least 5 form submissions
2. ✅ Test with different file types
3. ✅ Test with and without files
4. ✅ Test on different browsers
5. ✅ Check Google Sheets has all entries
6. ✅ Verify all file URLs are working
7. ✅ No errors in console
8. ✅ No errors in WordPress debug log

## 📝 Test Report Template

```
Test Date: ______________
Tester: ______________
Browser: ______________
WordPress Version: ______________
PHP Version: ______________

Test Results:
- File Upload: ✅ Pass / ❌ Fail
- Google Sheets Integration: ✅ Pass / ❌ Fail
- Field Mapping: ✅ Pass / ❌ Fail
- File URLs: ✅ Pass / ❌ Fail
- Error Handling: ✅ Pass / ❌ Fail

Notes:
_________________________________
_________________________________
```

---

**Ready to test? Let's go! 🚀**
