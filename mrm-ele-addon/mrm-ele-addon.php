<?php
/**
 * Plugin Name: MRM Ele Addon
 * Description: Custom Elementor addon plugin with drag and drop widgets
 * Plugin URI: 
 * Version: 2.5.0
 * Author: Burhan Hasanfatta
 * Author URI: 
 * Text Domain: mrm-ele-addon
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Main MRM Ele Addon Class
 */
final class MRM_Ele_Addon {

    /**
     * Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Load includes
        $this->load_includes();

        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_frontend_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
    }

    /**
     * Load includes
     */
    public function load_includes() {
        // Load Service Account Manager
        if (file_exists(__DIR__ . '/includes/service-account-manager.php')) {
            require_once(__DIR__ . '/includes/service-account-manager.php');
        }

        // Load CF7 Popup AJAX Handler
        if (file_exists(__DIR__ . '/includes/cf7-popup-ajax-handler.php')) {
            require_once(__DIR__ . '/includes/cf7-popup-ajax-handler.php');
        }

        // Load CF7 Popup Security
        if (file_exists(__DIR__ . '/includes/cf7-popup-security.php')) {
            require_once(__DIR__ . '/includes/cf7-popup-security.php');
        }
    }

    /**
     * Admin notice
     * Warning when the site doesn't have Elementor installed or activated.
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'mrm-ele-addon'),
            '<strong>' . esc_html__('MRM Ele Addon', 'mrm-ele-addon') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'mrm-ele-addon') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required Elementor version.
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'mrm-ele-addon'),
            '<strong>' . esc_html__('MRM Ele Addon', 'mrm-ele-addon') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'mrm-ele-addon') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required PHP version.
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'mrm-ele-addon'),
            '<strong>' . esc_html__('MRM Ele Addon', 'mrm-ele-addon') . '</strong>',
            '<strong>' . esc_html__('PHP', 'mrm-ele-addon') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Register Widgets
     */
    public function register_widgets($widgets_manager) {
        // Include Widget files
        require_once(__DIR__ . '/widgets/demo-widget.php');
        require_once(__DIR__ . '/widgets/mrm-header-widget.php');
        require_once(__DIR__ . '/widgets/hero-slider-widget.php');
        require_once(__DIR__ . '/widgets/feature-box-widget.php');
        require_once(__DIR__ . '/widgets/cause-box-widget.php');
        require_once(__DIR__ . '/widgets/about-charity-widget.php');
        require_once(__DIR__ . '/widgets/event-box-widget.php');
        require_once(__DIR__ . '/widgets/volunteer-box-widget.php');
        require_once(__DIR__ . '/widgets/blog-box-widget.php');
        require_once(__DIR__ . '/widgets/get-in-touch-widget.php');
        require_once(__DIR__ . '/widgets/contact-form-widget.php');
        require_once(__DIR__ . '/widgets/footer-widget.php');
        require_once(__DIR__ . '/widgets/cf7-popup-widget.php');

        // Register widgets
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Demo_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\MRM_Header_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Hero_Slider_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Feature_Box_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Cause_Box_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\About_Charity_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Event_Box_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Volunteer_Box_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Blog_Box_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Get_In_Touch_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Contact_Form_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\Footer_Widget());
        $widgets_manager->register(new \MRM_Ele_Addon\Widgets\CF7_Popup_Widget());
    }

    /**
     * Add custom category for widgets
     */
    public function add_elementor_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'mrm-elements',
            [
                'title' => esc_html__('MRM Elements', 'mrm-ele-addon'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * Register frontend scripts
     */
    public function register_frontend_scripts() {
        wp_register_script(
            'mrm-cf7-popup-script',
            plugins_url('/assets/js/cf7-popup-script.js', __FILE__),
            ['jquery'],
            self::VERSION,
            true
        );
    }

    /**
     * Register frontend styles
     */
    public function register_frontend_styles() {
        wp_register_style(
            'mrm-cf7-popup-style',
            plugins_url('/assets/css/cf7-popup-style.css', __FILE__),
            [],
            self::VERSION
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        // Enqueue CF7 Popup assets
        wp_enqueue_style('mrm-cf7-popup-style');
        wp_enqueue_script('mrm-cf7-popup-script');

        // Localize script with AJAX data
        wp_localize_script('mrm-cf7-popup-script', 'mrmCF7PopupData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mrm_cf7_popup_nonce'),
            'siteUrl' => site_url(),
        ]);
    }
}

// Initialize the plugin
MRM_Ele_Addon::instance();

