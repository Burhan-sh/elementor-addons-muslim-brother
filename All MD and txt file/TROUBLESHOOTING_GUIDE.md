# Google Sheets Integration - Troubleshooting Guide

## Problem: Sheet me Entry Nahi Ho Rahi

### Quick Checklist ✅

- [ ] Contact Form 7 plugin active hai
- [ ] Form properly submit ho raha hai (CF7 success message aa raha hai)
- [ ] Google Sheets Integration enabled hai widget settings me
- [ ] Sheet ID correct hai
- [ ] API Key valid hai
- [ ] Sheet publicly accessible hai
- [ ] Field mapping JSON valid hai
- [ ] Browser console me error nahi hai

---

## Step-by-Step Debugging

### 1️⃣ Browser Console Logs (PRIMARY)

**Kaise Check Kare:**
```
1. F12 press karo (Developer Tools)
2. Console tab pe jao
3. Form submit karo
4. Console me message dekho
```

**Success Message:**
```
Data sent to Google Sheets successfully
```

**Error Messages:**

#### Error: "API request failed with status code: 400"
**Reason:** API key ya request format galat hai  
**Solution:**
- API key dobara check karo
- Field mapping JSON validate karo (JSON validator use karo)
- Sheet name exactly match karna chahiye

#### Error: "API request failed with status code: 401"
**Reason:** API key invalid ya expired  
**Solution:**
- Google Cloud Console me jao
- New API key create karo
- Purana key ko replace karo

#### Error: "API request failed with status code: 403"
**Reason:** Permission denied  
**Solution:**
- Google Sheet open karo
- Share button pe click karo
- "Anyone with the link" select karo
- "Viewer" permission do
- Save karo

#### Error: "API request failed with status code: 404"
**Reason:** Sheet ID galat hai  
**Solution:**
- Sheet URL se ID dobara copy karo
- Format: `https://docs.google.com/spreadsheets/d/SHEET_ID_HERE/edit`
- Middle wala part copy karo

#### Error: "Failed to send data to Google Sheets"
**Reason:** Generic error, network issue  
**Solution:**
- Network tab check karo (detailed response dekho)
- Server logs check karo
- API quota check karo (Google Cloud Console)

---

### 2️⃣ Network Tab (DETAILED DEBUGGING)

**Steps:**
```
1. F12 press karo
2. Network tab pe jao
3. Form submit karo
4. Filter me "mrm_cf7_popup_google_sheets" search karo
5. Request pe click karo
```

**Check Karne Wali Cheezein:**

#### A. Request Payload
```json
{
  "action": "mrm_cf7_popup_google_sheets",
  "nonce": "xyz123...",
  "sheet_id": "1ABcD...",
  "sheet_name": "Sheet1",
  "api_key": "AIza...",
  "data": {
    "Name": "John Doe",
    "Email": "john@example.com"
  }
}
```

**Verify:**
- ✅ sheet_id empty to nahi hai
- ✅ api_key empty to nahi hai
- ✅ data object me values aa rahi hain

#### B. Response
```json
{
  "success": false,
  "data": {
    "message": "API request failed with status code: 403"
  }
}
```

**Agar yaha detailed error hai, to wo asli problem hai!**

---

### 3️⃣ WordPress Debug Log

**Enable Debug Mode:**

