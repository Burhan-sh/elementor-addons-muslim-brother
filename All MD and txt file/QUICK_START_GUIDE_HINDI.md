# CF7 Popup - Google Sheets Integration Quick Start Guide

## 🎯 तुरंत शुरू करें (3 Methods)

आपके पास **3 तरीके** हैं Google Sheets में data भेजने के लिए:

| Method | Setup Time | Security | Best For |
|--------|-----------|----------|----------|
| **Service Account** ⭐ | 15 min | High | Production websites |
| **Apps Script Webhook** | 5 min | Medium | Quick testing |
| **API Key** | 2 min | Read-only | Testing only (Write नहीं होगा) |

---

## Method 1: Service Account (Recommended ⭐)

### Step-by-Step Setup:

#### 1. Google Cloud Console Setup

```
🔗 https://console.cloud.google.com
```

1. **Project select/create करें**
2. **APIs Enable करें:**
   - APIs & Services > Library
   - "Google Sheets API" search करके Enable
   - "Google Drive API" भी Enable (optional)

3. **Service Account बनाएं:**
   ```
   APIs & Services > Credentials > Create Credentials > Service Account
   
   Name: cf7-google-sheets
   Role: (skip या Editor)
   ```

4. **JSON Key Download करें:**
   ```
   Service Account खोलें > Keys tab > Add Key > Create New Key
   Type: JSON
   ```
   
   ⚠️ **File को safe जगह save करें!**

#### 2. Service Account Email Copy करें

JSON file में यह होगा:
```json
{
  "client_email": "cf7-google-sheets@your-project.iam.gserviceaccount.com",
  ...
}
```

**`client_email` को copy करें**

#### 3. Google Sheet Share करें

1. अपनी Sheet खोलें:
   ```
   https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit
   ```

2. **Share button** क्लिक करें

3. Service Account email paste करें और **Editor** permission दें

#### 4. WordPress में Setup करें

**Elementor में CF7 Popup widget खोलें:**

```
Google Sheets Integration section:
├─ Enable Google Sheets: Yes
├─ Authentication Method: Service Account
├─ Service Account Input: Paste JSON Content
├─ Service Account JSON: [पूरा JSON paste करें]
├─ Google Sheet ID: [आपकी sheet ID]
├─ Sheet Name: Sheet1
└─ Field Mapping: {"your-name":"Name","your-email":"Email",...}
```

✅ **Done! Test करें**

---

## Method 2: Apps Script Webhook (Quick & Easy)

### Setup (5 Minutes):

#### 1. Google Sheet में Script खोलें

```
Extensions > Apps Script
```

#### 2. Code Paste करें

File खोलें: `/workspace/mrm-ele-addon/GOOGLE_APPS_SCRIPT_WEBHOOK.gs`

सारा code copy करके Apps Script में paste करें।

#### 3. Deploy करें

```
1. Save करें (Ctrl + S)
2. Deploy > New Deployment
3. Type: Web app
4. Settings:
   - Execute as: Me
   - Who has access: Anyone
5. Deploy क्लिक करें
6. URL copy करें
```

URL ऐसा होगा:
```
https://script.google.com/macros/s/AKfycbx.../exec
```

#### 4. Widget में URL paste करें

```
Elementor Widget:
├─ Enable Google Sheets: Yes
├─ Authentication Method: Apps Script Webhook
├─ Webhook URL: [आपका Web App URL]
└─ Field Mapping: {"your-name":"Name","your-email":"Email",...}
```

✅ **Done! Test करें**

---

## Method 3: API Key (Testing Only - Write नहीं होगा)

⚠️ **Warning:** API Key से सिर्फ Read हो सकता है, Write **नहीं** होगा।

यह method **401 error** देगा write के लिए।

### Setup:

1. Google Cloud Console > Credentials
2. Create Credentials > API Key
3. Copy API Key
4. Widget में paste करें

**⛔ यह production के लिए use न करें!**

---

## 🧪 Testing Guide

### Test करने के लिए:

