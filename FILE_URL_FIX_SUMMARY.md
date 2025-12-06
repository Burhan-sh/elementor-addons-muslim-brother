# File Upload URL Google Sheets Fix - Summary

## समस्या (Problem) 

Aapne bataya ki:
- ✅ Contact Form 7 se file upload WordPress media me ho rahi thi
- ✅ File ka URL WordPress me create ho raha tha (jaise: `https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png`)
- ❌ **Lekin Google Sheets me file URL nahi ja rahi thi - column empty aa raha tha**

Your mapping:
```json
{
  "your-name": "name",
  "your-email": "email",
  "tel-835": "phone number",
  "file-225": "Adhar card",
  "your-message": "Description"
}
```

Google Sheet me "Adhar card" column (where `file-225` is mapped) **empty** aa raha tha.

---

## Root Cause (मूल कारण)

**Field Name Mismatch:**

1. Contact Form 7 file upload fields ka HTML name attribute **brackets ke saath** hota hai:
   - Actual field name: `file-225[]` (with `[]` at the end)

2. File upload hone ke baad, URL store hoti thi with brackets:
   ```javascript
   this.uploadedFiles["file-225[]"] = "https://...url...";
   ```

3. Lekin Google Sheets mapping me aap brackets **ke bina** field name use karte ho:
   ```json
   "file-225": "Adhar card"
   ```

4. Jab Google Sheets me data bhejne ka time aata tha:
   ```javascript
   // Yeh dhoondhta tha:
   this.uploadedFiles["file-225"]  // ❌ Not found!
   
   // Lekin stored tha:
   this.uploadedFiles["file-225[]"] // ✅ Actual location
   ```

**Result:** URL nahi milti, toh empty string Google Sheets me chala jata tha.

---

## समाधान (Solution)

Maine **2 fixes** implement kiye hain:

### Fix 1: Field Name Normalization (Storage Time)

**File:** `/workspace/mrm-ele-addon/assets/js/cf7-popup-script.js`  
**Lines:** 257-271

```javascript
// Store uploaded file URLs
results.forEach(result => {
    if (result.success && result.data) {
        // Normalize field name - remove brackets [] if present
        // CF7 file fields use name like "file-225[]" but form data uses "file-225"
        const normalizedFieldName = result.data.field_name.replace(/\[\]$/, '');
        this.uploadedFiles[normalizedFieldName] = result.data.url;
        console.log('💾 Stored file URL for field:', normalizedFieldName, '→', result.data.url);
    }
});
```

**Kya karta hai:**
- Jab file upload hoti hai, field name se brackets `[]` **remove** kar deta hai
- Store karta hai: `this.uploadedFiles["file-225"] = "https://..."`
- Ab mapping ke time milega easily!

---

### Fix 2: Flexible Lookup (Retrieval Time)

**File:** `/workspace/mrm-ele-addon/assets/js/cf7-popup-script.js`  
**Lines:** 368-381

```javascript
// Map form fields to sheet columns
for (const [formField, sheetColumn] of Object.entries(fieldMapping)) {
    let value = formData.find(item => item.name === formField);
    
    // Check if this field has an uploaded file - prioritize uploaded file URL
    // Try both with and without brackets (e.g., "file-225" and "file-225[]")
    const normalizedFormField = formField.replace(/\[\]$/, '');
    const formFieldWithBrackets = normalizedFormField + '[]';
    
    if (this.uploadedFiles && (this.uploadedFiles[normalizedFormField] || this.uploadedFiles[formFieldWithBrackets])) {
        const fileUrl = this.uploadedFiles[normalizedFormField] || this.uploadedFiles[formFieldWithBrackets];
        mappedData[sheetColumn] = fileUrl;
        console.log('📎 Using uploaded file URL for', formField, ':', fileUrl);
    } else if (value) {
        // ... rest of the code
    }
}
```

**Kya karta hai:**
- Google Sheets mapping ke time **dono variants** try karta hai:
  - Pehle `file-225` (without brackets)
  - Phir `file-225[]` (with brackets)
- Whichever mil jaye, use kar leta hai
- **Double safety** for backward compatibility!

---

## अब कैसे काम करेगा (How It Works Now)

### Complete Flow:

```
1. User file select karta hai
   ↓
2. File upload hoti hai WordPress media me via AJAX
   ↓
3. WordPress returns file URL: "https://example.com/wp-content/uploads/2025/12/file.pdf"
   ↓
4. JavaScript normalizes field name: "file-225[]" → "file-225"
   ↓
5. Store karta hai: uploadedFiles["file-225"] = "https://...url..."
   ↓
6. CF7 form submit hota hai
   ↓
7. wpcf7mailsent event trigger hota hai
   ↓
8. sendToGoogleSheets() method call hota hai
   ↓
9. Field mapping apply hoti hai: "file-225" → "Adhar card"
   ↓
10. uploadedFiles se URL find hota hai: "file-225" → "https://...url..."
   ↓
11. Data Google Sheets me bheja jata hai
   ↓
12. ✅ "Adhar card" column me file URL appear hota hai!
```

