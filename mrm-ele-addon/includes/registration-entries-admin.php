<?php
/**
 * Registration Entries Admin Page
 * Displays form submissions in WP_List_Table format
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load WP_List_Table if not already loaded
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Custom List Table for Registration Entries
 */
class MRM_Registration_Entries_List_Table extends WP_List_Table {
    
    private $db_handler;
    private $form_id;
    private $dynamic_columns = array();
    
    /**
     * Constructor
     */
    public function __construct($db_handler, $form_id = 0) {
        parent::__construct(array(
            'singular' => 'entry',
            'plural'   => 'entries',
            'ajax'     => false
        ));
        
        $this->db_handler = $db_handler;
        $this->form_id = $form_id;
        
        // Get dynamic columns from form data
        if ($form_id > 0) {
            $this->set_dynamic_columns();
        }
    }
    
    /**
     * Set dynamic columns based on form data
     */
    private function set_dynamic_columns() {
        global $wpdb;
        $table_name = $this->db_handler->get_table_name();
        
        // Get first entry to determine columns
        $first_entry = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT data FROM {$table_name} WHERE form_id = %d LIMIT 1",
                $this->form_id
            ),
            ARRAY_A
        );
        
        if ($first_entry && !empty($first_entry['data'])) {
            $data = json_decode($first_entry['data'], true);
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    // Create readable column name
                    $column_name = ucwords(str_replace(array('_', '-'), ' ', $key));
                    $this->dynamic_columns[$key] = $column_name;
                }
            }
        }
    }
    
    /**
     * Get columns
     */
    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'id' => __('ID', 'mrm-ele-addon'),
        );
        
        // Add dynamic columns
        foreach ($this->dynamic_columns as $key => $name) {
            $columns['field_' . sanitize_key($key)] = esc_html($name);
        }
        
        // Add fixed columns
        $columns['date_time'] = __('Date & Time', 'mrm-ele-addon');
        $columns['user_id'] = __('User', 'mrm-ele-addon');
        
        return $columns;
    }
    
    /**
     * Get sortable columns
     */
    public function get_sortable_columns() {
        return array(
            'id' => array('id', false),
            'date_time' => array('date_time', true)
        );
    }
    
    /**
     * Column default
     */
    public function column_default($item, $column_name) {
        // Handle dynamic columns
        if (strpos($column_name, 'field_') === 0) {
            $field_key = str_replace('field_', '', $column_name);
            $data = json_decode($item['data'], true);
            
            if (isset($data[$field_key])) {
                $value = $data[$field_key];
                
                // Check if it's a URL (file upload)
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return '<a href="' . esc_url($value) . '" target="_blank" rel="noopener">' . esc_html(basename($value)) . '</a>';
                }
                
                return esc_html($value);
            }
            
            return '-';
        }
        
        // Handle other columns
        switch ($column_name) {
            case 'id':
                return absint($item['id']);
            
            case 'date_time':
                return esc_html($item['date_time']);
            
            case 'user_id':
                $user_id = absint($item['user_id']);
                if ($user_id > 0) {
                    $user = get_user_by('id', $user_id);
                    if ($user) {
                        return esc_html($user->display_name) . ' (' . $user_id . ')';
                    }
                }
                return '0 (Guest)';
            
            default:
                return isset($item[$column_name]) ? esc_html($item[$column_name]) : '-';
        }
    }
    
    /**
     * Column checkbox
     */
    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="entries[]" value="%s" />',
            absint($item['id'])
        );
    }
    
    /**
     * Get bulk actions
     */
    public function get_bulk_actions() {
        return array(
            'delete' => __('Delete', 'mrm-ele-addon')
        );
    }
    
    /**
     * Prepare items
     */
    public function prepare_items() {
        if ($this->form_id <= 0) {
            $this->items = array();
            return;
        }
        
        $per_page = 20;
        $current_page = $this->get_pagenum();
        
        // Get items
        $this->items = $this->db_handler->get_entries_by_form($this->form_id, $per_page, $current_page);
        
        // Set pagination
        $total_items = $this->db_handler->get_entries_count_by_form($this->form_id);
        
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
        
        // Set column headers
        $this->_column_headers = array(
            $this->get_columns(),
            array(), // Hidden columns
            $this->get_sortable_columns()
        );
    }
}

/**
 * Admin Page Class
 */
class MRM_Registration_Entries_Admin {
    
    private $db_handler;
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'handle_actions'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Set database handler
     */
    public function set_db_handler($db_handler) {
        $this->db_handler = $db_handler;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Registration Form Submissions', 'mrm-ele-addon'),
            __('Registration Form Submissions', 'mrm-ele-addon'),
            'manage_options',
            'mrm-registration-entries',
            array($this, 'render_admin_page'),
            'dashicons-list-view',
            30
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_mrm-registration-entries') {
            return;
        }
        