Edit `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

**Log File:** `/wp-content/debug.log`

**Kya Dekhe:**
```
[06-Dec-2024 10:30:45] MRM CF7 Popup - Google Sheets Error: cURL error 28: Connection timeout
[06-Dec-2024 10:31:20] PHP Warning: Invalid argument supplied
```

---

### 4️⃣ Database Security Logs

**Check Table:** `wp_mrm_cf7_popup_security_logs`

**SQL Query:**
```sql
SELECT * FROM wp_mrm_cf7_popup_security_logs 
WHERE type LIKE '%Injection%' 
ORDER BY timestamp DESC;
```

**Agar yaha entries hain:**
- Form data ko security filter ne block kiya hai
- Form fields me suspicious content hai
- Rate limit exceed ho gaya hai (5 submissions per 5 minutes)

---

## Common Issues & Solutions

### Issue 1: "Security check failed"
**Reason:** Nonce validation fail  
**Solution:** 
- Page refresh karo
- Cache clear karo
- Cookie enable hai check karo

### Issue 2: "Missing required fields"
**Reason:** Sheet ID ya API key empty  
**Solution:**
- Widget settings me dobara jao
- Fields properly fill karo
- Save karo

### Issue 3: Form Data Nahi Ja Raha
**Reason:** Field mapping galat hai  
**Solution:**

**Wrong ❌:**
```json
{
  your-name: "Name",
  your-email: "Email"
}
```

**Correct ✅:**
```json
{
  "your-name": "Name",
  "your-email": "Email"
}
```

- Keys aur values dono me double quotes use karo
- Commas sahi jagah ho
- Last item ke baad comma nahi hona chahiye

### Issue 4: Rate Limit Exceeded
**Reason:** 5 minutes me 5+ submissions  
**Solution:**
- 5 minutes wait karo
- Ya cookies clear karke try karo
- Testing ke liye rate limit temporarily disable kar sakte ho

### Issue 5: Column Mismatch
**Reason:** Google Sheet me column name aur mapping me name match nahi ho raha  
**Solution:**

**Example:**
Sheet me column: `Full Name`  
Mapping me value: `Name` ❌

**Correct:**
```json
{
  "your-name": "Full Name"
}
```

Column names **exactly match** hone chahiye (case-sensitive)!

---

## Testing Checklist

### Pre-Flight Check:

1. **Basic CF7 Form Test:**
   - Google Sheets integration ko **disable** karo
   - Form submit karo
   - CF7 success message aa raha hai? ✅

2. **API Key Test:**
   - Browser me ye URL kholo:
   ```
   https://sheets.googleapis.com/v4/spreadsheets/YOUR_SHEET_ID?key=YOUR_API_KEY
   ```
   - Agar error aa raha hai, API key invalid hai

3. **Sheet Access Test:**
   - Sheet ka link browser me incognito mode me kholo
   - Agar "Access Denied" aa raha hai, permission galat hai

4. **Field Mapping Test:**
   - JSON validator use karo: https://jsonlint.com/
   - Mapping JSON paste karo
   - "Valid JSON" hona chahiye

---

## Advanced Debugging

### Enable Extended Logging

`cf7-popup-ajax-handler.php` me line 65 ke baad add karo:

```php
error_log('MRM CF7 Google Sheets - Sheet ID: ' . $sheet_id);
error_log('MRM CF7 Google Sheets - API Key: ' . substr($api_key, 0, 10) . '...');
error_log('MRM CF7 Google Sheets - Data: ' . print_r($sanitized_data, true));
```

Phir form submit karo aur `debug.log` dekho.

### Check Google Sheets API Response

Network tab me Response dekho:

**Google API Error Response:**
```json
{
  "error": {
    "code": 403,
    "message": "The caller does not have permission",
    "status": "PERMISSION_DENIED"
  }
}
```

**Common Google API Errors:**

| Code | Message | Solution |
|------|---------|----------|
| 400 | Bad Request | Request format check karo |
| 401 | Unauthorized | API key invalid |
| 403 | Permission Denied | Sheet sharing setting check karo |
| 404 | Not Found | Sheet ID galat hai |
| 429 | Rate Limit | Google API quota exceed, wait karo |

---

## Contact Form 7 Integration Check

### CF7 Events Console Logging

Browser console me ye paste karo:

```javascript
document.addEventListener('wpcf7mailsent', function(event) {
    console.log('CF7 Form Sent:', event.detail);
}, false);

document.addEventListener('wpcf7invalid', function(event) {
    console.log('CF7 Form Invalid:', event.detail);
}, false);

document.addEventListener('wpcf7mailfailed', function(event) {
    console.log('CF7 Mail Failed:', event.detail);
}, false);
```

Phir form submit karo - console me detailed CF7 status dikhega.

---

## Still Not Working?

### Last Resort Debugging:

1. **Disable Other Plugins:**
   - Temporarily saare plugins disable karo (CF7 aur Elementor chhod ke)
   - Test karo
   - One by one enable karo aur dekhna kaunsa conflict kar raha hai

2. **Test with Default Form:**
   - Ek simple CF7 form banao (sirf name aur email)
   - Widget me wo form select karo
   - Sheet me bhi sirf 2 columns rakho: Name | Email
   - Simple mapping use karo
   - Test karo

3. **Check Server Requirements:**
   - PHP version 7.0+ hai?
   - cURL enabled hai?
   - `allow_url_fopen` enabled hai?
   - Outgoing connections allowed hain?

4. **Contact Hosting Support:**
   - Agar sab kuch sahi hai but kaam nahi kar raha
   - To hosting provider se pucho:
     - "Are outgoing HTTPS connections to googleapis.com allowed?"
     - "Is wp_remote_post function working?"

---

## Success Verification

**Agar sab kuch sahi hai to:**

1. ✅ Browser console me: "Data sent to Google Sheets successfully"
2. ✅ Network tab me status: 200
3. ✅ Google Sheet me new row add ho gayi
4. ✅ Timestamp automatic add ho gaya

---

## Need Help?

Agar abhi bhi problem hai, to ye information collect karo:

1. Browser console screenshot
2. Network tab ke request/response screenshot
3. Debug log entries
4. Widget settings screenshot
5. Google Sheet column names
6. CF7 form field names

Yeh saari details ke saath help maang sakte ho.

---

**Last Updated:** December 6, 2024  
**Version:** 1.0.0
