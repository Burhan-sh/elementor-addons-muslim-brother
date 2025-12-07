# CF7 Popup File Upload Fix - Testing Checklist

## 🧪 Pre-Testing Setup

### Before You Start Testing:

- [ ] Browser: Chrome, Firefox, or Edge (latest version)
- [ ] Open Browser Console (F12 or Ctrl+Shift+J)
- [ ] Enable "Preserve log" in Console settings
- [ ] Clear console before each test (Ctrl+L)
- [ ] Open Network tab to monitor AJAX calls
- [ ] Have Google Sheet open in another tab

---

## ✅ Test Cases

### Test 1: Text-Only Form (No Files)
**Purpose**: Verify basic functionality without files

- [ ] Fill form with text data only (no files)
- [ ] Click Submit
- [ ] Check Console:
  - [ ] No file upload messages
  - [ ] Form submits directly
  - [ ] "SENDING TO GOOGLE SHEETS (ONE TIME)" appears once
  - [ ] "SUCCESS!" message appears
- [ ] Check Google Sheet:
  - [ ] **1 new row** added
  - [ ] All text data present
  - [ ] No file URL column (or empty)
- [ ] Check Network tab:
  - [ ] Only 1 AJAX call to `mrm_cf7_popup_google_sheets`

**Expected Result**: ✅ 1 entry with text data only

---

### Test 2: Small Image (10-50 KB)
**Purpose**: Verify quick file upload

Prepare: Use a small JPG/PNG image (10-50 KB)

- [ ] Fill form with text data
- [ ] Select small image file
- [ ] Click Submit
- [ ] Check Console:
  - [ ] "📤 Starting file uploads..."
  - [ ] "⏳ Uploading: filename.jpg (XX KB)"
  - [ ] "📊 Upload progress: 100.0%"
  - [ ] "✅ File uploaded successfully!"
  - [ ] "💾 Stored file URL"
  - [ ] "📨 Submitting form after file uploads complete..."
  - [ ] "📊 SENDING TO GOOGLE SHEETS (ONE TIME)"
  - [ ] "✅ Using UPLOADED file URL"
  - [ ] "✅ ✅ ✅ SUCCESS!"
- [ ] Check Google Sheet:
  - [ ] **1 new row** added
  - [ ] All text data present
  - [ ] File URL column has **full image URL**
  - [ ] Click URL to verify it opens the full image
- [ ] Check Network tab:
  - [ ] 1 AJAX call to `mrm_cf7_popup_upload_file`
  - [ ] 1 AJAX call to `mrm_cf7_popup_google_sheets`

**Expected Result**: ✅ 1 entry with complete data and small image URL

---

### Test 3: Large Image (1-3 MB)
**Purpose**: Verify large file upload and timing

Prepare: Use a large JPG/PNG image (1-3 MB)

- [ ] Fill form with text data
- [ ] Select large image file
- [ ] Click Submit
- [ ] Check Console:
  - [ ] "📤 Starting file uploads..."
  - [ ] "⏳ Uploading: large-image.jpg (XXXX KB)"
  - [ ] "📊 Upload progress: 25.0%" (multiple times)
  - [ ] "📊 Upload progress: 50.0%"
  - [ ] "📊 Upload progress: 75.0%"
  - [ ] "📊 Upload progress: 100.0%"
  - [ ] "✅ File uploaded successfully!"
  - [ ] "💾 Stored file URL"
  - [ ] Wait time: 3-10 seconds for upload
  - [ ] "📨 Submitting form after file uploads complete..."
  - [ ] "📊 SENDING TO GOOGLE SHEETS (ONE TIME)"
  - [ ] "✅ Using UPLOADED file URL"
  - [ ] "✅ ✅ ✅ SUCCESS!"
- [ ] Check Google Sheet:
  - [ ] **1 new row** added (not 4-5!)
  - [ ] All text data present
  - [ ] File URL column has **full large image URL**
  - [ ] Click URL to verify full-size image opens
- [ ] Verify timing:
  - [ ] Form did NOT submit while file was uploading
  - [ ] Data sent AFTER file upload completed

**Expected Result**: ✅ 1 entry with full-size image URL (not thumbnail)

---

### Test 4: PDF File (1-3 MB)
**Purpose**: Verify document upload

Prepare: Use a PDF file (1-3 MB)

- [ ] Fill form with text data
- [ ] Select PDF file
- [ ] Click Submit
- [ ] Check Console:
  - [ ] File upload messages appear
  - [ ] Upload progress shows
  - [ ] "✅ File uploaded successfully!"
  - [ ] PDF URL stored
  - [ ] Form submits after upload
  - [ ] "SENDING TO GOOGLE SHEETS (ONE TIME)"
  - [ ] "SUCCESS!"
- [ ] Check Google Sheet:
  - [ ] **1 new row** added
  - [ ] All text data present
  - [ ] File URL column has **PDF URL**
  - [ ] Click URL to verify PDF opens/downloads
- [ ] Check Network tab:
  - [ ] 1 upload AJAX call
  - [ ] 1 Google Sheets AJAX call

**Expected Result**: ✅ 1 entry with PDF URL

---

### Test 5: Multiple Files
**Purpose**: Verify multiple file upload support

Prepare: 2-3 files (mixed: image + PDF)

- [ ] Fill form with text data
- [ ] Select multiple files (if form allows)
- [ ] Click Submit
- [ ] Check Console:
  - [ ] "📤 Starting file uploads..."
  - [ ] Upload messages for each file
  - [ ] "⏳ Waiting for X file(s) to upload..."
  - [ ] Progress for File 1
  - [ ] Progress for File 2
  - [ ] "✅ All files uploaded successfully"
  - [ ] Multiple "💾 Stored file URL" messages
  - [ ] "📦 Final uploaded files object: {...}"
  - [ ] Form submits after ALL uploads
  - [ ] "SENDING TO GOOGLE SHEETS (ONE TIME)"
  - [ ] Multiple "✅ Using UPLOADED file URL" messages
  - [ ] "SUCCESS!"
