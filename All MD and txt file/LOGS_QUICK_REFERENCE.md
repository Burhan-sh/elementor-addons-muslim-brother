# Quick Logs Reference - Hindi Guide

## 🚀 Fast Debugging - 5 Minute Guide

### Sabse Pehle Ye Karo (Most Important!)

#### 1. Browser Console (सबसे जरूरी!)
```
Steps:
1. F12 press karo
2. Console tab
3. Form submit karo
4. Red errors dekho
```

**Success dikhe to:**
```
✅ Data sent to Google Sheets successfully
✅ CC email sent successfully
```

**Error dikhe to:**
```
❌ Failed to send data to Google Sheets: [reason]
❌ Google Sheets AJAX error: [error]
```

---

### Quick Error Solutions

#### Error: "API request failed with status code: 400"
```
Problem: API key ya Sheet ID galat
Fix:
1. Widget settings kholo
2. Sheet ID check karo (URL se copy karo)
3. API key check karo
4. Field mapping JSON validate karo
```

#### Error: "API request failed with status code: 403"
```
Problem: Sheet accessible nahi hai
Fix:
1. Google Sheet kholo
2. Share button → "Anyone with the link"
3. "Viewer" permission do
4. Save
```

#### Error: "Security check failed"
```
Problem: Page outdated hai
Fix:
1. Ctrl + F5 (hard refresh)
2. Cache clear karo
3. Dobara try karo
```

#### Error: "Rate Limit Exceeded"
```
Problem: Bahut zyada submissions
Fix:
1. 5 minutes wait karo
2. Ya cookies clear karo
```

---

## 📍 All Log Locations

### 1. JavaScript Console
**Location:** Browser Developer Tools → Console Tab  
**Shows:** Frontend errors, AJAX responses  
**Best For:** Immediate debugging

### 2. Network Tab
**Location:** Browser Developer Tools → Network Tab  
**Shows:** AJAX requests, API responses, status codes  
**Best For:** Detailed request/response analysis

### 3. WordPress Debug Log
**Location:** `/wp-content/debug.log`  
**Shows:** PHP errors, backend issues  
**Best For:** Server-side errors

### 4. Database Security Logs
**Location:** Database table `wp_mrm_cf7_popup_security_logs`  
**Shows:** Security incidents, blocked submissions  
**Best For:** Security issues, rate limiting

### 5. Server Error Log
**Location:** `/public_html/error_log` (cPanel)  
**Shows:** Critical PHP errors, fatal errors  
**Best For:** Server configuration issues

---

## 🔍 Step-by-Step Debugging Flow

```
START
  ↓
1. Form submit ho raha hai?
   NO → Check CF7 installation
   YES → Continue
  ↓
2. Browser console me error hai?
   YES → Fix that error first
   NO → Continue
  ↓
3. Network tab me 200 status hai?
   NO → Check error message in response
   YES → Continue
  ↓
4. Sheet me data nahi aa raha?
   → Check field mapping
   → Check column names
   → Check Sheet permissions
```

---

## 💻 Quick Commands

### Enable WordPress Debug
Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### View Debug Log (SSH)
```bash
tail -f wp-content/debug.log
```

### Check Security Logs (MySQL)
```sql
SELECT * FROM wp_mrm_cf7_popup_security_logs 
ORDER BY timestamp DESC 
LIMIT 20;
```

### Check Recent Errors (cPanel)
```
File Manager → public_html → error_log
(Last 20 lines dekho)
```

---

## 📊 Browser Console Commands

### Test AJAX Manually
```javascript
jQuery.ajax({
    url: mrmCF7PopupData.ajaxUrl,
    type: 'POST',
    data: {
        action: 'mrm_cf7_popup_google_sheets',
        nonce: mrmCF7PopupData.nonce,
        sheet_id: 'YOUR_SHEET_ID',
        sheet_name: 'Sheet1',
        api_key: 'YOUR_API_KEY',
        data: {
            'Name': 'Test User',
            'Email': 'test@example.com'
        }
    },
    success: function(response) {
        console.log('Success:', response);
    },
    error: function(xhr, status, error) {
        console.log('Error:', error);
    }
});
```

### Monitor CF7 Events
```javascript
// Console me paste karo
document.addEventListener('wpcf7mailsent', function(event) {
    console.log('✅ Form Sent Successfully:', event.detail);
}, false);

document.addEventListener('wpcf7invalid', function(event) {
    console.log('❌ Form Invalid:', event.detail);
}, false);
```

---

## 🎯 Common Problems Quick Fix

| Problem | Quick Check | Quick Fix |
|---------|-------------|-----------|
| Form submit nahi ho raha | CF7 plugin active? | Activate CF7 |
| Popup open nahi ho raha | Console error? | Check JS error |
| Sheet me data nahi ja raha | Network tab 200? | Check response |
| "Security check failed" | Page cached? | Ctrl + F5 |
| Rate limit error | 5 min me 5+ submit? | Wait 5 minutes |
| Column mismatch | Column names same? | Match exactly |

