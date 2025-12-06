# 🗺️ Debug Locations Map - Visual Guide

## Where to Find What (Hindi)

```
┌─────────────────────────────────────────────────────────────┐
│                    DEBUGGING ROADMAP                         │
└─────────────────────────────────────────────────────────────┘

         Form Submit Kiya
                ↓
    ┌───────────────────────┐
    │ Kya CF7 Success       │
    │ Message Aa Raha Hai?  │
    └───────────────────────┘
            ↓ NO
    ┌───────────────────────────────────┐
    │ CF7 ISSUE HAI                     │
    │ Check:                            │
    │ • CF7 plugin active?              │
    │ • Form properly configured?        │
    │ • Required fields filled?          │
    └───────────────────────────────────┘
            ↓ YES
    ┌───────────────────────┐
    │ Browser Console       │
    │ Check Karo (F12)      │
    └───────────────────────┘
            ↓
    ┌─────────────────────────────────────┐
    │ Console Me Kya Dikha?               │
    ├─────────────────────────────────────┤
    │ ✅ "Data sent successfully"         │
    │    → SUCCESS! Sheet check karo      │
    │                                     │
    │ ❌ "API request failed: 400"        │
    │    → API key/Sheet ID galat         │
    │                                     │
    │ ❌ "API request failed: 403"        │
    │    → Sheet permissions check        │
    │                                     │
    │ ❌ "Security check failed"          │
    │    → Page refresh karo              │
    │                                     │
    │ ❌ "Rate limit exceeded"            │
    │    → 5 min wait karo                │
    └─────────────────────────────────────┘
            ↓
    ┌───────────────────────┐
    │ Network Tab           │
    │ Detailed Check        │
    └───────────────────────┘
            ↓
    ┌─────────────────────────────────────┐
    │ Status Code?                        │
    ├─────────────────────────────────────┤
    │ 200 → Success                       │
    │ 400 → Bad Request (data issue)      │
    │ 401 → API key invalid               │
    │ 403 → Permission denied             │
    │ 404 → Sheet not found               │
    │ 500 → Server error                  │
    └─────────────────────────────────────┘
```

---

## 📍 LOG LOCATIONS TABLE

| Log Type | Location | Access Method | Shows | Priority |
|----------|----------|---------------|-------|----------|
| **JavaScript Console** | Browser DevTools → Console | F12 | Frontend errors, AJAX status | 🔴 HIGH |
| **Network Tab** | Browser DevTools → Network | F12 | Request/Response details | 🔴 HIGH |
| **WordPress Debug** | `/wp-content/debug.log` | FTP/cPanel | PHP errors, backend issues | 🟡 MEDIUM |
| **Security Logs** | Database table | phpMyAdmin | Blocked submissions, attacks | 🟡 MEDIUM |
| **Server Error** | `/error_log` | cPanel/SSH | Critical server errors | 🟢 LOW |

---

## 🎯 ERROR TYPE → LOG LOCATION MAP

```
┌────────────────────────────────────────────────────────┐
│  Error Type              │  Check This Log             │
├────────────────────────────────────────────────────────┤
│  Form submit nahi ho     │  CF7 Status + Console       │
│  Popup nahi khul raha    │  Console (JS errors)        │
│  Sheet me data nahi      │  Console + Network Tab      │
│  API error               │  Network Tab Response       │
│  Security block          │  Database Security Logs     │
│  Rate limit              │  Console + Security Logs    │
│  PHP error               │  WordPress Debug Log        │
│  Server crash            │  Server Error Log           │
└────────────────────────────────────────────────────────┘
```

---

## 🔍 BROWSER CONSOLE - DETAILED

### Location:
```
Browser → Right Click → Inspect
        OR
Press F12
        OR
Ctrl + Shift + I (Windows)
Cmd + Option + I (Mac)
```

### What You'll See:

#### ✅ SUCCESS LOGS:
```javascript
Data sent to Google Sheets successfully
CC email sent successfully
```

#### ❌ ERROR LOGS:
```javascript
Failed to send data to Google Sheets: API request failed with status code: 403
Google Sheets AJAX error: Network Error
Security check failed
```

### Console Commands to Run:

#### Check If Widget Loaded:
```javascript
jQuery('.mrm-cf7-popup-wrapper').length
// Should return: 1 or more (number of widgets on page)
```

#### Check Widget Config:
```javascript
jQuery('.mrm-cf7-popup-modal').data('google-sheets')
// Should return: {enabled: true, sheetId: "...", ...}
```

