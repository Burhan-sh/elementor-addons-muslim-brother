<?php
/**
 * MRM CF7 Popup - Security Layer
 * Enhanced security and validation for form submissions
 */

if (!defined('ABSPATH')) {
    exit;
}

class MRM_CF7_Popup_Security {

    /**
     * Constructor
     */
    public function __construct() {
        // Add security headers
        add_action('send_headers', array($this, 'add_security_headers'));
        
        // Sanitize CF7 form data
        add_filter('wpcf7_posted_data', array($this, 'sanitize_cf7_data'), 10, 1);
        
        // Add rate limiting
        add_action('wpcf7_before_send_mail', array($this, 'check_rate_limit'), 10, 3);
        
        // Validate file uploads
        add_filter('wpcf7_validate_file', array($this, 'validate_file_upload'), 20, 2);
        add_filter('wpcf7_validate_file*', array($this, 'validate_file_upload'), 20, 2);
    }

    /**
     * Add security headers
     */
    public function add_security_headers() {
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
        }
    }

    /**
     * Sanitize Contact Form 7 data
     */
    public function sanitize_cf7_data($posted_data) {
        if (empty($posted_data)) {
            return $posted_data;
        }

        $sanitized_data = array();

        foreach ($posted_data as $key => $value) {
            // Skip if key is empty
            if (empty($key)) {
                continue;
            }

            $sanitized_key = sanitize_key($key);

            // Sanitize based on value type
            if (is_array($value)) {
                $sanitized_data[$sanitized_key] = $this->sanitize_array($value);
            } else {
                $sanitized_data[$sanitized_key] = $this->sanitize_value($value, $key);
            }
        }

        return $sanitized_data;
    }

    /**
     * Sanitize array values
     */
    private function sanitize_array($array) {
        $sanitized = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize_array($value);
            } else {
                $sanitized[$key] = $this->sanitize_value($value);
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize individual value
     */
    private function sanitize_value($value, $key = '') {
        // Remove null bytes
        $value = str_replace(chr(0), '', $value);

        // SQL Injection Prevention
        $sql_patterns = array(
            '/(\bSELECT\b|\bUNION\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|\bCREATE\b|\bALTER\b|\bEXEC\b|\bEXECUTE\b)/i',
            '/(\bOR\b\s+\d+\s*=\s*\d+|\bAND\b\s+\d+\s*=\s*\d+)/i',
            '/(--|\/\*|\*\/|;)/i',
            '/(\bxp_\w+|\bsp_\w+)/i'
        );

        foreach ($sql_patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                // Log security incident
                $this->log_security_incident('SQL Injection Attempt', $value);
                return '';
            }
        }

        // XSS Prevention
        $xss_patterns = array(
            '/(<script[^>]*>.*?<\/script>)/is',
            '/(<iframe[^>]*>.*?<\/iframe>)/is',
            '/(javascript:)/i',
            '/(on\w+\s*=)/i',
            '/(<embed|<object|<applet)/i',
            '/(eval\s*\(|expression\s*\()/i',
            '/(<link[^>]*>)/i',
            '/(<meta[^>]*>)/i'
        );

        foreach ($xss_patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                // Log security incident
                $this->log_security_incident('XSS Attempt', $value);
                $value = preg_replace($pattern, '', $value);
            }
        }

        // LDAP Injection Prevention
        $ldap_patterns = array(
            '/(\*|\(|\)|\\\\|\||&)/i'
        );

        $contains_ldap = false;
        foreach ($ldap_patterns as $pattern) {
            if (preg_match($pattern, $value) && strlen($value) < 100) {
                $contains_ldap = true;
                break;
            }
        }

        // Command Injection Prevention
        $command_patterns = array(
            '/(;|\||`|\$\(|&&|\|\|)/i',
            '/(system|exec|shell_exec|passthru|popen|proc_open)/i'
        );

        foreach ($command_patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $this->log_security_incident('Command Injection Attempt', $value);
                return '';
            }
        }

        // Path Traversal Prevention
        if (preg_match('/(\.\.\/|\.\.\\\\)/i', $value)) {
            $this->log_security_incident('Path Traversal Attempt', $value);
            return '';
        }

        // Sanitize based on field type
        if (strpos($key, 'email') !== false) {
            return sanitize_email($value);
        } elseif (strpos($key, 'url') !== false || filter_var($value, FILTER_VALIDATE_URL)) {
            return esc_url_raw($value);
        } elseif (strpos($key, 'tel') !== false || strpos($key, 'phone') !== false) {
            // Sanitize phone number
            return preg_replace('/[^0-9+\-\(\)\s]/', '', $value);
        } elseif (is_numeric($value)) {
            return floatval($value);
        } else {
            // General text sanitization
            return sanitize_text_field($value);
        }
    }

    /**
     * Check rate limiting
     */
    public function check_rate_limit($contact_form, &$abort, $submission) {
        $ip_address = $this->get_client_ip();
        $transient_key = 'mrm_cf7_popup_rate_' . md5($ip_address);
        
        // Get submission count
        $submission_count = get_transient($transient_key);
        
        // Allow 5 submissions per 5 minutes per IP
        $max_submissions = 5;
        $time_window = 300; // 5 minutes in seconds

        if ($submission_count === false) {
            // First submission
            set_transient($transient_key, 1, $time_window);
        } else {
            if ($submission_count >= $max_submissions) {
                // Rate limit exceeded
                $abort = true;
                $this->log_security_incident('Rate Limit Exceeded', $ip_address);
                
                // Add error message
                $submission->add_response_message(
                    __('Too many submissions. Please try again later.', 'mrm-ele-addon'),
                    'error'
                );
                
                return;
            }
            
            // Increment counter
            set_transient($transient_key, $submission_count + 1, $time_window);
        }
    }

    /**
     * Validate file uploads
     */
    public function validate_file_upload($result, $tag) {
        if (!isset($_FILES[$tag->name])) {
            return $result;
        }

        $file = $_FILES[$tag->name];

        // Check file size (max 5MB)
        $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
        
        if ($file['size'] > $max_file_size) {
            $result->invalidate($tag, __('File size exceeds 5MB limit.', 'mrm-ele-addon'));
            return $result;
        }

        // Allowed file types
        $allowed_types = array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'text/csv'
        );

        // Check file type
        $file_type = $file['type'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Additional validation for file extension
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv');

        if (!in_array($file_type, $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
            $result->invalidate($tag, __('Invalid file type. Only images, PDFs, and documents are allowed.', 'mrm-ele-addon'));
            $this->log_security_incident('Invalid File Upload Attempt', $file['name']);
            return $result;
        }

        // Check for double extensions (e.g., file.php.jpg)
        $name_parts = explode('.', $file['name']);
        if (count($name_parts) > 2) {
            $result->invalidate($tag, __('Invalid file name.', 'mrm-ele-addon'));
            $this->log_security_incident('Suspicious File Name', $file['name']);
            return $result;
        }

        // Scan file content for malicious code
        if (function_exists('file_get_contents')) {
            $file_content = file_get_contents($file['tmp_name']);
            
            // Check for PHP code in uploads
            if (preg_match('/<\?php|<\?=|<script|eval\(|base64_decode/i', $file_content)) {
                $result->invalidate($tag, __('Suspicious file content detected.', 'mrm-ele-addon'));
                $this->log_security_incident('Malicious File Upload Attempt', $file['name']);
                return $result;
            }
        }

        return $result;
    }

    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );

        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }

    /**
     * Log security incidents
     */
    private function log_security_incident($type, $details) {
        $log_entry = array(
            'timestamp' => current_time('mysql'),
            'type' => $type,
            'details' => $details,
            'ip' => $this->get_client_ip(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : 'Unknown'
        );

        // Log to WordPress debug log
        if (defined('WP_DEBUG') && WP_DEBUG === true) {
            error_log('MRM CF7 Popup Security: ' . wp_json_encode($log_entry));
        }

        // Store in database (optional - create custom table for security logs)
        $this->store_security_log($log_entry);

        // Send email notification for critical incidents (optional)
        if (in_array($type, array('SQL Injection Attempt', 'XSS Attempt', 'Command Injection Attempt'))) {
            $this->send_security_alert($log_entry);
        }
    }

    /**
     * Store security log in database
     */
    private function store_security_log($log_entry) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mrm_cf7_popup_security_logs';

        // Create table if it doesn't exist
        $this->create_security_log_table();

        // Insert log entry
        $wpdb->insert(
            $table_name,
            array(
                'timestamp' => $log_entry['timestamp'],
                'type' => $log_entry['type'],
                'details' => $log_entry['details'],
                'ip_address' => $log_entry['ip'],
                'user_agent' => $log_entry['user_agent']
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );
    }

    /**
     * Create security log table
     */
    private function create_security_log_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mrm_cf7_popup_security_logs';
        $charset_collate = $wpdb->get_charset_collate();

        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                type varchar(100) NOT NULL,
                details text NOT NULL,
                ip_address varchar(45) NOT NULL,
                user_agent text NOT NULL,
                PRIMARY KEY  (id),
                KEY ip_address (ip_address),
                KEY timestamp (timestamp)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    /**
     * Send security alert email
     */
    private function send_security_alert($log_entry) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');

        $subject = '[SECURITY ALERT] ' . $log_entry['type'] . ' - ' . $site_name;
        
        $message = '<html><body>';
        $message .= '<h2 style="color: #d32f2f;">Security Alert</h2>';
        $message .= '<p><strong>Type:</strong> ' . esc_html($log_entry['type']) . '</p>';
        $message .= '<p><strong>Time:</strong> ' . esc_html($log_entry['timestamp']) . '</p>';
        $message .= '<p><strong>IP Address:</strong> ' . esc_html($log_entry['ip']) . '</p>';
        $message .= '<p><strong>Details:</strong><br><code>' . esc_html($log_entry['details']) . '</code></p>';
        $message .= '<p><strong>User Agent:</strong> ' . esc_html($log_entry['user_agent']) . '</p>';
        $message .= '</body></html>';

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($admin_email, $subject, $message, $headers);
    }

    /**
     * Clean old security logs (run weekly)
     */
    public function clean_old_logs() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mrm_cf7_popup_security_logs';

        // Delete logs older than 30 days
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $table_name WHERE timestamp < DATE_SUB(NOW(), INTERVAL %d DAY)",
                30
            )
        );
    }
}

// Initialize security layer
$mrm_cf7_popup_security = new MRM_CF7_Popup_Security();

// Schedule log cleanup
if (!wp_next_scheduled('mrm_cf7_popup_cleanup_logs')) {
    wp_schedule_event(time(), 'weekly', 'mrm_cf7_popup_cleanup_logs');
}

add_action('mrm_cf7_popup_cleanup_logs', array($mrm_cf7_popup_security, 'clean_old_logs'));
