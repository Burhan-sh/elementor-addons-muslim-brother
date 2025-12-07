# CF7 Popup File Upload & AJAX Fix - Complete Solution

## समस्या (Problem)
1. **Multiple AJAX Calls**: Google Sheets में data 4-5 बार submit हो रहा था
2. **File Upload Timing**: बड़ी images/PDFs upload होने से पहले ही AJAX call हो जाती थी
3. **Empty/Small File URLs**: छोटी images का URL जा रहा था, बड़ी files का URL खाली जा रहा था

## समाधान (Solution) - हिंदी में

### 🔧 मुख्य बदलाव (Main Changes)

#### 1. **Duplicate Submission Prevention** (डुप्लीकेट सबमिशन रोकना)
- **3 Flags जोड़े गए**:
  - `isSubmitting`: Form submit हो रहा है या नहीं
  - `googleSheetsSent`: Google Sheets को data भेजा गया या नहीं
  - `_googleSheetsSending`: AJAX call progress में है या नहीं

#### 2. **File Upload First, Then Data Submit** (पहले फाइल अपलोड, फिर डेटा)
- Form submit होने पर पहले check करते हैं कि file है या नहीं
- अगर file है तो:
  1. Form submission रोक देते हैं
  2. सभी files को पहले WordPress media library में upload करते हैं
  3. File URLs store करते हैं
  4. सब files upload होने के बाद form submit करते हैं

#### 3. **Single AJAX Call to Google Sheets** (सिर्फ एक बार AJAX)
- `handleFormSuccess` में पहले check करते हैं कि data पहले से भेजा गया है या नहीं
- अगर पहले भेज चुके हैं तो return कर देते हैं
- AJAX भेजने से पहले flag set करते हैं

#### 4. **File Upload Progress & Validation** (अपलोड प्रगति और जांच)
- Maximum file size: **5MB** (आपकी requirement के अनुसार)
- Upload progress का console log
- 60 second timeout large files के लिए
- Error handling improved

### 📋 How It Works (कैसे काम करता है)

```
Step 1: User fills form and clicks Submit
        ↓
Step 2: Check if files exist?
        ↓ YES
Step 3: Upload ALL files to WordPress Media
        (File 1 uploading... 25%... 50%... 100%)
        (File 2 uploading... 25%... 50%... 100%)
        ↓
Step 4: Store all file URLs
        {
          "file-225": "https://site.com/wp-content/uploads/2025/12/image.png",
          "document-123": "https://site.com/wp-content/uploads/2025/12/doc.pdf"
        }
        ↓
Step 5: Submit form to CF7
        ↓
Step 6: CF7 sends email (wpcf7mailsent event)
        ↓
Step 7: Send to Google Sheets (ONE TIME ONLY)
        - Use uploaded file URLs from Step 4
        - Map all data according to field mapping
        - Send SINGLE AJAX request
        ↓
Step 8: Success! Data in Google Sheets with file URLs
```

### 🛡️ Protection Against Duplicates (डुप्लीकेट से सुरक्षा)

1. **Form Submit Level**: 
   - `isSubmitting` flag prevents multiple form submissions

2. **Form Success Level**: 
   - `googleSheetsSent` flag prevents handling success event multiple times

3. **AJAX Level**: 
   - `_googleSheetsSending` flag prevents sending AJAX while previous is in progress

### 📝 Code Changes Summary

#### **cf7-popup-script.js** - मुख्य बदलाव:

1. **Constructor में flags add किए**:
```javascript
this.isSubmitting = false;
this.isFileUploading = false;
this.googleSheetsSent = false;
this.uploadedFiles = {};
```

2. **Form submission में file upload check**:
```javascript
if (hasFiles && !this.isFileUploading) {
    e.preventDefault();
    this.isSubmitting = true;
    this.isFileUploading = true;
    this.uploadFilesBeforeSubmit($form);
}
```

3. **Upload files complete होने का इंतजार**:
```javascript
Promise.all(uploadPromises)
    .then((results) => {
        // Store all file URLs
        results.forEach(result => {
            this.uploadedFiles[fieldName] = result.data.url;
        });
        // Now submit form
        this.submitFormAfterUpload($form);
    });
```

4. **Handle success में duplicate check**:
```javascript
handleFormSuccess(event) {
    if (this.googleSheetsSent) {
        return; // Already sent, skip
    }
    this.googleSheetsSent = true;
    this.sendToGoogleSheets(event.detail.inputs);
}
```

5. **Send to Google Sheets में file URLs use करना**:
```javascript
if (this.uploadedFiles[fieldName]) {
    mappedData[sheetColumn] = this.uploadedFiles[fieldName];
    // Use uploaded file URL
}
```

### ✅ Benefits (फायदे)

1. **✅ Single AJAX Call**: अब सिर्फ **1 बार** data Google Sheets में जाएगा
2. **✅ Complete File Upload**: फाइल पूरी upload होने के बाद ही URL भेजा जाएगा
3. **✅ Large Files Support**: 5MB तक की files properly upload होंगी
4. **✅ Progress Tracking**: Console में progress देख सकते हैं
5. **✅ Better Error Handling**: Errors का proper message मिलेगा

### 🔍 Testing Guide (टेस्टिंग कैसे करें)