- [ ] Check Google Sheet:
  - [ ] **1 new row** added
  - [ ] All text data present
  - [ ] All file URL columns have URLs
  - [ ] Click each URL to verify files open

**Expected Result**: ✅ 1 entry with all file URLs

---

### Test 6: File Size Limit (>5MB)
**Purpose**: Verify file size validation

Prepare: File larger than 5MB

- [ ] Fill form with text data
- [ ] Select file >5MB
- [ ] Click Submit
- [ ] Check Console:
  - [ ] "❌ File exceeds 5MB limit" error
  - [ ] Form does NOT submit
  - [ ] Error message shown to user
- [ ] Check Google Sheet:
  - [ ] No new row added
- [ ] Verify user sees error message

**Expected Result**: ✅ Upload rejected with clear error

---

### Test 7: No Internet / Slow Connection
**Purpose**: Verify error handling

- [ ] Open Chrome DevTools → Network tab
- [ ] Set throttling to "Slow 3G" or "Offline"
- [ ] Fill form and submit with file
- [ ] Check Console:
  - [ ] Upload timeout or error message
  - [ ] Proper error handling
  - [ ] Form remains in submitting state or shows error
- [ ] Set network back to "No throttling"
- [ ] Verify system recovers properly

**Expected Result**: ✅ Graceful error handling

---

### Test 8: Rapid Submit (Spam Prevention)
**Purpose**: Verify duplicate submission prevention

- [ ] Fill form
- [ ] Click Submit button rapidly 5 times
- [ ] Check Console:
  - [ ] "⚠️ Form is already submitting" warnings
  - [ ] Only first submission processes
  - [ ] Others are blocked
- [ ] Check Google Sheet:
  - [ ] **Only 1 entry** added

**Expected Result**: ✅ Only first submission processed

---

### Test 9: Multiple Form Submissions
**Purpose**: Verify flags reset properly

- [ ] Submit form with data (wait for success)
- [ ] Wait 3 seconds (popup should close)
- [ ] Re-open popup
- [ ] Submit form again with different data
- [ ] Check Console:
  - [ ] Flags are reset
  - [ ] Second submission works properly
  - [ ] "🔄 All submission flags reset" appears
- [ ] Check Google Sheet:
  - [ ] **2 separate entries** (one for each submission)
  - [ ] No duplicates within each submission

**Expected Result**: ✅ Each submission creates 1 entry

---

### Test 10: Different File Types
**Purpose**: Verify various file type support

Test these file types:
- [ ] JPG image
- [ ] PNG image
- [ ] GIF image
- [ ] WebP image
- [ ] PDF document
- [ ] DOCX document (if allowed)
- [ ] XLSX document (if allowed)

For each:
- [ ] File uploads successfully
- [ ] URL appears in Google Sheet
- [ ] URL is accessible

**Expected Result**: ✅ All allowed types work properly

---

## 🔍 Verification Points

After all tests, verify:

### Console Logs
- [ ] No red error messages (❌) except intentional tests
- [ ] All success messages (✅) appear
- [ ] Upload progress shows properly
- [ ] "ONE TIME" appears in Google Sheets log
- [ ] No duplicate "SENDING TO GOOGLE SHEETS" messages

### Google Sheet
- [ ] Each test created exactly 1 row
- [ ] No duplicate entries
- [ ] All file URLs are complete (not thumbnails)
- [ ] All URLs are accessible
- [ ] Text data is accurate
- [ ] Timestamps are present

### Network Tab
- [ ] File upload AJAX calls = number of files
- [ ] Google Sheets AJAX call = 1 per form submission
- [ ] No failed requests (except intentional tests)
- [ ] Response codes are 200

### User Experience
- [ ] Form shows "submitting" state during upload
- [ ] Progress is visible (check console)
- [ ] Success message appears
- [ ] Popup closes after success
- [ ] Form resets properly

---

## 📋 Bug Report Template

If you find an issue, report it with:

```
**Test Case**: [Test number and name]
**Browser**: [Chrome/Firefox/Edge + version]
**File Size**: [X MB/KB]
**File Type**: [JPG/PDF/etc]

**Steps**:
1. [What you did]
2. [What you did next]

**Expected**: [What should happen]
**Actual**: [What actually happened]

**Console Errors**: [Paste error messages]
**Screenshot**: [Attach console/network screenshots]
**Google Sheet**: [Describe entries added]
```

---

## ✅ Sign-Off Checklist

Before marking as complete:

- [ ] All 10 test cases passed
- [ ] No duplicate Google Sheet entries
- [ ] File URLs are complete (not thumbnails)
- [ ] Large files (2-5MB) upload successfully
- [ ] Console logs are clean
- [ ] Network tab shows single AJAX calls
- [ ] No errors in production console
- [ ] Multiple submissions work properly
- [ ] Flags reset between submissions
- [ ] Error handling works for failed uploads

---

## 🎉 Success Criteria

✅ **ALL TESTS MUST SHOW**:
1. **Only 1 AJAX call** to Google Sheets per submission
2. **Only 1 entry** in Google Sheet per submission
3. **Complete file URLs** (full size, not thumbnails)
4. **Upload completes** before data is sent
5. **No duplicate entries**
6. **Clean console logs** with clear progression

---

**Testing Date**: _________________
**Tested By**: _________________
**Browser**: _________________
**Result**: PASS ✅ / FAIL ❌
**Notes**: _________________

---

**Document Version**: 1.0
**Created**: December 7, 2025
