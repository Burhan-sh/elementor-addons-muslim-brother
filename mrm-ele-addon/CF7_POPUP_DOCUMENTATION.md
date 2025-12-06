# MRM CF7 Popup Widget Documentation

## Overview

The **MRM CF7 Popup Widget** is a powerful Contact Form 7 integration for Elementor that displays forms in a customizable popup modal. It includes advanced features like Google Sheets integration, time-based triggers, and enhanced security.

## Features

### 1. **Popup Display**
- Responsive design (Desktop, Tablet, Mobile)
- Customizable popup modal
- Multiple trigger options
- Smooth animations

### 2. **Contact Form 7 Integration**
- Select any CF7 form
- Show/hide form labels
- Full form styling control
- Custom CSS support

### 3. **Popup Trigger Options**

#### Button Click (Default)
- Customizable button text
- Button styling (color, hover effects, animations)
- Alignment options

#### Auto Popup
- Time-delayed trigger (configurable in seconds)
- Show after X seconds on page

#### Page Load
- Show immediately when page loads
- Optional delay

#### Exit Intent
- Trigger when user moves cursor to leave page

### 4. **Popup Frequency Control**

- **Always Show**: Display on every trigger
- **Once Per Session**: Show only once per browser session
- **Once Per User**: Show only once per user (lifetime cookie)
- **Time Interval**: Show every X minutes (configurable)

### 5. **Email Settings**

#### CC Email (Optional)
- Send copy to additional email addresses
- Multiple emails supported (comma-separated)
- Fully sanitized and validated

### 6. **Google Sheets Integration** (Optional)

#### Setup Requirements
1. Google Sheet ID
2. Sheet Name/Tab
3. Google API Key
4. Field Mapping (JSON format)

#### Field Mapping Format
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "your-phone": "Phone",
  "your-message": "Message"
}
```

#### Features
- Automatic timestamp insertion
- File upload support (stores URLs only)
- Column mapping to CF7 fields
- Secure API communication

### 7. **Security Features**

#### SQL Injection Prevention
- Pattern detection for SQL keywords
- Input sanitization
- Query validation

#### XSS Protection
- Script tag filtering
- JavaScript code removal
- Event handler blocking
- Safe HTML sanitization

#### Command Injection Prevention
- Shell command blocking
- System function detection
- Path traversal prevention

#### File Upload Security
- File type validation
- File size limits (5MB max)
- Extension whitelist
- Content scanning for malicious code
- Double extension prevention

#### Rate Limiting
- 5 submissions per 5 minutes per IP
- Automatic blocking
- User-friendly error messages

#### Security Logging
- All incidents logged
- Admin email alerts for critical threats
- Database storage
- Automatic log cleanup (30 days)

## Installation

1. Make sure **Elementor** is installed and activated
2. Install **Contact Form 7** plugin
3. The widget will appear in Elementor under "MRM Elements" category

## Usage

### Basic Setup

1. **Add Widget**
   - Drag "MRM CF7 Popup" widget to your page
   - Select a Contact Form 7 from dropdown
   - Configure trigger type

2. **Customize Button**
   - Set button text
   - Choose alignment
   - Style colors, typography, and hover effects

3. **Configure Popup**
   - Set popup width and padding
   - Customize background colors
   - Add border radius and shadows

### Advanced Configuration

#### Google Sheets Integration

1. **Create Google Sheet**
   - Create a new Google Sheet
   - Add column headers matching your form fields
   - Note the Sheet ID from URL

2. **Get API Key**
   - Go to Google Cloud Console
   - Create/select a project
   - Enable Google Sheets API
   - Create API credentials (API Key)

3. **Configure Widget**
   - Enable Google Sheets in widget settings
   - Enter Sheet ID
   - Enter Sheet Name (e.g., "Sheet1")
   - Enter API Key
   - Add field mapping JSON

4. **Field Mapping Example**
   ```json
   {
     "your-name": "Full Name",
     "your-email": "Email Address",
     "your-phone": "Phone Number",
     "your-message": "Message",
     "your-subject": "Subject"
   }
   ```

#### File Upload Handling

When a CF7 form includes file uploads:
- Files are stored in WordPress media library
- Only the file URL is sent to Google Sheets
- Supported formats: Images, PDFs, Documents
- Maximum size: 5MB per file

#### Email CC Configuration

1. Enable CC Email in widget settings
2. Enter email address(es)
3. Multiple emails: `email1@example.com, email2@example.com`
4. Emails are sent after successful CF7 submission

### Popup Trigger Examples

#### Auto Popup After 10 Seconds
```
Trigger Type: Auto Popup
Delay: 10 seconds
Frequency: Once Per Session
```

#### Exit Intent (One Time Only)
```
Trigger Type: Exit Intent
Frequency: Once Per User
```

#### Recurring Every 5 Minutes
```
Trigger Type: Button Click
Frequency: Time Interval
Interval: 5 minutes
```

## Styling Options

### Button Styling
- Typography (font, size, weight)
- Text color
- Background color
- Border
- Border radius
- Padding
- Box shadow
- Hover effects
- Animations (Elementor hover animations)

### Popup Modal Styling
- Background color
- Width (responsive)
- Max width
- Padding
- Border radius
- Box shadow
- Overlay color

### Close Button Styling
- Color
- Hover color
- Size

### Form Fields Styling
- Background color
- Text color
- Typography
- Padding
- Border
- Border radius
- Focus state

### Submit Button Styling
- Typography
- Text color
- Background color
- Hover colors
- Padding
- Border radius

## JavaScript Events

### Custom Events

The widget triggers custom jQuery events you can hook into:

```javascript
// Popup opened
$(document).on('mrm_cf7_popup_opened', function(e, widgetId) {
    console.log('Popup opened:', widgetId);
});

