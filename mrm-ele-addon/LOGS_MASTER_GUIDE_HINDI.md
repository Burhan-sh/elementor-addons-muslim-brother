# 🎯 Complete Logs Guide - Hindi/Hinglish

## Ye Guide Kiske Liye Hai?

Agar aapki **Google Sheets me entry nahi ho rahi** aur aapko **logs dekhne hain** to yeh guide step-by-step batayegi ki **kaha se kya dekhe**.

---

## 📌 TL;DR (Too Long; Didn't Read)

**Sabse Important:**
1. **Browser console** me F12 press karo
2. **Console tab** kholo
3. **Form submit** karo
4. **Red error** messages dekho
5. Error ke basis pe fix karo

**99% problems yahi solve ho jayenge!** ✅

---

## 🚀 Complete Step-by-Step Guide

### STEP 1: Browser Console Check (START HERE!)

**Kya Karna Hai:**

1. **Form wala page kholo** (jaha widget hai)
2. **F12 press karo** (ya Right Click → Inspect)
3. **Console tab** pe click karo
4. **Clear button** (🚫) pe click karo (old logs clear karne ke liye)
5. **Form submit karo**
6. **Console me messages dekho**

**Success Dikhe To:**
```
✅ Data sent to Google Sheets successfully
```
**Matlab:** Sab kuch theek hai! Ab Google Sheet check karo.

**Error Dikhe To:**

#### Error 1: `Failed to send data to Google Sheets: API request failed with status code: 400`
```
Problem: API key ya Sheet ID galat hai
        Ya field mapping JSON galat hai

Solution:
1. Widget settings kholo (Elementor editor me)
2. Google Sheets section me jao
3. Sheet ID check karo (URL se dobara copy karo)
4. API key check karo
5. Field Mapping ko jsonlint.com pe validate karo
6. Save karke dobara try karo
```

#### Error 2: `Failed to send data to Google Sheets: API request failed with status code: 403`
```
Problem: Google Sheet accessible nahi hai
        Permission problem hai

Solution:
1. Google Sheet kholo
2. Top right me "Share" button pe click karo
3. "Anyone with the link" select karo
4. Permission: "Viewer" ya "Editor" rakho
5. "Done" pe click karo
6. Dobara form submit karke try karo
```

#### Error 3: `Failed to send data to Google Sheets: API request failed with status code: 401`
```
Problem: API key invalid hai ya expire ho gaya hai

Solution:
1. Google Cloud Console pe jao: https://console.cloud.google.com
2. APIs & Services → Credentials
3. Purana API key ke bagal me edit icon pe click karo
4. Check karo key enabled hai ya nahi
5. Agar disabled hai to new API key banao
6. New key widget me paste karo
7. Save karke try karo
```

#### Error 4: `Failed to send data to Google Sheets: API request failed with status code: 404`
```
Problem: Sheet ID galat hai
        Sheet exist nahi karta

Solution:
1. Google Sheet ka URL dekho:
   https://docs.google.com/spreadsheets/d/1ABcD3fGhI.../edit
                                            ↑↑↑↑↑↑↑
                                         Yeh ID hai

2. Is ID ko copy karo (bina /edit ke)
3. Widget settings me paste karo
4. Save karke try karo
```

#### Error 5: `Security check failed`
```
Problem: Page cached hai ya nonce expire ho gaya

Solution:
1. Ctrl + F5 press karo (hard refresh)
2. Dobara form submit karo
3. Agar phir bhi problem ho to cookies clear karo
```

#### Error 6: `Too many submissions. Please try again later.`
```
Problem: Rate limit exceed ho gaya (5 submissions per 5 minutes)

Solution:
1. 5 minutes wait karo
2. Ya cookies clear karo
3. Phir dobara submit karo
```

---

### STEP 2: Network Tab Check (Detailed Debugging)

Agar console me error clear nahi dikha ya zyada detail chahiye:

**Kya Karna Hai:**

1. **F12 press karo**
2. **Network tab** pe click karo
3. **Clear button** (🚫) pe click karo
4. **Form submit karo**
5. **Filter box** me type karo: `mrm_cf7_popup_google_sheets`
6. **Request pe click karo**

**Kya Dekhna Hai:**

#### A. Headers Tab:
```
Status Code: 200 (Success) ✅
Status Code: 400, 403, 404 (Error) ❌
```

#### B. Payload Tab (Request):
```json
{
  "action": "mrm_cf7_popup_google_sheets",
  "sheet_id": "1ABcD...",        ← Check: Empty to nahi?
  "sheet_name": "Sheet1",
  "api_key": "AIza...",          ← Check: Empty to nahi?
  "data": {
    "Name": "Test User",         ← Check: Data aa raha hai?
    "Email": "test@example.com"
  }
}
```

