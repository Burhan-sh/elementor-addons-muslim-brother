# 📤 Service Account File Upload - Setup Guide

## 🎯 Overview

अब आप **Service Account JSON file को directly Elementor में upload** कर सकते हैं!

**Features:**
- ✅ Direct file upload in Elementor widget
- ✅ Automatic secure storage (`/wp-content/uploads/mrm-cf7-private/`)
- ✅ Protected with `.htaccess` (no direct access)
- ✅ Easy to change anytime from Elementor
- ✅ No manual file management needed

---

## 🚀 Quick Setup (2 Minutes)

### Step 1: Create Service Account (Google Cloud Console)

यदि आपने already नहीं बनाया है:

1. https://console.cloud.google.com खोलें
2. Project select करें: "My CF7 Integration"
3. APIs & Services > Credentials
4. Create Credentials > Service Account
5. Name: `cf7-google-sheets`
6. Create and Continue
7. Done क्लिक करें

### Step 2: Download JSON Key File

1. Service Accounts list में अपना account खोलें
2. **Keys** tab > **Add Key** > **Create New Key**
3. Type: **JSON** select करें
4. **Create** क्लिक करें
5. File download हो जाएगी (e.g., `my-cf7-integration-xxxxx.json`)

⚠️ **Important:** यह file safe रखें! यह आपका credential है।

### Step 3: Share Google Sheet

1. अपनी Google Sheet खोलें:
   ```
   https://docs.google.com/spreadsheets/d/1OtbFHlzlUFGlPEFCUEKskaaVMv4ZoGrvEcLOUS4amE8/edit
   ```

2. **Share** button क्लिक करें

3. JSON file खोलें और **`client_email`** copy करें:
   ```json
   {
     "client_email": "cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com",
     ...
   }
   ```

4. यह email **Google Sheet में paste** करें

5. Permission: **Editor** select करें

6. **Send** क्लिक करें (notification का option uncheck कर सकते हैं)

✅ Done! Sheet अब Service Account के साथ shared है।

### Step 4: Upload File in Elementor

अब WordPress में:

1. **Elementor > Edit Page** जहाँ CF7 Popup widget है

2. **Widget Settings** खोलें

3. **Google Sheets Integration** section:

```
┌─────────────────────────────────────────────────────────────┐
│ Google Sheets Integration                                   │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ☑️ Enable Google Sheets: YES                                │
│                                                             │
│ Authentication Method: Service Account (Recommended) ▼      │
│                                                             │
│ Service Account Input: Upload JSON File (Recommended) ▼    │
│                                                             │
│ ┌─────────────────────────────────────────────────────┐   │
│ │ Upload Service Account JSON                         │   │
│ │                                                     │   │
│ │ [📁 Choose File] or [Drag & Drop]                  │   │
│ │                                                     │   │
│ │ Upload your Service Account JSON key file.         │   │
│ │ It will be stored securely.                        │   │
│ └─────────────────────────────────────────────────────┘   │
│                                                             │
│ Google Sheet ID: 1OtbFHlzlUFGlPEFCUEKskaaVMv4ZoGrvEcLOUS4amE8
│                                                             │
│ Sheet Name/Tab: Sheet1                                      │
│                                                             │
│ Field Mapping (JSON):                                       │
│ ┌─────────────────────────────────────────────────────┐   │
│ │ {                                                   │   │
│ │   "your-name": "Name",                              │   │
│ │   "your-email": "Email",                            │   │
│ │   "your-phone": "Phone",                            │   │
│ │   "your-message": "Message"                         │   │
│ │ }                                                   │   │
│ └─────────────────────────────────────────────────────┘   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

4. **[Choose File]** button पर click करें

5. Downloaded JSON file select करें

6. Upload होने के बाद success message दिखेगा:
   ```
   ✅ File Uploaded Successfully!
   my-cf7-integration-xxxxx.json
   Stored securely. You can change it anytime.
   ```

7. **Update** button क्लिक करें (page save करें)

✅ **Done!** Setup complete है।

---

## 🧪 Testing

### Test Form Submission:

1. अपनी website खोलें जहाँ form है

2. Test data भरें:
   ```
   Name: Test User
   Email: test@example.com  
   Phone: 9876543210
   Message: Testing Google Sheets integration with file upload
   ```

3. Submit करें

4. **F12** press करें (Browser Console)

5. देखें:
   ```javascript
   ✅ Data sent to Google Sheets successfully
   Response: {
     spreadsheetId: "...",
     updates: {
       updatedRange: "Sheet1!A2:E2",
       updatedRows: 1
     }
   }
   ```

6. **Google Sheet** check करें - new row add हुई होनी चाहिए!

---

## 🔄 Change Service Account (भविष्य में)

कल अगर आपको Service Account change करना हो:

1. Elementor widget settings खोलें

2. **"Upload New File"** button पर click करें
   (या directly file selector में new file choose करें)

3. New JSON file upload करें

4. **Update** save करें

✅ **Done!** Old credentials automatically replace हो जाएंगे।

**बिल्कुल आसान!** कोई manual file management नहीं।

---

## 🔐 Security Features

### Automatic Security:

1. **Secure Folder:**
   ```
   /wp-content/uploads/mrm-cf7-private/
   ```
   - Automatically created
   - Protected with `.htaccess`
   - Direct access blocked

2. **File Protection:**
   ```apache
   # .htaccess content (auto-generated)
   Order Deny,Allow
   Deny from all
   ```

3. **Filename:**
   ```
   service-account-{widget-id}.json
   ```
   - Unique per widget
   - Non-guessable

4. **Permissions:**
   ```
   chmod 0600 (read/write owner only)
   ```

### Manual Verification (Optional):

Check security:
```bash
# Try to access file directly in browser
https://yoursite.com/wp-content/uploads/mrm-cf7-private/service-account-xxxxx.json

