<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hero Slider Widget
 */
class Hero_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-hero-slider';
    }

    public function get_title() {
        return esc_html__('Hero Slider', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['hero', 'slider', 'carousel', 'banner'];
    }

    protected function register_controls() {
        
        // Slides Content Section
        $this->start_controls_section(
            'slides_section',
            [
                'label' => esc_html__('Slides', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_image',
            [
                'label' => esc_html__('Background Image', 'mrm-ele-addon'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=1600',
                ],
            ]
        );

        $repeater->add_control(
            'slide_subtitle',
            [
                'label' => esc_html__('Subtitle', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Change the life, Change the world', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('GIVING HELP TO THOSE WHO NEED IT', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Together we can make a difference in the lives of those who need it most.', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'show_primary_button',
            [
                'label' => esc_html__('Show Primary Button', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'primary_button_text',
            [
                'label' => esc_html__('Primary Button Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Make a Donation', 'mrm-ele-addon'),
                'condition' => [
                    'show_primary_button' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'primary_button_link',
            [
                'label' => esc_html__('Primary Button Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#donate',
                ],
                'condition' => [
                    'show_primary_button' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'show_secondary_button',
            [
                'label' => esc_html__('Show Secondary Button', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'secondary_button_text',
            [
                'label' => esc_html__('Secondary Button Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('View Our Causes', 'mrm-ele-addon'),
                'condition' => [
                    'show_secondary_button' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'secondary_button_link',
            [
                'label' => esc_html__('Secondary Button Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#causes',
                ],
                'condition' => [
                    'show_secondary_button' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'overlay_opacity',
            [
                'label' => esc_html__('Overlay Opacity', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
            ]
        );

        $repeater->add_control(
            'overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => esc_html__('Slides', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_subtitle' => esc_html__('Change the life, Change the world', 'mrm-ele-addon'),
                        'slide_title' => esc_html__('GIVING HELP TO THOSE WHO NEED IT', 'mrm-ele-addon'),
                        'slide_description' => esc_html__('Together we can make a difference in the lives of those who need it most.', 'mrm-ele-addon'),
                    ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->end_controls_section();

        // Slider Settings
        $this->start_controls_section(
            'slider_settings',
            [
                'label' => esc_html__('Slider Settings', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => esc_html__('Show Navigation Dots', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'mrm-ele-addon'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => esc_html__('Slider Height', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 90,
                    'unit' => 'vh',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-hero-slider' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Container
        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__('Container', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'gradient_background',
                'label' => esc_html__('Gradient Background', 'mrm-ele-addon'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .mrm-hero-slider::before',
            ]
        );

        $this->end_controls_section();

        // Style: Subtitle
        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .hero-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .hero-subtitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .hero-subtitle',
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
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .hero-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .hero-title',
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
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .hero-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .hero-description',
            ]
        );

        $this->end_controls_section();

        // Style: Primary Button
        $this->start_controls_section(
            'style_primary_button',
            [
                'label' => esc_html__('Primary Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_btn_bg',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .btn-primary' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_btn_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .btn-primary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'primary_btn_typography',
                'selector' => '{{WRAPPER}} .btn-primary',
            ]
        );

        $this->end_controls_section();

        // Style: Secondary Button
        $this->start_controls_section(
            'style_secondary_button',
            [
                'label' => esc_html__('Secondary Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'secondary_btn_border_color',
            [
                'label' => esc_html__('Border Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .btn-secondary' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_btn_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .btn-secondary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'secondary_btn_typography',
                'selector' => '{{WRAPPER}} .btn-secondary',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="mrm-hero-slider">
            <div class="hero-slider-wrapper">
                <?php foreach ($settings['slides'] as $index => $slide) : 
                    $active_class = $index === 0 ? 'active' : '';
                    $overlay_opacity = $slide['overlay_opacity']['size'] / 100;
                ?>
                <div class="hero-slide <?php echo esc_attr($active_class); ?>" style="background-image: url('<?php echo esc_url($slide['slide_image']['url']); ?>');">
                    <div class="hero-overlay" style="background-color: <?php echo esc_attr($slide['overlay_color']); ?>; opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>
                    <div class="mrm-container">
                        <div class="hero-content">
                            <?php if (!empty($slide['slide_subtitle'])) : ?>
                            <span class="hero-subtitle"><?php echo esc_html($slide['slide_subtitle']); ?></span>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['slide_title'])) : ?>
                            <h1 class="hero-title"><?php echo esc_html($slide['slide_title']); ?></h1>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['slide_description'])) : ?>
                            <p class="hero-description"><?php echo esc_html($slide['slide_description']); ?></p>
                            <?php endif; ?>
                            
                            <div class="hero-buttons">
                                <?php if ($slide['show_primary_button'] === 'yes' && !empty($slide['primary_button_text'])) : 
                                    $target = $slide['primary_button_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $slide['primary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                                ?>
                                <a href="<?php echo esc_url($slide['primary_button_link']['url']); ?>" class="btn-primary" <?php echo $target . $nofollow; ?>>
                                    <?php echo esc_html($slide['primary_button_text']); ?>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($slide['show_secondary_button'] === 'yes' && !empty($slide['secondary_button_text'])) : 
                                    $target = $slide['secondary_button_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $slide['secondary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                                ?>
                                <a href="<?php echo esc_url($slide['secondary_button_link']['url']); ?>" class="btn-secondary" <?php echo $target . $nofollow; ?>>
                                    <?php echo esc_html($slide['secondary_button_text']); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($settings['show_dots'] === 'yes' && count($settings['slides']) > 1) : ?>
            <div class="slider-controls">
                <?php foreach ($settings['slides'] as $index => $slide) : 
                    $active_class = $index === 0 ? 'active' : '';
                ?>
                <span class="slider-dot <?php echo esc_attr($active_class); ?>" data-slide="<?php echo esc_attr($index); ?>"></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

        <style>
            .mrm-hero-slider {
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .mrm-hero-slider::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                opacity: 0.3;
                z-index: 1;
            }

            .hero-slider-wrapper {
                position: relative;
                z-index: 2;
            }

            .hero-slide {
                display: none;
                position: relative;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 500px;
                align-items: center;
            }

            .hero-slide.active {
                display: flex;
            }

            .hero-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1;
            }

            .mrm-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                position: relative;
                z-index: 2;
            }

            .hero-content {
                color: #ffffff;
                max-width: 700px;
                animation: fadeInUp 1s ease;
                padding: 80px 0;
            }

            .hero-subtitle {
                display: inline-block;
                padding: 8px 20px;
                border-radius: 20px;
                font-size: 14px;
                margin-bottom: 20px;
                animation: fadeInDown 1s ease;
            }

            .hero-title {
                font-size: 56px;
                font-weight: bold;
                margin-bottom: 20px;
                line-height: 1.2;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            }

            .hero-description {
                font-size: 18px;
                margin-bottom: 30px;
                line-height: 1.8;
            }

            .hero-buttons {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .btn-primary,
            .btn-secondary {
                display: inline-block;
                padding: 12px 30px;
                border-radius: 50px;
                font-weight: 600;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
                font-size: 16px;
                text-decoration: none;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            }

            .btn-secondary {
                background: transparent;
                border: 2px solid;
            }

            .btn-secondary:hover {
                background: #ffffff;
                color: #ff5722;
            }

            .slider-controls {
                position: absolute;
                bottom: 30px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
                z-index: 3;
            }

            .slider-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .slider-dot.active {
                background: #ffffff;
                width: 30px;
                border-radius: 10px;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 32px;
                }
                .hero-description {
                    font-size: 16px;
                }
                .hero-buttons {
                    flex-direction: column;
                }
            }
        </style>

        <script>
        (function($) {
            'use strict';
            
            $(document).ready(function() {
                let currentSlide = 0;
                const slides = $('.hero-slide');
                const dots = $('.slider-dot');
                const autoplay = <?php echo $settings['autoplay'] === 'yes' ? 'true' : 'false'; ?>;
                const autoplaySpeed = <?php echo intval($settings['autoplay_speed']); ?>;

                function showSlide(index) {
                    slides.removeClass('active');
                    dots.removeClass('active');
                    $(slides[index]).addClass('active');
                    $(dots[index]).addClass('active');
                }

                dots.on('click', function() {
                    currentSlide = $(this).data('slide');
                    showSlide(currentSlide);
                });

                if (autoplay && slides.length > 1) {
                    setInterval(function() {
                        currentSlide = (currentSlide + 1) % slides.length;
                        showSlide(currentSlide);
                    }, autoplaySpeed);
                }
            });
        })(jQuery);
        </script>

        <?php
    }
}
