# Google Sheets Integration - Setup Checklist

## ✅ Complete This Checklist Step-by-Step

### Part 1: Google Cloud Console Setup

- [ ] **Step 1.1:** Go to [Google Cloud Console](https://console.cloud.google.com/)
- [ ] **Step 1.2:** Create or select your project: `my-cf7-integration`
- [ ] **Step 1.3:** Enable Google Sheets API
- [ ] **Step 1.4:** Create Service Account
- [ ] **Step 1.5:** Download JSON key file
- [ ] **Step 1.6:** Note your service account email:
  ```
  cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com
  ```

### Part 2: WordPress/Elementor Setup

- [ ] **Step 2.1:** Open your page in Elementor editor
- [ ] **Step 2.2:** Edit the CF7 Popup widget
- [ ] **Step 2.3:** Go to **Content** → **Google Sheets Integration**
- [ ] **Step 2.4:** Toggle **Enable Google Sheets** to ON
- [ ] **Step 2.5:** Select **Service Account** as authentication method
- [ ] **Step 2.6:** Keep **Upload JSON File** selected
- [ ] **Step 2.7:** Click **Choose File** button
- [ ] **Step 2.8:** Upload `my-cf7-integration-31e3099c308f.json`
- [ ] **Step 2.9:** Enter your Google Sheet ID:
  ```
  Example: 1OtbFHlzlUFGlPEFCUEKskaaVMv4ZoGrvEcLOUS4amE8
  ```
- [ ] **Step 2.10:** Enter Sheet Name (default: `Sheet1`)
- [ ] **Step 2.11:** Add Field Mapping JSON:
  ```json
  {
    "your-name": "Name",
    "your-email": "Email",
    "your-phone": "Phone",
    "your-message": "Message"
  }
  ```
- [ ] **Step 2.12:** Click **UPDATE** to save widget settings

### Part 3: Google Sheets Setup

- [ ] **Step 3.1:** Open your Google Sheet
- [ ] **Step 3.2:** Click **Share** button (top right)
- [ ] **Step 3.3:** Enter service account email:
  ```
  cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com
  ```
- [ ] **Step 3.4:** Select **Editor** permission (NOT Viewer)
- [ ] **Step 3.5:** Uncheck **Notify people** checkbox
- [ ] **Step 3.6:** Click **Share** or **Done**
- [ ] **Step 3.7:** Verify the sheet has column headers matching your field mapping:
  - Column A: `Name`
  - Column B: `Email`
  - Column C: `Phone`
  - Column D: `Message`
  - Column E: `Timestamp` (added automatically)

### Part 4: Testing

- [ ] **Step 4.1:** Save and exit Elementor editor
- [ ] **Step 4.2:** Open the page in a regular browser window
- [ ] **Step 4.3:** Press **F12** to open Developer Console
- [ ] **Step 4.4:** Go to **Console** tab
- [ ] **Step 4.5:** Fill out the contact form with test data:
  - Name: `Test User`
  - Email: `test@example.com`
  - Phone: `1234567890`
  - Message: `This is a test submission`
- [ ] **Step 4.6:** Submit the form
- [ ] **Step 4.7:** Check console for success message:
  ```
  ✅ Data sent to Google Sheets successfully
  ```
- [ ] **Step 4.8:** Go to your Google Sheet
- [ ] **Step 4.9:** Verify new row was added with test data
- [ ] **Step 4.10:** Check that Timestamp column is populated

### Part 5: Troubleshooting (If Needed)

#### If you see: "Service Account credentials not found"

- [ ] Verify you clicked **UPDATE** in Elementor after uploading JSON
- [ ] Re-upload the JSON file
- [ ] Check that JSON file is valid (open in text editor)
- [ ] Check WordPress media library to confirm file was uploaded

#### If you see: "403 Forbidden" error

- [ ] **You forgot to share the Google Sheet!**
- [ ] Share the sheet with service account email
- [ ] Verify permission is **Editor** (not Viewer)
- [ ] Try removing and re-adding the service account email

#### If you see: "404 Not Found" error

- [ ] Check that Sheet ID is correct
- [ ] Verify Sheet Name matches (case-sensitive)
- [ ] Confirm the sheet wasn't deleted

#### If you see: "Invalid credentials" error

- [ ] Download a fresh JSON key from Google Cloud Console
- [ ] Re-upload the new JSON file
- [ ] Verify the service account still exists

#### If nothing appears in Google Sheets

- [ ] Check column headers match field mapping exactly
- [ ] Verify field mapping JSON is valid (use a JSON validator)
- [ ] Check that CF7 field names match (e.g., `your-name`, not `name`)
- [ ] Look at browser console for errors
- [ ] Check WordPress debug.log

### Part 6: Going to Production

When ready to switch from test to production account:

- [ ] Create production service account in Google Cloud
- [ ] Download production JSON key
- [ ] In Elementor, upload new JSON file (replaces old one)
- [ ] Click **UPDATE**
- [ ] Remove old service account from Google Sheet sharing
- [ ] Share sheet with new production service account email
- [ ] Test with production sheet

---

## Quick Reference

### Your Test Credentials

```
Project ID: my-cf7-integration
Service Account Email: cf7-google-sheets@my-cf7-integration.iam.gserviceaccount.com
JSON Filename: my-cf7-integration-31e3099c308f.json
```

### Required Permissions

- **Google Cloud:** Service Account Token Creator
- **Google Sheets:** Editor permission on your sheet
- **WordPress:** Elementor editor access

### Important URLs

- Google Cloud Console: https://console.cloud.google.com/
- Service Accounts: https://console.cloud.google.com/iam-admin/serviceaccounts
- APIs & Services: https://console.cloud.google.com/apis/dashboard

### Files Modified (For Reference)

1. `/mrm-ele-addon/includes/cf7-popup-ajax-handler.php` - Fixed credential retrieval
2. `/mrm-ele-addon/assets/js/cf7-popup-script.js` - Added debug logging
3. `/mrm-ele-addon/widgets/cf7-popup-widget.php` - Improved instructions

---

**Last Updated:** December 6, 2025  
**Status:** Ready for Testing  
**Support:** See GOOGLE_SHEETS_JSON_UPLOAD_FIX.md for detailed documentation