# Should show: 403 Forbidden ✅
```

---

## 📁 File Storage Details

### Where Files are Stored:

```
WordPress Installation
└── wp-content/
    └── uploads/
        └── mrm-cf7-private/           ← Secure folder
            ├── .htaccess              ← Access protection
            ├── index.php              ← Directory listing protection
            └── service-account-{widget-id}.json  ← Your credentials
```

### Database Storage:

File path stored in WordPress options:
```php
Option name: mrm_sa_path_{widget_id}
Option value: /full/path/to/service-account-{widget-id}.json
```

### Cleanup:

Old unused files automatically deleted after 30 days.

---

## 🔧 Troubleshooting

### ❌ File Upload Failed

**Problem:** File upload button not working

**Solutions:**
1. Check file size (must be < 2MB)
2. Verify file extension is `.json`
3. Ensure file contains valid JSON

### ❌ Permission Denied

**Problem:** "Permission denied" error

**Solutions:**
1. Check WordPress upload folder permissions:
   ```bash
   chmod 755 /wp-content/uploads/
   ```
2. Ensure PHP has write access
3. Check server disk space

### ❌ File Not Found

**Problem:** "Service Account credentials not found"

**Solutions:**
1. Re-upload the JSON file
2. Clear WordPress cache
3. Save widget settings again

### ❌ Invalid Credentials

**Problem:** "Invalid Service Account credentials format"

**Solutions:**
1. Verify JSON file is from Google Cloud Console
2. Check file is not corrupted
3. Download fresh JSON key from Google Cloud

### ❌ 403 Forbidden Error (Google Sheets API)

**Problem:** API returns 403

**Solutions:**
1. **Verify sheet is shared** with Service Account email
2. Check **Editor permission** is given
3. Ensure **Google Sheets API** is enabled in Cloud Console

---

## 💡 Best Practices

### 1. Backup Credentials

**Do:**
- ✅ Keep original JSON file in safe location (not on server)
- ✅ Store in password manager
- ✅ Keep offline backup

**Don't:**
- ❌ Commit JSON files to Git
- ❌ Share JSON files via email
- ❌ Store in publicly accessible locations

### 2. Regular Updates

- Review Service Account permissions quarterly
- Rotate keys if compromised
- Delete old unused Service Accounts

### 3. Monitoring

- Check Google Cloud Console for API usage
- Monitor for suspicious activity
- Enable Google Cloud audit logs

---

## 🎯 Comparison: File Upload vs Manual Methods

| Feature | File Upload | Paste JSON | File Path |
|---------|------------|-----------|-----------|
| **Ease of Use** | ⭐⭐⭐⭐⭐ Easy | ⭐⭐⭐ Medium | ⭐⭐ Hard |
| **Security** | 🔒 High | 🔒 Medium | 🔒 High |
| **Change Ease** | ✅ Very Easy | ✅ Easy | ❌ Hard |
| **Recommended** | ✅ Yes | ⚠️ Alternative | ❌ No |

### Why File Upload is Best:

1. **No FTP/SSH needed** - Direct from Elementor
2. **Automatic security** - Folder and permissions handled
3. **Easy to change** - Just upload new file
4. **No code editing** - Pure UI-based
5. **Backup-friendly** - Original file kept safe offline

---

## 📊 Visual Flow

```
┌─────────────────────────────────────────────────────────────┐
│  1. Download JSON from Google Cloud Console                 │
└─────────────────┬───────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────────────────────────┐
│  2. Upload in Elementor Widget                              │
│     - Choose file                                           │
│     - Upload button                                         │
└─────────────────┬───────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────────────────────────┐
│  3. Automatic Processing                                    │
│     - Copy to secure folder                                 │
│     - Set permissions (0600)                                │
│     - Store path in database                                │
│     - Create .htaccess protection                           │
└─────────────────┬───────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────────────────────────┐
│  4. Form Submission                                         │
│     - Retrieve credentials from secure location             │
│     - Generate JWT token                                    │
│     - Get OAuth access token                                │
│     - Send data to Google Sheets                            │
└─────────────────┬───────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────────────────────────┐
│  ✅ Success! Data in Google Sheet                           │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎉 Advantages Summary

