<?php
/**
 * MDB Reviews Slider Widget
 * 
 * A reviews slider widget with a two-column staggered layout and star ratings
 *
 * @package MDB Custom Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * MDB Reviews Slider Widget Class
 *
 * @since 1.0.0
 */
class MDB_Reviews_Slider_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-reviews-slider';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'MDB Reviews Slider', 'mdb-custom-widgets' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

    /**
     * Get widget keywords.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'reviews', 'testimonials', 'slider', 'masonry', 'ratings', 'mdb' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'mdb-reviews-slider-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-reviews-slider-widget' ];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // Content Tab - Reviews
        $this->start_controls_section(
            'reviews_section',
            [
                'label' => esc_html__( 'Reviews', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'review_title',
            [
                'label' => esc_html__( 'Review Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Great Service!', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'review_rating',
            [
                'label' => esc_html__( 'Rating', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1 ' . esc_html__( 'Star', 'mdb-custom-widgets' ),
                    '1.5' => '1.5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '2' => '2 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '2.5' => '2.5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '3' => '3 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '3.5' => '3.5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '4' => '4 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '4.5' => '4.5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '5' => '5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                ],
                'default' => '5',
            ]
        );

        $repeater->add_control(
            'review_text',
            [
                'label' => esc_html__( 'Review Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'This is an amazing product with excellent quality and great customer service. Highly recommended!', 'mdb-custom-widgets' ),
                'rows' => 4,
            ]
        );

        $repeater->add_control(
            'review_person_name',
            [
                'label' => esc_html__( 'Person Name', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'John Smith', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'review_person_title',
            [
                'label' => esc_html__( 'Person Title/Location', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Satisfied Customer', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => esc_html__( 'Reviews List', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'review_title' => esc_html__( 'Excellent Quality', 'mdb-custom-widgets' ),
                        'review_rating' => '5',
                        'review_text' => esc_html__( 'Outstanding product with great attention to detail. Installation was smooth and the results exceeded expectations.', 'mdb-custom-widgets' ),
                        'review_person_name' => esc_html__( 'Sarah Johnson', 'mdb-custom-widgets' ),
                        'review_person_title' => esc_html__( 'Happy Customer', 'mdb-custom-widgets' ),
                    ],
                    [
                        'review_title' => esc_html__( 'Fast Delivery', 'mdb-custom-widgets' ),
                        'review_rating' => '4.5',
                        'review_text' => esc_html__( 'Quick shipping and excellent packaging. Product arrived in perfect condition and looks amazing in our home.', 'mdb-custom-widgets' ),
                        'review_person_name' => esc_html__( 'Mike Davis', 'mdb-custom-widgets' ),
                        'review_person_title' => esc_html__( 'Verified Buyer', 'mdb-custom-widgets' ),
                    ],
                    [
                        'review_title' => esc_html__( 'Great Service', 'mdb-custom-widgets' ),
                        'review_rating' => '5',
                        'review_text' => esc_html__( 'Customer service was helpful throughout the process. They answered all questions and provided excellent support.', 'mdb-custom-widgets' ),
                        'review_person_name' => esc_html__( 'Emma Wilson', 'mdb-custom-widgets' ),
                        'review_person_title' => esc_html__( 'Repeat Customer', 'mdb-custom-widgets' ),
                    ],
                    [
                        'review_title' => esc_html__( 'Perfect Fit', 'mdb-custom-widgets' ),
                        'review_rating' => '4.5',
                        'review_text' => esc_html__( 'Measured perfectly and fits exactly as expected. Quality materials and professional finish. Very satisfied.', 'mdb-custom-widgets' ),
                        'review_person_name' => esc_html__( 'Robert Brown', 'mdb-custom-widgets' ),
                        'review_person_title' => esc_html__( 'Home Owner', 'mdb-custom-widgets' ),
                    ],
                ],
                'title_field' => '{{{ review_title }}} - {{{ review_person_name }}}',
            ]
        );

        $this->end_controls_section();

        // Content Tab - Settings
        $this->start_controls_section(
            'settings_section',
            [
                'label' => esc_html__( 'Slider Settings', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_id',
            [
                'label' => esc_html__( 'Slider ID', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'mdb-reviews-slider',
                'description' => esc_html__( 'Unique ID for this slider (used by navigation widgets)', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__( 'Autoplay Speed (ms)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 500,
                    ],
                ],
                'default' => [
                    'size' => 5000,
                ],
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Tab - Navigation
        $this->start_controls_section(
            'navigation_section',
            [
                'label' => esc_html__( 'Navigation', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_navigation',
            [
                'label' => esc_html__( 'Show Navigation', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'Hide', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_previous',
            [
                'label' => esc_html__( 'Show Previous Button', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'Hide', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_next',
            [
                'label' => esc_html__( 'Show Next Button', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'Hide', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_alignment',
            [
                'label' => esc_html__( 'Button Alignment', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-navigation' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'show_navigation' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Cards
        $this->start_controls_section(
            'cards_style_section',
            [
                'label' => esc_html__( 'Review Cards', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_background_color',
            [
                'label' => esc_html__( 'Card Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__( 'Card Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '25',
                    'right' => '25',
                    'bottom' => '25',
                    'left' => '25',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mdb-review-card',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 4,
                            'blur' => 20,
                            'spread' => 0,
                            'color' => 'rgba(0, 0, 0, 0.1)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'cards_gap',
            [
                'label' => esc_html__( 'Gap Between Cards', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-slider-widget' => '--mdb-reviews-grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Typography
        $this->start_controls_section(
            'typography_style_section',
            [
                'label' => esc_html__( 'Typography', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Review Title Typography
        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__( 'Review Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mdb-review-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Review Text Typography
        $this->add_control(
            'text_heading',
            [
                'label' => esc_html__( 'Review Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .mdb-review-text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Person Name Typography
        $this->add_control(
            'person_heading',
            [
                'label' => esc_html__( 'Person Name', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'person_typography',
                'selector' => '{{WRAPPER}} .mdb-review-person-name',
            ]
        );

        $this->add_control(
            'person_color',
            [
                'label' => esc_html__( 'Person Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#444d5f',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-person-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Person Title Typography
        $this->add_control(
            'person_title_heading',
            [
                'label' => esc_html__( 'Person Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'person_title_typography',
                'selector' => '{{WRAPPER}} .mdb-review-person-title',
            ]
        );

        $this->add_control(
            'person_title_color',
            [
                'label' => esc_html__( 'Person Title Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-person-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Star Ratings
        $this->start_controls_section(
            'rating_style_section',
            [
                'label' => esc_html__( 'Star Ratings', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffa500',
                'selectors' => [
                    '{{WRAPPER}} .mdb-star.filled' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_empty_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e0e0e0',
                'selectors' => [
                    '{{WRAPPER}} .mdb-star.empty' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => esc_html__( 'Star Size', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 24,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-star' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Navigation Buttons
        $this->start_controls_section(
            'navigation_buttons_style_section',
            [
                'label' => esc_html__( 'Navigation Buttons', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_navigation' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_button_size',
            [
                'label' => esc_html__( 'Button Size', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_button_font_size',
            [
                'label' => esc_html__( 'Icon Size', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 24,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_button_gap',
            [
                'label' => esc_html__( 'Gap Between Buttons', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-navigation' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Button Colors
        $this->start_controls_tabs( 'nav_button_colors_tabs' );

        // Normal State
        $this->start_controls_tab(
            'nav_button_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'nav_button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_button_text_color',
            [
                'label' => esc_html__( 'Icon Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'nav_button_border',
                'selector' => '{{WRAPPER}} .mdb-reviews-nav-btn',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '2',
                            'right' => '2',
                            'bottom' => '2',
                            'left' => '2',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#007cba',
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'nav_button_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'nav_button_background_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_button_text_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Disabled State
        $this->start_controls_tab(
            'nav_button_disabled_tab',
            [
                'label' => esc_html__( 'Disabled', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'nav_button_background_color_disabled',
            [
                'label' => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:disabled' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_button_text_color_disabled',
            [
                'label' => esc_html__( 'Icon Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:disabled' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_button_opacity_disabled',
            [
                'label' => esc_html__( 'Opacity', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:disabled' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'navigation_margin',
            [
                'label' => esc_html__( 'Navigation Margin', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $reviews = $settings['reviews'];

        if ( empty( $reviews ) ) {
            echo '<div class="mdb-reviews-empty"><p>' . esc_html__( 'No reviews added yet.', 'mdb-custom-widgets' ) . '</p></div>';
            return;
        }

        $widget_id = $settings['slider_id'] ?: ( 'mdb-reviews-slider-' . $this->get_id() );
        $autoplay = $settings['autoplay'] === 'yes' ? 'true' : 'false';
        $autoplay_speed = $settings['autoplay_speed']['size'] ?: 5000;
        $pause_on_hover = $settings['pause_on_hover'] === 'yes' ? 'true' : 'false';

        // Group reviews into slides (4 reviews per slide for 2x2 grid)
        $slides = array_chunk( $reviews, 4 );
        ?>
        <div class="mdb-widget mdb-reviews-slider-widget" 
             data-widget-type="reviews-slider" 
             id="<?php echo esc_attr( $widget_id ); ?>"
             data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
             data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>"
             data-pause-on-hover="<?php echo esc_attr( $pause_on_hover ); ?>">
            
            <div class="mdb-reviews-slider-container">
                <div class="mdb-reviews-grid">
                    <?php foreach ( $slides as $slide_index => $slide_reviews ) : ?>
                        <div class="mdb-reviews-slide <?php echo $slide_index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr( $slide_index ); ?>">
                            <?php foreach ( $slide_reviews as $review ) : ?>
                                <div class="mdb-review-card">
                                    <div class="mdb-review-header">
                                        <h4 class="mdb-review-title"><?php echo esc_html( $review['review_title'] ); ?></h4>
                                        <div class="mdb-review-rating">
                                            <?php $this->render_stars( floatval( $review['review_rating'] ) ); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mdb-review-content">
                                        <p class="mdb-review-text"><?php echo esc_html( $review['review_text'] ); ?></p>
                                    </div>
                                    
                                    <div class="mdb-review-footer">
                                        <div class="mdb-review-person">
                                            <div class="mdb-review-person-name"><?php echo esc_html( $review['review_person_name'] ); ?></div>
                                            <?php if ( ! empty( $review['review_person_title'] ) ) : ?>
                                                <div class="mdb-review-person-title"><?php echo esc_html( $review['review_person_title'] ); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if ( $settings['show_navigation'] === 'yes' ) : ?>
                <div class="mdb-reviews-navigation">
                    <?php if ( $settings['show_previous'] === 'yes' ) : ?>
                        <button type="button" class="mdb-reviews-nav-btn mdb-reviews-prev" aria-label="<?php echo esc_attr__( 'Previous Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                            <i class="eicon-chevron-left" aria-hidden="true"></i>
                        </button>
                    <?php endif; ?>
                    
                    <?php if ( $settings['show_next'] === 'yes' ) : ?>
                        <button type="button" class="mdb-reviews-nav-btn mdb-reviews-next" aria-label="<?php echo esc_attr__( 'Next Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                            <i class="eicon-chevron-right" aria-hidden="true"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render star rating display
     *
     * @since 1.0.0
     * @access private
     * @param float $rating Rating value (1-5 with .5 increments)
     */
    private function render_stars( $rating ) {
        $full_stars = floor( $rating );
        $half_star = ( $rating - $full_stars ) >= 0.5 ? 1 : 0;
        $empty_stars = 5 - $full_stars - $half_star;
        
        echo '<div class="mdb-stars">';
        
        // Render full stars
        for ( $i = 0; $i < $full_stars; $i++ ) {
            echo '<span class="mdb-star filled">★</span>';
        }
        
        // Render half star
        if ( $half_star ) {
            echo '<span class="mdb-star half">★</span>';
        }
        
        // Render empty stars
        for ( $i = 0; $i < $empty_stars; $i++ ) {
            echo '<span class="mdb-star empty">★</span>';
        }
        
        echo '</div>';
    }

    /**
     * Render widget output in the editor.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function content_template() {
        ?>
        <#
        var reviews = settings.reviews;
        
        if ( ! reviews.length ) {
            #>
            <div class="mdb-reviews-empty">
                <p><?php echo esc_html__( 'No reviews added yet.', 'mdb-custom-widgets' ); ?></p>
            </div>
            <#
            return;
        }
        
        var widgetId = settings.slider_id || 'mdb-reviews-slider-' + Math.random().toString(36).substr(2, 9);
        var autoplay = settings.autoplay === 'yes' ? 'true' : 'false';
        var autoplaySpeed = settings.autoplay_speed.size || 5000;
        var pauseOnHover = settings.pause_on_hover === 'yes' ? 'true' : 'false';
        
        // Group reviews into slides (4 reviews per slide)
        var slides = [];
        for ( var i = 0; i < reviews.length; i += 4 ) {
            slides.push( reviews.slice(i, i + 4) );
        }
        
        function renderStars( rating ) {
            rating = parseFloat( rating );
            var fullStars = Math.floor( rating );
            var halfStar = ( rating - fullStars ) >= 0.5 ? 1 : 0;
            var emptyStars = 5 - fullStars - halfStar;
            var output = '<div class="mdb-stars">';
            
            for ( var i = 0; i < fullStars; i++ ) {
                output += '<span class="mdb-star filled">★</span>';
            }
            
            if ( halfStar ) {
                output += '<span class="mdb-star half">★</span>';
            }
            
            for ( var i = 0; i < emptyStars; i++ ) {
                output += '<span class="mdb-star empty">★</span>';
            }
            
            output += '</div>';
            return output;
        }
        #>
        
        <div class="mdb-widget mdb-reviews-slider-widget" 
             data-widget-type="reviews-slider" 
             id="{{{ widgetId }}}"
             data-autoplay="{{{ autoplay }}}"
             data-autoplay-speed="{{{ autoplaySpeed }}}"
             data-pause-on-hover="{{{ pauseOnHover }}}">
            
            <div class="mdb-reviews-slider-container">
                <div class="mdb-reviews-grid">
                    <# _.each( slides, function( slideReviews, slideIndex ) { #>
                        <div class="mdb-reviews-slide <# if ( slideIndex === 0 ) { #>active<# } #>" data-slide="{{{ slideIndex }}}">
                            <# _.each( slideReviews, function( review ) { #>
                                <div class="mdb-review-card">
                                    <div class="mdb-review-header">
                                        <h4 class="mdb-review-title">{{{ review.review_title }}}</h4>
                                        <div class="mdb-review-rating">
                                            {{{ renderStars( review.review_rating ) }}}
                                        </div>
                                    </div>
                                    
                                    <div class="mdb-review-content">
                                        <p class="mdb-review-text">{{{ review.review_text }}}</p>
                                    </div>
                                    
                                    <div class="mdb-review-footer">
                                        <div class="mdb-review-person">
                                            <div class="mdb-review-person-name">{{{ review.review_person_name }}}</div>
                                            <# if ( review.review_person_title ) { #>
                                                <div class="mdb-review-person-title">{{{ review.review_person_title }}}</div>
                                            <# } #>
                                        </div>
                                    </div>
                                </div>
                            <# }); #>
                        </div>
                    <# }); #>
                </div>
            </div>
            
            <# if ( settings.show_navigation === 'yes' ) { #>
                <div class="mdb-reviews-navigation">
                    <# if ( settings.show_previous === 'yes' ) { #>
                        <button type="button" class="mdb-reviews-nav-btn mdb-reviews-prev" aria-label="<?php echo esc_attr__( 'Previous Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                            <i class="eicon-chevron-left" aria-hidden="true"></i>
                        </button>
                    <# } #>
                    
                    <# if ( settings.show_next === 'yes' ) { #>
                        <button type="button" class="mdb-reviews-nav-btn mdb-reviews-next" aria-label="<?php echo esc_attr__( 'Next Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                            <i class="eicon-chevron-right" aria-hidden="true"></i>
                        </button>
                    <# } #>
                </div>
            <# } #>
        </div>
        <?php
    }
}
