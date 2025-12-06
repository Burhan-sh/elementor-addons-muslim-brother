/**
 * Send data to Google Sheets
 * FIXED VERSION - Use this to replace lines 75-128 in cf7-popup-ajax-handler.php
 */
private function send_to_google_sheets($sheet_id, $sheet_name, $api_key, $data) {
    // Prepare values
    $values = array();
    $row = array();
    
    foreach ($data as $column => $value) {
        $row[] = $value;
    }
    
    $values[] = $row;
    
    // Prepare request body
    $body = array(
        'values' => $values,
        'majorDimension' => 'ROWS'
    );

    // Build Google Sheets API URL with API key
    $url = sprintf(
        'https://sheets.googleapis.com/v4/spreadsheets/%s/values/%s:append?key=%s&valueInputOption=USER_ENTERED',
        $sheet_id,
        $sheet_name,
        $api_key
    );

    // Make API request
    $response = wp_remote_post($url, array(
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body' => wp_json_encode($body),
        'timeout' => 30,
        'sslverify' => true,
    ));

    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => $response->get_error_message()
        );
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        return array(
            'success' => true,
            'data' => json_decode($response_body, true)
        );
    } else {
        // Log detailed error for debugging
        error_log('MRM CF7 Popup - Google Sheets API Error: ' . $response_body);
        return array(
            'success' => false,
            'message' => 'API request failed with status code: ' . $response_code,
            'details' => json_decode($response_body, true)
        );
    }
}
