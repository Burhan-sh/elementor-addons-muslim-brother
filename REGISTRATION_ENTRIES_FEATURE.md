# Registration Form Submissions Feature - Complete Documentation

## Overview
A new feature has been added to the MRM Ele Addon plugin that automatically stores all Contact Form 7 submissions in a database and displays them in a WordPress admin panel with full management capabilities.

## Key Features

### 1. Automatic Database Storage
- **Automatic Activation**: Works by default when CF7 Popup widget is added - no configuration needed
- **Independent from Google Sheets**: Stores data even if Google Sheets integration is disabled
- **Complete Data Capture**: Stores all form fields including file uploads (as URLs)
- **User Tracking**: Records user ID if logged in, otherwise stores as 0 (guest)

### 2. Database Structure
Table Name: `wp_mrm_registration_entries`

Columns:
- `id` - Auto-increment primary key
- `form_name` - Name of the Contact Form 7 form
- `form_id` - ID of the Contact Form 7 form
- `data` - JSON encoded form data (all fields)
- `date_time` - Submission date and time
- `user_id` - WordPress user ID (0 for guests)

### 3. Admin Interface
Location: WordPress Admin → **Registration Form Submissions**

#### Features:
- **Form Selector Dropdown**: Select which form's submissions to view
- **Dynamic Table Columns**: Table headers automatically adjust based on form fields
- **Responsive Design**: Works on all screen sizes
- **Pagination**: Shows 20 entries per page
- **Search and Filter**: Select specific forms to view their data

### 4. Data Display
- Each form field becomes a column in the table
- File uploads are shown as clickable links to view/download
- User information displays username and ID (or "0 (Guest)")
- Date/time of submission is recorded

### 5. CSV Export
- **Full Data Export**: Export all entries for selected form
- **Formatted Headers**: Column names are human-readable
- **UTF-8 Support**: Properly handles international characters
- **Timestamped Filename**: `registration-entries-{form-name}-{date-time}.csv`

How to use:
1. Select a form from dropdown
2. Click "Download CSV" button
3. File downloads automatically

### 6. Delete Functionality

#### Password Protection
**Password**: `jiomerelal`

#### Single Entry Delete:
1. Click delete link in table row
2. Enter password in browser prompt
3. Entry is deleted

#### Bulk Delete:
1. Select multiple entries using checkboxes
2. Choose "Delete" from bulk actions dropdown
3. Click "Apply"
4. Enter password in modal popup
5. Selected entries are deleted

## How It Works

### Automatic Data Capture Flow

1. **User Submits Form**
   - User fills out Contact Form 7 form
   - Clicks submit button

2. **Form Processing**
   - CF7 processes the form
   - Sends email (if configured)
   - Triggers `wpcf7_mail_sent` action

3. **Database Storage** (NEW)
   - Plugin hooks into `wpcf7_mail_sent` action
   - Extracts all form data
   - Converts file uploads to URLs
   - Stores data as JSON in database
   - Records timestamp and user ID

4. **Google Sheets** (if enabled)
   - Data is also sent to Google Sheets
   - Works independently of database storage

### Data Format in Database

Example JSON stored in `data` column:

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "aadhar-file": "https://example.com/wp-content/uploads/2025/12/aadhar-123456.pdf",
  "description": "Sample message"
}
```

## File Structure

### New Files Created

1. **includes/registration-entries-db.php**
   - Handles database operations
   - Creates table on initialization
   - Captures form submissions
   - Provides query methods

2. **includes/registration-entries-admin.php**
   - Admin page registration
   - WP_List_Table implementation
   - CSV export functionality
   - Delete with password protection

3. **assets/css/admin-entries.css**
   - Styling for admin interface
   - Responsive design
   - Modal styles for password prompt

### Modified Files

1. **mrm-ele-addon.php**
   - Added loading of new includes
   - Made DB handler globally accessible

## Usage Instructions

### For Site Administrators

1. **Viewing Submissions**
   - Go to WordPress Admin
   - Click "Registration Form Submissions" in sidebar
   - Select a form from dropdown
   - Click "Search"
   - View all submissions in table format

2. **Exporting Data**
   - Select a form
   - Click "Download CSV" button
   - Open CSV file in Excel or Google Sheets

3. **Deleting Entries**
   - Single: Click delete link, enter password
   - Bulk: Select entries, choose "Delete", enter password
   - Password: `jiomerelal`

### For Developers

#### Accessing Data Programmatically

```php
// Get database handler
global $mrm_registration_db;

// Get all form names
$forms = $mrm_registration_db->get_form_names();

// Get entries for specific form
$entries = $mrm_registration_db->get_entries_by_form($form_id, $per_page, $page_number);

// Get total count
$count = $mrm_registration_db->get_entries_count_by_form($form_id);

// Delete entry
$mrm_registration_db->delete_entry($entry_id);
```

#### Customizing Table Columns

The table columns are generated dynamically from the JSON data. To modify how columns are displayed, edit the `column_default()` method in `MRM_Registration_Entries_List_Table` class.

## Security Features

1. **SQL Injection Prevention**
   - All queries use prepared statements
   - Data is sanitized before storage

2. **XSS Protection**
   - All output is escaped
   - HTML is filtered using `wp_kses_post()`

3. **Password-Protected Delete**
   - Deletion requires password: `jiomerelal`
   - Prevents accidental data loss

4. **Capability Checks**
   - Admin page requires `manage_options` capability
   - Only administrators can access

5. **Nonce Verification**
   - All actions use WordPress nonces
   - Prevents CSRF attacks

## Database Performance

- Indexes on `form_id`, `date_time`, and `user_id` for fast queries
- Pagination to handle large datasets
- JSON storage for flexible schema

## Compatibility

- **WordPress**: 5.0+
- **PHP**: 7.0+
- **Contact Form 7**: Any version
- **Elementor**: 3.0+

## Troubleshooting

### Table Not Created
If the table doesn't exist:
1. Deactivate and reactivate the plugin
2. Check database permissions
3. Check error logs in WordPress

### Data Not Saving
If form submissions aren't being saved:
1. Verify Contact Form 7 is active
2. Check that forms are using CF7 Popup widget
3. Check WordPress error logs
4. Verify database table exists

### CSV Export Issues
If CSV download fails:
1. Check file permissions
2. Verify output buffering is off
3. Check for PHP errors in logs

### Password Not Working
If delete password doesn't work:
1. Verify password is exactly: `jiomerelal` (case-sensitive)
2. Clear browser cache
3. Check for JavaScript errors

## Future Enhancements (Optional)

Potential features that can be added:
- Search/filter within table
- Export to other formats (Excel, PDF)
- Email notifications for new submissions
- Data visualization/charts
- Duplicate entry detection
- Form submission statistics
- Custom column sorting
- Entry editing capability

## Support

For issues or questions:
1. Check WordPress error logs
2. Enable WordPress debug mode
3. Check browser console for JavaScript errors
4. Review database structure

## Changelog

### Version 1.0.0 (Current)
- Initial release
- Automatic database storage
- Admin interface with WP_List_Table
- Dynamic columns based on form fields
- CSV export functionality
- Password-protected delete (single and bulk)
- User tracking
- File upload URL storage

---

**Note**: This feature works completely independently. You don't need to configure anything - it starts working automatically when you add the CF7 Popup widget to any page.