**Check Karo:**
- ✅ sheet_id present hai (not empty)
- ✅ api_key present hai (not empty)
- ✅ data object me values aa rahi hain
- ✅ Field names correct hain

#### C. Response Tab:
```json
// Success Response
{
  "success": true,
  "data": {
    "message": "Data sent to Google Sheets successfully"
  }
}

// Error Response
{
  "success": false,
  "data": {
    "message": "Missing required fields"  ← Asli error yaha hai!
  }
}
```

**Response me error message** actual problem batata hai!

---

### STEP 3: WordPress Debug Log Check (Backend Errors)

Agar console aur network tab me kuch clear nahi dikha:

**Debug Mode Enable Karo:**

1. **wp-config.php file edit karo** (FTP ya cPanel File Manager se)
2. **"That's all, stop editing!"** line se pehle ye lines add karo:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

3. **Save karo**

**Log File Dekho:**

**Location:** `/wp-content/debug.log`

**Kaise Access Kare:**

**Option A: cPanel**
```
1. cPanel login
2. File Manager open
3. public_html → wp-content folder
4. debug.log file right click → View
   (ya download karke notepad me kholo)
```

**Option B: FTP**
```
1. FileZilla (ya koi FTP client) se connect
2. /wp-content/ folder
3. debug.log download karo
4. Notepad++ me kholo
```

**Kya Dikhega:**

```
[06-Dec-2024 10:30:45 UTC] PHP Warning: Undefined array key "sheet_id"
[06-Dec-2024 10:31:20 UTC] MRM CF7 Popup - Google Sheets Error: cURL error 28: Connection timeout
[06-Dec-2024 10:32:00 UTC] PHP Fatal error: Call to undefined function wpcf7()
```

**Common Errors:**

| Error | Matlab | Solution |
|-------|--------|----------|
| `cURL error 28: Timeout` | Server ka connection slow hai | Hosting provider ko batao |
| `Call to undefined function wpcf7()` | CF7 plugin inactive hai | CF7 activate karo |
| `Undefined array key` | Koi setting missing hai | Widget settings complete karo |

---

### STEP 4: Database Security Logs (Advanced)

Agar form submit hi nahi ho raha ya security error aa raha hai:

**Database Me Dekho:**

**Access Database:**

1. **cPanel → phpMyAdmin**
2. **Left side se apna database select karo**
3. **Table dhundho:** `wp_mrm_cf7_popup_security_logs`
   (Agar aapka prefix different hai to `yourprefix_mrm_cf7_popup_security_logs`)

**SQL Query:**

```sql
SELECT * FROM wp_mrm_cf7_popup_security_logs 
ORDER BY timestamp DESC 
LIMIT 20;
```

**Kya Dikhega:**

```
┌────┬─────────────────────┬───────────────────────┬─────────────┬──────────────┐
│ id │ timestamp           │ type                  │ details     │ ip_address   │
├────┼─────────────────────┼───────────────────────┼─────────────┼──────────────┤
│ 1  │ 2024-12-06 10:30:45 │ Rate Limit Exceeded   │ 192.168.1.1 │ 192.168.1.1  │
│ 2  │ 2024-12-06 10:25:30 │ SQL Injection Attempt │ SELECT *... │ 192.168.1.1  │
└────┴─────────────────────┴───────────────────────┴─────────────┴──────────────┘
```

**Agar yaha entries hain to:**

1. **Rate Limit Exceeded:** 5 min me 5+ submissions kiye → Wait karo
2. **SQL Injection Attempt:** Form data me suspicious content tha → Form fields check karo
3. **XSS Attempt:** JavaScript code submit kiya → Safe data bhejo

---

## 🔧 Common Problems & Quick Fixes

### Problem 1: Sheet me entry nahi ho rahi

**Check Sequence:**
```
1. Console error hai?
   YES → Error fix karo (upar dekho)
   NO  → Continue

2. Network tab me status 200 hai?
   NO  → Response me error message dekho
   YES → Continue

3. Sheet publicly accessible hai?
   NO  → Share settings change karo
   YES → Continue

4. Field mapping correct hai?
   NO  → jsonlint.com pe validate karo
   YES → Debug log check karo
```

### Problem 2: Form submit nahi ho raha

**Quick Fixes:**
```
□ CF7 plugin active hai?
□ Form properly configured hai?
□ Required fields fill kiye?
□ Console me JS error hai?
```

### Problem 3: "Security check failed"

**Quick Fix:**
```
Ctrl + F5 (hard refresh page)
```

### Problem 4: Rate limit error

