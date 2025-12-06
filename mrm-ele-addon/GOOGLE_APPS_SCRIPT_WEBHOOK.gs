/**
 * Google Apps Script - CF7 Popup Webhook Handler
 * 
 * Setup Instructions (हिंदी में):
 * 1. अपनी Google Sheet खोलें
 * 2. Extensions > Apps Script पर जाएं
 * 3. यह पूरा code paste करें
 * 4. Save करें (Ctrl + S)
 * 5. Deploy > New Deployment क्लिक करें
 * 6. "Select type" > "Web app" चुनें
 * 7. Settings:
 *    - Description: "CF7 Form Webhook"
 *    - Execute as: "Me"
 *    - Who has access: "Anyone"
 * 8. "Deploy" क्लिक करें
 * 9. Copy the Web App URL
 * 10. Elementor widget में paste करें
 */

/**
 * Handle POST requests from CF7 form
 */
function doPost(e) {
  try {
    // Get active spreadsheet and sheet
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    
    // Parse incoming data
    var data = JSON.parse(e.postData.contents);
    
    // Get headers from first row (if exists)
    var headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];
    
    // If no headers, create them from data keys
    if (headers.length === 0 || headers[0] === '') {
      headers = Object.keys(data);
      sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
      
      // Format header row
      sheet.getRange(1, 1, 1, headers.length)
        .setBackground('#4285f4')
        .setFontColor('#ffffff')
        .setFontWeight('bold');
    }
    
    // Prepare row data based on headers
    var row = [];
    for (var i = 0; i < headers.length; i++) {
      var header = headers[i];
      row.push(data[header] || '');
    }
    
    // Append row to sheet
    sheet.appendRow(row);
    
    // Format the new row
    var lastRow = sheet.getLastRow();
    sheet.getRange(lastRow, 1, 1, headers.length)
      .setBorder(true, true, true, true, false, false);
    
    // Auto-resize columns
    for (var i = 1; i <= headers.length; i++) {
      sheet.autoResizeColumn(i);
    }
    
    // Return success response
    return ContentService.createTextOutput(JSON.stringify({
      'success': true,
      'message': 'Data added successfully',
      'row': lastRow,
      'timestamp': new Date().toISOString()
    })).setMimeType(ContentService.MimeType.JSON);
    
  } catch (error) {
    // Return error response
    return ContentService.createTextOutput(JSON.stringify({
      'success': false,
      'message': 'Error: ' + error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * Handle GET requests (for testing)
 */
function doGet(e) {
  return ContentService.createTextOutput(JSON.stringify({
    'status': 'active',
    'message': 'CF7 Webhook is ready to receive data',
    'method': 'POST',
    'contentType': 'application/json'
  })).setMimeType(ContentService.MimeType.JSON);
}

/**
 * Test function to verify setup
 */
function testWebhook() {
  var testData = {
    'Name': 'Test User',
    'Email': 'test@example.com',
    'Phone': '+91 1234567890',
    'Message': 'This is a test message',
    'Timestamp': new Date().toISOString()
  };
  
  var mockEvent = {
    postData: {
      contents: JSON.stringify(testData)
    }
  };
  
  var result = doPost(mockEvent);
  Logger.log(result.getContent());
}

/**
 * Advanced: Custom email notification on form submission
 */
function sendEmailNotification(data) {
  var recipient = 'your-email@example.com'; // Change this
  var subject = 'New Form Submission - ' + new Date().toLocaleDateString();
  
  var body = 'New form submission received:\n\n';
  for (var key in data) {
    body += key + ': ' + data[key] + '\n';
  }
  
  MailApp.sendEmail(recipient, subject, body);
}

/**
 * Advanced: Add timestamp in India timezone
 */
function getIndiaTimestamp() {
  var now = new Date();
  var options = {
    timeZone: 'Asia/Kolkata',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  };
  return now.toLocaleString('en-IN', options);
}