### For You (Site Owner):

✅ **No Technical Skills Needed**
- No FTP access required
- No server file management
- No code editing

✅ **Easy Management**
- Change from Elementor anytime
- Visual feedback on upload
- Clear error messages

✅ **Secure by Default**
- Automatic folder protection
- File permissions handled
- Access control built-in

### For Future Maintenance:

✅ **Simple Updates**
- Upload new file when needed
- Old file auto-replaced
- No manual cleanup

✅ **Multiple Widgets**
- Each widget has own credentials
- No conflicts
- Independent management

---

## 📞 Support

### If You Need Help:

1. **Check Console:**
   - F12 > Console tab
   - Look for error messages

2. **Verify Setup:**
   - File uploaded successfully?
   - Sheet shared with Service Account?
   - Editor permission given?

3. **Test Connection:**
   - Submit test form
   - Check browser console
   - Verify data in sheet

### Common Success Indicators:

✅ File upload shows success message  
✅ Widget saves without errors  
✅ Console shows "Data sent successfully"  
✅ New row appears in Google Sheet  

---

## 🎯 Final Checklist

Setup Complete? Check all:

- [ ] Service Account created in Google Cloud Console
- [ ] JSON key file downloaded
- [ ] Google Sheet shared with Service Account email
- [ ] Editor permission given to Service Account
- [ ] JSON file uploaded in Elementor widget
- [ ] Success message visible after upload
- [ ] Sheet ID configured correctly
- [ ] Field mapping set properly
- [ ] Widget settings saved
- [ ] Test form submitted successfully
- [ ] Data visible in Google Sheet
- [ ] No errors in browser console

---

## 🚀 You're All Set!

अब आपका setup **100% complete** है!

**Key Points to Remember:**
1. ✅ File automatically secure storage में save होती है
2. ✅ भविष्य में बदलना बहुत easy है
3. ✅ कोई manual file management नहीं
4. ✅ सब कुछ Elementor से manage होता है

**Next Time You Need to Change:**
1. Open Elementor
2. Upload new JSON file
3. Save
4. Done! 🎉

**Enjoy your automated Google Sheets integration!** 😊
