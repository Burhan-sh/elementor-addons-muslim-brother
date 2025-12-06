# MRM CF7 Popup - Quick Reference Card

## 🎯 Widget Name
**MRM CF7 Popup**

## 📍 Location in Elementor
**Category**: MRM Elements  
**Icon**: Popup icon

## ⚡ Quick Setup (5 Minutes)

### Step 1: Add Widget
Drag "MRM CF7 Popup" to your page

### Step 2: Select Form
Choose your Contact Form 7 from dropdown

### Step 3: Configure Trigger
- **Button Click**: Default, shows button
- **Auto Popup**: Set delay in seconds
- **Page Load**: Opens immediately
- **Exit Intent**: Opens when user leaves

### Step 4: Style & Publish
Done! That's the basic setup.

## 🔧 Common Settings

### Button Text
```
Default: "Contact Us"
Location: Content Tab > Popup Button
```

### Popup Frequency
```
Always Show: Every time
Once Per Session: Once per visit
Once Per User: Once lifetime
Time Interval: Every X minutes
```

### Labels
```
Show Labels: ON (default)
Hide Labels: OFF
Location: Content Tab > Contact Form
```

## 🌐 Google Sheets Setup

### Required Info
1. **Sheet ID**: From URL
   ```
   https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit
   ```

2. **Sheet Name**: Usually "Sheet1"

3. **API Key**: From Google Cloud Console

4. **Field Mapping**: JSON format
   ```json
   {
     "your-name": "Name",
     "your-email": "Email",
     "your-phone": "Phone",
     "your-message": "Message"
   }
   ```

### Google API Steps
1. Go to: console.cloud.google.com
2. Create project
3. Enable "Google Sheets API"
4. Create API Key
5. Copy and paste in widget

## 📧 CC Email Setup

### Location
Content Tab > Email Settings

### Format
```
Single: email@example.com
Multiple: email1@example.com, email2@example.com
```

## 🎨 Styling Quick Access

### Button Styling
```
Style Tab > Button
- Typography
- Colors (Normal & Hover)
- Border
- Border Radius
- Padding
- Animation
```

### Popup Styling
```
Style Tab > Popup Modal
- Background Color
- Width
- Padding
- Border Radius
- Overlay Color
```

### Form Fields
```
Style Tab > Form Fields
- Background
- Text Color
- Border
- Padding
```

## 🔐 Security Features (Automatic)

✅ SQL Injection Protection  
✅ XSS Protection  
✅ Command Injection Prevention  
✅ File Upload Security (5MB max)  
✅ Rate Limiting (5 per 5 min)  
✅ Security Logging  

## 🚨 Common Issues

### Popup Not Opening
- ✓ Check CF7 plugin is active
- ✓ Select a form in widget settings
- ✓ Clear browser cache

### Google Sheets Not Working
- ✓ Verify API key is correct
- ✓ Check Sheet ID
- ✓ Enable Google Sheets API
- ✓ Validate JSON mapping

### Form Not Submitting
- ✓ Rate limit (wait 5 minutes)
- ✓ Check CF7 form settings
- ✓ Clear cookies

## 📱 Responsive

Widget is **automatically responsive** for:
- Desktop (Full width popup)
- Tablet (95% width)
- Mobile (95% width, adjusted padding)

## 🎬 JavaScript Events

```javascript
// Popup opened
$(document).on('mrm_cf7_popup_opened', function(e, widgetId) {
    console.log('Opened:', widgetId);
});

// Popup closed
$(document).on('mrm_cf7_popup_closed', function(e, widgetId) {
    console.log('Closed:', widgetId);
});

// Form submitted
$(document).on('mrm_cf7_popup_submitted', function(e, widgetId, data) {
    console.log('Submitted:', widgetId, data);
});
```

## 📊 Field Mapping Examples

