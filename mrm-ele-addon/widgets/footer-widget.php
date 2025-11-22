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
 * Footer Widget
 */
class Footer_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-footer';
    }

    public function get_title() {
        return esc_html__('Footer', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-footer';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['footer', 'bottom', 'links'];
    }

    protected function register_controls() {
        
        // Column 1 Section
        $this->start_controls_section(
            'column1_section',
            [
                'label' => esc_html__('Column 1 - About', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'col1_show',
            [
                'label' => esc_html__('Show Column 1', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'col1_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'About Hope Foundation',
                'label_block' => true,
                'condition' => [
                    'col1_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'col1_description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'We are dedicated to creating positive change in the lives of underprivileged communities through sustainable programs and initiatives that address education, healthcare, and basic needs.',
                'label_block' => true,
                'condition' => [
                    'col1_show' => 'yes',
                ],
            ]
        );

        // Social Links
        $social_repeater = new Repeater();

        $social_repeater->add_control(
            'social_icon',
            [
                'label' => esc_html__('Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
            ]
        );

        $social_repeater->add_control(
            'social_link',
            [
                'label' => esc_html__('Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'social_links',
            [
                'label' => esc_html__('Social Links', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $social_repeater->get_controls(),
                'default' => [
                    [
                        'social_icon' => [
                            'value' => 'fab fa-facebook-f',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-twitter',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-instagram',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-linkedin-in',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-youtube',
                            'library' => 'fa-brands',
                        ],
                    ],
                ],
                'title_field' => '<# if (social_icon.value) { #><i class="{{{ social_icon.value }}}"></i><# } #>',
                'condition' => [
                    'col1_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Column 2 Section
        $this->start_controls_section(
            'column2_section',
            [
                'label' => esc_html__('Column 2 - Quick Links', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'col2_show',
            [
                'label' => esc_html__('Show Column 2', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'col2_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Quick Links',
                'label_block' => true,
                'condition' => [
                    'col2_show' => 'yes',
                ],
            ]
        );

        $links_repeater = new Repeater();

        $links_repeater->add_control(
            'link_text',
            [
                'label' => esc_html__('Link Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Home',
                'label_block' => true,
            ]
        );

        $links_repeater->add_control(
            'link_url',
            [
                'label' => esc_html__('Link URL', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#home',
                ],
            ]
        );

        $this->add_control(
            'col2_links',
            [
                'label' => esc_html__('Links', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $links_repeater->get_controls(),
                'default' => [
                    ['link_text' => 'Home', 'link_url' => ['url' => '#home']],
                    ['link_text' => 'About Us', 'link_url' => ['url' => '#about']],
                    ['link_text' => 'Our Causes', 'link_url' => ['url' => '#causes']],
                    ['link_text' => 'Events', 'link_url' => ['url' => '#events']],
                    ['link_text' => 'Blog', 'link_url' => ['url' => '#blog']],
                    ['link_text' => 'Contact', 'link_url' => ['url' => '#contact']],
                ],
                'title_field' => '{{{ link_text }}}',
                'condition' => [
                    'col2_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Column 3 Section
        $this->start_controls_section(
            'column3_section',
            [
                'label' => esc_html__('Column 3 - Support', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'col3_show',
            [
                'label' => esc_html__('Show Column 3', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'col3_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Support',
                'label_block' => true,
                'condition' => [
                    'col3_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'col3_links',
            [
                'label' => esc_html__('Links', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $links_repeater->get_controls(),
                'default' => [
                    ['link_text' => 'Make a Donation', 'link_url' => ['url' => '#donate']],
                    ['link_text' => 'Become a Volunteer', 'link_url' => ['url' => '#volunteers']],
                    ['link_text' => 'Privacy Policy', 'link_url' => ['url' => '#']],
                    ['link_text' => 'Terms & Conditions', 'link_url' => ['url' => '#']],
                    ['link_text' => 'FAQ', 'link_url' => ['url' => '#']],
                ],
                'title_field' => '{{{ link_text }}}',
                'condition' => [
                    'col3_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Column 4 Section
        $this->start_controls_section(
            'column4_section',
            [
                'label' => esc_html__('Column 4 - Contact Info', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'col4_show',
            [
                'label' => esc_html__('Show Column 4', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'col4_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Contact Info',
                'label_block' => true,
                'condition' => [
                    'col4_show' => 'yes',
                ],
            ]
        );

        $contact_repeater = new Repeater();

        $contact_repeater->add_control(
            'contact_icon',
            [
                'label' => esc_html__('Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-map-marker-alt',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $contact_repeater->add_control(
            'contact_text',
            [
                'label' => esc_html__('Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '123 Charity Street, New Delhi',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'col4_contacts',
            [
                'label' => esc_html__('Contact Items', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $contact_repeater->get_controls(),
                'default' => [
                    [
                        'contact_icon' => ['value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid'],
                        'contact_text' => '123 Charity Street, New Delhi',
                    ],
                    [
                        'contact_icon' => ['value' => 'fas fa-phone', 'library' => 'fa-solid'],
                        'contact_text' => '+91 123 456 7890',
                    ],
                    [
                        'contact_icon' => ['value' => 'fas fa-envelope', 'library' => 'fa-solid'],
                        'contact_text' => 'info@hopefoundation.org',
                    ],
                ],
                'title_field' => '{{{ contact_text }}}',
                'condition' => [
                    'col4_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Bottom Section
        $this->start_controls_section(
            'bottom_section',
            [
                'label' => esc_html__('Footer Bottom', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'copyright_text',
            [
                'label' => esc_html__('Copyright Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '© 2024 Hope Foundation. All rights reserved.',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Style: Footer
        $this->start_controls_section(
            'style_footer',
            [
                'label' => esc_html__('Footer', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'footer_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mrm-footer' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-footer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'footer_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 60,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Headings
        $this->start_controls_section(
            'style_headings',
            [
                'label' => esc_html__('Column Headings', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .footer-col h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .footer-col h3',
            ]
        );

        $this->end_controls_section();

        // Style: Links
        $this->start_controls_section(
            'style_links',
            [
                'label' => esc_html__('Links', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .footer-col ul li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__('Hover Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .footer-col ul li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'selector' => '{{WRAPPER}} .footer-col ul li a',
            ]
        );

        $this->end_controls_section();

        // Style: Social Icons
        $this->start_controls_section(
            'style_social',
            [
                'label' => esc_html__('Social Icons', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .social-links a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label' => esc_html__('Icon Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .social-links a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_bg_color',
            [
                'label' => esc_html__('Hover Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .social-links a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_color',
            [
                'label' => esc_html__('Hover Icon Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .social-links a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Bottom
        $this->start_controls_section(
            'style_bottom',
            [
                'label' => esc_html__('Footer Bottom', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bottom_border_color',
            [
                'label' => esc_html__('Border Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .footer-bottom' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bottom_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .footer-bottom' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <footer class="mrm-footer">
            <div class="mrm-container">
                <div class="footer-content">
                    <?php if ($settings['col1_show'] === 'yes') : ?>
                    <div class="footer-col">
                        <h3><?php echo esc_html($settings['col1_title']); ?></h3>
                        <p><?php echo esc_html($settings['col1_description']); ?></p>
                        <?php if (!empty($settings['social_links'])) : ?>
                        <div class="social-links">
                            <?php foreach ($settings['social_links'] as $social) : 
                                $target = $social['social_link']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $social['social_link']['nofollow'] ? ' rel="nofollow"' : '';
                            ?>
                            <a href="<?php echo esc_url($social['social_link']['url']); ?>" <?php echo $target . $nofollow; ?>>
                                <?php Icons_Manager::render_icon($social['social_icon'], ['aria-hidden' => 'true']); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['col2_show'] === 'yes') : ?>
                    <div class="footer-col">
                        <h3><?php echo esc_html($settings['col2_title']); ?></h3>
                        <ul>
                            <?php foreach ($settings['col2_links'] as $link) : 
                                $target = $link['link_url']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $link['link_url']['nofollow'] ? ' rel="nofollow"' : '';
                            ?>
                            <li><a href="<?php echo esc_url($link['link_url']['url']); ?>" <?php echo $target . $nofollow; ?>><?php echo esc_html($link['link_text']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['col3_show'] === 'yes') : ?>
                    <div class="footer-col">
                        <h3><?php echo esc_html($settings['col3_title']); ?></h3>
                        <ul>
                            <?php foreach ($settings['col3_links'] as $link) : 
                                $target = $link['link_url']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $link['link_url']['nofollow'] ? ' rel="nofollow"' : '';
                            ?>
                            <li><a href="<?php echo esc_url($link['link_url']['url']); ?>" <?php echo $target . $nofollow; ?>><?php echo esc_html($link['link_text']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['col4_show'] === 'yes') : ?>
                    <div class="footer-col">
                        <h3><?php echo esc_html($settings['col4_title']); ?></h3>
                        <ul class="footer-contact">
                            <?php foreach ($settings['col4_contacts'] as $contact) : ?>
                            <li>
                                <?php Icons_Manager::render_icon($contact['contact_icon'], ['aria-hidden' => 'true']); ?>
                                <?php echo esc_html($contact['contact_text']); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="footer-bottom">
                    <p><?php echo esc_html($settings['copyright_text']); ?></p>
                </div>
            </div>
        </footer>

        <style>
            .mrm-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 40px;
                margin-bottom: 40px;
            }

            .footer-col h3 {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .footer-col p {
                line-height: 1.8;
                margin-bottom: 20px;
                opacity: 0.9;
            }

            .social-links {
                display: flex;
                gap: 10px;
            }

            .social-links a {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .social-links a:hover {
                transform: translateY(-5px);
            }

            .footer-col ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .footer-col ul li {
                margin-bottom: 12px;
            }

            .footer-col ul li a {
                opacity: 0.9;
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .footer-col ul li a:hover {
                opacity: 1;
                padding-left: 5px;
            }

            .footer-contact li {
                display: flex;
                align-items: flex-start;
                gap: 15px;
                margin-bottom: 15px;
            }

            .footer-contact li i,
            .footer-contact li svg {
                color: #ff5722;
                margin-top: 3px;
            }

            .footer-bottom {
                border-top: 1px solid;
                padding-top: 20px;
                text-align: center;
                opacity: 0.9;
            }

            .footer-bottom p {
                margin: 0;
            }
        </style>

        <?php
    }
}