#### Check AJAX Data:
```javascript
console.log(mrmCF7PopupData)
// Should show: {ajaxUrl: "...", nonce: "..."}
```

---

## 🌐 NETWORK TAB - DETAILED

### Location:
```
Browser F12 → Network Tab
```

### Steps:
```
1. Network tab kholo
2. "Clear" button (🚫) pe click karo
3. Form submit karo
4. Filter me "mrm_cf7_popup" search karo
5. Request pe click karo
```

### What to Check:

#### 📤 REQUEST (Payload Tab):
```json
{
  "action": "mrm_cf7_popup_google_sheets",
  "nonce": "abc123xyz",
  "sheet_id": "1ABcD3fGhI...",
  "sheet_name": "Sheet1",
  "api_key": "AIzaSy...",
  "data": {
    "Name": "Test User",
    "Email": "test@example.com"
  },
  "widget_id": "abc123"
}
```

**Verify:**
- ✅ sheet_id present and correct
- ✅ api_key present (not empty)
- ✅ data object has values
- ✅ nonce present

#### 📥 RESPONSE (Response Tab):

**Success:**
```json
{
  "success": true,
  "data": {
    "message": "Data sent to Google Sheets successfully",
    "data": {
      "spreadsheetId": "...",
      "updates": {...}
    }
  }
}
```

**Error:**
```json
{
  "success": false,
  "data": {
    "message": "Missing required fields"
  }
}
```

#### 📊 STATUS CODE:

| Code | Meaning | Location of Problem |
|------|---------|---------------------|
| 200 | Success | No problem ✅ |
| 400 | Bad Request | Request data format |
| 401 | Unauthorized | API key invalid |
| 403 | Forbidden | Sheet permissions |
| 404 | Not Found | Sheet ID wrong |
| 500 | Server Error | PHP code issue |

---

## 📝 WORDPRESS DEBUG LOG

### Location:
```
/wp-content/debug.log
```

### How to Access:

#### Method 1: cPanel File Manager
```
1. cPanel login
2. File Manager open
3. public_html → wp-content
4. debug.log file download
```

#### Method 2: FTP
```
1. FTP client (FileZilla) se connect
2. /wp-content/ folder
3. debug.log download
```

#### Method 3: SSH
```bash
cd /path/to/wordpress
tail -f wp-content/debug.log
```

### What You'll See:

#### PHP Errors:
```
[06-Dec-2024 10:30:45 UTC] PHP Warning: Undefined array key "sheet_id" in /path/to/cf7-popup-ajax-handler.php on line 35

[06-Dec-2024 10:31:20 UTC] PHP Fatal error: Call to undefined function wpcf7_contact_form() in /path/to/cf7-popup-widget.php on line 82
```

#### Plugin Logs:
```
[06-Dec-2024 10:32:15 UTC] MRM CF7 Popup - Google Sheets Error: cURL error 28: Connection timeout after 30000 milliseconds

[06-Dec-2024 10:33:00 UTC] MRM CF7 Popup Security: {"timestamp":"2024-12-06 10:33:00","type":"SQL Injection Attempt","details":"SELECT * FROM users"}
```

---

## 🗄️ DATABASE SECURITY LOGS

### Location:
```
Database Table: wp_mrm_cf7_popup_security_logs
```

### How to Access:

#### Method 1: phpMyAdmin
```
1. cPanel → phpMyAdmin
2. Select your database
3. Find table: wp_mrm_cf7_popup_security_logs
4. Click "Browse"
```

#### Method 2: SQL Query
```sql
-- Latest 20 logs
SELECT * FROM wp_mrm_cf7_popup_security_logs 
ORDER BY timestamp DESC 
LIMIT 20;

-- Security incidents only
SELECT * FROM wp_mrm_cf7_popup_security_logs 
WHERE type LIKE '%Injection%' 
   OR type LIKE '%XSS%'
ORDER BY timestamp DESC;

-- Rate limit logs
SELECT * FROM wp_mrm_cf7_popup_security_logs 
WHERE type = 'Rate Limit Exceeded' 
ORDER BY timestamp DESC 
LIMIT 10;

-- Logs from specific IP
SELECT * FROM wp_mrm_cf7_popup_security_logs 
WHERE ip_address = '192.168.1.100' 
ORDER BY timestamp DESC;
```

