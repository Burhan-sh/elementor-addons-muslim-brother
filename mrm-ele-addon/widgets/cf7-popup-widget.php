<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MRM CF7 Popup Widget
 */
class CF7_Popup_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-cf7-popup';
    }

    public function get_title() {
        return esc_html__('MRM CF7 Popup', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-popup';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['contact', 'form', 'cf7', 'popup', 'modal', 'contact form 7'];
    }

    public function get_script_depends() {
        return ['mrm-cf7-popup-script'];
    }

    public function get_style_depends() {
        return ['mrm-cf7-popup-style'];
    }

    /**
     * Get Contact Form 7 forms
     */
    private function get_contact_forms() {
        $forms = ['' => esc_html__('Select a form', 'mrm-ele-addon')];
        
        if (function_exists('wpcf7')) {
            $cf7_forms = get_posts([
                'post_type' => 'wpcf7_contact_form',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            ]);

            if (!empty($cf7_forms)) {
                foreach ($cf7_forms as $form) {
                    $forms[$form->ID] = $form->post_title;
                }
            }
        }

        return $forms;
    }

    /**
     * Get CF7 form fields
     */
    private function get_cf7_form_fields($form_id) {
        if (empty($form_id) || !function_exists('wpcf7_contact_form')) {
            return [];
        }

        $form = wpcf7_contact_form($form_id);
        if (!$form) {
            return [];
        }

        $form_fields = [];
        $form_content = $form->prop('form');
        
        // Parse form fields
        preg_match_all('/\[([^\]]+)\]/', $form_content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $field) {
                $parts = explode(' ', $field);
                $field_type = $parts[0];
                
                // Skip submit button
                if ($field_type === 'submit') {
                    continue;
                }
                
                // Get field name
                $field_name = '';
                foreach ($parts as $part) {
                    if (strpos($part, '*') === false && !in_array($part, ['text', 'email', 'tel', 'textarea', 'select', 'checkbox', 'radio', 'file', 'date', 'number', 'url'])) {
                        $field_name = $part;
                        break;
                    }
                }
                
                if (!empty($field_name)) {
                    $form_fields[$field_name] = ucwords(str_replace(['_', '-'], ' ', $field_name));
                }
            }
        }

        return $form_fields;
    }

    protected function register_controls() {
        
        // ========================================
        // CONTENT TAB
        // ========================================

        // CF7 Form Section
        $this->start_controls_section(
            'section_cf7_form',
            [
                'label' => esc_html__('Contact Form', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cf7_form_id',
            [
                'label' => esc_html__('Select Contact Form', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_contact_forms(),
                'default' => '',
                'description' => esc_html__('Select a Contact Form 7. If no forms appear, please create one first.', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'show_labels',
            [
                'label' => esc_html__('Show Labels', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mrm-ele-addon'),
                'label_off' => esc_html__('Hide', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Popup Button Section
        $this->start_controls_section(
            'section_popup_button',
            [
                'label' => esc_html__('Popup Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Contact Us', 'mrm-ele-addon'),
                'placeholder' => esc_html__('Enter button text', 'mrm-ele-addon'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => esc_html__('Alignment', 'mrm-ele-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'mrm-ele-addon'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'mrm-ele-addon'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'mrm-ele-addon'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'mrm-ele-addon'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-button-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Popup Trigger Settings
        $this->start_controls_section(
            'section_popup_trigger',
            [
                'label' => esc_html__('Popup Trigger', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'popup_trigger_type',
            [
                'label' => esc_html__('Trigger Type', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'button' => esc_html__('On Button Click', 'mrm-ele-addon'),
                    'auto' => esc_html__('Auto Popup (Time-based)', 'mrm-ele-addon'),
                    'load' => esc_html__('On Page Load', 'mrm-ele-addon'),
                    'exit' => esc_html__('On Exit Intent', 'mrm-ele-addon'),
                ],
                'default' => 'button',
            ]
        );

        $this->add_control(
            'auto_popup_delay',
            [
                'label' => esc_html__('Auto Popup Delay (seconds)', 'mrm-ele-addon'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 300,
                'step' => 1,
                'condition' => [
                    'popup_trigger_type' => 'auto',
                ],
            ]
        );

        $this->add_control(
            'popup_frequency',
            [
                'label' => esc_html__('Popup Frequency', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'always' => esc_html__('Always Show', 'mrm-ele-addon'),
                    'once' => esc_html__('Once Per Session', 'mrm-ele-addon'),
                    'once_user' => esc_html__('Once Per User (Lifetime)', 'mrm-ele-addon'),
                    'interval' => esc_html__('Time Interval', 'mrm-ele-addon'),
                ],
                'default' => 'always',
            ]
        );

        $this->add_control(
            'popup_interval_minutes',
            [
                'label' => esc_html__('Show Every (minutes)', 'mrm-ele-addon'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 1440,
                'step' => 1,
                'condition' => [
                    'popup_frequency' => 'interval',
                ],
                'description' => esc_html__('Popup will show again after this many minutes', 'mrm-ele-addon'),
            ]
        );

        $this->end_controls_section();

        // Email Settings
        $this->start_controls_section(
            'section_email_settings',
            [
                'label' => esc_html__('Email Settings', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_cc_email',
            [
                'label' => esc_html__('Enable CC Email', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Send a copy to an additional email address', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'cc_email_address',
            [
                'label' => esc_html__('CC Email Address', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'email@example.com',
                'condition' => [
                    'enable_cc_email' => 'yes',
                ],
                'description' => esc_html__('Multiple emails can be separated by comma', 'mrm-ele-addon'),
            ]
        );

        $this->end_controls_section();

        // Google Sheets Integration
        $this->start_controls_section(
            'section_google_sheets',
            [
                'label' => esc_html__('Google Sheets Integration', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_google_sheets',
            [
                'label' => esc_html__('Enable Google Sheets', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'google_auth_method',
            [
                'label' => esc_html__('Authentication Method', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'api_key' => esc_html__('API Key (Read Only)', 'mrm-ele-addon'),
                    'service_account' => esc_html__('Service Account (Recommended)', 'mrm-ele-addon'),
                    'webhook' => esc_html__('Apps Script Webhook', 'mrm-ele-addon'),
                ],
                'default' => 'service_account',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                ],
                'description' => esc_html__('Service Account is required for writing data to Google Sheets', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'auth_method_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="padding:10px;background:#fff3cd;border-left:3px solid #ffc107;margin:10px 0;">'
                    . '<strong>⚠️ Important:</strong> API Keys can only READ publicly shared data. '
                    . 'To WRITE data to Google Sheets, you must use Service Account or Webhook method.</div>',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'api_key',
                ],
            ]
        );

        $this->add_control(
            'google_sheet_id',
            [
                'label' => esc_html__('Google Sheet ID', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '1OtbFHlzlUFGlPEFCUEKskaaVMv4ZoGrvEcLOUS4amE8',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                ],
                'description' => esc_html__('The ID from your Google Sheet URL', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'google_sheet_name',
            [
                'label' => esc_html__('Sheet Name/Tab', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Sheet1',
                'default' => 'Sheet1',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method!' => 'webhook',
                ],
            ]
        );

        // API Key Method Controls
        $this->add_control(
            'google_api_key',
            [
                'label' => esc_html__('Google API Key', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'AIzaSyDhJgrN1kbAZuuEMrl4u5eylFGcI_d1U80',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'api_key',
                ],
                'description' => esc_html__('Your Google Sheets API key (Read Only)', 'mrm-ele-addon'),
            ]
        );

        // Service Account Method Controls
        $this->add_control(
            'service_account_input_method',
            [
                'label' => esc_html__('Service Account Input', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'json_content' => esc_html__('Paste JSON Content', 'mrm-ele-addon'),
                    'file_path' => esc_html__('File Path', 'mrm-ele-addon'),
                ],
                'default' => 'json_content',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'service_account',
                ],
            ]
        );

        $this->add_control(
            'service_account_json',
            [
                'label' => esc_html__('Service Account JSON', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 10,
                'placeholder' => '{"type":"service_account","project_id":"...","private_key":"..."}',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'service_account',
                    'service_account_input_method' => 'json_content',
                ],
                'description' => esc_html__('Paste your Service Account JSON key file content here', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'service_account_file_path',
            [
                'label' => esc_html__('Service Account File Path', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '/wp-content/uploads/private/service-account.json',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'service_account',
                    'service_account_input_method' => 'file_path',
                ],
                'description' => esc_html__('Absolute path to Service Account JSON file', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'service_account_help',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="padding:10px;background:#d1ecf1;border-left:3px solid #0c5460;margin:10px 0;">'
                    . '<strong>📖 Setup Guide:</strong><br>'
                    . '1. Create Service Account in Google Cloud Console<br>'
                    . '2. Download JSON key file<br>'
                    . '3. Share your Google Sheet with Service Account email<br>'
                    . '4. Give it Editor permission<br><br>'
                    . '<a href="/wp-content/plugins/mrm-ele-addon/GOOGLE_SHEETS_401_ERROR_SOLUTION_HINDI.md" target="_blank">View Detailed Guide</a>'
                    . '</div>',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'service_account',
                ],
            ]
        );

        // Webhook Method Controls
        $this->add_control(
            'google_webhook_url',
            [
                'label' => esc_html__('Apps Script Webhook URL', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'https://script.google.com/macros/s/.../exec',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'webhook',
                ],
                'description' => esc_html__('Your Google Apps Script Web App URL', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'webhook_help',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="padding:10px;background:#d1ecf1;border-left:3px solid #0c5460;margin:10px 0;">'
                    . '<strong>Quick Setup:</strong><br>'
                    . '1. Open your Google Sheet<br>'
                    . '2. Extensions > Apps Script<br>'
                    . '3. Create doPost(e) function<br>'
                    . '4. Deploy as Web App<br>'
                    . '5. Copy the URL here<br>'
                    . '</div>',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                    'google_auth_method' => 'webhook',
                ],
            ]
        );

        $this->add_control(
            'field_mapping_note',
            [
                'label' => esc_html__('Field Mapping', 'mrm-ele-addon'),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('Map your form fields to Google Sheet columns below. Use column headers from your sheet (e.g., Name, Email, Phone)', 'mrm-ele-addon'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'enable_google_sheets' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'field_mapping',
            [
                'label' => esc_html__('Field Mapping (JSON)', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => '{"your-name":"Name","your-email":"Email","your-phone":"Phone","your-message":"Message"}',
                'description' => esc_html__('Map CF7 fields to Google Sheet columns in JSON format', 'mrm-ele-addon'),
                'condition' => [
                    'enable_google_sheets' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ========================================
        // STYLE TAB
        // ========================================

        // Button Style
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__('Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-trigger',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-trigger',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-trigger',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e64a19',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-trigger:hover',
            ]
        );

        $this->add_control(
            'button_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'mrm-ele-addon'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Popup Modal Style
        $this->start_controls_section(
            'section_popup_style',
            [
                'label' => esc_html__('Popup Modal', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'popup_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_width',
            [
                'label' => esc_html__('Width', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1200,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_max_width',
            [
                'label' => esc_html__('Max Width', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popup_box_shadow',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-content',
            ]
        );

        $this->add_control(
            'popup_overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.75)',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Close Button Style
        $this->start_controls_section(
            'section_close_button_style',
            [
                'label' => esc_html__('Close Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'close_button_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-close' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'close_button_hover_color',
            [
                'label' => esc_html__('Hover Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff0000',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-close:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'close_button_size',
            [
                'label' => esc_html__('Size', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Form Fields Style
        $this->start_controls_section(
            'section_form_fields_style',
            [
                'label' => esc_html__('Form Fields', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input:not([type="submit"]), {{WRAPPER}} .mrm-cf7-popup-form textarea, {{WRAPPER}} .mrm-cf7-popup-form select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input:not([type="submit"]), {{WRAPPER}} .mrm-cf7-popup-form textarea, {{WRAPPER}} .mrm-cf7-popup-form select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-form input:not([type="submit"]), {{WRAPPER}} .mrm-cf7-popup-form textarea, {{WRAPPER}} .mrm-cf7-popup-form select',
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input:not([type="submit"]), {{WRAPPER}} .mrm-cf7-popup-form textarea, {{WRAPPER}} .mrm-cf7-popup-form select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-form input:not([type="submit"]), {{WRAPPER}} .mrm-cf7-popup-form textarea, {{WRAPPER}} .mrm-cf7-popup-form select',
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input:not([type="submit"]), {{WRAPPER}} .mrm-cf7-popup-form textarea, {{WRAPPER}} .mrm-cf7-popup-form select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Submit Button Style
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label' => esc_html__('Submit Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_typography',
                'selector' => '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]',
            ]
        );

        $this->start_controls_tabs('tabs_submit_style');

        $this->start_controls_tab(
            'tab_submit_normal',
            [
                'label' => esc_html__('Normal', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'submit_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submit_hover',
            [
                'label' => esc_html__('Hover', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'submit_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e64a19',
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'submit_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'submit_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cf7-popup-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['cf7_form_id'])) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__('Please select a Contact Form 7 from the widget settings.', 'mrm-ele-addon') . '</div>';
            }
            return;
        }

        if (!function_exists('wpcf7')) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-danger">' . esc_html__('Contact Form 7 plugin is not installed or activated.', 'mrm-ele-addon') . '</div>';
            }
            return;
        }

        $widget_id = $this->get_id();
        $hide_labels_class = $settings['show_labels'] !== 'yes' ? 'hide-labels' : '';
        $animation_class = !empty($settings['button_hover_animation']) ? 'elementor-animation-' . $settings['button_hover_animation'] : '';
        
        // Prepare data attributes for JS
        $popup_data = [
            'trigger' => $settings['popup_trigger_type'],
            'frequency' => $settings['popup_frequency'],
            'delay' => isset($settings['auto_popup_delay']) ? $settings['auto_popup_delay'] : 5,
            'interval' => isset($settings['popup_interval_minutes']) ? $settings['popup_interval_minutes'] : 5,
            'widgetId' => $widget_id,
        ];
        
        // Google Sheets data
        $google_sheets_data = [];
        if ($settings['enable_google_sheets'] === 'yes' && !empty($settings['google_sheet_id'])) {
            $auth_method = $settings['google_auth_method'] ?? 'service_account';
            
            $google_sheets_data = [
                'enabled' => true,
                'authMethod' => $auth_method,
                'sheetId' => $settings['google_sheet_id'],
                'sheetName' => $settings['google_sheet_name'] ?? 'Sheet1',
                'fieldMapping' => !empty($settings['field_mapping']) ? $settings['field_mapping'] : '{}',
            ];
            
            // Add method-specific data
            if ($auth_method === 'api_key') {
                $google_sheets_data['apiKey'] = $settings['google_api_key'] ?? '';
            } elseif ($auth_method === 'service_account') {
                $input_method = $settings['service_account_input_method'] ?? 'json_content';
                $google_sheets_data['serviceAccountMethod'] = $input_method;
                
                if ($input_method === 'json_content') {
                    $google_sheets_data['serviceAccountJson'] = $settings['service_account_json'] ?? '';
                } else {
                    $google_sheets_data['serviceAccountPath'] = $settings['service_account_file_path'] ?? '';
                }
            } elseif ($auth_method === 'webhook') {
                $google_sheets_data['webhookUrl'] = $settings['google_webhook_url'] ?? '';
            }
        }
        
        // CC Email data
        $cc_email = '';
        if ($settings['enable_cc_email'] === 'yes' && !empty($settings['cc_email_address'])) {
            $cc_email = sanitize_text_field($settings['cc_email_address']);
        }
        ?>

        <div class="mrm-cf7-popup-wrapper" data-widget-id="<?php echo esc_attr($widget_id); ?>">
            <?php if ($settings['popup_trigger_type'] === 'button') : ?>
            <div class="mrm-cf7-popup-button-wrapper">
                <button class="mrm-cf7-popup-trigger <?php echo esc_attr($animation_class); ?>" 
                        data-popup-id="mrm-popup-<?php echo esc_attr($widget_id); ?>">
                    <?php echo esc_html($settings['button_text']); ?>
                </button>
            </div>
            <?php endif; ?>

            <div id="mrm-popup-<?php echo esc_attr($widget_id); ?>" 
                 class="mrm-cf7-popup-modal" 
                 data-popup-config='<?php echo esc_attr(wp_json_encode($popup_data)); ?>'
                 data-google-sheets='<?php echo esc_attr(wp_json_encode($google_sheets_data)); ?>'
                 data-cc-email='<?php echo esc_attr($cc_email); ?>'>
                <div class="mrm-cf7-popup-overlay"></div>
                <div class="mrm-cf7-popup-content">
                    <button class="mrm-cf7-popup-close" aria-label="<?php echo esc_attr__('Close', 'mrm-ele-addon'); ?>">
                        <span>&times;</span>
                    </button>
                    <div class="mrm-cf7-popup-form <?php echo esc_attr($hide_labels_class); ?>">
                        <?php echo do_shortcode('[contact-form-7 id="' . intval($settings['cf7_form_id']) . '"]'); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}
