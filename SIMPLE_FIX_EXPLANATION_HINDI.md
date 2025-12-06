# File URL Google Sheets Fix - Simple Explanation (सरल व्याख्या)

## आपकी समस्या (Your Problem)

Aapne kaha:
> "File upload field ki image ya document WordPress media mai upload hona chahiye wo bhi wordpress media mai upload ho raha hai, upload hone ke baad uski ek file URL banti hai wo URL muje google sheet mai us column mai chahiye jaha per file upload mapped hai"

**Simple words me:**
- File upload ho rahi hai ✅
- File URL ban rahi hai ✅  
- **Par Google Sheet me URL nahi aa rahi thi** ❌

**Example:**
```
Uploaded file: protection_ima.png
File URL: https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png

Google Sheet me "Adhar card" column: (empty) ❌ ← Yeh problem thi
```

---

## क्यों हो रही थी Problem?

Contact Form 7 file field ka naam hota hai: `file-225[]` (brackets ke saath)

Lekin aap mapping me use karte ho: `file-225` (without brackets)

```json
{
  "file-225": "Adhar card"  ← Aapka mapping (no brackets)
}
```

**Toh kya hota tha:**

1. File upload → Store hoti thi as `file-225[]` (with brackets)
2. Google Sheets bhejne ka time → Dhoondhti thi `file-225` (without brackets)
3. Nahi milti → Empty column send ho jata tha

**Analogy:**
- Aap ek box me apple rakho "APPLE[]" naam se
- Baad me dhoondhte ho "APPLE" naam se
- Nahi milta kyunki naam match nahi hua!

---

## Maine Kya Fix Kiya?

### Fix 1: Storage Time Fix (जब File Upload होती है)

**Pehle:**
```javascript
// File upload → Store with brackets
uploadedFiles["file-225[]"] = "https://...url...";
```

**Ab:**
```javascript
// Brackets remove kar ke store karte hain
const fieldName = "file-225[]";
const cleanName = fieldName.replace(/\[\]$/, ''); // Remove []
uploadedFiles["file-225"] = "https://...url..."; // ✅ No brackets
```

### Fix 2: Retrieval Time Fix (जब Google Sheets में भेजते हैं)

**Pehle:**
```javascript
// Sirf without brackets try karta tha
const url = uploadedFiles["file-225"]; // Agar file-225[] me stored tha toh nahi milta
```

**Ab:**
```javascript
// Dono try karta hai - safety ke liye
const url = uploadedFiles["file-225"] || uploadedFiles["file-225[]"];
// Dono jagah dekh leta hai, jo mil jaye woh use kar leta hai
```

---

## Ab Kaise Kaam Karega?

### Step-by-Step Flow:

**1. User File Select Karta Hai**
```
User clicks "Choose File" → Selects protection_ima.png
```

**2. File Upload API Call**
```
JavaScript → WordPress AJAX → Upload to media library
```

**3. File Upload Success (WordPress Response)**
```json
{
  "success": true,
  "url": "https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png",
  "field_name": "file-225[]"
}
```

**4. Field Name Clean Karke Store Karte Hain**
```javascript
// "file-225[]" → "file-225" (brackets remove)
uploadedFiles["file-225"] = "https://...url...";

Console: 💾 Stored file URL for field: file-225 → https://...
```

**5. Form Submit Hota Hai**
```
User fills form → Clicks Submit → CF7 processes form
```

**6. CF7 Success Event (wpcf7mailsent)**
```javascript
// Yeh event trigger hota hai jab CF7 email send kar deta hai
```

**7. Google Sheets Integration Trigger**
```javascript
sendToGoogleSheets() function call hota hai
```

**8. Field Mapping Apply**
```javascript
Mapping: { "file-225": "Adhar card" }

// Field name se URL dhoondhte hain
uploadedFiles["file-225"] // ✅ Mil gaya!
// Agar nahi mila toh try karte hain:
uploadedFiles["file-225[]"] // Backup

Console: 📎 Using uploaded file URL for file-225: https://...
```

**9. Google Sheets API Call**
```javascript
Data send:
{
  "name": "Burhan Hasanfatta",
  "email": "burhanh786@gmail.com",
  "phone number": "234...",
  "Adhar card": "https://...url...",  ← ✅ File URL!
  "Description": "This is protected image"
}
```

**10. Google Sheet Update**
```
New row add hota hai with file URL in "Adhar card" column ✅
```

---

## Console Logs (F12 me yeh dikhega)

Jab aap form submit karoge, browser console me yeh logs dikhengi:

```
📤 Starting file uploads...
   ↓
📁 Uploading file: protection_ima.png for field: file-225[]
   ↓
✅ File uploaded: protection_ima.png → https://themuslimbrotherhood.in/.../protection_ima.png
   ↓
💾 Stored file URL for field: file-225 → https://themuslimbrotherhood.in/.../protection_ima.png
   ↓
📨 Submitting form after file uploads...
   ↓
📊 Google Sheets Data: {enabled: true, ...}
   ↓
📁 Uploaded Files: {
    file-225: "https://themuslimbrotherhood.in/.../protection_ima.png"
}
   ↓
📎 Using uploaded file URL for file-225: https://themuslimbrotherhood.in/.../protection_ima.png
   ↓
📤 Sending to Google Sheets: {...}
   ↓
✅ Data sent to Google Sheets successfully
```