1. **Browser Console खोलें** (F12 key press करें)
2. **Form fill करें** with files (small and large both)
3. **Submit करें**
4. **Console में देखें**:
   - `📁 Uploading file:` - File upload start
   - `📊 Upload progress:` - Upload percentage
   - `✅ File uploaded successfully!` - Upload complete
   - `📨 Submitting form after file uploads complete...` - Form submitting
   - `🎉 Form submission successful!` - Form success
   - `📊 SENDING TO GOOGLE SHEETS (ONE TIME)` - Sending to sheets
   - `✅ ✅ ✅ SUCCESS!` - Data sent

5. **Google Sheet check करें**: सिर्फ **1 entry** add होनी चाहिए, file URLs के साथ

### 📊 Console Messages Explained

| Message | Meaning |
|---------|---------|
| `📤 Starting file uploads...` | File upload process शुरू हो गई |
| `⏳ Waiting for X file(s)...` | X files का upload हो रहा है |
| `📊 Upload progress: 50%` | File 50% upload हो गई |
| `✅ File uploaded successfully!` | File upload पूरी हो गई |
| `📦 Final uploaded files object:` | सभी file URLs ready हैं |
| `📨 Submitting form...` | Form submit हो रहा है |
| `🎉 Form submission successful!` | Form submit सफल |
| `📊 SENDING TO GOOGLE SHEETS (ONE TIME)` | Google Sheets को data भेज रहे हैं |
| `✅ Using UPLOADED file URL` | Upload की हुई file का URL use कर रहे हैं |
| `✅ ✅ ✅ SUCCESS!` | Data successfully Google Sheets में गया |
| `⚠️ Already processed` | Duplicate call detected और रोक दिया |

### 🚫 What's Fixed

❌ **BEFORE** (पहले):
- 4-5 AJAX calls → 4-5 duplicate entries in Google Sheet
- Small/empty file URLs → incomplete data
- Race condition → files uploading while data already sent

✅ **AFTER** (अब):
- **1 AJAX call** → **1 entry** in Google Sheet
- Complete file URLs → full data with proper links
- Sequential process → files first, then data

### 📁 File Upload Limits

- **Maximum file size**: 5MB
- **Allowed types**: Images (JPG, PNG, GIF, WebP), Documents (PDF, DOC, DOCX, XLS, XLSX), Audio, Video
- **Upload timeout**: 60 seconds (enough for 5MB files)

### 🔐 Security Features

- ✅ Nonce verification
- ✅ File type validation
- ✅ File size validation
- ✅ AJAX security checks
- ✅ Sanitized data

### 💡 Important Notes

1. **Browser Console में देखें**: सभी logs console में दिखेंगे, debugging के लिए helpful है
2. **File size limit**: 5MB से बड़ी files upload नहीं होंगी (आपकी requirement)
3. **Network speed**: Slow internet पर large files में समय लगेगा, लेकिन properly upload होंगी
4. **Multiple files**: एक साथ कई files upload हो सकती हैं, सब complete होने का wait करेगा

### 🎯 Testing Scenarios

Test these scenarios to verify the fix:

1. **Text-only form**: कोई file नहीं → direct submit → 1 entry
2. **Small image** (10-50 KB): Quick upload → 1 entry with URL
3. **Large image** (2-4 MB): Slow upload → wait → 1 entry with URL
4. **PDF file** (1-3 MB): Upload → 1 entry with URL
5. **Multiple files**: सभी upload → 1 entry with all URLs
6. **Submit multiple times**: Multiple entries (intended behavior for separate submissions)

### 🐛 Debugging Tips

अगर कोई issue हो तो:

1. **Console खोलें** (F12)
2. **Error messages देखें**
3. **Network tab** में AJAX calls check करें
4. **Check for**:
   - Red error messages (❌)
   - Warning messages (⚠️)
   - Success messages (✅)

### 📞 Support

अगर कोई problem आए:
1. Browser console की screenshot लें
2. Network tab की screenshot लें
3. Google Sheet की screenshot लें (data कितनी बार add हुई)

---

## Technical Details (for developers)

### Modified Files
- `/workspace/mrm-ele-addon/assets/js/cf7-popup-script.js`

### Key Functions Modified
1. `constructor()` - Added flags
2. `initCF7Integration()` - Added duplicate prevention
3. `uploadFilesBeforeSubmit()` - Improved file upload handling
4. `submitFormAfterUpload()` - Better form submission
5. `handleFormSuccess()` - Duplicate check added
6. `sendToGoogleSheets()` - Additional duplicate prevention
7. `uploadSingleFile()` - Progress tracking & validation
8. `resetSubmissionFlags()` - Reset all flags

### Flags Used
- `isSubmitting`: Boolean - Tracks form submission state
- `isFileUploading`: Boolean - Tracks file upload in progress
- `googleSheetsSent`: Boolean - Tracks if data sent to sheets
- `_googleSheetsSending`: Boolean - Tracks AJAX call in progress
- `uploadedFiles`: Object - Stores uploaded file URLs

---

**Created**: December 7, 2025
**Issue**: Multiple AJAX calls & file upload timing
**Status**: ✅ FIXED
**Version**: 1.0
