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
 * Cause Box Widget
 */
class Cause_Box_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-cause-box';
    }

    public function get_title() {
        return esc_html__('Cause Box', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['cause', 'donation', 'progress', 'charity'];
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
                    'url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=500',
                ],
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => esc_html__('Category', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Education', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Education for Children', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Support quality education for underprivileged children and help them build a brighter future.', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'raised_amount',
            [
                'label' => esc_html__('Raised Amount', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('₹4,10,000', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'goal_amount',
            [
                'label' => esc_html__('Goal Amount', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('₹5,00,000', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'percentage',
            [
                'label' => esc_html__('Progress Percentage', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 82,
                    'unit' => '%',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Donate Now', 'mrm-ele-addon'),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#donate',
                ],
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label' => esc_html__('Show Category', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_progress',
            [
                'label' => esc_html__('Show Progress Bar', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label' => esc_html__('Show Button', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
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
                    '{{WRAPPER}} .mrm-cause-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .mrm-cause-card',
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-cause-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Category
        $this->start_controls_section(
            'style_category',
            [
                'label' => esc_html__('Category', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'category_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .cause-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .cause-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'selector' => '{{WRAPPER}} .cause-category',
            ]
        );

        $this->end_controls_section();

        // Style: Progress
        $this->start_controls_section(
            'style_progress',
            [
                'label' => esc_html__('Progress Bar', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'progress_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'progress_fill_color',
            [
                'label' => esc_html__('Fill Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .progress-fill' => 'background: linear-gradient(90deg, {{VALUE}}, #ff8a65);',
                ],
            ]
        );

        $this->add_control(
            'percentage_bg_color',
            [
                'label' => esc_html__('Percentage Background', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .percentage' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'percentage_text_color',
            [
                'label' => esc_html__('Percentage Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .percentage' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Button
        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__('Button', 'mrm-ele-addon'),
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
                    '{{WRAPPER}} .btn-cause' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .btn-cause' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .btn-cause',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="mrm-cause-card">
            <div class="cause-image">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                <?php if ($settings['show_category'] === 'yes' && !empty($settings['category'])) : ?>
                <span class="cause-category"><?php echo esc_html($settings['category']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="cause-content">
                <h3><?php echo esc_html($settings['title']); ?></h3>
                <p><?php echo esc_html($settings['description']); ?></p>
                
                <?php if ($settings['show_progress'] === 'yes') : ?>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo esc_attr($settings['percentage']['size']); ?>%"></div>
                </div>
                
                <div class="cause-stats">
                    <span class="raised"><?php echo esc_html($settings['raised_amount']); ?> raised</span>
                    <span class="goal"><?php echo esc_html($settings['goal_amount']); ?> goal</span>
                </div>
                
                <span class="percentage"><?php echo esc_html($settings['percentage']['size']); ?>%</span>
                <?php endif; ?>
                
                <?php if ($settings['show_button'] === 'yes' && !empty($settings['button_text'])) : 
                    $target = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
                ?>
                <a href="<?php echo esc_url($settings['button_link']['url']); ?>" class="btn-cause" <?php echo $target . $nofollow; ?>>
                    <?php echo esc_html($settings['button_text']); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .mrm-cause-card {
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .mrm-cause-card:hover {
                transform: translateY(-10px);
            }

            .cause-image {
                position: relative;
                height: 250px;
                overflow: hidden;
            }

            .cause-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .mrm-cause-card:hover .cause-image img {
                transform: scale(1.1);
            }

            .cause-category {
                position: absolute;
                top: 20px;
                left: 20px;
                padding: 8px 20px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 600;
            }

            .cause-content {
                padding: 30px;
                position: relative;
            }

            .cause-content h3 {
                font-size: 22px;
                margin-bottom: 15px;
                color: #1a1a1a;
            }

            .cause-content p {
                color: #666666;
                margin-bottom: 20px;
                line-height: 1.8;
            }

            .progress-bar {
                width: 100%;
                height: 8px;
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 15px;
            }

            .progress-fill {
                height: 100%;
                border-radius: 10px;
                transition: width 1s ease;
            }

            .cause-stats {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
                font-size: 14px;
            }

            .raised {
                color: #1a1a1a;
                font-weight: 600;
            }

            .goal {
                color: #666666;
            }

            .percentage {
                position: absolute;
                top: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 14px;
            }

            .btn-cause {
                display: block;
                width: 100%;
                text-align: center;
                padding: 12px 30px;
                border-radius: 50px;
                font-weight: 600;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
                font-size: 16px;
                text-decoration: none;
                margin-top: 15px;
            }

            .btn-cause:hover {
                background-color: #e64a19;
            }
        </style>

        <?php
    }
}
