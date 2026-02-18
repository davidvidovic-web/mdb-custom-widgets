<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor is loaded
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * MDB Simple Reviews Widget
 *
 * A simple, horizontal review slider using Swiper.
 *
 * @since 1.0.0
 */
class MDB_Simple_Reviews_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-simple-reviews';
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
        return esc_html__( 'MDB Simple Reviews', 'mdb-custom-widgets' );
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
        return 'eicon-slides';
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
        return [ 'reviews', 'slider', 'carousel', 'testimonial', 'simple', 'mdb' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'swiper', 'mdb-simple-reviews-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-simple-reviews-widget' ];
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
            'section_reviews',
            [
                'label' => esc_html__( 'Reviews', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'review_rating',
            [
                'label' => esc_html__( 'Rating', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '5' => '5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '4.5' => '4.5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '4' => '4 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '3.5' => '3.5 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '3' => '3 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '2' => '2 ' . esc_html__( 'Stars', 'mdb-custom-widgets' ),
                    '1' => '1 ' . esc_html__( 'Star', 'mdb-custom-widgets' ),
                ],
                'default' => '5',
            ]
        );

        $repeater->add_control(
            'review_title',
            [
                'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Great Service', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'review_content',
            [
                'label' => esc_html__( 'Review', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mdb-custom-widgets' ),
            ]
        );

        $repeater->add_control(
            'review_author',
            [
                'label' => esc_html__( 'Author', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'John Doe', 'mdb-custom-widgets' ),
            ]
        );
        
        $repeater->add_control(
            'review_date',
            [
                'label' => esc_html__( 'Date / Info', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '2 days ago', 'mdb-custom-widgets' ),
            ]
        );

        $repeater->add_control(
            'review_verified',
            [
                'label' => esc_html__( 'Verified Badge', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'Hide', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'reviews_list',
            [
                'label' => esc_html__( 'Reviews List', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'review_title' => 'Excellent Product',
                        'review_content' => 'I love this product! It works exactly as described and the quality is top notch.',
                        'review_author' => 'Jane Smith',
                        'review_rating' => '5',
                    ],
                    [
                        'review_title' => 'Fast Shipping',
                        'review_content' => 'Arrived very quickly and was well packaged. Will buy again.',
                        'review_author' => 'Mike Johnson',
                        'review_rating' => '5',
                    ],
                    [
                        'review_title' => 'Great Support',
                        'review_content' => 'Had a small issue and support fixed it immediately. Very impressed.',
                        'review_author' => 'Sarah Williams',
                        'review_rating' => '4.5',
                    ],
                ],
                'title_field' => '{{{ review_author }}} - {{{ review_title }}}',
            ]
        );

        $this->end_controls_section();

        // Content Tab - Slider Settings
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => esc_html__( 'Slider Settings', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => esc_html__( 'Slides Per View', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    'auto' => 'Auto',
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between (px)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
            ]
        );
        
        $this->add_control(
            'show_arrows',
            [
                'label' => esc_html__( 'Arrows', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__( 'Show', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'Hide', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => esc_html__( 'Pagination Dots', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__( 'Show', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'Hide', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__( 'Autoplay Speed (ms)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__( 'Infinite Loop', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Card
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-simple-review-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label' => esc_html__( 'Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-simple-review-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-simple-review-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mdb-simple-review-card',
                'default' => [
                    'horizontal' => 0,
                    'vertical' => 4,
                    'blur' => 15,
                    'spread' => 0,
                    'color' => 'rgba(0,0,0,0.05)',
                ],
            ]
        );

        $this->add_control(
            'card_border_type',
            [
                'label' => esc_html__( 'Border Type', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'mdb-custom-widgets' ),
                    'solid' => esc_html__( 'Solid', 'mdb-custom-widgets' ),
                    'double' => esc_html__( 'Double', 'mdb-custom-widgets' ),
                    'dotted' => esc_html__( 'Dotted', 'mdb-custom-widgets' ),
                    'dashed' => esc_html__( 'Dashed', 'mdb-custom-widgets' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .mdb-simple-review-card' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_width',
            [
                'label' => esc_html__( 'Border Width', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mdb-simple-review-card' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'card_border_type!' => 'none',
                ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label' => esc_html__( 'Border Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eaeaea',
                'selectors' => [
                    '{{WRAPPER}} .mdb-simple-review-card' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'card_border_type!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Content
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Rating Stars
        $this->add_control(
            'heading_rating',
            [
                'label' => esc_html__( 'Rating', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F8B600',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-stars' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'star_size',
            [
                'label' => esc_html__( 'Star Size', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-stars' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'size' => 16,
                ],
            ]
        );

        // Title
        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mdb-review-title',
            ]
        );

        // Content
        $this->add_control(
            'heading_content',
            [
                'label' => esc_html__( 'Review Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Content Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .mdb-review-content',
            ]
        );

        // Author
        $this->add_control(
            'heading_author',
            [
                'label' => esc_html__( 'Author', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => esc_html__( 'Author Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-author strong' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .mdb-review-author strong',
            ]
        );
        
        $this->add_control(
            'date_color',
            [
                'label' => esc_html__( 'Date/Info Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .mdb-review-date' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .mdb-review-date',
            ]
        );
        
        $this->add_control(
            'verified_color',
            [
                'label' => esc_html__( 'Verified Badge Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#49c3c9',
                'selectors' => [
                    '{{WRAPPER}} .mdb-verified-badge' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
        
        // Navigation Style
        $this->start_controls_section(
            'section_style_navigation',
            [
                'label' => esc_html__( 'Navigation & Pagination', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'nav_color',
            [
                'label' => esc_html__( 'Arrow Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mdb-swiper-button-prev, {{WRAPPER}} .mdb-swiper-button-next' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__( 'Dot Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}}; opacity: 1;',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'dot_active_color',
            [
                'label' => esc_html__( 'Active Dot Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#49c3c9',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
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
        
        // Slider Settings
        $slides_per_view = $settings['slides_per_view'];
        $space_between = $settings['space_between']['size'];
        $autoplay = $settings['autoplay'] === 'yes';
        $autoplay_speed = $settings['autoplay_speed'];
        $loop = $settings['loop'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $show_dots = $settings['show_dots'] === 'yes';

        // Responsive Settings
        $slides_per_view_tablet = $settings['slides_per_view_tablet'] ?: '2';
        $slides_per_view_mobile = $settings['slides_per_view_mobile'] ?: '1';
        $space_between_tablet = isset($settings['space_between_tablet']['size']) ? $settings['space_between_tablet']['size'] : 20;
        $space_between_mobile = isset($settings['space_between_mobile']['size']) ? $settings['space_between_mobile']['size'] : 10;
        
        if ($slides_per_view === 'auto') {
            $slides_per_view = 'auto'; // Pass string 'auto' if selected
        } else {
            $slides_per_view = intval($slides_per_view);
        }

        $widget_settings = [
            'slidesPerView' => $slides_per_view,
            'spaceBetween' => $space_between,
            'autoplay' => $autoplay,
            'autoplaySpeed' => $autoplay_speed,
            'loop' => $loop,
            'breakpoints' => [
                320 => [
                    'slidesPerView' => $slides_per_view_mobile === 'auto' ? 'auto' : intval($slides_per_view_mobile),
                    'spaceBetween' => $space_between_mobile,
                ],
                768 => [
                    'slidesPerView' => $slides_per_view_tablet === 'auto' ? 'auto' : intval($slides_per_view_tablet),
                    'spaceBetween' => $space_between_tablet,
                ],
                1024 => [
                    'slidesPerView' => $slides_per_view,
                    'spaceBetween' => $space_between,
                ],
            ],
            'navigation' => $show_arrows,
            'pagination' => $show_dots,
        ];
        
        $this->add_render_attribute( 'slider-wrapper', 'class', 'mdb-simple-reviews-slider-wrapper' );
        $this->add_render_attribute( 'slider-wrapper', 'data-settings', json_encode( $widget_settings ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'slider-wrapper' ); ?>>
            <div class="swiper mdb-simple-reviews-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['reviews_list'] as $review ) : ?>
                        <div class="swiper-slide mdb-simple-review-slide">
                            <div class="mdb-simple-review-card">
                                <div class="mdb-review-header">
                                    <div class="mdb-review-stars">
                                        <?php 
                                        $rating = floatval($review['review_rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<i class="fa fa-star"></i>';
                                            } elseif ($i - 0.5 <= $rating) {
                                                echo '<i class="fa fa-star-half-o"></i>';
                                            } else {
                                                echo '<i class="fa fa-star-o"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php if ( $review['review_verified'] === 'yes' ) : ?>
                                        <span class="mdb-verified-badge" title="Verified Customer">
                                            <i class="fa fa-check-circle"></i> <?php esc_html_e( 'Verified', 'mdb-custom-widgets' ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ( ! empty( $review['review_title'] ) ) : ?>
                                    <h4 class="mdb-review-title"><?php echo esc_html( $review['review_title'] ); ?></h4>
                                <?php endif; ?>
                                
                                <div class="mdb-review-content">
                                    <?php echo esc_html( $review['review_content'] ); ?>
                                </div>
                                
                                <div class="mdb-review-footer">
                                    <div class="mdb-review-author">
                                        <strong><?php echo esc_html( $review['review_author'] ); ?></strong>
                                    </div>
                                    <?php if ( ! empty( $review['review_date'] ) ) : ?>
                                        <div class="mdb-review-date"><?php echo esc_html( $review['review_date'] ); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ( $show_dots ) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
                
                <?php if ( $show_arrows ) : ?>
                    <div class="mdb-swiper-button-prev"><i class="eicon-chevron-left"></i></div>
                    <div class="mdb-swiper-button-next"><i class="eicon-chevron-right"></i></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
