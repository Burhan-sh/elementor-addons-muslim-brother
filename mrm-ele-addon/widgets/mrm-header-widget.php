<?php
namespace MRM_Ele_Addon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * MRM Header Widget
 */
class MRM_Header_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'mrm-header';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('MRM Header', 'mrm-ele-addon');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-header';
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
        return ['mrm', 'header', 'navigation', 'menu', 'navbar'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // ==================== TOP BAR SECTION ====================
        $this->start_controls_section(
            'topbar_section',
            [
                'label' => esc_html__('Top Bar', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_topbar',
            [
                'label' => esc_html__('Show Top Bar', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'topbar_welcome_text',
            [
                'label' => esc_html__('Welcome Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Welcome to Hope Foundation: Be the reason someone smiles today', 'mrm-ele-addon'),
                'label_block' => true,
                'condition' => [
                    'show_topbar' => 'yes',
                ],
            ]
        );

        // Top Bar Info Items Repeater
        $repeater = new Repeater();

        $repeater->add_control(
            'topbar_icon',
            [
                'label' => esc_html__('Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-phone',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'topbar_text',
            [
                'label' => esc_html__('Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('+91 123 456 7890', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'topbar_link',
            [
                'label' => esc_html__('Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mrm-ele-addon'),
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'topbar_items',
            [
                'label' => esc_html__('Top Bar Items', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'topbar_icon' => [
                            'value' => 'fas fa-map-marker-alt',
                            'library' => 'fa-solid',
                        ],
                        'topbar_text' => esc_html__('123 Charity Street, New Delhi', 'mrm-ele-addon'),
                    ],
                    [
                        'topbar_icon' => [
                            'value' => 'fas fa-phone',
                            'library' => 'fa-solid',
                        ],
                        'topbar_text' => esc_html__('+91 123 456 7890', 'mrm-ele-addon'),
                    ],
                    [
                        'topbar_icon' => [
                            'value' => 'fas fa-envelope',
                            'library' => 'fa-solid',
                        ],
                        'topbar_text' => esc_html__('info@hopefoundation.org', 'mrm-ele-addon'),
                    ],
                ],
                'title_field' => '{{{ topbar_text }}}',
                'condition' => [
                    'show_topbar' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== LOGO SECTION ====================
        $this->start_controls_section(
            'logo_section',
            [
                'label' => esc_html__('Logo', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo_type',
            [
                'label' => esc_html__('Logo Type', 'mrm-ele-addon'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon_text',
                'options' => [
                    'icon_text' => esc_html__('Icon + Text', 'mrm-ele-addon'),
                    'image' => esc_html__('Image', 'mrm-ele-addon'),
                    'text' => esc_html__('Text Only', 'mrm-ele-addon'),
                ],
            ]
        );

        $this->add_control(
            'logo_icon',
            [
                'label' => esc_html__('Logo Icon', 'mrm-ele-addon'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-hand-holding-heart',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'logo_type' => 'icon_text',
                ],
            ]
        );

        $this->add_control(
            'logo_image',
            [
                'label' => esc_html__('Logo Image', 'mrm-ele-addon'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'logo_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'logo_text',
            [
                'label' => esc_html__('Logo Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Hope Foundation', 'mrm-ele-addon'),
                'label_block' => true,
                'condition' => [
                    'logo_type!' => 'image',
                ],
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label' => esc_html__('Logo Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mrm-ele-addon'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== MENU SECTION ====================
        $this->start_controls_section(
            'menu_section',
            [
                'label' => esc_html__('Menu Items', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $menu_repeater = new Repeater();

        $menu_repeater->add_control(
            'menu_text',
            [
                'label' => esc_html__('Menu Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Home', 'mrm-ele-addon'),
                'label_block' => true,
            ]
        );

        $menu_repeater->add_control(
            'menu_link',
            [
                'label' => esc_html__('Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mrm-ele-addon'),
                'default' => [
                    'url' => '#home',
                ],
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => esc_html__('Menu Items', 'mrm-ele-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $menu_repeater->get_controls(),
                'default' => [
                    [
                        'menu_text' => esc_html__('Home', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#home'],
                    ],
                    [
                        'menu_text' => esc_html__('About Us', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#about'],
                    ],
                    [
                        'menu_text' => esc_html__('Our Causes', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#causes'],
                    ],
                    [
                        'menu_text' => esc_html__('Events', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#events'],
                    ],
                    [
                        'menu_text' => esc_html__('Volunteers', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#volunteers'],
                    ],
                    [
                        'menu_text' => esc_html__('Blog', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#blog'],
                    ],
                    [
                        'menu_text' => esc_html__('Contact', 'mrm-ele-addon'),
                        'menu_link' => ['url' => '#contact'],
                    ],
                ],
                'title_field' => '{{{ menu_text }}}',
            ]
        );

        $this->end_controls_section();

        // ==================== BUTTON SECTION ====================
        $this->start_controls_section(
            'button_section',
            [
                'label' => esc_html__('Donate Button', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_CONTENT,
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

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mrm-ele-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Donate Now', 'mrm-ele-addon'),
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'mrm-ele-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mrm-ele-addon'),
                'default' => [
                    'url' => '#donate',
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE: TOP BAR ====================
        $this->start_controls_section(
            'style_topbar_section',
            [
                'label' => esc_html__('Top Bar Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_topbar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'topbar_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-topbar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'topbar_text_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-topbar' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'topbar_icon_color',
            [
                'label' => esc_html__('Icon Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-topbar-info i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mrm-topbar-info svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'topbar_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-header-topbar',
            ]
        );

        $this->add_responsive_control(
            'topbar_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-topbar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'topbar_icon_size',
            [
                'label' => esc_html__('Icon Size', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-topbar-info i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mrm-topbar-info svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'topbar_item_spacing',
            [
                'label' => esc_html__('Item Spacing', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-topbar-right' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE: NAVBAR ====================
        $this->start_controls_section(
            'style_navbar_section',
            [
                'label' => esc_html__('Navbar Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'navbar_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-navbar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'navbar_shadow',
                'label' => esc_html__('Box Shadow', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-header-navbar',
            ]
        );

        $this->add_responsive_control(
            'navbar_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 15,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-navbar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'navbar_sticky',
            [
                'label' => esc_html__('Sticky Navbar', 'mrm-ele-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrm-ele-addon'),
                'label_off' => esc_html__('No', 'mrm-ele-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE: LOGO ====================
        $this->start_controls_section(
            'style_logo_section',
            [
                'label' => esc_html__('Logo Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logo_color',
            [
                'label' => esc_html__('Logo Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-logo' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mrm-header-logo i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mrm-header-logo svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'logo_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-header-logo span',
            ]
        );

        $this->add_responsive_control(
            'logo_icon_size',
            [
                'label' => esc_html__('Icon Size', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 32,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-logo i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mrm-header-logo svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'logo_type' => 'icon_text',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_image_width',
            [
                'label' => esc_html__('Image Width', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 150,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'logo_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-header-logo' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE: MENU ====================
        $this->start_controls_section(
            'style_menu_section',
            [
                'label' => esc_html__('Menu Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label' => esc_html__('Text Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mrm-nav-menu li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_hover_color',
            [
                'label' => esc_html__('Hover/Active Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-nav-menu li a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mrm-nav-menu li a.active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mrm-nav-menu li a::after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-nav-menu li a',
            ]
        );

        $this->add_responsive_control(
            'menu_item_spacing',
            [
                'label' => esc_html__('Item Spacing', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-nav-menu' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE: BUTTON ====================
        $this->start_controls_section(
            'style_button_section',
            [
                'label' => esc_html__('Button Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-btn-donate' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mrm-btn-donate' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => esc_html__('Hover Background Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e64a19',
                'selectors' => [
                    '{{WRAPPER}} .mrm-btn-donate:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'mrm-ele-addon'),
                'selector' => '{{WRAPPER}} .mrm-btn-donate',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'mrm-ele-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 25,
                    'bottom' => 10,
                    'left' => 25,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-btn-donate' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

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
                    'size' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mrm-btn-donate' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE: MOBILE ====================
        $this->start_controls_section(
            'style_mobile_section',
            [
                'label' => esc_html__('Mobile Menu Style', 'mrm-ele-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hamburger_color',
            [
                'label' => esc_html__('Hamburger Color', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5722',
                'selectors' => [
                    '{{WRAPPER}} .mrm-hamburger span' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_menu_bg',
            [
                'label' => esc_html__('Mobile Menu Background', 'mrm-ele-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mrm-nav-menu.active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_breakpoint',
            [
                'label' => esc_html__('Mobile Breakpoint', 'mrm-ele-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 480,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'size' => 768,
                    'unit' => 'px',
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
        $is_sticky = $settings['navbar_sticky'] === 'yes' ? 'mrm-sticky' : '';
        ?>

        <div class="mrm-header-wrapper">
            
            <?php if ($settings['show_topbar'] === 'yes') : ?>
            <!-- Top Bar -->
            <div class="mrm-header-topbar">
                <div class="mrm-container">
                    <div class="mrm-topbar-content">
                        <?php if (!empty($settings['topbar_welcome_text'])) : ?>
                        <div class="mrm-topbar-welcome">
                            <span><?php echo esc_html($settings['topbar_welcome_text']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['topbar_items'])) : ?>
                        <div class="mrm-topbar-right">
                            <?php foreach ($settings['topbar_items'] as $item) : 
                                $link_tag = 'span';
                                $link_attrs = '';
                                if (!empty($item['topbar_link']['url'])) {
                                    $link_tag = 'a';
                                    $target = $item['topbar_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $item['topbar_link']['nofollow'] ? ' rel="nofollow"' : '';
                                    $link_attrs = 'href="' . esc_url($item['topbar_link']['url']) . '"' . $target . $nofollow;
                                }
                            ?>
                            <div class="mrm-topbar-info">
                                <<?php echo $link_tag; ?> <?php echo $link_attrs; ?>>
                                    <?php Icons_Manager::render_icon($item['topbar_icon'], ['aria-hidden' => 'true']); ?>
                                    <span><?php echo esc_html($item['topbar_text']); ?></span>
                                </<?php echo $link_tag; ?>>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Navigation -->
            <nav class="mrm-header-navbar <?php echo esc_attr($is_sticky); ?>">
                <div class="mrm-container">
                    <div class="mrm-nav-wrapper">
                        <!-- Logo -->
                        <div class="mrm-header-logo">
                            <?php 
                            $logo_target = $settings['logo_link']['is_external'] ? ' target="_blank"' : '';
                            $logo_nofollow = $settings['logo_link']['nofollow'] ? ' rel="nofollow"' : '';
                            ?>
                            <a href="<?php echo esc_url($settings['logo_link']['url']); ?>" <?php echo $logo_target . $logo_nofollow; ?>>
                                <?php if ($settings['logo_type'] === 'image' && !empty($settings['logo_image']['url'])) : ?>
                                    <img src="<?php echo esc_url($settings['logo_image']['url']); ?>" alt="<?php echo esc_attr($settings['logo_text']); ?>">
                                <?php elseif ($settings['logo_type'] === 'icon_text') : ?>
                                    <?php Icons_Manager::render_icon($settings['logo_icon'], ['aria-hidden' => 'true']); ?>
                                    <span><?php echo esc_html($settings['logo_text']); ?></span>
                                <?php else : ?>
                                    <span><?php echo esc_html($settings['logo_text']); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>

                        <!-- Menu Items -->
                        <?php if (!empty($settings['menu_items'])) : ?>
                        <ul class="mrm-nav-menu" id="mrmNavMenu">
                            <?php foreach ($settings['menu_items'] as $index => $item) : 
                                $menu_target = $item['menu_link']['is_external'] ? ' target="_blank"' : '';
                                $menu_nofollow = $item['menu_link']['nofollow'] ? ' rel="nofollow"' : '';
                                $active_class = $index === 0 ? 'active' : '';
                            ?>
                            <li>
                                <a href="<?php echo esc_url($item['menu_link']['url']); ?>" 
                                   class="<?php echo esc_attr($active_class); ?>"
                                   <?php echo $menu_target . $menu_nofollow; ?>>
                                    <?php echo esc_html($item['menu_text']); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>

                        <!-- Right Side -->
                        <div class="mrm-nav-right">
                            <?php if ($settings['show_button'] === 'yes') : 
                                $btn_target = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
                                $btn_nofollow = $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
                            ?>
                            <a href="<?php echo esc_url($settings['button_link']['url']); ?>" 
                               class="mrm-btn-donate"
                               <?php echo $btn_target . $btn_nofollow; ?>>
                                <?php echo esc_html($settings['button_text']); ?>
                            </a>
                            <?php endif; ?>
                            
                            <div class="mrm-hamburger" id="mrmHamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

        </div>

        <style>
            /* Container */
            .mrm-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            /* Top Bar */
            .mrm-header-topbar {
                font-size: 14px;
            }

            .mrm-topbar-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
            }

            .mrm-topbar-welcome {
                font-style: italic;
            }

            .mrm-topbar-right {
                display: flex;
                flex-wrap: wrap;
            }

            .mrm-topbar-info {
                display: flex;
                align-items: center;
            }

            .mrm-topbar-info a,
            .mrm-topbar-info span {
                display: flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
                color: inherit;
                transition: opacity 0.3s ease;
            }

            .mrm-topbar-info a:hover {
                opacity: 0.8;
            }

            /* Navbar */
            .mrm-header-navbar {
                position: relative;
                z-index: 1000;
            }

            .mrm-header-navbar.mrm-sticky {
                position: sticky;
                top: 0;
            }

            .mrm-nav-wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            /* Logo */
            .mrm-header-logo {
                display: flex;
                align-items: center;
                font-weight: bold;
            }

            .mrm-header-logo a {
                display: flex;
                align-items: center;
                text-decoration: none;
                color: inherit;
            }

            .mrm-header-logo img {
                display: block;
                max-height: 60px;
            }

            /* Menu */
            .mrm-nav-menu {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .mrm-nav-menu li a {
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
                position: relative;
                display: block;
            }

            .mrm-nav-menu li a::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 0;
                height: 2px;
                transition: width 0.3s ease;
            }

            .mrm-nav-menu li a:hover::after,
            .mrm-nav-menu li a.active::after {
                width: 100%;
            }

            /* Right Side */
            .mrm-nav-right {
                display: flex;
                align-items: center;
                gap: 20px;
            }

            /* Button */
            .mrm-btn-donate {
                display: inline-block;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
            }

            .mrm-btn-donate:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            }

            /* Hamburger */
            .mrm-hamburger {
                display: none;
                flex-direction: column;
                gap: 5px;
                cursor: pointer;
            }

            .mrm-hamburger span {
                width: 25px;
                height: 3px;
                transition: all 0.3s ease;
            }

            .mrm-hamburger.active span:nth-child(1) {
                transform: rotate(45deg) translate(8px, 8px);
            }

            .mrm-hamburger.active span:nth-child(2) {
                opacity: 0;
            }

            .mrm-hamburger.active span:nth-child(3) {
                transform: rotate(-45deg) translate(8px, -8px);
            }

            /* Responsive */
            @media (max-width: <?php echo esc_attr($settings['mobile_breakpoint']['size']); ?>px) {
                .mrm-topbar-content {
                    flex-direction: column;
                    text-align: center;
                    gap: 10px;
                }

                .mrm-topbar-right {
                    flex-direction: column;
                    gap: 10px;
                }

                .mrm-nav-menu {
                    position: fixed;
                    top: 70px;
                    left: -100%;
                    width: 100%;
                    height: calc(100vh - 70px);
                    flex-direction: column;
                    align-items: center;
                    padding: 50px 0;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
                    transition: left 0.3s ease;
                }

                .mrm-nav-menu.active {
                    left: 0;
                }

                .mrm-hamburger {
                    display: flex;
                }

                .mrm-btn-donate {
                    padding: 8px 20px !important;
                    font-size: 14px;
                }
            }
        </style>

        <script>
        (function($) {
            'use strict';
            
            $(document).ready(function() {
                // Mobile menu toggle
                $('#mrmHamburger').on('click', function() {
                    $(this).toggleClass('active');
                    $('#mrmNavMenu').toggleClass('active');
                });

                // Close mobile menu when clicking on a link
                $('#mrmNavMenu a').on('click', function() {
                    $('#mrmHamburger').removeClass('active');
                    $('#mrmNavMenu').removeClass('active');
                });

                // Active menu on scroll
                $(window).on('scroll', function() {
                    var scrollPos = $(window).scrollTop();
                    
                    $('#mrmNavMenu a').each(function() {
                        var currLink = $(this);
                        var refElement = $(currLink.attr("href"));
                        
                        if (refElement.length && refElement.position().top - 100 <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                            $('#mrmNavMenu a').removeClass("active");
                            currLink.addClass("active");
                        }
                    });
                });
            });
        })(jQuery);
        </script>

        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var isSticky = settings.navbar_sticky === 'yes' ? 'mrm-sticky' : '';
        #>

        <div class="mrm-header-wrapper">
            
            <# if (settings.show_topbar === 'yes') { #>
            <div class="mrm-header-topbar">
                <div class="mrm-container">
                    <div class="mrm-topbar-content">
                        <# if (settings.topbar_welcome_text) { #>
                        <div class="mrm-topbar-welcome">
                            <span>{{{ settings.topbar_welcome_text }}}</span>
                        </div>
                        <# } #>
                        
                        <# if (settings.topbar_items && settings.topbar_items.length) { #>
                        <div class="mrm-topbar-right">
                            <# _.each(settings.topbar_items, function(item) { #>
                            <div class="mrm-topbar-info">
                                <# if (item.topbar_link && item.topbar_link.url) { #>
                                <a href="{{{ item.topbar_link.url }}}">
                                <# } else { #>
                                <span>
                                <# } #>
                                    <# if (item.topbar_icon && item.topbar_icon.value) { #>
                                        <i class="{{{ item.topbar_icon.value }}}"></i>
                                    <# } #>
                                    <span>{{{ item.topbar_text }}}</span>
                                <# if (item.topbar_link && item.topbar_link.url) { #>
                                </a>
                                <# } else { #>
                                </span>
                                <# } #>
                            </div>
                            <# }); #>
                        </div>
                        <# } #>
                    </div>
                </div>
            </div>
            <# } #>

            <nav class="mrm-header-navbar {{{ isSticky }}}">
                <div class="mrm-container">
                    <div class="mrm-nav-wrapper">
                        <div class="mrm-header-logo">
                            <a href="{{{ settings.logo_link.url }}}">
                                <# if (settings.logo_type === 'image' && settings.logo_image.url) { #>
                                    <img src="{{{ settings.logo_image.url }}}" alt="{{{ settings.logo_text }}}">
                                <# } else if (settings.logo_type === 'icon_text') { #>
                                    <# if (settings.logo_icon && settings.logo_icon.value) { #>
                                        <i class="{{{ settings.logo_icon.value }}}"></i>
                                    <# } #>
                                    <span>{{{ settings.logo_text }}}</span>
                                <# } else { #>
                                    <span>{{{ settings.logo_text }}}</span>
                                <# } #>
                            </a>
                        </div>

                        <# if (settings.menu_items && settings.menu_items.length) { #>
                        <ul class="mrm-nav-menu">
                            <# _.each(settings.menu_items, function(item, index) { 
                                var activeClass = index === 0 ? 'active' : '';
                            #>
                            <li>
                                <a href="{{{ item.menu_link.url }}}" class="{{{ activeClass }}}">
                                    {{{ item.menu_text }}}
                                </a>
                            </li>
                            <# }); #>
                        </ul>
                        <# } #>

                        <div class="mrm-nav-right">
                            <# if (settings.show_button === 'yes') { #>
                            <a href="{{{ settings.button_link.url }}}" class="mrm-btn-donate">
                                {{{ settings.button_text }}}
                            </a>
                            <# } #>
                            
                            <div class="mrm-hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

        </div>
        
        <?php
    }
}