// Popup closed
$(document).on('mrm_cf7_popup_closed', function(e, widgetId) {
    console.log('Popup closed:', widgetId);
});

// Form submitted successfully
$(document).on('mrm_cf7_popup_submitted', function(e, widgetId, formData) {
    console.log('Form submitted:', widgetId, formData);
});
```

## Security Best Practices

1. **API Keys**
   - Never expose API keys publicly
   - Use environment-specific keys
   - Rotate keys regularly

2. **Email Validation**
   - Always validate CC email addresses
   - Use trusted email providers

3. **Form Validation**
   - Enable CF7 validation
   - Use required fields
   - Add reCAPTCHA for spam protection

4. **File Uploads**
   - Only enable if necessary
   - Set appropriate file size limits
   - Use file type restrictions

5. **Rate Limiting**
   - Monitor security logs
   - Adjust rate limits if needed
   - Block suspicious IPs

## Troubleshooting

### Popup Not Opening
- Check if Contact Form 7 plugin is active
- Verify form is selected in widget settings
- Check browser console for JavaScript errors
- Clear browser cache

### Google Sheets Not Working
- Verify API key is correct and active
- Check Sheet ID is correct
- Ensure Google Sheets API is enabled
- Verify field mapping JSON is valid
- Check sheet permissions (must be accessible with API key)

### CC Email Not Sending
- Check email address format
- Verify WordPress mail function works
- Check spam folder
- Review mail server logs

### Form Submission Blocked
- Check rate limiting (5 per 5 minutes)
- Wait and try again
- Clear cookies if testing
- Check security logs

### File Upload Issues
- Check file size (max 5MB)
- Verify file type is allowed
- Check server upload limits
- Review file permissions

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance

- Lightweight CSS and JavaScript
- Lazy loading of popup content
- Optimized for mobile devices
- No external dependencies (except jQuery)

## GDPR Compliance

When using Google Sheets integration:
- Inform users in your privacy policy
- Get consent for data processing
- Secure API keys properly
- Implement data retention policies

## Support

For issues or questions:
1. Check this documentation
2. Review security logs
3. Test with default CF7 form
4. Check WordPress debug log

## Changelog

### Version 1.0.0
- Initial release
- Contact Form 7 integration
- Google Sheets integration
- Multiple trigger options
- Advanced security features
- Rate limiting
- File upload support
- CC email functionality
- Responsive design
- Custom styling options

## Credits

Developed by: Burhan Hasanfatta
Plugin: MRM Ele Addon
