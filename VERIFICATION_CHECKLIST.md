# Verification Checklist - Registration Entries Feature

## ✅ Quick Verification Steps

Follow these steps to verify the feature is working correctly:

---

## 1. Check Files Exist

**Navigate to plugin directory and verify:**

```bash
/mrm-ele-addon/
├── includes/
│   ├── registration-entries-db.php          ✓ NEW
│   ├── registration-entries-admin.php       ✓ NEW
│   ├── cf7-popup-ajax-handler.php          (existing)
│   ├── cf7-popup-security.php              (existing)
│   └── service-account-manager.php         (existing)
├── assets/
│   └── css/
│       ├── admin-entries.css               ✓ NEW
│       └── cf7-popup-style.css            (existing)
└── mrm-ele-addon.php                       ✓ MODIFIED
```

**Status:** [ ] Files verified

---

## 2. Check Database Table

**In WordPress admin:**
1. Go to phpMyAdmin or use a database plugin
2. Look for table: `wp_mrm_registration_entries`
3. Verify columns: id, form_name, form_id, data, date_time, user_id

**Alternative (PHP):**
```php
// Add to functions.php temporarily
global $wpdb;
$table_name = $wpdb->prefix . 'mrm_registration_entries';
$exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
echo $exists ? 'Table exists!' : 'Table not found!';
```

**Status:** [ ] Table exists

---

## 3. Check Admin Menu

**In WordPress admin:**
1. Look at left sidebar
2. Find menu item: "Registration Form Submissions"
3. Icon should be a list icon (dashicons-list-view)

**Status:** [ ] Menu appears

---

## 4. Test Form Submission

**Steps:**
1. Create a Contact Form 7 form (or use existing)
2. Add CF7 Popup widget to a page
3. Configure the widget with your form
4. Visit the page on frontend
5. Fill out and submit the form
6. Check CF7 email is sent (existing functionality)

**Status:** [ ] Form submits successfully

---

## 5. Check Data in Database

**In WordPress admin:**
1. Go to "Registration Form Submissions"
2. You should see your form in the dropdown
3. Select the form
4. Click "Search"
5. You should see the submission in the table

**Expected Result:**
- Table shows your submission
- Columns match your form fields
- Date/time is correct
- User shows "0 (Guest)" or your username

**Status:** [ ] Data appears in admin

---

## 6. Test Dynamic Columns

**Verify columns match form fields:**

**Example:**
- Form has: Name, Email, Phone, Message
- Table should show: ID, Name, Email, Phone, Message, Date & Time, User

**If you have different forms:**
1. Submit to Form A (fields: name, email)
2. Submit to Form B (fields: title, description)
3. Select Form A → Should show: ID, Name, Email, Date, User
4. Select Form B → Should show: ID, Title, Description, Date, User

**Status:** [ ] Dynamic columns work

---

## 7. Test File Uploads

**If your form has file upload:**
1. Submit form with a file attached
2. Check admin table
3. File field should show as a clickable link
4. Click link → File should open/download

**Expected:**
- Link format: `filename.pdf` or similar
- Clicking opens the file
- URL points to WordPress uploads directory

**Status:** [ ] File uploads work

---

## 8. Test CSV Export

**Steps:**
1. Select a form that has submissions
2. Click "Download CSV" button
3. File should download automatically
4. Open in Excel or Google Sheets
5. Verify all data is present

**Expected Filename:**
`registration-entries-{form-name}-{date-time}.csv`

**Expected Content:**
- First row: Headers (ID, Date & Time, User ID, Form Fields...)
- Following rows: Your data
- Special characters display correctly

**Status:** [ ] CSV export works

---

## 9. Test Single Delete

**Steps:**
1. In the admin table, find a test entry
2. Hover over row → Click "Delete" link
3. Browser prompt appears asking for password
4. Enter: `jiomerelal`
5. Entry should be deleted
6. Page refreshes with success message

**Status:** [ ] Single delete works

---

## 10. Test Bulk Delete

**Steps:**
1. Check multiple entries using checkboxes
2. Select "Delete" from "Bulk Actions" dropdown
3. Click "Apply"
4. Password modal should appear
5. Enter password: `jiomerelal`
6. Click "Delete" button
7. Entries should be deleted
8. Success message appears

**Status:** [ ] Bulk delete works

---

## 11. Test Password Protection

**Verify password security:**

**Wrong Password Test:**
1. Try to delete with wrong password
2. Should show error message
3. Entry should NOT be deleted

**Correct Password Test:**
1. Use password: `jiomerelal` (exact, lowercase)
2. Should delete successfully

**Status:** [ ] Password protection works

---

## 12. Test with Multiple Forms

**If you have multiple CF7 forms:**
1. Submit to Form 1
2. Submit to Form 2
3. Go to admin page
4. Dropdown should show both forms
5. Select Form 1 → Shows only Form 1 data
6. Select Form 2 → Shows only Form 2 data

