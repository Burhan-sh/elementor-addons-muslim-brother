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
        $sheet_id = sanitize_text_field($_POST['sheet_id'] ?? '');
        $sheet_name = sanitize_text_field($_POST['sheet_name'] ?? 'Sheet1');
        $api_key = sanitize_text_field($_POST['api_key'] ?? '');
        $widget_id = sanitize_text_field($_POST['widget_id'] ?? '');
        $data = $_POST['data'] ?? array();

        // Validate required fields
        if (empty($sheet_id) || empty($api_key)) {
            wp_send_json_error(array('message' => 'Missing required fields'));
            return;
        }

        // Sanitize data
        $sanitized_data = $this->sanitize_form_data($data);

        // Prepare data for Google Sheets
        try {
            $result = $this->send_to_google_sheets($sheet_id, $sheet_name, $api_key, $sanitized_data);
            
            if ($result['success']) {
                wp_send_json_success(array(
                    'message' => 'Data sent to Google Sheets successfully',
                    'data' => $result['data']
                ));
            } else {
                wp_send_json_error(array(
                    'message' => $result['message']
                ));
            }
        } catch (Exception $e) {
            error_log('MRM CF7 Popup - Google Sheets Error: ' . $e->getMessage());
            wp_send_json_error(array(
                'message' => 'Failed to send data to Google Sheets'
            ));
        }
    }

    /**
     * Send data to Google Sheets
     */
    private function send_to_google_sheets($sheet_id, $sheet_name, $api_key, $data) {
        // Google Sheets API endpoint
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheet_id}/values/{$sheet_name}:append";
        
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

        // Make API request
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => wp_json_encode($body),
            'timeout' => 30,
            'sslverify' => true,
        ) + array(
            'url' => $url . '?key=' . $api_key . '&valueInputOption=USER_ENTERED'
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
            return array(
                'success' => false,
                'message' => 'API request failed with status code: ' . $response_code
            );
        }
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
}

// Initialize the handler
new MRM_CF7_Popup_AJAX_Handler();
