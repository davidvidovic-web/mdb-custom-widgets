<?php
/**
 * MDB Reviews Navigation Widget
 * 
 * A navigation widget for controlling reviews slider with customizable buttons
 *
 * @package MDB Custom Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * MDB Reviews Navigation Widget Class
 *
 * @since 1.0.0
 */
class MDB_Reviews_Navigation_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-reviews-navigation';
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
        return esc_html__( 'MDB Reviews Navigation', 'mdb-custom-widgets' );
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
        return 'eicon-navigation-horizontal';
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
        return [ 'navigation', 'reviews', 'slider', 'buttons', 'mdb' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'mdb-reviews-navigation-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-reviews-navigation-widget' ];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // Content Tab - Navigation Settings
        $this->start_controls_section(
            'navigation_section',
            [
                'label' => esc_html__( 'Navigation Settings', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'target_slider',
            [
                'label' => esc_html__( 'Target Slider ID', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'mdb-reviews-slider',
                'description' => esc_html__( 'Enter the ID of the reviews slider this navigation should control', 'mdb-custom-widgets' ),
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
            ]
        );

        $this->end_controls_section();

        // Style Tab - Navigation Buttons
        $this->start_controls_section(
            'buttons_style_section',
            [
                'label' => esc_html__( 'Navigation Buttons', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_size',
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
            'button_border_radius',
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
                    '{{WRAPPER}} .mdb-category-nav-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_font_size',
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
            'button_gap',
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
        $this->start_controls_tabs( 'button_colors_tabs' );

        // Normal State
        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'button_background_color',
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
            'button_text_color',
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
                'name' => 'button_border',
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
            'button_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'button_background_color_hover',
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
            'button_text_color_hover',
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
            'button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_transform',
            [
                'label' => esc_html__( 'Hover Effect', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-nav-btn:hover' => 'transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);',
                ],
            ]
        );

        $this->end_controls_tab();

        // Disabled State
        $this->start_controls_tab(
            'button_disabled_tab',
            [
                'label' => esc_html__( 'Disabled', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'button_background_color_disabled',
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
            'button_text_color_disabled',
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
            'button_opacity_disabled',
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

        $this->end_controls_section();

        // Style Tab - Container
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__( 'Container', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__( 'Margin', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-reviews-navigation-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__( 'Padding', 'mdb-custom-widgets' ),
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
                    '{{WRAPPER}} .mdb-reviews-navigation-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $target_slider = $settings['target_slider'];
        $show_previous = $settings['show_previous'] === 'yes';
        $show_next = $settings['show_next'] === 'yes';

        // Add data attributes for JavaScript
        ?>
        <div class="mdb-reviews-navigation-widget" data-target="<?php echo esc_attr( $target_slider ); ?>">
            <div class="mdb-reviews-navigation">
                <?php if ( $show_previous ) : ?>
                    <button type="button" class="mdb-reviews-nav-btn mdb-category-nav-btn mdb-reviews-prev" aria-label="<?php echo esc_attr__( 'Previous Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                        <i class="eicon-chevron-left" aria-hidden="true"></i>
                    </button>
                <?php endif; ?>
                
                <?php if ( $show_next ) : ?>
                    <button type="button" class="mdb-reviews-nav-btn mdb-category-nav-btn mdb-reviews-next" aria-label="<?php echo esc_attr__( 'Next Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                        <i class="eicon-chevron-right" aria-hidden="true"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
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
        var targetSlider = settings.target_slider;
        var showPrevious = settings.show_previous === 'yes';
        var showNext = settings.show_next === 'yes';
        #>
        <div class="mdb-reviews-navigation-widget" data-target="{{{ targetSlider }}}">
            <div class="mdb-reviews-navigation">
                <# if ( showPrevious ) { #>
                    <button type="button" class="mdb-reviews-nav-btn mdb-category-nav-btn mdb-reviews-prev" aria-label="<?php echo esc_attr__( 'Previous Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                        <i class="eicon-chevron-left" aria-hidden="true"></i>
                    </button>
                <# } #>
                
                <# if ( showNext ) { #>
                    <button type="button" class="mdb-reviews-nav-btn mdb-category-nav-btn mdb-reviews-next" aria-label="<?php echo esc_attr__( 'Next Reviews', 'mdb-custom-widgets' ); ?>" aria-disabled="false">
                        <i class="eicon-chevron-right" aria-hidden="true"></i>
                    </button>
                <# } #>
            </div>
        </div>
        <?php
    }
}
