# MRM CF7 Popup - Quick Start Guide

## Kya Hai Ye Widget?

**MRM CF7 Popup** ek powerful Elementor widget hai jo Contact Form 7 ko ek beautiful popup me display karta hai. Isme bahut saare advanced features hain jaise Google Sheets integration, time-based triggers, aur high security.

## Main Features

### 1. **Popup Ka Display**
- **Responsive**: Web, Tablet, aur Mobile sabhi me perfect dikhega
- **Customizable**: Har cheez ko apne hisaab se customize kar sakte ho
- **Multiple Triggers**: Button click, auto popup, page load, exit intent

### 2. **Form Integration**
- Contact Form 7 ke saath seamless integration
- Labels show/hide kar sakte ho
- Complete styling control
- Custom CSS support

### 3. **Popup Triggers**

#### **Button Click** (Default)
- Button ka text customize kar sakte ho
- Color, hover effects, animations sab control kar sakte ho

#### **Auto Popup**
- Time delay ke baad automatically popup open hoga
- Example: 5 seconds ke baad popup show karna

#### **Page Load**
- Jaise hi page load ho popup open ho jayega

#### **Exit Intent**
- Jab user page se jane lage tab popup open hoga

### 4. **Popup Frequency**

- **Always Show**: Har baar show hoga
- **Once Per Session**: Ek session me ek hi baar
- **Once Per User**: User ko zindagi me ek hi baar dikhega
- **Time Interval**: Har 5 min, 10 min, ya custom time ke baad

### 5. **Email Settings**

#### **CC Email** (Optional)
- Kisi aur email pe bhi copy bhej sakte ho
- Multiple emails support karta hai

### 6. **Google Sheets Integration** (Optional)

Form ka data directly Google Sheets me jayega!

#### Setup Steps:
1. Google Sheet ID
2. Sheet ka naam
3. Google API Key
4. Field mapping (JSON format me)

#### Example Field Mapping:
```json
{
  "your-name": "Name",
  "your-email": "Email",
  "your-phone": "Phone",
  "your-message": "Message"
}
```

### 7. **Security Features**

Ye widget **bahut hi zyada secure** hai:

- **SQL Injection Protection**: SQL injection attacks se bachata hai
- **XSS Protection**: Cross-site scripting se bachata hai
- **Command Injection Prevention**: Harmful commands ko block karta hai
- **File Upload Security**: Sirf safe files upload ho sakti hain (5MB max)
- **Rate Limiting**: Ek IP se 5 minutes me sirf 5 submissions
- **Security Logging**: Saari security incidents log hoti hain

## Installation

1. **Elementor** install aur activate karo
2. **Contact Form 7** plugin install karo
3. Widget automatically "MRM Elements" category me mil jayega

## Basic Setup - Step by Step

### Step 1: Widget Add Karo
1. Elementor editor open karo
2. "MRM CF7 Popup" widget ko page pe drag karo
3. Ek Contact Form 7 select karo

### Step 2: Button Customize Karo
1. Button text likho (jaise "Contact Us")
2. Alignment choose karo
3. Colors aur hover effects set karo

### Step 3: Popup Style Karo
1. Width aur padding set karo
2. Background color choose karo
3. Border radius aur shadows add karo

## Google Sheets Setup (Detailed)

### Step 1: Google Sheet Tayyar Karo
1. Google Sheets me nayi sheet banao
2. First row me column headers likho:
   ```
   Name | Email | Phone | Message | Timestamp
   ```
3. URL se Sheet ID copy karo:
   ```
   https://docs.google.com/spreadsheets/d/SHEET_ID_YAHA_HAI/edit
   ```

### Step 2: API Key Banao
1. [Google Cloud Console](https://console.cloud.google.com/) pe jao
2. New project banao
3. "APIs & Services" > "Library" me jao
4. "Google Sheets API" search karke enable karo
5. "Credentials" > "Create Credentials" > "API Key"
6. API Key copy karo

### Step 3: Widget Me Configure Karo
1. "Enable Google Sheets" on karo
2. Sheet ID paste karo
3. Sheet name likho (usually "Sheet1")
4. API Key paste karo
5. Field mapping JSON likho:

```json
{
  "your-name": "Name",
  "your-email": "Email",
  "your-phone": "Phone",
  "your-message": "Message"
}
```

**Important**: Left side me CF7 field name hai, right side me Google Sheet column name hai.

## File Upload Handling

Agar form me file upload hai:
- File WordPress media library me save hogi
- Google Sheets me sirf file ka URL jayega
- Supported: Images, PDFs, Documents
- Maximum size: 5MB

## Examples

### Example 1: Simple Contact Button
```
Trigger: Button Click
Button Text: "Get in Touch"
Frequency: Always Show
```

### Example 2: Welcome Popup
```
Trigger: Auto Popup
Delay: 3 seconds
Frequency: Once Per User
```

### Example 3: Exit Intent Popup
```
Trigger: Exit Intent
Frequency: Once Per Session
```

### Example 4: Recurring Popup
```
Trigger: Button Click
Frequency: Time Interval
Interval: 10 minutes
```

## Common Issues & Solutions

### Popup Nahi Khul Raha
- Contact Form 7 plugin active hai ya nahi check karo
- Form select kiya hai ya nahi dekho
- Browser console me error check karo
- Cache clear karo

### Google Sheets Kaam Nahi Kar Raha
- API key sahi hai check karo
- Sheet ID correct hai dekho
- Google Sheets API enabled hai confirm karo
- Field mapping JSON valid hai verify karo
- Sheet public hai ya API key ke saath accessible hai

### CC Email Nahi Ja Rahi
- Email address format sahi hai dekho
- WordPress mail function kaam kar raha hai test karo
- Spam folder check karo

### Form Submit Nahi Ho Raha
- Rate limit ho sakta hai (5 submissions per 5 minutes)
- Wait karke try karo
- Cookies clear karke test karo

## Security Tips

1. **API Keys Ko Safe Rakho**
   - Publicly share mat karo
   - Regular rotate karo

2. **Email Validation**
   - Hamesha valid email use karo
   - Trusted providers hi use karo

3. **Form Validation**
   - CF7 validation enable rakho
   - Required fields lagao
   - reCAPTCHA add karo spam ke liye

4. **File Uploads**
   - Sirf zarurat hone pe enable karo
   - File size limit set karo

## Performance Tips

- Widget lightweight hai
- Mobile pe fast hai
- jQuery ke alawa koi dependency nahi

## GDPR & Privacy

Google Sheets use kar rahe ho to:
- Privacy policy me mention karo
- User se consent lo
- Data secure rakho
- Retention policy set karo

## Support

Agar koi problem ho:
1. Ye documentation padho
2. Security logs check karo
3. Default CF7 form ke saath test karo
4. WordPress debug log dekho

## Version

**Current Version**: 1.0.0

## Credits

**Developer**: Burhan Hasanfatta  
**Plugin**: MRM Ele Addon  
**License**: GPL v2 or later

---

## Quick Reference - Field Mapping

### Common CF7 Field Names
```
your-name
your-email
your-phone
your-subject
your-message
your-company
your-website
```

### Example Complete Mapping
```json
{
  "your-name": "Full Name",
  "your-email": "Email Address",
  "your-phone": "Phone Number",
  "your-company": "Company Name",
  "your-subject": "Subject",
  "your-message": "Message",
  "your-file": "Attachment URL"
}
```

## Aur Kuch Chahiye?

Agar koi aur feature chahiye ya koi problem hai, to:
1. Documentation carefully padho
2. Examples try karo
3. Test form ke saath practice karo

**Happy Building!** 🚀