1. **Form submit करें** आपकी website पर
2. **Browser Console खोलें** (F12)
3. **देखें logs:**
   ```
   ✅ Data sent to Google Sheets successfully
   या
   ❌ Failed to send data to Google Sheets: [error message]
   ```

4. **Google Sheet check करें** - new row add हुई या नहीं

### Common Errors & Solutions:

#### ❌ Error: 401 Unauthorized
**Problem:** API Key use कर रहे हैं (write support नहीं है)  
**Solution:** Service Account या Webhook use करें

#### ❌ Error: 403 Forbidden
**Problem:** Sheet share नहीं की Service Account से  
**Solution:** Sheet को Service Account email के साथ share करें (Editor permission)

#### ❌ Error: 404 Not Found
**Problem:** Sheet ID गलत है  
**Solution:** Sheet URL से correct ID copy करें

#### ❌ Error: Invalid credentials
**Problem:** Service Account JSON गलत format में है  
**Solution:** JSON file को फिर से download करके paste करें

#### ❌ Error: Missing required fields
**Problem:** Sheet ID या Field Mapping missing है  
**Solution:** सभी required fields भरें

---

## 📝 Field Mapping JSON Format

### Example 1: Basic Form

CF7 Form:
```
[text* your-name]
[email* your-email]
[tel your-phone]
[textarea your-message]
```

Field Mapping JSON:
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "your-phone": "Phone",
  "your-message": "Message"
}
```

Google Sheet में columns:
```
| Name | Email | Phone | Message | Timestamp |
```

### Example 2: Custom Columns

Field Mapping:
```json
{
  "your-name": "Full Name",
  "your-email": "Email Address",
  "your-phone": "Contact Number",
  "your-subject": "Subject",
  "your-message": "Description"
}
```

---

## 🔐 Security Best Practices

### Service Account JSON को Safe रखें:

#### Option 1: Secure Directory (Recommended)
```bash
# Create secure directory
mkdir -p /wp-content/uploads/private/
cd /wp-content/uploads/private/

# Upload JSON file
# service-account-key.json

# Create .htaccess to block access
echo "deny from all" > .htaccess
```

Widget में:
```
Service Account Input: File Path
File Path: /wp-content/uploads/private/service-account-key.json
```

#### Option 2: WordPress Database
Widget में directly JSON paste करें (encrypted storage)

---

## 🎨 Advanced Configuration

### 1. Custom Timestamp Format

Field Mapping में add करें:
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "Timestamp": "Submission Date"
}
```

Timestamp automatically add होगा।

### 2. Multiple Forms, Same Sheet

अलग-अलग CF7 forms के लिए:
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "form_id": "Contact Form"
}
```

### 3. Conditional Fields

Apps Script में customize करें:
```javascript
if (data['Service'] === 'Premium') {
  sendEmailNotification(data);
}
```

---

## 📞 Support & Help

### Documentation Files:
- `/workspace/GOOGLE_SHEETS_401_ERROR_SOLUTION_HINDI.md` - Detailed error solutions
- `/workspace/mrm-ele-addon/GOOGLE_APPS_SCRIPT_WEBHOOK.gs` - Apps Script code
- `/workspace/mrm-ele-addon/CF7_POPUP_DOCUMENTATION.md` - Complete widget docs

### Debugging:

Enable WordPress Debug:
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Check logs:
```
/wp-content/debug.log
```

---

## ✅ Checklist

### Before Going Live:

- [ ] Service Account setup और test किया
- [ ] Google Sheet properly shared है
- [ ] Field Mapping correct है
- [ ] Test submission successful है
- [ ] Browser console में कोई error नहीं
- [ ] Google Sheet में data visible है
- [ ] Service Account JSON secure location में है
- [ ] Backup लिया JSON file का

---

## 🚀 आगे क्या करें?

1. ✅ Method चुनें (Service Account recommended)
2. ✅ Setup follow करें
3. ✅ Test करें
4. ✅ Production में deploy करें

**Questions?** Documentation पढ़ें या मुझसे पूछें!
