<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get In Touch Widget
 */
class Get_In_Touch_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-get-in-touch';
    }

    public function get_title() {
        return esc_html__('Get In Touch', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['contact', 'info', 'address', 'phone', 'email'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Get In Touch',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Have questions or want to learn more about our work? We\'d love to hear from you!',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Contact Items Section
        $this->start_controls_section(
            'contact_items_section',
            [
                'label' => esc_html__('Contact Items', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_icon',
            [
                'label' => esc_html__('Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-map-marker-alt',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'item_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Address',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_content',
            [
                'label' => esc_html__('Content', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '123 Charity Street, Nehru Place, New Delhi - 110019',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label' => esc_html__('Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $repeater->add_control(
            'hide_item',
            [
                'label' => esc_html__('Hide This Item', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'contact_items',
            [
                'label' => esc_html__('Contact Items', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_icon' => [
                            'value' => 'fas fa-map-marker-alt',
                            'library' => 'fa-solid',
                        ],
                        'item_title' => 'Address',
                        'item_content' => '123 Charity Street, Nehru Place, New Delhi - 110019',
                    ],
                    [
                        'item_icon' => [
                            'value' => 'fas fa-phone',
                            'library' => 'fa-solid',
                        ],
                        'item_title' => 'Phone',
                        'item_content' => '+91 123 456 7890',
                    ],
                    [
                        'item_icon' => [
                            'value' => 'fas fa-envelope',
                            'library' => 'fa-solid',
                        ],
                        'item_title' => 'Email',
                        'item_content' => 'info@hopefoundation.org',
                    ],
                    [
                        'item_icon' => [
                            'value' => 'fas fa-clock',
                            'library' => 'fa-solid',
                        ],
                        'item_title' => 'Working Hours',
                        'item_content' => 'Mon - Sat: 9:00 AM - 6:00 PM',
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->end_controls_section();

        // Style: Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Section', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'section_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-section' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 80,
                    'right' => 0,
                    'bottom' => 80,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-contact-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Title
        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .contact-info h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .contact-info h2',
            ]
        );

        $this->end_controls_section();

        // Style: Description
        $this->start_controls_section(
            'style_description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .contact-info > p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .contact-info > p',
            ]
        );

        $this->end_controls_section();

        // Style: Contact Items
        $this->start_controls_section(
            'style_items',
            [
                'label' => esc_html__('Contact Items', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__('Icon Background', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .contact-item i' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .contact-item i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .contact-item svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_title_color',
            [
                'label' => esc_html__('Title Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .contact-item h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_title_typography',
                'selector' => '{{WRAPPER}} .contact-item h4',
            ]
        );

        $this->add_control(
            'item_content_color',
            [
                'label' => esc_html__('Content Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .contact-item p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_content_typography',
                'selector' => '{{WRAPPER}} .contact-item p',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="mrm-contact-section">
            <div class="mrm-container">
                <div class="contact-info">
                    <h2><?php echo esc_html($settings['title']); ?></h2>
                    <p><?php echo esc_html($settings['description']); ?></p>
                    
                    <div class="contact-details">
                        <?php foreach ($settings['contact_items'] as $item) : 
                            if ($item['hide_item'] === 'yes') continue;
                            
                            $has_link = !empty($item['item_link']['url']);
                            $target = $has_link && $item['item_link']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $has_link && $item['item_link']['nofollow'] ? ' rel="nofollow"' : '';
                        ?>
                        <div class="contact-item">
                            <i class="icon-wrapper">
                                <?php Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']); ?>
                            </i>
                            <div>
                                <h4><?php echo esc_html($item['item_title']); ?></h4>
                                <?php if ($has_link) : ?>
                                    <a href="<?php echo esc_url($item['item_link']['url']); ?>" <?php echo $target . $nofollow; ?>>
                                        <p><?php echo esc_html($item['item_content']); ?></p>
                                    </a>
                                <?php else : ?>
                                    <p><?php echo esc_html($item['item_content']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .mrm-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .contact-info h2 {
                font-size: 38px;
                margin-bottom: 20px;
            }

            .contact-info > p {
                margin-bottom: 40px;
                line-height: 1.8;
            }

            .contact-details {
                display: flex;
                flex-direction: column;
                gap: 25px;
            }

            .contact-item {
                display: flex;
                gap: 20px;
            }

            .contact-item i {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                flex-shrink: 0;
            }

            .contact-item h4 {
                font-size: 18px;
                margin-bottom: 5px;
            }

            .contact-item p {
                margin: 0;
            }

            .contact-item a {
                text-decoration: none;
                color: inherit;
                transition: opacity 0.3s ease;
            }

            .contact-item a:hover {
                opacity: 0.7;
            }
        </style>

        <?php
    }
}