### Table Structure:
```
┌────────────────────────────────────────────────────┐
│ Column      │ Example Value                        │
├────────────────────────────────────────────────────┤
│ id          │ 1                                    │
│ timestamp   │ 2024-12-06 10:30:45                  │
│ type        │ Rate Limit Exceeded                  │
│ details     │ 192.168.1.100                        │
│ ip_address  │ 192.168.1.100                        │
│ user_agent  │ Mozilla/5.0 (Windows...)             │
└────────────────────────────────────────────────────┘
```

### Common Log Types:
- `SQL Injection Attempt`
- `XSS Attempt`
- `Command Injection Attempt`
- `Path Traversal Attempt`
- `Rate Limit Exceeded`
- `Invalid File Upload Attempt`
- `Malicious File Upload Attempt`

---

## 🎛️ QUICK ACCESS GUIDE

### 1️⃣ I NEED TO CHECK: "Is my form working?"
**Go to:** Browser Console  
**Look for:** CF7 success message

### 2️⃣ I NEED TO CHECK: "Why sheet me data nahi ja raha?"
**Go to:** Browser Console + Network Tab  
**Look for:** Error messages, status codes

### 3️⃣ I NEED TO CHECK: "Is API key valid?"
**Go to:** Network Tab → Response  
**Look for:** 401 or 403 status

### 4️⃣ I NEED TO CHECK: "PHP errors hai kya?"
**Go to:** WordPress Debug Log  
**Look for:** PHP warnings/errors

### 5️⃣ I NEED TO CHECK: "Am I blocked?"
**Go to:** Database Security Logs  
**Look for:** Rate limit entries

---

## 📞 SUPPORT INFORMATION COLLECTION

Jab help mangni ho, ye screenshots/info collect karo:

### Required Information:
```
□ Browser Console screenshot
□ Network Tab → Request Payload screenshot
□ Network Tab → Response screenshot
□ Widget Settings screenshot (Google Sheets section)
□ CF7 Form structure (field names)
□ Google Sheet screenshot (column headers)
□ Debug log (last 50 lines)
```

### How to Screenshot:

**Windows:**
- Snipping Tool (Win + Shift + S)
- Print Screen

**Mac:**
- Cmd + Shift + 4

**Browser:**
- Right click → "Screenshot" (Firefox)
- DevTools → 3 dots → Capture screenshot

---

## 🚀 QUICK DIAGNOSTIC SCRIPT

Save this as bookmark in browser:

```javascript
javascript:(function(){console.clear();console.log('=== MRM CF7 POPUP DIAGNOSTICS ===');console.log('Date:',new Date().toLocaleString());console.log('URL:',window.location.href);console.log('\n--- Dependencies ---');console.log('jQuery:',typeof jQuery!=='undefined'?'✅':'❌');console.log('Elementor:',typeof elementorFrontend!=='undefined'?'✅':'❌');console.log('CF7:',typeof wpcf7!=='undefined'?'✅':'❌');console.log('\n--- Widget Status ---');var widgets=jQuery('.mrm-cf7-popup-wrapper').length;console.log('Widgets found:',widgets);if(widgets>0){jQuery('.mrm-cf7-popup-wrapper').each(function(i){var $modal=jQuery(this).find('.mrm-cf7-popup-modal');var gsData=$modal.data('google-sheets');console.log('\nWidget '+(i+1)+':');console.log('  Google Sheets:',gsData&&gsData.enabled?'✅ Enabled':'❌ Disabled');if(gsData&&gsData.enabled){console.log('  Sheet ID:',gsData.sheetId?'✅':'❌ Missing');console.log('  API Key:',gsData.apiKey?'✅':'❌ Missing');}});}console.log('\n=== END DIAGNOSTICS ===');})();
```

**Usage:**
1. Copy above code
2. Create new bookmark
3. Paste in URL field
4. Name it "CF7 Debug"
5. Click bookmark to run diagnostic

---

## 📚 RELATED DOCUMENTATION

- `TROUBLESHOOTING_GUIDE.md` - Detailed problem solutions
- `LOGS_QUICK_REFERENCE.md` - Fast debugging tips
- `CF7_POPUP_DOCUMENTATION.md` - Full widget documentation
- `README_CF7_POPUP.md` - Hindi setup guide

---

**Pro Tip:** Browser Console hi 90% problems solve kar deta hai!

**Remember:** Logs read karna seekh liya to debugging bahut easy ho jayegi! 🎯
