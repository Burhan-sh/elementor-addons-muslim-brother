<?php
/**
 * MRM CF7 Popup - AJAX Handler
 * Handles Google Sheets integration and CC email functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class MRM_CF7_Popup_AJAX_Handler {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_mrm_cf7_popup_google_sheets', array($this, 'handle_google_sheets'));
        add_action('wp_ajax_nopriv_mrm_cf7_popup_google_sheets', array($this, 'handle_google_sheets'));
        
        add_action('wp_ajax_mrm_cf7_popup_send_cc', array($this, 'handle_cc_email'));
        add_action('wp_ajax_nopriv_mrm_cf7_popup_send_cc', array($this, 'handle_cc_email'));
        
        add_action('wp_ajax_mrm_cf7_popup_upload_file', array($this, 'handle_file_upload'));
        add_action('wp_ajax_nopriv_mrm_cf7_popup_upload_file', array($this, 'handle_file_upload'));
    }

    /**
     * Handle Google Sheets integration
     */
    public function handle_google_sheets() {
        // Verify nonce
        if (!check_ajax_referer('mrm_cf7_popup_nonce', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }

        // Sanitize and validate input
        $auth_method = sanitize_text_field($_POST['auth_method'] ?? 'service_account');
        $sheet_id = sanitize_text_field($_POST['sheet_id'] ?? '');
        $sheet_name = sanitize_text_field($_POST['sheet_name'] ?? 'Sheet1');
        $widget_id = sanitize_text_field($_POST['widget_id'] ?? '');
        
        // Decode JSON data string (sent from JS to avoid serialization issues with File objects)
        $data_raw = wp_unslash($_POST['data'] ?? '');
        if (is_string($data_raw)) {
            $data = json_decode($data_raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback to array if not JSON
                $data = is_array($data_raw) ? $data_raw : array();
            }
        } else {
            $data = is_array($data_raw) ? $data_raw : array();
        }
        
        // Debug logging
        error_log('MRM CF7 Popup - Google Sheets Request:');
        error_log('Auth Method: ' . $auth_method);
        error_log('Sheet ID: ' . $sheet_id);
        error_log('Widget ID: ' . $widget_id);
        error_log('Data received: ' . print_r($data, true));
        if ($auth_method === 'service_account') {
            error_log('File ID: ' . absint($_POST['file_id'] ?? 0));
        }

        // Validate required fields
        if (empty($sheet_id)) {
            wp_send_json_error(array('message' => 'Missing Google Sheet ID'));
            return;
        }

        // Sanitize data
        $sanitized_data = $this->sanitize_form_data($data);

        // Handle different authentication methods
        try {
            $result = false;
            
            switch ($auth_method) {
                case 'service_account':
                    $service_account_json = wp_unslash($_POST['service_account_json'] ?? '');
                    $service_account_path = sanitize_text_field($_POST['service_account_path'] ?? '');
                    $result = $this->send_to_google_sheets_service_account(
                        $sheet_id, 
                        $sheet_name, 
                        $service_account_json, 
                        $service_account_path, 
                        $sanitized_data
                    );
                    break;
                    
                case 'api_key':
                    $api_key = sanitize_text_field($_POST['api_key'] ?? '');
                    $result = $this->send_to_google_sheets_api_key(
                        $sheet_id, 
                        $sheet_name, 
                        $api_key, 
                        $sanitized_data
                    );
                    break;
                    
                case 'webhook':
                    $webhook_url = esc_url_raw($_POST['webhook_url'] ?? '');
                    $result = $this->send_to_google_sheets_webhook(
                        $webhook_url, 
                        $sanitized_data
                    );
                    break;
                    
                default:
                    wp_send_json_error(array('message' => 'Invalid authentication method'));
                    return;
            }
            
            if ($result['success']) {
                wp_send_json_success(array(
                    'message' => 'Data sent to Google Sheets successfully',
                    'data' => $result['data'] ?? array()
                ));
            } else {
                wp_send_json_error(array(
                    'message' => $result['message'],
                    'details' => $result['details'] ?? array()
                ));
            }
        } catch (Exception $e) {
            error_log('MRM CF7 Popup - Google Sheets Error: ' . $e->getMessage());
            wp_send_json_error(array(
                'message' => 'Failed to send data to Google Sheets: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Send to Google Sheets using Service Account
     */
    private function send_to_google_sheets_service_account($sheet_id, $sheet_name, $service_account_json, $service_account_path, $data) {
        // Get service account credentials
        $credentials = null;
        
        if (!empty($service_account_json)) {
            // Use JSON content (pasted directly)
            $credentials = json_decode($service_account_json, true);
        } elseif (!empty($service_account_path)) {
            // Check if it's an uploaded file (service_account_path = 'uploaded')
            if ($service_account_path === 'uploaded') {
                // Use uploaded file via Service Account Manager
                $widget_id = sanitize_text_field($_POST['widget_id'] ?? '');
                $file_id = absint($_POST['file_id'] ?? 0);
                
                if ($file_id && $widget_id) {
                    if (class_exists('MRM_Service_Account_Manager')) {
                        $credentials = MRM_Service_Account_Manager::get_credentials($file_id, $widget_id);
                    } else {
                        // Fallback to direct media library access
                        $file_path = get_attached_file($file_id);
                        if ($file_path && file_exists($file_path)) {
                            $json_content = file_get_contents($file_path);
                            $credentials = json_decode($json_content, true);
                        }
                    }
                } else {
                    return array(
                        'success' => false,
                        'message' => 'Service Account file not found. Please upload a valid JSON key file.',
                        'debug' => array(
                            'widget_id' => $widget_id,
                            'file_id' => $file_id
                        )
                    );
                }
            } else {
                // Direct file path (legacy support)
                if (file_exists($service_account_path)) {
                    $json_content = file_get_contents($service_account_path);
                    $credentials = json_decode($json_content, true);
                }
            }
        } else {
            return array(
                'success' => false,
                'message' => 'Service Account credentials not found. Please upload a JSON key file or paste the JSON content.'
            );
        }

        if (!$credentials || !isset($credentials['private_key']) || !isset($credentials['client_email'])) {
            error_log('MRM CF7 Popup - Invalid Service Account credentials');
            error_log('Credentials received: ' . print_r($credentials, true));
            return array(
                'success' => false,
                'message' => 'Invalid Service Account credentials format. Please ensure the JSON file contains valid service account data.',
                'debug' => array(
                    'has_credentials' => !empty($credentials),
                    'has_private_key' => isset($credentials['private_key']),
                    'has_client_email' => isset($credentials['client_email']),
                    'credential_keys' => $credentials ? array_keys($credentials) : array()
                )
            );
        }

        // Generate JWT token
        $jwt_token = $this->create_jwt_token($credentials);
        
        if (!$jwt_token) {
            return array(
                'success' => false,
                'message' => 'Failed to generate authentication token'
            );
        }

        // Get OAuth access token
        $access_token = $this->get_access_token($jwt_token);
        
        if (!$access_token) {
            return array(
                'success' => false,
                'message' => 'Failed to obtain access token'
            );
        }

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

        // Build Google Sheets API URL
        $url = sprintf(
            'https://sheets.googleapis.com/v4/spreadsheets/%s/values/%s:append?valueInputOption=USER_ENTERED',
            $sheet_id,
            $sheet_name
        );

        // Make API request with OAuth token
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
            ),
            'body' => wp_json_encode($body),
            'timeout' => 30,
            'sslverify' => true,
        ));

        return $this->handle_api_response($response);
    }

    /**
     * Send to Google Sheets using API Key (Read Only - will fail for write operations)
     */
    private function send_to_google_sheets_api_key($sheet_id, $sheet_name, $api_key, $data) {
        if (empty($api_key)) {
            return array(
                'success' => false,
                'message' => 'API Key is required'
            );
        }

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

        return $this->handle_api_response($response);
    }

    /**
     * Send to Google Sheets using Webhook
     */
    private function send_to_google_sheets_webhook($webhook_url, $data) {
        if (empty($webhook_url)) {
            return array(
                'success' => false,
                'message' => 'Webhook URL is required'
            );
        }

        // Send data to webhook
        $response = wp_remote_post($webhook_url, array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => wp_json_encode($data),
            'timeout' => 30,
            'sslverify' => true,
        ));

        return $this->handle_api_response($response);
    }

    /**
     * Create JWT token for Service Account
     */
    private function create_jwt_token($credentials) {
        $now = time();
        $expiration = $now + 3600; // 1 hour

        $header = array(
            'alg' => 'RS256',
            'typ' => 'JWT'
        );

        $claim_set = array(
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $expiration,
            'iat' => $now
        );

        // Encode header and claim set
        $header_encoded = $this->base64url_encode(wp_json_encode($header));
        $claim_set_encoded = $this->base64url_encode(wp_json_encode($claim_set));
        
        $signature_input = $header_encoded . '.' . $claim_set_encoded;

        // Sign with private key
        $private_key = $credentials['private_key'];
        $signature = '';
        
        if (openssl_sign($signature_input, $signature, $private_key, 'SHA256')) {
            $signature_encoded = $this->base64url_encode($signature);
            return $signature_input . '.' . $signature_encoded;
        }

        return false;
    }

    /**
     * Get OAuth access token using JWT
     */
    private function get_access_token($jwt_token) {
        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'body' => array(
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt_token
            ),
            'timeout' => 30,
        ));

        if (is_wp_error($response)) {
            error_log('MRM CF7 - OAuth Error: ' . $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['access_token'])) {
            return $data['access_token'];
        }

        error_log('MRM CF7 - OAuth Response: ' . $body);
        return false;
    }

    /**
     * Handle API response
     */
    private function handle_api_response($response) {
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message()
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);

        if ($response_code === 200 || $response_code === 201) {
            return array(
                'success' => true,
                'data' => json_decode($response_body, true)
            );
        } else {
            // Log detailed error for debugging
            error_log('MRM CF7 Popup - Google Sheets API Error (' . $response_code . '): ' . $response_body);
            return array(
                'success' => false,
                'message' => 'API request failed with status code: ' . $response_code,
                'details' => json_decode($response_body, true)
            );
        }
    }

    /**
     * Base64 URL encode
     */
    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Handle CC Email
     */
    public function handle_cc_email() {
        // Verify nonce
        if (!check_ajax_referer('mrm_cf7_popup_nonce', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }

        // Sanitize and validate input
        $cc_email = sanitize_text_field($_POST['cc_email'] ?? '');
        $form_data = $_POST['form_data'] ?? array();
        $widget_id = sanitize_text_field($_POST['widget_id'] ?? '');

        // Validate email
        $cc_emails = array_map('trim', explode(',', $cc_email));
        $valid_emails = array();

        foreach ($cc_emails as $email) {
            if (is_email($email)) {
                $valid_emails[] = sanitize_email($email);
            }
        }

        if (empty($valid_emails)) {
            wp_send_json_error(array('message' => 'Invalid email address'));
            return;
        }

        // Sanitize form data
        $sanitized_data = $this->sanitize_form_data_array($form_data);

        // Prepare email
        $subject = 'New Form Submission - ' . get_bloginfo('name');
        $message = $this->prepare_email_message($sanitized_data);
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
        );

        // Send email
        $sent = wp_mail($valid_emails, $subject, $message, $headers);

        if ($sent) {
            wp_send_json_success(array('message' => 'CC email sent successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to send CC email'));
        }
    }

    /**
     * Sanitize form data
     */
    private function sanitize_form_data($data) {
        $sanitized = array();

        foreach ($data as $key => $value) {
            $sanitized_key = sanitize_key($key);
            
            if (is_array($value)) {
                $sanitized[$sanitized_key] = $this->sanitize_form_data($value);
            } else {
                // Check if it's a URL (for file uploads)
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    $sanitized[$sanitized_key] = esc_url_raw($value);
                } else {
                    // Use wp_kses to allow only safe HTML
                    $sanitized[$sanitized_key] = wp_kses_post($value);
                }
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize form data array
     */
    private function sanitize_form_data_array($data) {
        $sanitized = array();

        foreach ($data as $field) {
            if (!isset($field['name']) || !isset($field['value'])) {
                continue;
            }

            $name = sanitize_text_field($field['name']);
            $value = $field['value'];

            // Handle file uploads - store URL only
            if (strpos($name, 'file') !== false && filter_var($value, FILTER_VALIDATE_URL)) {
                $sanitized[$name] = esc_url_raw($value);
            } else {
                // Sanitize based on field type
                $sanitized[$name] = $this->sanitize_field_value($value);
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize field value
     */
    private function sanitize_field_value($value) {
        // Protection against SQL injection
        global $wpdb;
        
        if (is_array($value)) {
            return array_map(array($this, 'sanitize_field_value'), $value);
        }

        // Remove any SQL keywords and dangerous characters
        $dangerous_patterns = array(
            '/(\bSELECT\b|\bUNION\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|\bCREATE\b|\bALTER\b)/i',
            '/(<script[^>]*>.*?<\/script>)/is',
            '/(<iframe[^>]*>.*?<\/iframe>)/is',
            '/(javascript:)/i',
            '/(on\w+\s*=)/i'
        );

        $value = preg_replace($dangerous_patterns, '', $value);

        // Remove null bytes
        $value = str_replace(chr(0), '', $value);

        // Sanitize
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return sanitize_email($value);
        } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
            return esc_url_raw($value);
        } else {
            return sanitize_text_field($value);
        }
    }

    /**
     * Prepare email message
     */
    private function prepare_email_message($data) {
        $message = '<html><body>';
        $message .= '<h2>New Form Submission</h2>';
        $message .= '<table style="border-collapse: collapse; width: 100%; max-width: 600px;">';
        
        foreach ($data as $key => $value) {
            $label = ucwords(str_replace(array('_', '-'), ' ', $key));
            $message .= '<tr>';
            $message .= '<td style="border: 1px solid #ddd; padding: 10px; background: #f9f9f9; font-weight: bold;">' . esc_html($label) . '</td>';
            
            if (is_array($value)) {
                $message .= '<td style="border: 1px solid #ddd; padding: 10px;">' . esc_html(implode(', ', $value)) . '</td>';
            } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
                $message .= '<td style="border: 1px solid #ddd; padding: 10px;"><a href="' . esc_url($value) . '">' . esc_html($value) . '</a></td>';
            } else {
                $message .= '<td style="border: 1px solid #ddd; padding: 10px;">' . esc_html($value) . '</td>';
            }
            
            $message .= '</tr>';
        }
        
        $message .= '</table>';
        $message .= '<p style="margin-top: 20px; color: #666; font-size: 12px;">Submitted on: ' . current_time('mysql') . '</p>';
        $message .= '</body></html>';

        return $message;
    }

    /**
     * Validate and sanitize Google Sheets API response
     */
    private function validate_api_response($response) {
        if (empty($response) || !is_array($response)) {
            return false;
        }

        // Check for required fields in response
        if (!isset($response['spreadsheetId']) || !isset($response['updates'])) {
            return false;
        }

        return true;
    }

    /**
     * Handle file upload to WordPress media library
     */
    public function handle_file_upload() {
        // Verify nonce
        if (!check_ajax_referer('mrm_cf7_popup_nonce', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }

        // Check if file was uploaded
        if (empty($_FILES) || !isset($_FILES['file'])) {
            wp_send_json_error(array('message' => 'No file uploaded'));
            return;
        }

        $file = $_FILES['file'];

        // Validate file
        $allowed_types = array(
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg',
            'video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo'
        );

        $file_type = $file['type'];
        
        // Get file extension
        $file_name = sanitize_file_name($file['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Additional validation for mime type
        if (!in_array($file_type, $allowed_types)) {
            // Try to validate by extension as fallback
            $valid_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp3', 'wav', 'ogg', 'mp4', 'mpeg', 'mov', 'avi');
            
            if (!in_array($file_ext, $valid_extensions)) {
                wp_send_json_error(array(
                    'message' => 'File type not allowed. Allowed types: images, documents (PDF, Word, Excel), audio, and video files.',
                    'file_type' => $file_type,
                    'file_ext' => $file_ext
                ));
                return;
            }
        }

        // Check file size (max 10MB)
        $max_size = 10 * 1024 * 1024; // 10MB in bytes
        if ($file['size'] > $max_size) {
            wp_send_json_error(array('message' => 'File size exceeds 10MB limit'));
            return;
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(array('message' => 'File upload error: ' . $file['error']));
            return;
        }

        // Include WordPress file handling functions
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        // Upload file to WordPress media library
        $upload_overrides = array(
            'test_form' => false,
            'test_type' => true,
            'test_size' => true,
        );

        // Move uploaded file
        $uploaded_file = wp_handle_upload($file, $upload_overrides);

        if (isset($uploaded_file['error'])) {
            wp_send_json_error(array(
                'message' => 'Upload failed: ' . $uploaded_file['error']
            ));
            return;
        }

        // File was successfully uploaded
        $file_url = $uploaded_file['url'];
        $file_path = $uploaded_file['file'];
        $file_type = $uploaded_file['type'];

        // Get field name from request
        $field_name = sanitize_text_field($_POST['field_name'] ?? 'file');

        // Insert file as attachment
        $attachment = array(
            'guid' => $file_url,
            'post_mime_type' => $file_type,
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_path)),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $file_path);

        // Generate attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);

        // Log success
        error_log('MRM CF7 Popup - File uploaded successfully: ' . $file_url);
        error_log('Field name: ' . $field_name);
        error_log('Attachment ID: ' . $attach_id);

        // Return success with file URL
        wp_send_json_success(array(
            'message' => 'File uploaded successfully',
            'url' => $file_url,
            'attachment_id' => $attach_id,
            'field_name' => $field_name,
            'file_name' => basename($file_path),
            'file_type' => $file_type
        ));
    }
}

// Initialize the handler
new MRM_CF7_Popup_AJAX_Handler();
