<?php
/**
 * Registration Entries Database Handler
 * Handles database operations for form submissions
 */

if (!defined('ABSPATH')) {
    exit;
}

class MRM_Registration_Entries_DB {
    
    /**
     * Table name
     */
    private $table_name;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'mrm_registration_entries';
        
        // Create table on initialization
        $this->create_table();
        
        // Hook into CF7 form submission
        add_action('wpcf7_mail_sent', array($this, 'save_form_submission'), 10, 1);
    }
    
    /**
     * Create database table if it doesn't exist
     */
    public function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            form_name varchar(255) NOT NULL,
            form_id bigint(20) NOT NULL,
            data longtext NOT NULL,
            date_time datetime NOT NULL,
            user_id bigint(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY form_id (form_id),
            KEY date_time (date_time),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Log table creation
        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name) {
            error_log('MRM Registration Entries: Table created or already exists');
        } else {
            error_log('MRM Registration Entries: Failed to create table');
        }
    }
    
    /**
     * Save form submission to database
     */
    public function save_form_submission($contact_form) {
        global $wpdb;
        
        // Get form details
        $submission = WPCF7_Submission::get_instance();
        
        if (!$submission) {
            return;
        }
        
        // Get form ID and name
        $form_id = $contact_form->id();
        $form_name = $contact_form->title();
        
        // Get submitted data
        $posted_data = $submission->get_posted_data();
        
        // Get current user ID (0 if not logged in)
        $user_id = get_current_user_id();
        
        // Prepare data for storage
        $form_data = array();
        
        foreach ($posted_data as $key => $value) {
            // Skip internal CF7 fields
            if (strpos($key, '_wpcf7') === 0) {
                continue;
            }
            
            // Handle file uploads - get URL from uploaded files
            if (is_array($value)) {
                $form_data[$key] = implode(', ', $value);
            } else {
                $form_data[$key] = $value;
            }
        }
        
        // Get uploaded files from CF7
        $uploaded_files = $submission->uploaded_files();
        if (!empty($uploaded_files)) {
            foreach ($uploaded_files as $field_name => $file_path) {
                if (!empty($file_path)) {
                    // If it's an array of files
                    if (is_array($file_path)) {
                        $urls = array();
                        foreach ($file_path as $single_file) {
                            $url = $this->get_file_url_from_path($single_file);
                            if ($url) {
                                $urls[] = $url;
                            }
                        }
                        if (!empty($urls)) {
                            $form_data[$field_name] = implode(', ', $urls);
                        }
                    } else {
                        // Single file
                        $url = $this->get_file_url_from_path($file_path);
                        if ($url) {
                            $form_data[$field_name] = $url;
                        }
                    }
                }
            }
        }
        
        // Convert data to JSON
        $data_json = wp_json_encode($form_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // Insert into database
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'form_name' => sanitize_text_field($form_name),
                'form_id' => absint($form_id),
                'data' => $data_json,
                'date_time' => current_time('mysql'),
                'user_id' => absint($user_id)
            ),
            array('%s', '%d', '%s', '%s', '%d')
        );
        
        if ($result === false) {
            error_log('MRM Registration Entries: Failed to save form submission - ' . $wpdb->last_error);
        } else {
            error_log('MRM Registration Entries: Form submission saved successfully (ID: ' . $wpdb->insert_id . ')');
        }
    }
    
    /**
     * Get file URL from file path
     */
    private function get_file_url_from_path($file_path) {
        if (empty($file_path)) {
            return '';
        }
        
        // Get upload directory info
        $upload_dir = wp_upload_dir();
        $base_path = $upload_dir['basedir'];
        $base_url = $upload_dir['baseurl'];
        
        // Convert absolute path to URL
        if (strpos($file_path, $base_path) === 0) {
            return str_replace($base_path, $base_url, $file_path);
        }
        
        // Try to get attachment URL by searching in media library
        global $wpdb;
        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE guid LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like(basename($file_path)) . '%'
        ));
        
        if ($attachment_id) {
            return wp_get_attachment_url($attachment_id);
        }
        
        return $file_path;
    }
    
    /**
     * Get all form names
     */
    public function get_form_names() {
        global $wpdb;
        
        $results = $wpdb->get_results(
            "SELECT DISTINCT form_name, form_id FROM {$this->table_name} ORDER BY form_name ASC",
            ARRAY_A
        );
        
        return $results;
    }
    
    /**
     * Get entries by form ID
     */
    public function get_entries_by_form($form_id, $per_page = 20, $page_number = 1) {
        global $wpdb;
        
        $offset = ($page_number - 1) * $per_page;
        
        $sql = $wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE form_id = %d ORDER BY date_time DESC LIMIT %d OFFSET %d",
            $form_id,
            $per_page,
            $offset
        );
        
        $results = $wpdb->get_results($sql, ARRAY_A);
        
        return $results;
    }
    
    /**
     * Get total entries count by form ID
     */
    public function get_entries_count_by_form($form_id) {
        global $wpdb;
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_name} WHERE form_id = %d",
            $form_id
        ));
        
        return intval($count);
    }
    
    /**
     * Get all entries by form ID (for CSV export)
     */
    public function get_all_entries_by_form($form_id) {
        global $wpdb;
        
        $sql = $wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE form_id = %d ORDER BY date_time DESC",
            $form_id
        );
        
        $results = $wpdb->get_results($sql, ARRAY_A);
        
        return $results;
    }
    
    /**
     * Delete entry
     */
    public function delete_entry($entry_id) {
        global $wpdb;
        
        $result = $wpdb->delete(
            $this->table_name,
            array('id' => absint($entry_id)),
            array('%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Delete multiple entries
     */
    public function delete_entries($entry_ids) {
        global $wpdb;
        
        if (empty($entry_ids) || !is_array($entry_ids)) {
            return false;
        }
        
        $entry_ids = array_map('absint', $entry_ids);
        $placeholders = implode(',', array_fill(0, count($entry_ids), '%d'));
        
        $sql = "DELETE FROM {$this->table_name} WHERE id IN ($placeholders)";
        $result = $wpdb->query($wpdb->prepare($sql, $entry_ids));
        
        return $result !== false;
    }
    
    /**
     * Get table name
     */
    public function get_table_name() {
        return $this->table_name;
    }
}

// Initialize
new MRM_Registration_Entries_DB();
