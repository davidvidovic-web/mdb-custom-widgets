<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * MDB Widget Base
 *
 * Base class for all MDB custom widgets
 *
 * @since 1.0.0
 */
abstract class MDB_Widget_Base extends \Elementor\Widget_Base {

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'mdb-widgets' ];
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
        return [ 'mdb', 'mydirectblinds', 'custom' ];
    }

    /**
     * Register widget dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets' ];
    }

    /**
     * Register widget dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets' ];
    }

    /**
     * Common responsive controls
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_responsive_control_args( $control_id, $args = [] ) {
        $default_args = [
            'label' => '',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [],
        ];

        $args = array_merge( $default_args, $args );

        $this->add_responsive_control( $control_id, $args );
    }

    /**
     * Add common typography control
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_typography_control( $control_id, $selector, $label = 'Typography' ) {
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $control_id,
                'label' => $label,
                'selector' => $selector,
            ]
        );
    }

    /**
     * Add common color control
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_color_control( $control_id, $selector, $label = 'Color', $property = 'color' ) {
        $this->add_control(
            $control_id,
            [
                'label' => $label,
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector => $property . ': {{VALUE}};',
                ],
            ]
        );
    }

    /**
     * Add common background control
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_background_control( $control_id, $selector, $label = 'Background' ) {
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => $control_id,
                'label' => $label,
                'types' => [ 'classic', 'gradient' ],
                'selector' => $selector,
            ]
        );
    }

    /**
     * Add common border control
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_border_control( $control_id, $selector, $label = 'Border' ) {
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $control_id,
                'label' => $label,
                'selector' => $selector,
            ]
        );
    }

    /**
     * Add common box shadow control
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_box_shadow_control( $control_id, $selector, $label = 'Box Shadow' ) {
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => $control_id,
                'label' => $label,
                'selector' => $selector,
            ]
        );
    }

    /**
     * Add common spacing controls
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_spacing_controls( $prefix, $selector ) {
        $this->add_responsive_control(
            $prefix . '_margin',
            [
                'label' => esc_html__( 'Margin', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_padding',
            [
                'label' => esc_html__( 'Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    }

    /**
     * Get alignment control
     *
     * @since 1.0.0
     * @access protected
     */
    protected function get_alignment_control() {
        return [
            'label' => esc_html__( 'Alignment', 'mdb-custom-widgets' ),
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
                'justify' => [
                    'title' => esc_html__( 'Justified', 'mdb-custom-widgets' ),
                    'icon' => 'eicon-text-align-justify',
                ],
            ],
            'default' => 'left',
            'toggle' => true,
        ];
    }

}