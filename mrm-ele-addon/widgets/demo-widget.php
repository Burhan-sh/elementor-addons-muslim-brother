<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Demo Widget
 */
class Demo_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'mrm-demo-widget';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('MRM Demo Widget', 'mrm-ele-addon');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['mrm-elements'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['mrm', 'demo', 'custom'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Title Control
        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Welcome to MRM Widget', 'mrm-ele-addon'),
                'placeholder' => esc_html__('Enter your title', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        // Description Control
        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('This is a demo widget from MRM Ele Addon plugin. You can customize this widget with various options.', 'mrm-ele-addon'),
                'placeholder' => esc_html__('Enter your description', 'mrm-ele-addon'),
                'rows' => 5,
            ]
        );

        // Button Text Control
        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Click Here', 'mrm-ele-addon'),
                'placeholder' => esc_html__('Enter button text', 'mrm-ele-addon'),
            ]
        );

        // Button Link Control
        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mrm-ele-addon'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'style_title_section',
            [
                'label' => esc_html__('Title Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Title Color
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mrm-demo-widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Title Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-demo-widget-title',
            ]
        );

        $this->end_controls_section();

        // Style Section - Description
        $this->start_controls_section(
            'style_description_section',
            [
                'label' => esc_html__('Description Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Description Color
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mrm-demo-widget-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Description Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-demo-widget-description',
            ]
        );

        $this->end_controls_section();

        // Style Section - Button
        $this->start_controls_section(
            'style_button_section',
            [
                'label' => esc_html__('Button Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Button Background Color
        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0073aa',
                'selectors' => [
                    '{{WRAPPER}} .mrm-demo-widget-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Button Text Color
        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-demo-widget-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Button Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-demo-widget-button',
            ]
        );

        // Button Padding
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-demo-widget-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Button Border Radius
        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-demo-widget-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $target = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
        $nofollow = $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
        ?>
        
        <div class="mrm-demo-widget-wrapper">
            <div class="mrm-demo-widget-content">
                <?php if (!empty($settings['title'])) : ?>
                    <h2 class="mrm-demo-widget-title">
                        <?php echo esc_html($settings['title']); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($settings['description'])) : ?>
                    <p class="mrm-demo-widget-description">
                        <?php echo esc_html($settings['description']); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($settings['button_text'])) : ?>
                    <div class="mrm-demo-widget-button-wrapper">
                        <a href="<?php echo esc_url($settings['button_link']['url']); ?>" 
                           class="mrm-demo-widget-button"
                           <?php echo $target . $nofollow; ?>>
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .mrm-demo-widget-wrapper {
                padding: 30px;
                background: #f9f9f9;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                text-align: center;
            }
            
            .mrm-demo-widget-content {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .mrm-demo-widget-title {
                margin: 0 0 15px 0;
                font-size: 28px;
                font-weight: 700;
                line-height: 1.3;
            }
            
            .mrm-demo-widget-description {
                margin: 0 0 20px 0;
                font-size: 16px;
                line-height: 1.6;
            }
            
            .mrm-demo-widget-button-wrapper {
                margin-top: 20px;
            }
            
            .mrm-demo-widget-button {
                display: inline-block;
                text-decoration: none;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
            }
            
            .mrm-demo-widget-button:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }
        </style>
        
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var target = settings.button_link.is_external ? ' target="_blank"' : '';
        var nofollow = settings.button_link.nofollow ? ' rel="nofollow"' : '';
        #>
        
        <div class="mrm-demo-widget-wrapper">
            <div class="mrm-demo-widget-content">
                <# if (settings.title) { #>
                    <h2 class="mrm-demo-widget-title">{{{ settings.title }}}</h2>
                <# } #>

                <# if (settings.description) { #>
                    <p class="mrm-demo-widget-description">{{{ settings.description }}}</p>
                <# } #>

                <# if (settings.button_text) { #>
                    <div class="mrm-demo-widget-button-wrapper">
                        <a href="{{ settings.button_link.url }}" 
                           class="mrm-demo-widget-button"
                           {{{ target }}} {{{ nofollow }}}>
                            {{{ settings.button_text }}}
                        </a>
                    </div>
                <# } #>
            </div>
        </div>
        
        <?php
    }
}

