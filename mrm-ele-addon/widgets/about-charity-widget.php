<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * About Charity Widget
 */
class About_Charity_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-about-charity';
    }

    public function get_title() {
        return esc_html__('About Charity Section', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-info-circle';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['about', 'charity', 'section'];
    }

    protected function register_controls() {
        
        // Image Section
        $this->start_controls_section(
            'image_section',
            [
                'label' => esc_html__('Image', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'mrm-ele-addon'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=600',
                ],
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => esc_html__('Show Badge', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_title',
            [
                'label' => esc_html__('Badge Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '67,90,95+',
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_subtitle',
            [
                'label' => esc_html__('Badge Subtitle', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'People We Helped',
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'About Charity',
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label' => esc_html__('Show Subtitle', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Helping Each Other Can Make World Better',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>Hope Foundation has been at the forefront of humanitarian work for over 15 years. We believe in creating lasting change through sustainable programs that address the root causes of poverty and inequality.</p><p>Our dedicated team works tirelessly to ensure that every donation makes a real impact. From providing basic necessities to empowering communities through education and skill development, we\'re committed to building a better future for all.</p>',
            ]
        );

        $this->end_controls_section();

        // Features Section
        $this->start_controls_section(
            'features_section',
            [
                'label' => esc_html__('Features', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_icon',
            [
                'label' => esc_html__('Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check-circle',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'feature_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Impactful Causes',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'feature_description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Focus on important social and humanitarian causes that make a meaningful difference.',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'hide_feature',
            [
                'label' => esc_html__('Hide This Feature', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => esc_html__('Features', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'feature_title' => 'Impactful Causes',
                        'feature_description' => 'Focus on important social and humanitarian causes that make a meaningful difference.',
                    ],
                    [
                        'feature_title' => 'Transparency',
                        'feature_description' => 'Complete financial accountability showing exactly how your donations create real-world change.',
                    ],
                    [
                        'feature_title' => 'Secure Donations',
                        'feature_description' => 'Safe and convenient online platform to support causes from anywhere in the world.',
                    ],
                ],
                'title_field' => '{{{ feature_title }}}',
            ]
        );

        $this->end_controls_section();

        // Stats Section
        $this->start_controls_section(
            'stats_section',
            [
                'label' => esc_html__('Statistics', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_stats',
            [
                'label' => esc_html__('Show Statistics', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'stat1_value',
            [
                'label' => esc_html__('Stat 1 Value', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '₹16M',
                'condition' => [
                    'show_stats' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat1_label',
            [
                'label' => esc_html__('Stat 1 Label', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Funds Collected',
                'condition' => [
                    'show_stats' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat2_value',
            [
                'label' => esc_html__('Stat 2 Value', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '6,478',
                'condition' => [
                    'show_stats' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stat2_label',
            [
                'label' => esc_html__('Stat 2 Label', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Active Volunteers',
                'condition' => [
                    'show_stats' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Sections...
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
                    '{{WRAPPER}} .mrm-about-section' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mrm-about-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Content
        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Subtitle Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .section-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .about-content h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .about-content h2',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .about-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .about-content p',
            ]
        );

        $this->end_controls_section();

        // Style: Features
        $this->start_controls_section(
            'style_features',
            [
                'label' => esc_html__('Features', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'feature_icon_color',
            [
                'label' => esc_html__('Icon Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4caf50',
                'selectors' => [
                    '{{WRAPPER}} .about-feature i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .about-feature svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_title_color',
            [
                'label' => esc_html__('Title Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .about-feature h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'feature_title_typography',
                'selector' => '{{WRAPPER}} .about-feature h4',
            ]
        );

        $this->end_controls_section();

        // Style: Badge
        $this->start_controls_section(
            'style_badge',
            [
                'label' => esc_html__('Badge', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .about-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .about-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Stats
        $this->start_controls_section(
            'style_stats',
            [
                'label' => esc_html__('Statistics', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stat_value_color',
            [
                'label' => esc_html__('Value Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .about-stat h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'stat_label_color',
            [
                'label' => esc_html__('Label Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .about-stat p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="mrm-about-section">
            <div class="mrm-container">
                <div class="about-wrapper">
                    <div class="about-image">
                        <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                        <?php if ($settings['show_badge'] === 'yes') : ?>
                        <div class="about-badge">
                            <h3><?php echo esc_html($settings['badge_title']); ?></h3>
                            <p><?php echo esc_html($settings['badge_subtitle']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="about-content">
                        <?php if ($settings['show_subtitle'] === 'yes' && !empty($settings['subtitle'])) : ?>
                        <span class="section-subtitle"><?php echo esc_html($settings['subtitle']); ?></span>
                        <?php endif; ?>
                        
                        <h2><?php echo esc_html($settings['title']); ?></h2>
                        
                        <div><?php echo wp_kses_post($settings['description']); ?></div>
                        
                        <?php if (!empty($settings['features'])) : ?>
                        <div class="about-features">
                            <?php foreach ($settings['features'] as $feature) : 
                                if ($feature['hide_feature'] === 'yes') continue;
                            ?>
                            <div class="about-feature">
                                <?php Icons_Manager::render_icon($feature['feature_icon'], ['aria-hidden' => 'true']); ?>
                                <div>
                                    <h4><?php echo esc_html($feature['feature_title']); ?></h4>
                                    <p><?php echo esc_html($feature['feature_description']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_stats'] === 'yes') : ?>
                        <div class="about-stats">
                            <div class="about-stat">
                                <h3><?php echo esc_html($settings['stat1_value']); ?></h3>
                                <p><?php echo esc_html($settings['stat1_label']); ?></p>
                            </div>
                            <div class="about-stat">
                                <h3><?php echo esc_html($settings['stat2_value']); ?></h3>
                                <p><?php echo esc_html($settings['stat2_label']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
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

            .about-wrapper {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 60px;
                align-items: center;
            }

            .about-image {
                position: relative;
            }

            .about-image img {
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
                width: 100%;
            }

            .about-badge {
                position: absolute;
                bottom: 30px;
                right: 30px;
                padding: 30px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            }

            .about-badge h3 {
                font-size: 32px;
                margin-bottom: 5px;
            }

            .about-badge p {
                font-size: 14px;
                margin: 0;
            }

            .section-subtitle {
                display: inline-block;
                font-weight: 600;
                margin-bottom: 15px;
                font-size: 16px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .about-content h2 {
                font-size: 38px;
                margin-bottom: 25px;
                line-height: 1.3;
            }

            .about-content p {
                margin-bottom: 20px;
                line-height: 1.8;
                font-size: 16px;
            }

            .about-features {
                margin: 30px 0;
            }

            .about-feature {
                display: flex;
                gap: 20px;
                margin-bottom: 25px;
            }

            .about-feature i,
            .about-feature svg {
                font-size: 24px;
                margin-top: 5px;
            }

            .about-feature h4 {
                font-size: 18px;
                margin-bottom: 8px;
            }

            .about-feature p {
                margin: 0;
                font-size: 15px;
            }

            .about-stats {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
                margin-top: 30px;
            }

            .about-stat {
                text-align: center;
                padding: 25px;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .about-stat h3 {
                font-size: 36px;
                margin-bottom: 5px;
            }

            .about-stat p {
                margin: 0;
                font-weight: 600;
            }

            @media (max-width: 992px) {
                .about-wrapper {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 480px) {
                .about-badge {
                    bottom: 10px;
                    right: 10px;
                    padding: 20px;
                }
            }
        </style>

        <?php
    }
}