**Quick Fix:**
```
5 minutes wait karo
Ya cookies clear karo
```

---

## 📁 All Logs Locations Summary

| Log Name | Location | Access Method | Shows |
|----------|----------|---------------|-------|
| **JavaScript Console** | Browser DevTools | F12 → Console | Frontend errors, AJAX status |
| **Network Tab** | Browser DevTools | F12 → Network | Request/Response details |
| **WordPress Debug** | `/wp-content/debug.log` | FTP/cPanel | PHP errors |
| **Security Logs** | Database table | phpMyAdmin | Security incidents |
| **Server Error** | `/error_log` | cPanel | Server errors |

---

## 🎬 Video Tutorial Steps

Agar video banani hai:

### Part 1: Console Logs (3 min)
1. Browser console kholo
2. Form submit karo
3. Errors dikhaao
4. Common errors explain karo

### Part 2: Network Tab (4 min)
1. Network tab kholo
2. Request details dikhaao
3. Response check karo
4. Status codes explain karo

### Part 3: Debug Log (3 min)
1. wp-config edit karo
2. debug.log location dikhaao
3. Errors read karo
4. Solutions batao

---

## 📞 Support ke Liye Information

Agar help chahiye to ye collect karo:

### Required Screenshots:
```
□ Browser Console (errors visible)
□ Network Tab → Request Payload
□ Network Tab → Response
□ Widget Settings → Google Sheets section
□ Google Sheet → Column headers
□ CF7 Form → Edit screen (field names visible)
```

### Required Text Info:
```
□ Error message (exact copy-paste)
□ WordPress version
□ CF7 version
□ Elementor version
□ PHP version (Hosting info)
```

---

## 💡 Pro Tips

1. **Console first!** - 90% problems yahi dikh jate hain
2. **Incognito mode** - Testing time pe cache nahi aata
3. **JSON validator** - Hamesha jsonlint.com pe check karo
4. **Simple test** - Pehle simple 2-field form test karo
5. **Column names** - Exactly match hone chahiye (case-sensitive!)
6. **Hard refresh** - Ctrl+F5 agar kuch change nahi dikh raha
7. **Wait patience** - Rate limit ho to 5 min wait karo
8. **Documentation** - In files ko padho:
   - `TROUBLESHOOTING_GUIDE.md` (Detailed solutions)
   - `LOGS_QUICK_REFERENCE.md` (Fast tips)
   - `DEBUG_LOCATIONS_MAP.md` (Visual guide)
   - `QUICK_DEBUG_CARD.txt` (Print reference)

---

## ✅ Success Checklist

Sab kuch sahi hai agar:

```
✅ Console: "Data sent to Google Sheets successfully"
✅ Network: Status code 200
✅ Response: {"success": true}
✅ Sheet: New row add ho gaya with timestamp
✅ No errors in debug.log
✅ No entries in security logs (blocked submissions)
```

---

## 🚦 Traffic Light System

```
🟢 GREEN (All Good):
   • Console: Success message
   • Network: 200 status
   • Sheet: Row added
   → Congratulations! Sab kuch theek hai! 🎉

🟡 YELLOW (Minor Issues):
   • Console: Warning (but data sent)
   • Network: 200 but slow
   • Sheet: Row added but some fields empty
   → Check field mapping

🔴 RED (Critical Issues):
   • Console: Error messages
   • Network: 400/403/404/500
   • Sheet: No row added
   → Fix error immediately!
```

---

## 🎯 Final Words

**Remember:**
- Logs darwaye nahi! Wo help karne ke liye hain
- Console sabse important hai - pehle wahi dekho
- Ek-ek step check karo, skip mat karo
- Documentation padho - sab kuch detail me likha hai
- Problems common hain - panic mat karo

**Har error ka solution hai!** 💪

---

## 📚 Related Files

Is guide ke saath ye files bhi padho:

1. **CF7_POPUP_DOCUMENTATION.md** - Complete widget documentation
2. **README_CF7_POPUP.md** - Quick start guide (Hindi)
3. **TROUBLESHOOTING_GUIDE.md** - Detailed troubleshooting
4. **LOGS_QUICK_REFERENCE.md** - Fast debugging tips
5. **DEBUG_LOCATIONS_MAP.md** - Visual debugging guide
6. **QUICK_DEBUG_CARD.txt** - Print reference card

---

**Version:** 1.0.0  
**Last Updated:** December 6, 2024  
**Plugin:** MRM Ele Addon  
**Widget:** CF7 Popup Widget  
**Support:** Check documentation files

---

**Happy Debugging!** 🚀

Agar koi doubt ho to documentation files me detail me likha hai. Sab kuch step-by-step explained hai! ✅
