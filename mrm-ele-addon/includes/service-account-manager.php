<?php
/**
 * Service Account Manager
 * Handles secure storage and retrieval of Service Account credentials
 */

if (!defined('ABSPATH')) {
    exit;
}

class MRM_Service_Account_Manager {

    /**
     * Upload directory path
     */
    private static $upload_dir = null;

    /**
     * Initialize
     */
    public static function init() {
        add_action('elementor/editor/after_save', array(__CLASS__, 'process_service_account_file'), 10, 2);
        add_filter('upload_mimes', array(__CLASS__, 'allow_json_upload'));
        add_filter('wp_check_filetype_and_ext', array(__CLASS__, 'check_json_filetype'), 10, 4);
    }

    /**
     * Get secure upload directory
     */
    public static function get_upload_dir() {
        if (self::$upload_dir !== null) {
            return self::$upload_dir;
        }

        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];
        
        // Create secure directory for service account files
        $secure_dir = $base_dir . '/mrm-cf7-private';
        
        if (!file_exists($secure_dir)) {
            wp_mkdir_p($secure_dir);
            
            // Create .htaccess to deny access
            $htaccess_content = "# Deny all direct access\n";
            $htaccess_content .= "Order Deny,Allow\n";
            $htaccess_content .= "Deny from all\n";
            file_put_contents($secure_dir . '/.htaccess', $htaccess_content);
            
            // Create index.php to prevent directory listing
            $index_content = "<?php\n// Silence is golden\n";
            file_put_contents($secure_dir . '/index.php', $index_content);
        }