### Basic Contact Form
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "your-message": "Message"
}
```

### Advanced Form
```json
{
  "your-name": "Full Name",
  "your-email": "Email Address",
  "your-phone": "Phone Number",
  "your-company": "Company",
  "your-subject": "Subject",
  "your-message": "Message",
  "your-file": "Attachment URL"
}
```

### With Custom Fields
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "budget": "Budget",
  "project-type": "Project Type",
  "deadline": "Deadline"
}
```

## 🔑 Important Keys

### Plugin Constants
```php
Plugin Name: MRM Ele Addon
Widget Class: CF7_Popup_Widget
Category: mrm-elements
```

### Script Handles
```php
CSS: mrm-cf7-popup-style
JS: mrm-cf7-popup-script
```

### AJAX Actions
```php
Google Sheets: mrm_cf7_popup_google_sheets
CC Email: mrm_cf7_popup_send_cc
```

## 📁 File Structure

```
mrm-ele-addon/
├── widgets/
│   └── cf7-popup-widget.php
├── includes/
│   ├── cf7-popup-ajax-handler.php
│   └── cf7-popup-security.php
├── assets/
│   ├── css/
│   │   └── cf7-popup-style.css
│   └── js/
│       └── cf7-popup-script.js
└── mrm-ele-addon.php
```

## 🎯 Pro Tips

1. **Test with default CF7 form first**
2. **Use session storage for testing** (Once Per Session)
3. **Check browser console** for errors
4. **Keep API keys secure**
5. **Add reCAPTCHA** to CF7 form for spam protection
6. **Monitor security logs** in database
7. **Use time intervals** for non-intrusive popups
8. **Test on mobile devices**
9. **Clear cache** after changes
10. **Backup before updates**

## 📞 Support Checklist

Before asking for help:

- [ ] CF7 plugin installed and active
- [ ] Form created in CF7
- [ ] Form selected in widget
- [ ] Browser console checked for errors
- [ ] Cache cleared
- [ ] Tested with different trigger types
- [ ] Verified API keys (if using Google Sheets)
- [ ] Checked WordPress debug log

## 🌟 Best Practices

### For Better UX
- Use exit intent for non-intrusive popup
- Set "Once Per Session" for better experience
- Keep form fields minimal
- Use clear button text
- Test on multiple devices

### For Better Security
- Always use rate limiting (built-in)
- Add reCAPTCHA to CF7 form
- Keep WordPress and plugins updated
- Monitor security logs
- Use strong API keys

### For Better Performance
- Minimize form fields
- Optimize images in popup
- Use appropriate trigger delays
- Test popup load time

## 🎨 Color Schemes Examples

### Professional Blue
```
Button BG: #0073aa
Button Hover: #005177
Popup BG: #ffffff
Overlay: rgba(0,0,0,0.8)
```

### Modern Orange
```
Button BG: #ff5722
Button Hover: #e64a19
Popup BG: #fafafa
Overlay: rgba(0,0,0,0.75)
```

### Elegant Dark
```
Button BG: #2c3e50
Button Hover: #34495e
Popup BG: #ecf0f1
Overlay: rgba(0,0,0,0.9)
```

## 📝 Documentation Files

1. **INSTALLATION_SUMMARY.md** - Complete feature list
2. **CF7_POPUP_DOCUMENTATION.md** - Full English documentation
3. **README_CF7_POPUP.md** - Hindi/Hinglish guide
4. **QUICK_REFERENCE.md** - This file

## ✅ Pre-Launch Checklist

- [ ] CF7 form created and tested
- [ ] Widget added to page
- [ ] Trigger type configured
- [ ] Button text customized
- [ ] Popup styled
- [ ] Google Sheets tested (if enabled)
- [ ] CC email tested (if enabled)
- [ ] Mobile view checked
- [ ] Security working (test file upload)
- [ ] Published and tested on live site

## 🚀 You're Ready!

Widget is fully functional and production-ready.

**Happy Building!** 🎉

---

**Plugin**: MRM Ele Addon  
**Widget**: MRM CF7 Popup  
**Version**: 1.0.0  
**Developer**: Burhan Hasanfatta
