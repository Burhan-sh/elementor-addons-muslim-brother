# Quick Start Guide - Registration Form Submissions

## ✅ Feature is Ready!

The registration form submission feature is now active in your MRM Ele Addon plugin.

## 🚀 What's New?

Every Contact Form 7 submission is now automatically saved to your database with:
- All form field data
- File upload URLs
- Submission date & time
- User ID (if logged in)

## 📍 Where to Find It?

**WordPress Admin Panel → Registration Form Submissions**

Look for the new menu item in your WordPress admin sidebar with a list icon.

## 🎯 How to Use

### View Submissions

1. Go to **Registration Form Submissions** in admin
2. Select a form from the dropdown
3. Click **Search**
4. See all submissions in a table

### Export to CSV

1. Select a form
2. Click **Download CSV** button
3. Open in Excel or Google Sheets

### Delete Entries

**Password Required**: `jiomerelal`

**Single Entry:**
- Click delete link → Enter password

**Multiple Entries:**
- Check boxes → Select "Delete" → Click Apply → Enter password

## 🔄 Dynamic Table Columns

The table automatically creates columns based on your form fields!

**Example:**

If your form has:
- Name field
- Email field  
- Phone field
- Aadhar file upload

The table will show columns:
- Name
- Email
- Phone
- Aadhar (with download link)
- Date & Time
- User

Different forms = Different columns automatically!

## 📊 Data Storage

All form submissions are stored in database table: `wp_mrm_registration_entries`

Data format (JSON):
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "file-field": "https://yoursite.com/uploads/file.pdf",
  "message": "Hello world"
}
```

## ⚙️ Important Notes

### ✅ Works Automatically
- No configuration needed
- Starts working immediately
- Independent of Google Sheets
- Won't affect existing functionality

### ✅ Secure
- Only admins can access
- Password-protected deletion
- SQL injection prevention
- XSS protection

### ✅ Performance
- Indexed database queries
- Pagination (20 entries per page)
- Fast CSV export

## 🔍 Features Checklist

- [x] Automatic database storage on form submission
- [x] Admin menu "Registration Form Submissions"
- [x] Form selector dropdown
- [x] Dynamic table columns
- [x] File upload URLs as clickable links
- [x] CSV export with full data
- [x] Single entry delete with password
- [x] Bulk delete with password
- [x] User ID tracking
- [x] Date/time recording
- [x] Responsive design
- [x] Pagination support

## 🎨 What You'll See

**Admin Page Header:**
```
┌─────────────────────────────────────────────┐
│ Select Form: [▼ Choose Form...] [Search]   │
│              [Download CSV]                  │
└─────────────────────────────────────────────┘
```

**Data Table:**
```
┌───┬─────────┬─────────────┬──────────┬────────────┬────────┐
│ □ │ ID      │ Name        │ Email    │ Phone      │ Date   │
├───┼─────────┼─────────────┼──────────┼────────────┼────────┤
│ □ │ 123     │ John Doe    │ john@... │ 123456...  │ Dec 7  │
│ □ │ 122     │ Jane Smith  │ jane@... │ 987654...  │ Dec 6  │
└───┴─────────┴─────────────┴──────────┴────────────┴────────┘
```

## 🛠️ Testing Steps

1. **Create a CF7 form** (if you don't have one)
2. **Add CF7 Popup widget** to a page
3. **Submit the form** from frontend
4. **Check admin panel** → Registration Form Submissions
5. **Select your form** from dropdown
6. **View the data** in the table
7. **Try CSV export** to verify data
8. **Test delete** with password: `jiomerelal`

## 📝 Delete Password

**Remember**: `jiomerelal`

Type it exactly as shown (all lowercase, no spaces)

## 💡 Pro Tips

1. **Different Forms → Different Views**
   - Each form shows its own data
   - Columns adjust automatically

2. **CSV for Backup**
   - Export regularly for backups
   - Share data with team members

3. **File Uploads**
   - Files are stored in WordPress media library
   - Table shows clickable links to files
   - URLs work even if you export to CSV

4. **Guest vs Logged-in Users**
   - Guest submissions show "0 (Guest)"
   - Logged-in users show "Username (ID)"

## ❓ Common Questions

**Q: Will this affect my existing forms?**
A: No, it only adds database storage. Everything else works as before.

**Q: Do I need to configure Google Sheets?**
A: No, this works independently. Google Sheets is optional.

**Q: What happens if I delete the plugin?**
A: The database table will remain. You can manually drop it if needed.

**Q: Can I customize the table columns?**
A: Yes, by editing the code in `registration-entries-admin.php`

**Q: Is there a limit on storage?**
A: Only your database size limit. The plugin uses efficient JSON storage.

## 📞 Need Help?

Check the full documentation: `REGISTRATION_ENTRIES_FEATURE.md`

---

**You're All Set! 🎉**

The feature is working now. Just use your Contact Form 7 as usual, and all data will be automatically saved and accessible in the admin panel.