**Status:** [ ] Multiple forms work

---

## 13. Test User Tracking

**Guest User Test:**
1. Log out of WordPress
2. Submit a form
3. Check admin → Should show "0 (Guest)"

**Logged-in User Test:**
1. Log in to WordPress
2. Submit a form
3. Check admin → Should show "Username (ID)"

**Status:** [ ] User tracking works

---

## 14. Test Google Sheets Independence

**Verify feature works without Google Sheets:**
1. Disable Google Sheets in widget settings
2. Submit a form
3. Check admin → Data should still appear
4. Enable Google Sheets
5. Submit again
6. Check → Data goes to BOTH database AND sheets

**Status:** [ ] Works independently

---

## 15. Test Pagination

**If you have 20+ entries:**
1. Submit 25+ test entries
2. Check admin page
3. Should show 20 entries per page
4. Pagination links at bottom
5. Click page 2 → Shows next 20 entries

**Status:** [ ] Pagination works

---

## 16. Check for Errors

**In WordPress:**
1. Enable WP_DEBUG in wp-config.php
2. Submit a form
3. Check for PHP errors/warnings
4. Check browser console for JS errors

**In Admin:**
1. Check admin page loads without errors
2. No console errors
3. All features functional

**Status:** [ ] No errors found

---

## 17. Check Existing Features

**Verify nothing broke:**
- [ ] CF7 forms still send emails
- [ ] Google Sheets integration still works
- [ ] Other widgets still function
- [ ] Elementor editor loads correctly
- [ ] Frontend displays properly

**Status:** [ ] Existing features work

---

## Common Issues & Solutions

### ❌ Table Not Created
**Solution:**
1. Deactivate plugin
2. Reactivate plugin
3. Check database permissions

### ❌ Menu Not Appearing
**Solution:**
1. Clear browser cache
2. Check user has 'manage_options' capability
3. Refresh admin page

### ❌ Data Not Saving
**Solution:**
1. Verify Contact Form 7 is active
2. Check form uses CF7 Popup widget
3. Check WordPress error logs
4. Verify database table exists

### ❌ CSV Download Fails
**Solution:**
1. Check file permissions
2. Disable output buffering plugins
3. Check PHP error logs

### ❌ Password Doesn't Work
**Solution:**
1. Verify exact password: `jiomerelal` (case-sensitive)
2. Clear browser cache
3. Try incognito mode
4. Check browser console for JS errors

### ❌ File URLs Don't Work
**Solution:**
1. Check WordPress uploads folder permissions
2. Verify files uploaded successfully
3. Check .htaccess rules
4. Check file URL in database

---

## Final Verification Summary

**Check all items below:**

- [ ] All 4 new files exist
- [ ] Database table created
- [ ] Admin menu appears
- [ ] Form submissions save to database
- [ ] Data displays in admin table
- [ ] Dynamic columns work correctly
- [ ] File uploads show as links
- [ ] CSV export works
- [ ] Single delete works with password
- [ ] Bulk delete works with password
- [ ] Password protection prevents unauthorized deletes
- [ ] Multiple forms work independently
- [ ] User tracking (guest/logged-in) works
- [ ] Feature works without Google Sheets
- [ ] Pagination works (if applicable)
- [ ] No PHP/JS errors
- [ ] Existing features still work

---

## 🎉 Success Criteria

**Feature is working correctly if:**

1. ✅ All files are in place
2. ✅ Database table exists with correct structure
3. ✅ Admin menu is visible and accessible
4. ✅ Form submissions automatically save to database
5. ✅ Admin page displays submissions correctly
6. ✅ Columns dynamically adjust based on form fields
7. ✅ CSV export downloads with full data
8. ✅ Delete functions require correct password
9. ✅ No errors in logs or console
10. ✅ Existing functionality unaffected

---

## 🆘 If Something's Not Working

1. **Check Error Logs:**
   - WordPress debug.log
   - PHP error log
   - Browser console

2. **Verify Prerequisites:**
   - WordPress 5.0+
   - PHP 7.0+
   - Contact Form 7 active
   - Database write permissions

3. **Review Documentation:**
   - `QUICK_START_REGISTRATION_ENTRIES.md`
   - `REGISTRATION_ENTRIES_FEATURE.md`

4. **Common Fixes:**
   - Deactivate and reactivate plugin
   - Clear all caches
   - Check file permissions
   - Verify database user has CREATE TABLE privilege

---

**Need Help?**
- Check the documentation files in the plugin folder
- Review error logs for specific issues
- Test with a simple form first
- Verify WordPress and PHP versions meet requirements

---

**Verification Complete?**

If all items are checked, the feature is fully functional and ready for production use! 🎉
