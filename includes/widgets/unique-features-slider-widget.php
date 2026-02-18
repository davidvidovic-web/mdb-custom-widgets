<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor is loaded
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * MDB Unique Features Slider Widget
 *
 * A feature slider with image, title, separator, and description.
 *
 * @since 1.0.0
 */
class MDB_Unique_Features_Slider_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-unique-features-slider';
    }

    /**
     * Get widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'MDB Unique Features Slider', 'mdb-custom-widgets' );
    }

    /**
     * Get widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-slider';
    }

    /**
     * Get widget keywords.
     *
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'slider', 'features', 'carousel', 'unique', 'roller', 'blinds' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @access public
     */
    public function get_script_depends() {
        return [ 'swiper', 'mdb-custom-widgets', 'mdb-unique-features-slider-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-unique-features-slider-widget' ];
    }

    /**
     * Register widget controls.
     *
     * @access protected
     */
    protected function register_controls() {

        // Content Tab - Slides
        $this->start_controls_section(
            'section_slides',
            [
                'label' => esc_html__( 'Slides', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Feature Title', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Enter your description here. Explain the unique feature in detail.', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => esc_html__( 'Slides List', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => 'Upgrated Tubes & Gear Drives',
                        'description' => "On blinds over 2100mm in width, we'll upgrade you FREE to the 50mm tube. For widths over 2500mm, we'll upgrade the gear drive clutch will allow you to roll your larger roller blinds up with ease.",
                    ],
                    [
                        'title' => 'Universal Brackets',
                        'description' => 'Top fix or side fix your brackets for easy installation. Take your pick with optional end bracket covers for added aesthetics on your face mounted roller blinds, available in several colours to match your brackets.',
                    ],
                    [
                        'title' => 'Roller Blind Fascia\'s & Pelmets',
                        'description' => 'Hide the roller blind hardware with our decorative aluminium fascia\'s or even choose from our custom pelmets. Available in 4 great colours, or fabric wrapped in 75mm and 100mm square profile or curved.',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // Content Tab - Settings
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
                ],
                'default' => '4',
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
        
        $this->add_control(
            'navigation_position',
            [
                'label' => esc_html__( 'Navigation Position', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'bottom-left' => esc_html__( 'Bottom Left', 'mdb-custom-widgets' ),
                    'center-sides' => esc_html__( 'Center Sides', 'mdb-custom-widgets' ),
                ],
                'default' => 'bottom-left',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Card
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card Style', 'mdb-custom-widgets' ),
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
                    '{{WRAPPER}} .mdb-unique-feature-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'card_border_width',
            [
                'label' => esc_html__( 'Border Width', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-card' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
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
                'default' => '#e5e5e5',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-card' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label' => esc_html__( 'Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 30,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );
        
        $this->add_control(
            'content_padding',
            [
                'label' => esc_html__( 'Content Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 0,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Image
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Image', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_height',
            [
                'label' => esc_html__( 'Height', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_bg_fit',
            [
                'label' => esc_html__( 'Object Fit', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'cover' => 'Cover',
                    'contain' => 'Contain',
                    'fill' => 'Fill',
                ],
                'default' => 'contain',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_padding',
            [
                'label' => esc_html__( 'Image Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 0,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Typography
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content Typography', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Title
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mdb-unique-feature-title',
            ]
        );

        // Separator
        $this->add_control(
            'separator_color',
            [
                'label' => esc_html__( 'Separator Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-separator' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'separator_width',
            [
                'label' => esc_html__( 'Separator Width', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-separator' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Description
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-feature-description' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .mdb-unique-feature-description',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Navigation
        $this->start_controls_section(
            'section_style_navigation',
            [
                'label' => esc_html__( 'Navigation', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_bg_color',
            [
                'label' => esc_html__( 'Button Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-features-button-next, {{WRAPPER}} .mdb-unique-features-button-prev' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'nav_border_color',
            [
                'label' => esc_html__( 'Button Border Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-features-button-next, {{WRAPPER}} .mdb-unique-features-button-prev' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'nav_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mdb-unique-features-button-next, {{WRAPPER}} .mdb-unique-features-button-prev' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $slides_per_view = $settings['slides_per_view'];
        $space_between = $settings['space_between']['size'];
        
        // Responsive logic
        $slides_per_view_tablet = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
        $slides_per_view_mobile = ! empty( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

        $widget_settings = [
            'slidesPerView' => intval($slides_per_view),
            'spaceBetween' => intval($space_between),
            'autoplay' => $settings['autoplay'] === 'yes',
            'autoplaySpeed' => $settings['autoplay_speed'],
            'loop' => $settings['loop'] === 'yes',
            'breakpoints' => [
                320 => [
                    'slidesPerView' => intval($slides_per_view_mobile),
                    'spaceBetween' => 10,
                ],
                768 => [
                    'slidesPerView' => intval($slides_per_view_tablet),
                    'spaceBetween' => 20,
                ],
                1024 => [
                    'slidesPerView' => intval($slides_per_view),
                    'spaceBetween' => intval($space_between),
                ],
            ],
        ];

        $this->add_render_attribute( 'swiper-wrapper', 'class', 'mdb-unique-features-slider-wrapper' );
        $this->add_render_attribute( 'swiper-wrapper', 'data-settings', json_encode( $widget_settings ) );
        ?>

        <div <?php echo $this->get_render_attribute_string( 'swiper-wrapper' ); ?>>
            <!-- Slider -->
            <div class="swiper mdb-unique-features-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) : ?>
                        <div class="swiper-slide mdb-unique-feature-slide">
                            <div class="mdb-unique-feature-card">
                                <div class="mdb-unique-feature-image">
                                    <?php if ( ! empty( $slide['image']['url'] ) ) : ?>
                                        <img src="<?php echo esc_url( $slide['image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="mdb-unique-feature-content">
                                    <h3 class="mdb-unique-feature-title"><?php echo wp_kses_post(nl2br($slide['title'])); ?></h3>
                                    <div class="mdb-unique-feature-separator"></div>
                                    <div class="mdb-unique-feature-description">
                                        <?php echo wp_kses_post( $slide['description'] ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Navigation Buttons - Positioned outside the Swiper container for custom layout -->
            <div class="mdb-unique-features-navigation mdb-nav-pos-<?php echo esc_attr($settings['navigation_position']); ?>">
                <div class="mdb-unique-features-button-prev">
                    <i class="eicon-chevron-left" aria-hidden="true"></i>
                    <span class="elementor-screen-only">Previous</span>
                </div>
                <div class="mdb-unique-features-button-next">
                    <i class="eicon-chevron-right" aria-hidden="true"></i>
                    <span class="elementor-screen-only">Next</span>
                </div>
            </div>

        </div>

        <?php
    }
}
