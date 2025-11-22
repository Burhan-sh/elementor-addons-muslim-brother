<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Event Box Widget
 */
class Event_Box_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-event-box';
    }

    public function get_title() {
        return esc_html__('Event Box', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['event', 'calendar', 'date', 'camp'];
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
                    'url' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=500',
                ],
            ]
        );

        $this->add_control(
            'date_day',
            [
                'label' => esc_html__('Day', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '15',
            ]
        );

        $this->add_control(
            'date_month',
            [
                'label' => esc_html__('Month', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'DEC',
            ]
        );

        $this->add_control(
            'time',
            [
                'label' => esc_html__('Time', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '10:00 AM',
            ]
        );

        $this->add_control(
            'show_time',
            [
                'label' => esc_html__('Show Time', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'time_icon',
            [
                'label' => esc_html__('Time Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-clock',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_time' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'location',
            [
                'label' => esc_html__('Location', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Mumbai, India',
            ]
        );

        $this->add_control(
            'show_location',
            [
                'label' => esc_html__('Show Location', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'location_icon',
            [
                'label' => esc_html__('Location Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-map-marker-alt',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_location' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Community Health Camp',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Free health checkups and medical consultations for underprivileged communities in Mumbai.',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_link',
            [
                'label' => esc_html__('Show Learn More Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'link_text',
            [
                'label' => esc_html__('Link Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Learn More',
                'condition' => [
                    'show_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'show_link' => 'yes',
                ],
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
                    '{{WRAPPER}} .mrm-event-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .mrm-event-card',
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
                    '{{WRAPPER}} .mrm-event-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Date Badge
        $this->start_controls_section(
            'style_date',
            [
                'label' => esc_html__('Date Badge', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'date_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .event-date' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .event-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_day_typography',
                'label' => esc_html__('Day Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .date-day',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_month_typography',
                'label' => esc_html__('Month Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .date-month',
            ]
        );

        $this->end_controls_section();

        // Style: Meta
        $this->start_controls_section(
            'style_meta',
            [
                'label' => esc_html__('Meta (Time & Location)', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .event-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_icon_color',
            [
                'label' => esc_html__('Icon Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .event-meta i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .event-meta svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .event-meta',
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
            'title_color',
            [
                'label' => esc_html__('Title Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .event-content h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .event-content h3',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .event-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .event-content p',
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__('Link Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .event-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="mrm-event-card">
            <div class="event-image">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                <div class="event-date">
                    <span class="date-day"><?php echo esc_html($settings['date_day']); ?></span>
                    <span class="date-month"><?php echo esc_html($settings['date_month']); ?></span>
                </div>
            </div>
            
            <div class="event-content">
                <div class="event-meta">
                    <?php if ($settings['show_time'] === 'yes' && !empty($settings['time'])) : ?>
                    <span>
                        <?php Icons_Manager::render_icon($settings['time_icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo esc_html($settings['time']); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_location'] === 'yes' && !empty($settings['location'])) : ?>
                    <span>
                        <?php Icons_Manager::render_icon($settings['location_icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo esc_html($settings['location']); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <h3><?php echo esc_html($settings['title']); ?></h3>
                <p><?php echo esc_html($settings['description']); ?></p>
                
                <?php if ($settings['show_link'] === 'yes' && !empty($settings['link_text'])) : 
                    $target = $settings['link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
                ?>
                <a href="<?php echo esc_url($settings['link']['url']); ?>" class="event-link" <?php echo $target . $nofollow; ?>>
                    <?php echo esc_html($settings['link_text']); ?> <i class="fas fa-arrow-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .mrm-event-card {
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .mrm-event-card:hover {
                transform: translateY(-10px);
            }

            .event-image {
                position: relative;
                height: 250px;
                overflow: hidden;
            }

            .event-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .mrm-event-card:hover .event-image img {
                transform: scale(1.1);
            }

            .event-date {
                position: absolute;
                top: 20px;
                left: 20px;
                width: 70px;
                height: 70px;
                border-radius: 10px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .date-day {
                font-size: 28px;
                line-height: 1;
            }

            .date-month {
                font-size: 14px;
                text-transform: uppercase;
            }

            .event-content {
                padding: 30px;
            }

            .event-meta {
                display: flex;
                gap: 20px;
                margin-bottom: 15px;
                font-size: 14px;
                flex-wrap: wrap;
            }

            .event-meta span {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .event-content h3 {
                font-size: 22px;
                margin-bottom: 15px;
            }

            .event-content p {
                margin-bottom: 20px;
                line-height: 1.8;
            }

            .event-link {
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: gap 0.3s ease;
                text-decoration: none;
            }

            .event-link:hover {
                gap: 15px;
            }
        </style>

        <?php
    }
}
