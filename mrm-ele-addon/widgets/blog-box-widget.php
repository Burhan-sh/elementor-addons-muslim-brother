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
 * Blog Box Widget
 */
class Blog_Box_Widget extends Widget_Base {

    public function get_name() {
        return 'mrm-blog-box';
    }

    public function get_title() {
        return esc_html__('Blog Box', 'mrm-ele-addon');
    }

    public function get_icon() {
        return 'eicon-post';
    }

    public function get_categories() {
        return ['mrm-elements'];
    }

    public function get_keywords() {
        return ['blog', 'post', 'article', 'news'];
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
                'label' => esc_html__('Featured Image', 'mrm-ele-addon'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=500',
                ],
            ]
        );

        $this->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '20 NOV',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__('Show Date', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'author',
            [
                'label' => esc_html__('Author', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Admin',
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label' => esc_html__('Show Author', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'author_icon',
            [
                'label' => esc_html__('Author Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_author' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'comments',
            [
                'label' => esc_html__('Comments', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => '5 Comments',
            ]
        );

        $this->add_control(
            'show_comments',
            [
                'label' => esc_html__('Show Comments', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'comments_icon',
            [
                'label' => esc_html__('Comments Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-comment',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_comments' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'How Your Donations Create Real Impact',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'excerpt',
            [
                'label' => esc_html__('Excerpt', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Discover the journey of your contribution and see how it transforms lives across communities.',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__('Show Read More Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Read More',
                'condition' => [
                    'show_read_more' => 'yes',
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
                    '{{WRAPPER}} .mrm-blog-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .mrm-blog-card',
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
                    '{{WRAPPER}} .mrm-blog-card' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .blog-date' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .blog-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .blog-date',
            ]
        );

        $this->end_controls_section();

        // Style: Meta
        $this->start_controls_section(
            'style_meta',
            [
                'label' => esc_html__('Meta', 'mrm-ele-addon'),
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
                    '{{WRAPPER}} .blog-meta' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .blog-meta i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .blog-meta svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .blog-meta',
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
                    '{{WRAPPER}} .blog-content h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .blog-content h3',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Excerpt Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .blog-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .blog-content p',
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__('Link Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .blog-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="mrm-blog-card">
            <div class="blog-image">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                <?php if ($settings['show_date'] === 'yes' && !empty($settings['date'])) : ?>
                <span class="blog-date"><?php echo esc_html($settings['date']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="blog-content">
                <div class="blog-meta">
                    <?php if ($settings['show_author'] === 'yes' && !empty($settings['author'])) : ?>
                    <span>
                        <?php Icons_Manager::render_icon($settings['author_icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo esc_html($settings['author']); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_comments'] === 'yes' && !empty($settings['comments'])) : ?>
                    <span>
                        <?php Icons_Manager::render_icon($settings['comments_icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo esc_html($settings['comments']); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <h3><?php echo esc_html($settings['title']); ?></h3>
                <p><?php echo esc_html($settings['excerpt']); ?></p>
                
                <?php if ($settings['show_read_more'] === 'yes' && !empty($settings['read_more_text'])) : 
                    $target = $settings['link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
                ?>
                <a href="<?php echo esc_url($settings['link']['url']); ?>" class="blog-link" <?php echo $target . $nofollow; ?>>
                    <?php echo esc_html($settings['read_more_text']); ?> <i class="fas fa-arrow-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .mrm-blog-card {
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .mrm-blog-card:hover {
                transform: translateY(-10px);
            }

            .blog-image {
                position: relative;
                height: 250px;
                overflow: hidden;
            }

            .blog-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .mrm-blog-card:hover .blog-image img {
                transform: scale(1.1);
            }

            .blog-date {
                position: absolute;
                top: 20px;
                left: 20px;
                padding: 10px 20px;
                border-radius: 5px;
                font-weight: bold;
                font-size: 14px;
            }

            .blog-content {
                padding: 30px;
            }

            .blog-meta {
                display: flex;
                gap: 20px;
                margin-bottom: 15px;
                font-size: 14px;
                flex-wrap: wrap;
            }

            .blog-meta span {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .blog-content h3 {
                font-size: 22px;
                margin-bottom: 15px;
            }

            .blog-content p {
                margin-bottom: 20px;
                line-height: 1.8;
            }

            .blog-link {
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: gap 0.3s ease;
                text-decoration: none;
            }

            .blog-link:hover {
                gap: 15px;
            }
        </style>

        <?php
    }
}
