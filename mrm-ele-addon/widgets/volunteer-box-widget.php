<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Volunteer Box Widget
 */
class Volunteer_Box_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-volunteer-box';
    }

    public function get_title() {
        return esc_html__('Volunteer Box', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['volunteer', 'team', 'member', 'person'];
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
            'image',
            [
                'label' => esc_html__('Image', 'mrm-ele-addon'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300',
                ],
            ]
        );

        $this->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Rahul Sharma',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => esc_html__('Position', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Senior Volunteer',
                'label_block' => true,
            ]
        );

        // Social Links Repeater
        $repeater = new Repeater();

        $repeater->add_control(
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

        $repeater->add_control(
            'social_link',
            [
                'label' => esc_html__('Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://facebook.com',
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
                'fields' => $repeater->get_controls(),
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
                ],
                'title_field' => '<# if (social_icon.value) { #><i class="{{{ social_icon.value }}}"></i><# } #>',
            ]
        );

        $this->end_controls_section();

        // Style: Box
        $this->start_controls_section(
            'style_box',
            [
                'label' => esc_html__('Box', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-volunteer-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .mrm-volunteer-card',
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-volunteer-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Image
        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__('Image', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__('Image Height', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 300,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .volunteer-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .volunteer-social a' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .volunteer-social a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .volunteer-social a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .volunteer-social a svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_bg_color',
            [
                'label' => esc_html__('Hover Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .volunteer-social a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icon_size',
            [
                'label' => esc_html__('Icon Size', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .volunteer-social a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .volunteer-social a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_box_size',
            [
                'label' => esc_html__('Box Size', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .volunteer-social a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Info
        $this->start_controls_section(
            'style_info',
            [
                'label' => esc_html__('Info', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Name Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .volunteer-info h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .volunteer-info h3',
            ]
        );

        $this->add_control(
            'position_color',
            [
                'label' => esc_html__('Position Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .volunteer-info p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'position_typography',
                'selector' => '{{WRAPPER}} .volunteer-info p',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="mrm-volunteer-card">
            <div class="volunteer-image">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['name']); ?>">
                
                <?php if (!empty($settings['social_links'])) : ?>
                <div class="volunteer-social">
                    <?php foreach ($settings['social_links'] as $link) : 
                        if (!empty($link['social_icon']['value'])) :
                            $target = $link['social_link']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $link['social_link']['nofollow'] ? ' rel="nofollow"' : '';
                    ?>
                    <a href="<?php echo esc_url($link['social_link']['url']); ?>" <?php echo $target . $nofollow; ?>>
                        <?php \Elementor\Icons_Manager::render_icon($link['social_icon'], ['aria-hidden' => 'true']); ?>
                    </a>
                    <?php 
                        endif;
                    endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="volunteer-info">
                <h3><?php echo esc_html($settings['name']); ?></h3>
                <p><?php echo esc_html($settings['position']); ?></p>
            </div>
        </div>

        <style>
            .mrm-volunteer-card {
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .mrm-volunteer-card:hover {
                transform: translateY(-10px);
            }

            .volunteer-image {
                position: relative;
                overflow: hidden;
            }

            .volunteer-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .mrm-volunteer-card:hover .volunteer-image img {
                transform: scale(1.1);
            }

            .volunteer-social {
                position: absolute;
                bottom: -50px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
                transition: bottom 0.3s ease;
            }

            .mrm-volunteer-card:hover .volunteer-social {
                bottom: 20px;
            }

            .volunteer-social a {
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .volunteer-social a:hover {
                transform: translateY(-5px);
            }

            .volunteer-info {
                padding: 25px;
                text-align: center;
            }

            .volunteer-info h3 {
                font-size: 20px;
                margin-bottom: 5px;
            }

            .volunteer-info p {
                font-size: 14px;
                margin: 0;
            }
        </style>

        <?php
    }
}