        wp_enqueue_style('mrm-admin-entries', plugin_dir_url(dirname(__FILE__)) . 'assets/css/admin-entries.css', array(), '1.0.0');
    }
    
    /**
     * Handle actions
     */
    public function handle_actions() {
        // Check if we're on the correct page
        if (!isset($_GET['page']) || $_GET['page'] !== 'mrm-registration-entries') {
            return;
        }
        
        // Handle CSV export
        if (isset($_GET['action']) && $_GET['action'] === 'export_csv' && isset($_GET['form_id'])) {
            check_admin_referer('mrm_export_csv');
            $this->export_csv(absint($_GET['form_id']));
        }
        
        // Handle single delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['entry'])) {
            check_admin_referer('mrm_delete_entry');
            $this->delete_entry(absint($_GET['entry']));
        }
        
        // Handle bulk delete
        if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['entries'])) {
            check_admin_referer('bulk-entries');
            $this->bulk_delete($_POST['entries']);
        }
    }
    
    /**
     * Export CSV
     */
    private function export_csv($form_id) {
        if (!$this->db_handler) {
            wp_die(__('Database handler not initialized', 'mrm-ele-addon'));
        }
        
        $entries = $this->db_handler->get_all_entries_by_form($form_id);
        
        if (empty($entries)) {
            wp_die(__('No entries found to export', 'mrm-ele-addon'));
        }
        
        // Get form name
        $form_name = '';
        if (!empty($entries[0]['form_name'])) {
            $form_name = sanitize_file_name($entries[0]['form_name']);
        }
        
        // Set headers for CSV download
        $filename = 'registration-entries-' . $form_name . '-' . date('Y-m-d-H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // Get column headers from first entry
        $first_data = json_decode($entries[0]['data'], true);
        $headers = array('ID', 'Date & Time', 'User ID');
        
        if (is_array($first_data)) {
            foreach ($first_data as $key => $value) {
                $headers[] = ucwords(str_replace(array('_', '-'), ' ', $key));
            }
        }
        
        // Write headers
        fputcsv($output, $headers);
        
        // Write data rows
        foreach ($entries as $entry) {
            $data = json_decode($entry['data'], true);
            $row = array(
                $entry['id'],
                $entry['date_time'],
                $entry['user_id']
            );
            
            if (is_array($data)) {
                foreach ($first_data as $key => $value) {
                    $row[] = isset($data[$key]) ? $data[$key] : '';
                }
            }
            
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Delete entry with password protection
     */
    private function delete_entry($entry_id) {
        // Check password
        if (!isset($_GET['password']) || $_GET['password'] !== 'jiomerelal') {
            wp_die(
                __('Invalid password. Please enter the correct password to delete entries.', 'mrm-ele-addon'),
                __('Password Required', 'mrm-ele-addon'),
                array('back_link' => true)
            );
        }
        
        if (!$this->db_handler) {
            wp_die(__('Database handler not initialized', 'mrm-ele-addon'));
        }
        
        $result = $this->db_handler->delete_entry($entry_id);
        
        if ($result) {
            wp_redirect(add_query_arg(array(
                'page' => 'mrm-registration-entries',
                'deleted' => 1
            ), admin_url('admin.php')));
            exit;
        } else {
            wp_die(__('Failed to delete entry', 'mrm-ele-addon'));
        }
    }
    
    /**
     * Bulk delete with password protection
     */
    private function bulk_delete($entry_ids) {
        // Check password
        if (!isset($_POST['delete_password']) || $_POST['delete_password'] !== 'jiomerelal') {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible"><p>';
                echo esc_html__('Invalid password. Please enter the correct password to delete entries.', 'mrm-ele-addon');
                echo '</p></div>';
            });
            return;
        }
        
        if (!$this->db_handler) {
            return;
        }
        
        $result = $this->db_handler->delete_entries($entry_ids);
        
        if ($result) {
            add_action('admin_notices', function() use ($entry_ids) {
                echo '<div class="notice notice-success is-dismissible"><p>';
                echo sprintf(
                    esc_html__('%d entries deleted successfully.', 'mrm-ele-addon'),
                    count($entry_ids)
                );
                echo '</p></div>';
            });
        }
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        if (!$this->db_handler) {
            echo '<div class="wrap"><h1>' . esc_html__('Registration Form Submissions', 'mrm-ele-addon') . '</h1>';
            echo '<div class="error"><p>' . esc_html__('Database handler not initialized', 'mrm-ele-addon') . '</p></div></div>';
            return;
        }
        
        // Get all form names
        $forms = $this->db_handler->get_form_names();
        
        // Get selected form
        $selected_form_id = isset($_GET['form_id']) ? absint($_GET['form_id']) : 0;
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Registration Form Submissions', 'mrm-ele-addon'); ?></h1>
            
            <?php
            // Show success message
            if (isset($_GET['deleted'])) {
                echo '<div class="notice notice-success is-dismissible"><p>';
                echo esc_html__('Entry deleted successfully.', 'mrm-ele-addon');
                echo '</p></div>';
            }
            ?>
            
            <div class="mrm-entries-header" style="margin: 20px 0; padding: 20px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <form method="get" action="">
                    <input type="hidden" name="page" value="mrm-registration-entries" />
                    
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <label for="form_id" style="font-weight: 600;">
                            <?php echo esc_html__('Select Form:', 'mrm-ele-addon'); ?>
                        </label>
                        
                        <select name="form_id" id="form_id" style="min-width: 300px;">
                            <option value=""><?php echo esc_html__('-- Select a Form --', 'mrm-ele-addon'); ?></option>
                            <?php foreach ($forms as $form) : ?>
                                <option value="<?php echo esc_attr($form['form_id']); ?>" <?php selected($selected_form_id, $form['form_id']); ?>>
                                    <?php echo esc_html($form['form_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="button button-primary">
                            <?php echo esc_html__('Search', 'mrm-ele-addon'); ?>
                        </button>
                        
                        <?php if ($selected_form_id > 0) : ?>
                            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('action' => 'export_csv', 'form_id' => $selected_form_id)), 'mrm_export_csv')); ?>" 
                               class="button button-secondary">
                                <?php echo esc_html__('Download CSV', 'mrm-ele-addon'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <?php if (empty($forms)) : ?>
                <div class="notice notice-info">
                    <p><?php echo esc_html__('No form submissions found yet.', 'mrm-ele-addon'); ?></p>
                </div>
            <?php elseif ($selected_form_id > 0) : ?>
                
                <form method="post" action="">
                    <?php
                    // Create list table
                    $list_table = new MRM_Registration_Entries_List_Table($this->db_handler, $selected_form_id);
                    $list_table->prepare_items();
                    
                    // Display list table
                    $list_table->display();
                    ?>
                    
                    <div id="mrm-delete-password-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100000;">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 30px; border-radius: 5px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); min-width: 400px;">
                            <h2 style="margin-top: 0;"><?php echo esc_html__('Password Required', 'mrm-ele-addon'); ?></h2>
                            <p><?php echo esc_html__('Enter password to delete entries:', 'mrm-ele-addon'); ?></p>
                            <input type="password" name="delete_password" id="delete_password" class="regular-text" style="width: 100%; margin: 10px 0;" />
                            <p style="margin-top: 20px;">
                                <button type="submit" class="button button-primary">
                                    <?php echo esc_html__('Delete', 'mrm-ele-addon'); ?>
                                </button>
                                <button type="button" class="button" onclick="document.getElementById('mrm-delete-password-modal').style.display='none';">
                                    <?php echo esc_html__('Cancel', 'mrm-ele-addon'); ?>
                                </button>
                            </p>
                        </div>
                    </div>
                </form>
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    // Show password modal when bulk action is triggered
                    $('#doaction, #doaction2').on('click', function(e) {
                        var action = $(this).siblings('select').val();
                        var checked = $('input[name="entries[]"]:checked').length;
                        
                        if (action === 'delete' && checked > 0) {
                            e.preventDefault();
                            $('#mrm-delete-password-modal').show();
                        }
                    });
                    
                    // Handle single delete click
                    $('.delete a').on('click', function(e) {
                        e.preventDefault();
                        var href = $(this).attr('href');
                        var password = prompt('<?php echo esc_js(__('Enter password to delete this entry:', 'mrm-ele-addon')); ?>');
                        
                        if (password) {
                            window.location.href = href + '&password=' + encodeURIComponent(password);
                        }
                    });
                });
                </script>
                
            <?php else : ?>
                <div class="notice notice-info">
                    <p><?php echo esc_html__('Please select a form to view submissions.', 'mrm-ele-addon'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

// Initialize admin class
$mrm_entries_admin = new MRM_Registration_Entries_Admin();

// Set DB handler after it's initialized
add_action('init', function() use ($mrm_entries_admin) {
    global $mrm_registration_db;
    if (isset($mrm_registration_db)) {
        $mrm_entries_admin->set_db_handler($mrm_registration_db);
    }
}, 20);
