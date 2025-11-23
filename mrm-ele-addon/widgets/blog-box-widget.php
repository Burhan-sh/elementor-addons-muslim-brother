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
        
        // Query Section
        $this->start_controls_section(
            'query_section',
            [
                'label' => esc_html__('Query', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_id',
            [
                'label' => esc_html__('Select Post', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_all_posts(),
                'label_block' => true,
                'description' => esc_html__('Select a specific blog post to display', 'mrm-ele-addon'),
            ]
        );

        $this->end_controls_section();

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content Settings', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'default_image',
            [
                'label' => esc_html__('Default Image (Fallback)', 'mrm-ele-addon'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'description' => esc_html__('This image will be used if the post has no featured image', 'mrm-ele-addon'),
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
            'date_format',
            [
                'label' => esc_html__('Date Format', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'default' => 'd M',
                'options' => [
                    'd M' => esc_html__('20 NOV', 'mrm-ele-addon'),
                    'M d' => esc_html__('NOV 20', 'mrm-ele-addon'),
                    'd F Y' => esc_html__('20 November 2024', 'mrm-ele-addon'),
                    'F d, Y' => esc_html__('November 20, 2024', 'mrm-ele-addon'),
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
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
            'show_comments',
            [
                'label' => esc_html__('Show Comments Count', 'mrm-ele-addon'),
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
            'excerpt_length',
            [
                'label' => esc_html__('Excerpt Length (words)', 'mrm-ele-addon'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 5,
                'max' => 100,
                'description' => esc_html__('Number of words to show in excerpt before truncation', 'mrm-ele-addon'),
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

    /**
     * Get all posts for dropdown
     */
    private function get_all_posts() {
        $posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        $options = ['' => esc_html__('Select a Post', 'mrm-ele-addon')];
        
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }

        return $options;
    }

    /**
     * Truncate text to specified word count
     */
    private function truncate_text($text, $word_limit) {
        $words = explode(' ', $text);
        if (count($words) > $word_limit) {
            return implode(' ', array_slice($words, 0, $word_limit)) . '...';
        }
        return $text;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get the post ID from settings
        $post_id = $settings['post_id'];
        
        // If no post selected, get the latest post
        if (empty($post_id)) {
            $latest_posts = get_posts([
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
            ]);
            
            if (!empty($latest_posts)) {
                $post_id = $latest_posts[0]->ID;
            } else {
                echo '<div class="mrm-blog-card"><p>' . esc_html__('No blog posts found. Please create a blog post first.', 'mrm-ele-addon') . '</p></div>';
                return;
            }
        }
        
        // Get the post object
        $post = get_post($post_id);
        
        if (!$post) {
            echo '<div class="mrm-blog-card"><p>' . esc_html__('Post not found.', 'mrm-ele-addon') . '</p></div>';
            return;
        }
        
        // Get post data
        $post_title = get_the_title($post_id);
        $post_link = get_permalink($post_id);
        $post_author = get_the_author_meta('display_name', $post->post_author);
        $post_date = get_the_date($settings['date_format'], $post_id);
        $comments_count = get_comments_number($post_id);
        
        // Get featured image or default
        if (has_post_thumbnail($post_id)) {
            $post_image = get_the_post_thumbnail_url($post_id, 'medium_large');
        } else {
            $post_image = !empty($settings['default_image']['url']) ? $settings['default_image']['url'] : \Elementor\Utils::get_placeholder_image_src();
        }
        
        // Get excerpt
        if (!empty($post->post_excerpt)) {
            $excerpt = $post->post_excerpt;
        } else {
            $excerpt = wp_strip_all_tags($post->post_content);
        }
        
        // Truncate excerpt based on word limit
        $excerpt = $this->truncate_text($excerpt, $settings['excerpt_length']);
        
        // Format comments text
        if ($comments_count == 0) {
            $comments_text = esc_html__('No Comments', 'mrm-ele-addon');
        } elseif ($comments_count == 1) {
            $comments_text = esc_html__('1 Comment', 'mrm-ele-addon');
        } else {
            $comments_text = sprintf(esc_html__('%s Comments', 'mrm-ele-addon'), $comments_count);
        }
        ?>

        <div class="mrm-blog-card">
            <div class="blog-image">
                <img src="<?php echo esc_url($post_image); ?>" alt="<?php echo esc_attr($post_title); ?>">
                <?php if ($settings['show_date'] === 'yes') : ?>
                <span class="blog-date"><?php echo esc_html(strtoupper($post_date)); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="blog-content">
                <div class="blog-meta">
                    <?php if ($settings['show_author'] === 'yes') : ?>
                    <span>
                        <?php Icons_Manager::render_icon($settings['author_icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo esc_html($post_author); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_comments'] === 'yes') : ?>
                    <span>
                        <?php Icons_Manager::render_icon($settings['comments_icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo esc_html($comments_text); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <h3><?php echo esc_html($post_title); ?></h3>
                <p><?php echo esc_html($excerpt); ?></p>
                
                <?php if ($settings['show_read_more'] === 'yes' && !empty($settings['read_more_text'])) : ?>
                <a href="<?php echo esc_url($post_link); ?>" class="blog-link">
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