        self::$upload_dir = $secure_dir;
        return $secure_dir;
    }

    /**
     * Allow JSON file upload
     */
    public static function allow_json_upload($mimes) {
        $mimes['json'] = 'application/json';
        return $mimes;
    }

    /**
     * Check JSON file type
     */
    public static function check_json_filetype($data, $file, $filename, $mimes) {
        $filetype = wp_check_filetype($filename, $mimes);
        
        if ($filetype['ext'] === 'json') {
            $data['ext'] = 'json';
            $data['type'] = 'application/json';
        }
        
        return $data;
    }

    /**
     * Process uploaded service account file
     */
    public static function process_service_account_file($post_id, $editor_data) {
        // This will be called when widget settings are saved
        // File is already in WordPress media library
        // We'll move it to secure location when needed
    }

    /**
     * Store service account credentials
     * 
     * @param int $file_id WordPress attachment ID
     * @param string $widget_id Widget ID
     * @return string|false Secure file path or false on failure
     */
    public static function store_credentials($file_id, $widget_id) {
        if (empty($file_id)) {
            return false;
        }

        // Get file from media library
        $file_path = get_attached_file($file_id);
        
        if (!$file_path || !file_exists($file_path)) {
            return false;
        }

        // Validate it's a JSON file
        $file_info = pathinfo($file_path);
        if (strtolower($file_info['extension']) !== 'json') {
            return false;
        }

        // Validate JSON content
        $json_content = file_get_contents($file_path);
        $credentials = json_decode($json_content, true);
        
        if (!$credentials || !isset($credentials['type']) || $credentials['type'] !== 'service_account') {
            error_log('MRM CF7: Invalid Service Account JSON file');
            return false;
        }

        // Get secure directory
        $secure_dir = self::get_upload_dir();
        
        // Create unique filename based on widget ID
        $secure_filename = 'service-account-' . sanitize_key($widget_id) . '.json';
        $secure_path = $secure_dir . '/' . $secure_filename;

        // Copy file to secure location
        if (copy($file_path, $secure_path)) {
            // Set proper file permissions
            chmod($secure_path, 0600);
            
            // Store path in option for this widget
            update_option('mrm_sa_path_' . $widget_id, $secure_path);
            
            return $secure_path;
        }

        return false;
    }

    /**
     * Get service account credentials
     * 
     * @param int $file_id WordPress attachment ID (optional)
     * @param string $widget_id Widget ID
     * @return array|false Credentials array or false
     */
    public static function get_credentials($file_id = null, $widget_id = null) {
        $file_path = null;

        // Try to get from stored path first
        if ($widget_id) {
            $stored_path = get_option('mrm_sa_path_' . $widget_id);
            if ($stored_path && file_exists($stored_path)) {
                $file_path = $stored_path;
            }
        }

        // If not found, try to get from media library and store
        if (!$file_path && $file_id) {
            $file_path = self::store_credentials($file_id, $widget_id);
        }

        // If still not found, try direct media library access
        if (!$file_path && $file_id) {
            $file_path = get_attached_file($file_id);
        }

        if (!$file_path || !file_exists($file_path)) {
            return false;
        }

        // Read and validate JSON
        $json_content = file_get_contents($file_path);
        $credentials = json_decode($json_content, true);

        if (!$credentials || !isset($credentials['type']) || $credentials['type'] !== 'service_account') {
            return false;
        }

        // Validate required fields
        $required_fields = ['private_key', 'client_email', 'token_uri'];
        foreach ($required_fields as $field) {
            if (!isset($credentials[$field])) {
                error_log('MRM CF7: Missing required field in Service Account: ' . $field);
                return false;
            }
        }

        return $credentials;
    }

    /**
     * Delete service account credentials
     * 
     * @param string $widget_id Widget ID
     * @return bool Success
     */
    public static function delete_credentials($widget_id) {
        $stored_path = get_option('mrm_sa_path_' . $widget_id);
        
        if ($stored_path && file_exists($stored_path)) {
            unlink($stored_path);
        }
        
        delete_option('mrm_sa_path_' . $widget_id);
        
        return true;
    }

    /**
     * Get service account email from credentials
     * 
     * @param int $file_id WordPress attachment ID
     * @param string $widget_id Widget ID
     * @return string|false Email or false
     */
    public static function get_service_account_email($file_id, $widget_id) {
        $credentials = self::get_credentials($file_id, $widget_id);
        
        if ($credentials && isset($credentials['client_email'])) {
            return $credentials['client_email'];
        }
        
        return false;
    }

    /**
     * Encrypt sensitive data (optional - for database storage)
     * 
     * @param string $data Data to encrypt
     * @return string Encrypted data
     */
    public static function encrypt_data($data) {
        if (!function_exists('openssl_encrypt')) {
            return base64_encode($data);
        }

        $key = self::get_encryption_key();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * Decrypt sensitive data
     * 
     * @param string $data Encrypted data
     * @return string Decrypted data
     */
    public static function decrypt_data($data) {
        if (!function_exists('openssl_decrypt')) {
            return base64_decode($data);
        }

        $key = self::get_encryption_key();
        $data = base64_decode($data);
        
        if (strpos($data, '::') === false) {
            return $data;
        }

        list($encrypted_data, $iv) = explode('::', $data, 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    }

    /**
     * Get encryption key
     * 
     * @return string Encryption key
     */
    private static function get_encryption_key() {
        // Use WordPress security keys
        if (defined('AUTH_KEY')) {
            return substr(AUTH_KEY, 0, 32);
        }
        
        return 'mrm-cf7-default-key-change-me';
    }

    /**
     * Clean up old service account files
     */
    public static function cleanup_old_files() {
        $secure_dir = self::get_upload_dir();
        $files = glob($secure_dir . '/service-account-*.json');
        
        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            // Check if file is older than 30 days and not in use
            if (filemtime($file) < (time() - (30 * DAY_IN_SECONDS))) {
                // Extract widget ID from filename
                $filename = basename($file);
                preg_match('/service-account-(.+)\.json/', $filename, $matches);
                
                if (isset($matches[1])) {
                    $widget_id = $matches[1];
                    $stored_path = get_option('mrm_sa_path_' . $widget_id);
                    
                    // Only delete if not in use
                    if (!$stored_path || $stored_path !== $file) {
                        unlink($file);
                    }
                }
            }
        }
    }
}

// Initialize
MRM_Service_Account_Manager::init();

// Schedule cleanup (run weekly)
if (!wp_next_scheduled('mrm_cf7_cleanup_sa_files')) {
    wp_schedule_event(time(), 'weekly', 'mrm_cf7_cleanup_sa_files');
}
add_action('mrm_cf7_cleanup_sa_files', array('MRM_Service_Account_Manager', 'cleanup_old_files'));