---

## कंसोल लॉग्स (Console Logs)

Ab aapko browser console (F12) me yeh logs dikhengi:

```
📤 Starting file uploads...
📁 Uploading file: protection_ima.png for field: file-225[]
✅ File uploaded: protection_ima.png → https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png
💾 Stored file URL for field: file-225 → https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png
📨 Submitting form after file uploads...
📊 Google Sheets Data: {...}
📁 Uploaded Files: {file-225: "https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png"}
📋 Form Data from CF7: [...]
📎 Using uploaded file URL for file-225 : https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png
📤 Sending to Google Sheets: {...}
✅ Data sent to Google Sheets successfully
```

---

## Testing Steps (टेस्टिंग कैसे करें)

1. **Clear Browser Cache:**
   - Ctrl + Shift + Delete
   - Clear Cached Images and Files

2. **Test Form Submission:**
   - Contact Form open karein
   - File select karein (image/PDF/document)
   - Form fill karein with other fields
   - Submit karein

3. **Check Browser Console (F12):**
   - Console tab me file upload logs check karein
   - "💾 Stored file URL for field" message dekhen
   - "📎 Using uploaded file URL" message dekhen
   - "✅ Data sent to Google Sheets successfully" confirm karein

4. **Verify Google Sheets:**
   - Apni Google Sheet open karein
   - New row check karein
   - "Adhar card" column (ya jo bhi aapka file field column hai) me **file URL** honi chahiye
   - Example: `https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png`

5. **Verify WordPress Media:**
   - WordPress Admin → Media Library
   - Uploaded file dikna chahiye
   - File URL check karein

---

## Expected Results (अपेक्षित परिणाम)

### Before Fix (पहले):
| name | email | phone number | Adhar card | Description |
|------|-------|--------------|------------|-------------|
| Burhan | burhanh786@gmail.com | 234... | **(EMPTY)** | This is protected image |

### After Fix (अब):
| name | email | phone number | Adhar card | Description |
|------|-------|--------------|------------|-------------|
| Burhan | burhanh786@gmail.com | 234... | **https://themuslimbrotherhood.in/wp-content/uploads/2025/12/protection_ima.png** | This is protected image |

---

## Backward Compatibility (पुरानी Settings)

Yeh fix **backward compatible** hai:

✅ Agar aapke paas purane forms hain jo file upload nahi karte - **woh bhi kaam karenge**  
✅ Agar aapne brackets ke saath field name store kiya hai - **woh bhi kaam karega**  
✅ Agar aapne brackets ke bina field name store kiya hai - **woh bhi kaam karega**  
✅ Existing Google Sheets integrations - **sab kaam karenge**

---

## Troubleshooting (समस्या समाधान)

### Agar abhi bhi URL nahi aa rahi:

1. **Check Field Mapping:**
   ```json
   // Ensure correct field name (without brackets)
   {
     "file-225": "Adhar card",  // ✅ Correct
     "file-225[]": "Adhar card"  // ❌ Don't use brackets in mapping
   }
   ```

2. **Check Browser Console:**
   - F12 → Console tab
   - Koi error message hai?
   - "💾 Stored file URL" message aa raha hai?
   - "📎 Using uploaded file URL" message aa raha hai?

3. **Check Google Sheets Integration:**
   - Service Account properly configured hai?
   - Sheet shared hai service account email ke saath?
   - Sheet ID aur Sheet Name sahi hai?

4. **Check WordPress Permissions:**
   - Media uploads allowed hain?
   - File size limit cross nahi ho rahi?
   - File type allowed hai?

5. **Check Network Tab:**
   - F12 → Network tab
   - File upload AJAX call successful hai?
   - Google Sheets AJAX call successful hai?
   - Status code 200 hai dono me?

---

## Files Modified (कौन सी Files बदली गईं)

1. **`/workspace/mrm-ele-addon/assets/js/cf7-popup-script.js`**
   - Line 257-271: Field name normalization (storage)
   - Line 368-381: Flexible field lookup (retrieval)

---

## Summary

**Problem:** File URL Google Sheets me nahi ja rahi thi because field name me brackets `[]` ki wajah se mismatch tha.

**Solution:** 
1. Storage time par brackets remove kar dete hain
2. Retrieval time par dono variants try karte hain (with/without brackets)

**Result:** Ab file URL properly Google Sheets me jayegi! ✅

---

## Next Steps

1. Clear browser cache
2. Test ek form submission karein with file upload
3. Check console logs (F12)
4. Verify Google Sheet me URL aa rahi hai

Agar koi problem aaye toh console logs share karein, main aur help karunga! 😊

---

**Date:** December 6, 2025  
**Status:** ✅ FIXED  
**Files Changed:** 1 (cf7-popup-script.js)  
**Lines Changed:** ~15 lines
