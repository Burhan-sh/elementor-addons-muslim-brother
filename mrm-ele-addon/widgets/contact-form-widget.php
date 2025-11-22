<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Contact Form 7 Widget
 */
class Contact_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-contact-form-7';
    }

    public function get_title() {
        return esc_html__('Contact Form 7', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['contact', 'form', 'cf7', 'contact form 7'];
    }

    /**
     * Get Contact Form 7 forms
     */
    private function get_contact_forms() {
        $forms = [];
        
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

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Form', 'mrm-ele-addon'),
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
                'description' => esc_html__('Select a Contact Form 7 from the list. If no forms appear, please create one in Contact > Contact Forms.', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'cf7_redirect_url',
            [
                'label' => esc_html__('Redirect URL (After Submission)', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-site.com/thank-you',
                'description' => esc_html__('Leave empty to stay on the same page after submission.', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'cf7_hide_labels',
            [
                'label' => esc_html__('Hide Labels', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Style: Form Container
        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__('Form Container', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 40,
                    'right' => 40,
                    'bottom' => 40,
                    'left' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'form_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'form_box_shadow',
                'selector' => '{{WRAPPER}} .mrm-contact-form',
            ]
        );

        $this->end_controls_section();

        // Style: Input Fields
        $this->start_controls_section(
            'style_inputs',
            [
                'label' => esc_html__('Input Fields', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]), {{WRAPPER}} .mrm-contact-form textarea, {{WRAPPER}} .mrm-contact-form select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]), {{WRAPPER}} .mrm-contact-form textarea, {{WRAPPER}} .mrm-contact-form select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__('Border Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e0e0e0',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]), {{WRAPPER}} .mrm-contact-form textarea, {{WRAPPER}} .mrm-contact-form select' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => esc_html__('Focus Border Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]):focus, {{WRAPPER}} .mrm-contact-form textarea:focus, {{WRAPPER}} .mrm-contact-form select:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]), {{WRAPPER}} .mrm-contact-form textarea, {{WRAPPER}} .mrm-contact-form select',
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 15,
                    'right' => 20,
                    'bottom' => 15,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]), {{WRAPPER}} .mrm-contact-form textarea, {{WRAPPER}} .mrm-contact-form select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input:not([type="submit"]), {{WRAPPER}} .mrm-contact-form textarea, {{WRAPPER}} .mrm-contact-form select' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Submit Button
        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__('Submit Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => esc_html__('Hover Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e64a19',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mrm-contact-form input[type="submit"]',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 15,
                    'right' => 40,
                    'bottom' => 15,
                    'left' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form input[type="submit"]' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Labels
        $this->start_controls_section(
            'style_labels',
            [
                'label' => esc_html__('Labels', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-form label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .mrm-contact-form label',
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

        $hide_labels_class = $settings['cf7_hide_labels'] === 'yes' ? 'hide-labels' : '';
        ?>

        <div class="mrm-contact-form <?php echo esc_attr($hide_labels_class); ?>">
            <?php echo do_shortcode('[contact-form-7 id="' . intval($settings['cf7_form_id']) . '"]'); ?>
        </div>

        <style>
            .mrm-contact-form {
                width: 100%;
            }

            .mrm-contact-form .wpcf7-form-control-wrap {
                display: block;
            }

            .mrm-contact-form input:not([type="submit"]),
            .mrm-contact-form textarea,
            .mrm-contact-form select {
                width: 100%;
                border: 1px solid;
                font-family: inherit;
                transition: border-color 0.3s ease;
                outline: none;
            }

            .mrm-contact-form textarea {
                resize: vertical;
                min-height: 120px;
            }

            .mrm-contact-form input[type="submit"] {
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                font-weight: 600;
            }

            .mrm-contact-form input[type="submit"]:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            }

            .mrm-contact-form .wpcf7-not-valid-tip {
                color: #dc3232;
                font-size: 14px;
                margin-top: 5px;
            }

            .mrm-contact-form .wpcf7-response-output {
                margin-top: 20px;
                padding: 15px;
                border-radius: 5px;
            }

            .mrm-contact-form.hide-labels label {
                display: none;
            }

            .mrm-contact-form .wpcf7-spinner {
                margin-left: 10px;
            }
        </style>

        <?php if (!empty($settings['cf7_redirect_url']['url'])) : ?>
        <script>
        document.addEventListener('wpcf7mailsent', function(event) {
            window.location.href = '<?php echo esc_url($settings['cf7_redirect_url']['url']); ?>';
        }, false);
        </script>
        <?php endif; ?>

        <?php
    }
}