---

## 📱 Mobile Testing

### Debug on Mobile:
1. **Android Chrome:**
   - Desktop Chrome → More Tools → Remote Devices
   - USB se phone connect karo
   - Inspect karo

2. **iOS Safari:**
   - Mac Safari → Develop → iPhone ka naam
   - Console dekho

---

## 🛠️ Testing Checklist

### Basic Test (2 minutes):
```
□ CF7 form working without widget?
□ Browser console empty?
□ Network tab shows 200 status?
□ Sheet publicly accessible?
□ API key valid?
```

### Advanced Test (5 minutes):
```
□ Field mapping JSON valid? (jsonlint.com)
□ CF7 field names correct?
□ Sheet column names matching?
□ Debug log empty?
□ Security logs empty?
```

---

## 🎓 Pro Tips

### Tip 1: Test API Key Separately
```
Browser me kholo:
https://sheets.googleapis.com/v4/spreadsheets/SHEET_ID?key=API_KEY

Agar sheet data dikha = API key working ✅
Agar error = API key problem ❌
```

### Tip 2: Validate JSON Online
```
1. Copy field mapping
2. jsonlint.com pe paste karo
3. Validate karo
4. Errors fix karo
```

### Tip 3: Test with Minimal Setup
```
CF7 Form:
[text* your-name]
[email* your-email]

Sheet Columns:
Name | Email | Timestamp

Mapping:
{"your-name":"Name","your-email":"Email"}
```

### Tip 4: Use Incognito Mode
```
Testing time:
- Incognito window use karo
- Cache/cookies interfere nahi karenge
- Fresh test milega
```

---

## 📞 When to Ask for Help

Agar ye sab karne ke baad bhi nahi ho raha:

### Collect Ye Information:
1. ✅ Browser console screenshot
2. ✅ Network tab screenshot (request + response)
3. ✅ Debug log entries (last 50 lines)
4. ✅ Widget settings screenshot
5. ✅ CF7 form structure
6. ✅ Google Sheet screenshot (headers)

### Share Kaha:
- Support forum
- GitHub issues
- Developer email

---

## ⚡ Quick Diagnostic Script

### Browser Console me Run Karo:

```javascript
// MRM CF7 Popup Diagnostic
console.log('=== MRM CF7 Popup Diagnostics ===');
console.log('jQuery loaded:', typeof jQuery !== 'undefined' ? '✅' : '❌');
console.log('Elementor loaded:', typeof elementorFrontend !== 'undefined' ? '✅' : '❌');
console.log('CF7 loaded:', typeof wpcf7 !== 'undefined' ? '✅' : '❌');
console.log('AJAX URL:', typeof mrmCF7PopupData !== 'undefined' ? mrmCF7PopupData.ajaxUrl : '❌ Not defined');
console.log('Nonce:', typeof mrmCF7PopupData !== 'undefined' ? (mrmCF7PopupData.nonce ? '✅' : '❌') : '❌');

// Check widgets
var widgets = jQuery('.mrm-cf7-popup-wrapper').length;
console.log('CF7 Popup Widgets found:', widgets);

if (widgets > 0) {
    jQuery('.mrm-cf7-popup-wrapper').each(function(i) {
        var $modal = jQuery(this).find('.mrm-cf7-popup-modal');
        var config = $modal.data('popup-config');
        var gsData = $modal.data('google-sheets');
        
        console.log('--- Widget ' + (i+1) + ' ---');
        console.log('Config:', config);
        console.log('Google Sheets Enabled:', gsData && gsData.enabled ? '✅' : '❌');
        if (gsData && gsData.enabled) {
            console.log('Sheet ID:', gsData.sheetId ? '✅' : '❌');
            console.log('API Key:', gsData.apiKey ? '✅' : '❌');
            console.log('Field Mapping:', gsData.fieldMapping ? '✅' : '❌');
        }
    });
}

console.log('=== End Diagnostics ===');
```

---

## 🎬 Video Tutorial Steps

Agar video bana rahe ho:

### Part 1: Enable Logs (2 min)
1. Show wp-config.php edit
2. Show debug.log location
3. Show browser F12

### Part 2: Read Logs (3 min)
1. Submit form
2. Show console errors
3. Show network tab
4. Show debug.log

### Part 3: Fix Issues (5 min)
1. Identify error
2. Show solution
3. Re-test
4. Confirm success

---

**Remember:** 
- ✅ Console logs = First priority
- ✅ Network tab = Most detailed
- ✅ Debug log = Backend errors
- ✅ Security logs = Rate limits

**Happy Debugging! 🚀**