---

## Testing Kaise Karein?

### Step 1: Browser Cache Clear Karein
```
1. Ctrl + Shift + Delete (Windows/Linux)
2. Cmd + Shift + Delete (Mac)
3. "Cached Images and Files" select karein
4. Clear Data click karein
```

### Step 2: Form Test Karein
```
1. Contact form open karein
2. File field me ek image/PDF select karein (e.g., Adhar card)
3. Baaki fields fill karein (name, email, phone, etc.)
4. Submit button click karein
5. Wait for success message
```

### Step 3: Console Check Karein (IMPORTANT!)
```
1. F12 press karein (Developer Tools)
2. "Console" tab select karein
3. Logs dekhein - upar wale pattern me dikhengi
4. Koi error nahi hona chahiye (red color me)
5. "💾 Stored file URL" message confirm karein
6. "📎 Using uploaded file URL" message confirm karein
7. "✅ Data sent to Google Sheets successfully" confirm karein
```

### Step 4: Google Sheet Verify Karein
```
1. Apni Google Sheet open karein
2. Last row check karein (latest entry)
3. "Adhar card" column me file URL honi chahiye
   Example: https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png
4. URL clickable hona chahiye
5. URL click karke verify karein ki file download/open hoti hai
```

### Step 5: WordPress Media Library Verify Karein
```
1. WordPress Admin panel me jaayein
2. Media → Library
3. Latest uploaded file dikni chahiye
4. File details check karein
5. File URL match karni chahiye Google Sheet wali se
```

---

## Expected Result (क्या होना चाहिए?)

### Before Fix:
| name | email | phone number | **Adhar card** | Description |
|------|-------|--------------|----------------|-------------|
| Burhan | burhanh786@gmail.com | 9879877017 | **(EMPTY)** ❌ | This is Optional |

### After Fix:
| name | email | phone number | **Adhar card** | Description |
|------|-------|--------------|----------------|-------------|
| Burhan | burhanh786@gmail.com | 9879877017 | **https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png** ✅ | This is Optional |

---

## Agar Abhi Bhi Problem Aaye?

### Check 1: Field Mapping Correct Hai?
```json
// Widget settings me yeh check karein:
{
  "file-225": "Adhar card"  // ✅ Correct (no brackets)
}

// NOT this:
{
  "file-225[]": "Adhar card"  // ❌ Wrong (with brackets)
}
```

### Check 2: Console Me Error Hai?
```
F12 → Console tab → Koi red color ka error?
Agar hai toh screenshot le ke share karein
```

### Check 3: Network Tab Check Karein
```
F12 → Network tab → Submit form → Check:

1. "mrm_cf7_popup_upload_file" request
   - Status: 200 OK?
   - Response me "url" field hai?

2. "mrm_cf7_popup_google_sheets" request
   - Status: 200 OK?
   - Response me "success: true" hai?
```

### Check 4: Google Sheets Settings
```
1. Service Account sahi se configure hai?
2. JSON key file upload ki hai?
3. Google Sheet share ki hai service account email ke saath?
4. Editor permission di hai?
5. Sheet ID aur Sheet Name correct hai?
```

### Check 5: WordPress Settings
```
1. File uploads allowed hain?
2. Max upload size sufficient hai?
3. File type allowed hai? (jpg, png, pdf, etc.)
4. WordPress Media Library me file dikh rahi hai?
```

---

## Important Notes

1. **Field Name Always Without Brackets:**
   - Mapping me hamesha brackets ke bina use karein: `file-225`
   - CF7 form me brackets honge `file-225[]` - yeh normal hai
   - Code automatically handle kar lega

2. **Multiple Files:**
   - Agar multiple file fields hain, sab ka mapping alag alag karein:
   ```json
   {
     "file-225": "Adhar card",
     "file-890": "Pan card",
     "resume-123": "Resume"
   }
   ```

3. **File Types Supported:**
   - Images: jpg, jpeg, png, gif, webp
   - Documents: pdf, doc, docx, xls, xlsx
   - Audio: mp3, wav, ogg
   - Video: mp4, mpeg, mov, avi
   - Max size: 10MB

4. **Clear Cache Important:**
   - Har baar test karne se pehle cache clear karein
   - Kyunki JavaScript file change hui hai

---

## Summary in One Line

**Problem:** Field name me brackets `[]` ki wajah se file URL nahi mil pa rahi thi.  
**Solution:** Brackets automatically remove kar dete hain, aur dono variants try karte hain.  
**Result:** File URL ab properly Google Sheets me jayegi! ✅

---

## Need More Help?

Agar koi confusion hai ya abhi bhi kaam nahi kar raha:

1. Browser console ke screenshots share karein (F12)
2. Network tab ke screenshots share karein
3. Google Sheet ka screenshot share karein
4. Field mapping JSON share karein

Main aapki aur help karunga! 😊

---

**Fix Date:** December 6, 2025  
**Status:** ✅ COMPLETED  
**Tested:** Ready for testing  
**File Changed:** `cf7-popup-script.js` (15 lines)
