<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * MDB Product Customizer Widget
 *
 * Displays a grid of selectable WooCommerce products (or demo items) with
 * a "Start Customising" CTA button. The selected card is highlighted with
 * a teal border and checkmark badge.
 *
 * @since 1.0.0
 */
class MDB_Product_Customizer_Widget extends MDB_Widget_Base {

    public function get_name() {
        return 'mdb-product-customizer';
    }

    public function get_title() {
        return esc_html__( 'MDB Product Customizer', 'mdb-custom-widgets' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'products', 'customizer', 'selector', 'pricing', 'mdb' ];
    }

    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'mdb-product-customizer-widget' ];
    }

    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-product-customizer-widget' ];
    }

    // -------------------------------------------------------------------------
    // Controls
    // -------------------------------------------------------------------------

    protected function register_controls() {

        /* ====================================================================
         * CONTENT TAB – Widget Settings
         * ==================================================================== */
        $this->start_controls_section(
            'section_widget_settings',
            [
                'label' => esc_html__( 'Widget Settings', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => esc_html__( 'Columns', 'mdb-custom-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * CONTENT TAB – Data Source
         * ==================================================================== */
        $this->start_controls_section(
            'section_data_source',
            [
                'label' => esc_html__( 'Data Source', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source_type',
            [
                'label'   => esc_html__( 'Source', 'mdb-custom-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'demo',
                'options' => [
                    'woocommerce' => esc_html__( 'WooCommerce Products', 'mdb-custom-widgets' ),
                    'demo'        => esc_html__( 'Demo / Custom Data', 'mdb-custom-widgets' ),
                ],
            ]
        );

        // ---- WooCommerce controls ----

        // Build product category list safely (avoid DB queries in editor)
        $product_categories = [];
        if ( class_exists( 'WooCommerce' ) ) {
            $can_query = ! (
                defined( 'ELEMENTOR_VERSION' )
                && isset( \Elementor\Plugin::$instance->editor )
                && is_object( \Elementor\Plugin::$instance->editor )
                && method_exists( \Elementor\Plugin::$instance->editor, 'is_edit_mode' )
                && \Elementor\Plugin::$instance->editor->is_edit_mode()
            );

            if ( $can_query ) {
                try {
                    $terms = get_terms( [
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => false,
                    ] );
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $product_categories[ $term->term_id ] = $term->name;
                        }
                    }
                } catch ( Exception $e ) {
                    $product_categories = [];
                }
            }
        }

        $this->add_control(
            'woo_category',
            [
                'label'     => esc_html__( 'Filter by Category', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::SELECT2,
                'multiple'  => true,
                'options'   => $product_categories,
                'condition' => [ 'source_type' => 'woocommerce' ],
            ]
        );

        $this->add_control(
            'woo_posts_per_page',
            [
                'label'     => esc_html__( 'Number of Products', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => 6,
                'min'       => 1,
                'max'       => 24,
                'condition' => [ 'source_type' => 'woocommerce' ],
            ]
        );

        $this->add_control(
            'woo_orderby',
            [
                'label'     => esc_html__( 'Order By', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'menu_order',
                'options'   => [
                    'menu_order' => esc_html__( 'Menu Order', 'mdb-custom-widgets' ),
                    'title'      => esc_html__( 'Title', 'mdb-custom-widgets' ),
                    'date'       => esc_html__( 'Date', 'mdb-custom-widgets' ),
                    'price'      => esc_html__( 'Price', 'mdb-custom-widgets' ),
                    'rand'       => esc_html__( 'Random', 'mdb-custom-widgets' ),
                ],
                'condition' => [ 'source_type' => 'woocommerce' ],
            ]
        );

        $this->add_control(
            'woo_order',
            [
                'label'     => esc_html__( 'Order', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'ASC',
                'options'   => [
                    'ASC'  => esc_html__( 'Ascending', 'mdb-custom-widgets' ),
                    'DESC' => esc_html__( 'Descending', 'mdb-custom-widgets' ),
                ],
                'condition' => [ 'source_type' => 'woocommerce' ],
            ]
        );

        $this->add_control(
            'woo_show_price',
            [
                'label'        => esc_html__( 'Show Price', 'mdb-custom-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off'    => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [ 'source_type' => 'woocommerce' ],
            ]
        );

        $this->add_control(
            'woo_price_prefix',
            [
                'label'     => esc_html__( 'Price Prefix', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => esc_html__( 'Starting from', 'mdb-custom-widgets' ),
                'condition' => [
                    'source_type'    => 'woocommerce',
                    'woo_show_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'woo_show_features',
            [
                'label'        => esc_html__( 'Show Short Description as Features', 'mdb-custom-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off'    => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [ 'source_type' => 'woocommerce' ],
                'description'  => esc_html__( 'Each line of the product short description becomes a feature bullet.', 'mdb-custom-widgets' ),
            ]
        );

        // ---- Demo / Custom controls ----

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'product_name',
            [
                'label'       => esc_html__( 'Product Name', 'mdb-custom-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Product Name', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'product_image',
            [
                'label'   => esc_html__( 'Product Image', 'mdb-custom-widgets' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_control(
            'product_price',
            [
                'label'       => esc_html__( 'Price', 'mdb-custom-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '57',
                'placeholder' => '0.00',
            ]
        );

        $repeater->add_control(
            'product_price_prefix',
            [
                'label'   => esc_html__( 'Price Prefix', 'mdb-custom-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Starting from', 'mdb-custom-widgets' ),
            ]
        );

        $repeater->add_control(
            'product_features',
            [
                'label'       => esc_html__( 'Features (one per line)', 'mdb-custom-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => "Great for blocking out light\nProvides ultimate privacy\nPerfect for bedrooms\nGreat insulation from outdoor temperatures\nYou can add motorisation for wireless interaction",
                'rows'        => 6,
                'placeholder' => esc_html__( 'Enter each feature on a new line', 'mdb-custom-widgets' ),
            ]
        );

        $repeater->add_control(
            'product_url',
            [
                'label'       => esc_html__( 'Product URL', 'mdb-custom-widgets' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com/product',
                'default'     => [ 'url' => '' ],
            ]
        );

        $this->add_control(
            'demo_products',
            [
                'label'       => esc_html__( 'Demo Products', 'mdb-custom-widgets' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'product_name'         => 'Roller Blinds',
                        'product_price'        => '57',
                        'product_price_prefix' => 'Starting from',
                        'product_features'     => "Great for blocking out light\nProvides ultimate privacy\nPerfect for bedrooms\nGreat insulation from outdoor temperatures\nYou can add motorisation for wireless interaction",
                    ],
                    [
                        'product_name'         => 'Double Roller Blinds',
                        'product_price'        => '133',
                        'product_price_prefix' => 'Starting from',
                        'product_features'     => "Great for blocking out light\nProvides ultimate privacy\nPerfect for bedrooms\nGreat insulation from outdoor temperatures\nYou can add motorisation for wireless interaction",
                    ],
                    [
                        'product_name'         => 'Full Blackout Cassette',
                        'product_price'        => '183',
                        'product_price_prefix' => 'Starting from',
                        'product_features'     => "Great for blocking out light\nProvides ultimate privacy\nPerfect for bedrooms\nGreat insulation from outdoor temperatures\nYou can add motorisation for wireless interaction",
                    ],
                ],
                'title_field' => '{{{ product_name }}}',
                'condition'   => [ 'source_type' => 'demo' ],
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * CONTENT TAB – Button
         * ==================================================================== */
        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'CTA Button', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label'        => esc_html__( 'Show Button', 'mdb-custom-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off'    => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'     => esc_html__( 'Button Text', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => esc_html__( 'Start Customising', 'mdb-custom-widgets' ),
                'condition' => [ 'show_button' => 'yes' ],
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label'       => esc_html__( 'Button URL', 'mdb-custom-widgets' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com/configure',
                'default'     => [ 'url' => '' ],
                'condition'   => [ 'show_button' => 'yes' ],
                'description' => esc_html__( 'Leave empty to let JS handle navigation based on the selected product.', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'button_disabled_without_selection',
            [
                'label'        => esc_html__( 'Disable Until Product Selected', 'mdb-custom-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off'    => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [ 'show_button' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * STYLE TAB – Layout
         * ==================================================================== */
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => esc_html__( 'Layout', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_gap',
            [
                'label'      => esc_html__( 'Card Gap', 'mdb-custom-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default'    => [ 'size' => 20, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .mdb-pc-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Card Padding', 'mdb-custom-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => '24',
                    'right'  => '24',
                    'bottom' => '24',
                    'left'   => '24',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mdb-pc-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * STYLE TAB – Cards
         * ==================================================================== */
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Cards', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#e0e0e0',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default'    => [ 'size' => 8, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .mdb-pc-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_selected_color',
            [
                'label'     => esc_html__( 'Selected Border / Accent Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4ecdc4',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-card.is-selected'                  => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .mdb-pc-card.is-selected .mdb-pc-check'    => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mdb-pc-card',
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * STYLE TAB – Product Name
         * ==================================================================== */
        $this->start_controls_section(
            'section_style_product_name',
            [
                'label' => esc_html__( 'Product Name', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'product_name_typography',
                'selector' => '{{WRAPPER}} .mdb-pc-product-name',
            ]
        );

        $this->add_control(
            'product_name_color',
            [
                'label'     => esc_html__( 'Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#2c3e50',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-product-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * STYLE TAB – Price
         * ==================================================================== */
        $this->start_controls_section(
            'section_style_price',
            [
                'label' => esc_html__( 'Price', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_prefix_color',
            [
                'label'     => esc_html__( 'Prefix Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#7f8c8d',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-price-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Price Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4ecdc4',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .mdb-pc-price',
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * STYLE TAB – Features
         * ==================================================================== */
        $this->start_controls_section(
            'section_style_features',
            [
                'label' => esc_html__( 'Features List', 'mdb-custom-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'features_color',
            [
                'label'     => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#95a5a6',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pc-features li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'features_typography',
                'selector' => '{{WRAPPER}} .mdb-pc-features li',
            ]
        );

        $this->end_controls_section();

        /* ====================================================================
         * STYLE TAB – Button
         * ==================================================================== */
        $this->start_controls_section(
            'section_style_button',
            [
                'label'     => esc_html__( 'Button', 'mdb-custom-widgets' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_button' => 'yes' ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .mdb-pc-btn',
            ]
        );

        $this->start_controls_tabs( 'button_tabs' );

        $this->start_controls_tab(
            'button_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'mdb-custom-widgets' ) ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#95a5a6',
                'selectors' => [ '{{WRAPPER}} .mdb-pc-btn' => 'background-color: {{VALUE}};' ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [ '{{WRAPPER}} .mdb-pc-btn' => 'color: {{VALUE}};' ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'mdb-custom-widgets' ) ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#7f8c8d',
                'selectors' => [ '{{WRAPPER}} .mdb-pc-btn:hover' => 'background-color: {{VALUE}};' ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [ '{{WRAPPER}} .mdb-pc-btn:hover' => 'color: {{VALUE}};' ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_tab_active',
            [ 'label' => esc_html__( 'Active (Selected)', 'mdb-custom-widgets' ) ]
        );

        $this->add_control(
            'button_bg_color_active',
            [
                'label'     => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4ecdc4',
                'selectors' => [ '{{WRAPPER}} .mdb-pc-btn.has-selection' => 'background-color: {{VALUE}};' ],
            ]
        );

        $this->add_control(
            'button_text_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [ '{{WRAPPER}} .mdb-pc-btn.has-selection' => 'color: {{VALUE}};' ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'mdb-custom-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => '14',
                    'right'  => '30',
                    'bottom' => '14',
                    'left'   => '30',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mdb-pc-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default'    => [ 'size' => 6, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .mdb-pc-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // -------------------------------------------------------------------------
    // Render (PHP / server-side)
    // -------------------------------------------------------------------------

    protected function render() {
        $settings = $this->get_settings_for_display();

        $products   = $this->get_products( $settings );
        $columns    = absint( $settings['columns'] ?? 3 );
        $show_btn   = 'yes' === ( $settings['show_button'] ?? 'yes' );
        $btn_text   = $settings['button_text'] ?? 'Start Customising';
        $btn_url    = ! empty( $settings['button_url']['url'] ) ? $settings['button_url']['url'] : '';
        $btn_target = ! empty( $settings['button_url']['is_external'] ) ? '_blank' : '_self';
        $btn_rel    = ! empty( $settings['button_url']['nofollow'] ) ? 'nofollow' : '';
        $btn_disabled = 'yes' === ( $settings['button_disabled_without_selection'] ?? 'no' );

        $this->add_render_attribute( 'wrapper', 'class', 'mdb-widget mdb-pc-widget' );
        $this->add_render_attribute( 'grid', [
            'class'               => 'mdb-pc-grid',
            'data-columns'        => $columns,
            'style'               => '--mdb-pc-columns:' . $columns . ';',
        ] );
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

            <?php if ( empty( $products ) ) : ?>
                <p class="mdb-pc-empty"><?php esc_html_e( 'No products found. Please check your widget settings.', 'mdb-custom-widgets' ); ?></p>
            <?php else : ?>
                <div <?php $this->print_render_attribute_string( 'grid' ); ?>>
                    <?php foreach ( $products as $index => $product ) :
                        $is_first = ( $index === 0 );
                    ?>
                        <div class="mdb-pc-card<?php echo $is_first ? ' is-selected' : ''; ?>"
                             data-product-url="<?php echo esc_url( $product['url'] ); ?>"
                             data-product-name="<?php echo esc_attr( $product['name'] ); ?>"
                             role="button"
                             tabindex="0"
                             aria-pressed="<?php echo $is_first ? 'true' : 'false'; ?>">

                            <div class="mdb-pc-check" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="#ffffff">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                </svg>
                            </div>

                            <?php if ( ! empty( $product['image'] ) ) : ?>
                                <div class="mdb-pc-image">
                                    <img src="<?php echo esc_url( $product['image'] ); ?>"
                                         alt="<?php echo esc_attr( $product['name'] ); ?>"
                                         loading="lazy">
                                </div>
                            <?php endif; ?>

                            <div class="mdb-pc-content">
                                <h3 class="mdb-pc-product-name"><?php echo esc_html( $product['name'] ); ?></h3>

                                <?php if ( ! empty( $product['price'] ) ) : ?>
                                    <p class="mdb-pc-price">
                                        <?php if ( ! empty( $product['price_prefix'] ) ) : ?>
                                            <span class="mdb-pc-price-prefix"><?php echo esc_html( $product['price_prefix'] ); ?>&nbsp;</span>
                                        <?php endif; ?>
                                        <span class="mdb-pc-price-amount"><?php echo wp_kses_post( $product['price'] ); ?></span>
                                    </p>
                                <?php endif; ?>

                                <?php if ( ! empty( $product['features'] ) ) : ?>
                                    <ul class="mdb-pc-features">
                                        <?php foreach ( $product['features'] as $feature ) : ?>
                                            <li><?php echo esc_html( $feature ); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( $show_btn ) : ?>
                    <div class="mdb-pc-footer">
                        <a class="mdb-pc-btn<?php echo ! $btn_disabled ? ' has-selection' : ''; ?>"
                           href="<?php echo esc_url( $btn_url ?: '#' ); ?>"
                           target="<?php echo esc_attr( $btn_target ); ?>"
                           <?php if ( $btn_rel ) : ?>rel="<?php echo esc_attr( $btn_rel ); ?>"<?php endif; ?>
                           data-base-url="<?php echo esc_url( $btn_url ); ?>"
                           <?php if ( $btn_disabled ) : ?>aria-disabled="true"<?php endif; ?>>
                            <?php echo esc_html( $btn_text ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
        <?php
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build unified product list from the chosen source.
     */
    private function get_products( array $settings ): array {
        if ( 'woocommerce' === ( $settings['source_type'] ?? 'demo' ) ) {
            return $this->get_woocommerce_products( $settings );
        }
        return $this->get_demo_products( $settings );
    }

    /**
     * Fetch real WooCommerce products.
     */
    private function get_woocommerce_products( array $settings ): array {
        $products = [];

        if ( ! class_exists( 'WooCommerce' ) ) {
            return $products;
        }

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => absint( $settings['woo_posts_per_page'] ?? 6 ),
            'orderby'        => sanitize_key( $settings['woo_orderby'] ?? 'menu_order' ),
            'order'          => in_array( $settings['woo_order'] ?? 'ASC', [ 'ASC', 'DESC' ], true ) ? $settings['woo_order'] : 'ASC',
        ];

        if ( ! empty( $settings['woo_category'] ) ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => array_map( 'absint', (array) $settings['woo_category'] ),
            ] ];
        }

        if ( 'price' === ( $settings['woo_orderby'] ?? '' ) ) {
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = '_price';
        }

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            $show_price    = 'yes' === ( $settings['woo_show_price'] ?? 'yes' );
            $price_prefix  = $settings['woo_price_prefix'] ?? 'Starting from';
            $show_features = 'yes' === ( $settings['woo_show_features'] ?? 'yes' );

            while ( $query->have_posts() ) {
                $query->the_post();
                $wc_product = wc_get_product( get_the_ID() );

                if ( ! $wc_product ) {
                    continue;
                }

                // Image
                $image_url = '';
                $thumb_id  = get_post_thumbnail_id();
                if ( $thumb_id ) {
                    $image_url = wp_get_attachment_image_url( $thumb_id, 'medium' );
                }
                if ( ! $image_url ) {
                    $image_url = \Elementor\Utils::get_placeholder_image_src();
                }

                // Price
                $price = '';
                if ( $show_price ) {
                    $price = $wc_product->get_price_html();
                }

                // Features from short description
                $features = [];
                if ( $show_features ) {
                    $short_desc = $wc_product->get_short_description();
                    $short_desc = wp_strip_all_tags( $short_desc );
                    if ( ! empty( $short_desc ) ) {
                        $features = array_filter( array_map( 'trim', explode( "\n", $short_desc ) ) );
                    }
                }

                $products[] = [
                    'name'         => get_the_title(),
                    'image'        => $image_url,
                    'price'        => $price,
                    'price_prefix' => $show_price ? $price_prefix : '',
                    'features'     => array_values( $features ),
                    'url'          => get_permalink(),
                ];
            }
            wp_reset_postdata();
        }

        return $products;
    }

    /**
     * Build product list from the demo repeater.
     */
    private function get_demo_products( array $settings ): array {
        $products = [];

        if ( empty( $settings['demo_products'] ) ) {
            return $products;
        }

        foreach ( $settings['demo_products'] as $item ) {
            $image_url = ! empty( $item['product_image']['url'] )
                ? $item['product_image']['url']
                : \Elementor\Utils::get_placeholder_image_src();

            $url = ! empty( $item['product_url']['url'] ) ? $item['product_url']['url'] : '';

            $raw_features = $item['product_features'] ?? '';
            $features     = array_filter( array_map( 'trim', explode( "\n", $raw_features ) ) );

            if ( ! empty( $item['product_price'] ) ) {
                $price = function_exists( 'wc_price' )
                    ? wc_price( floatval( $item['product_price'] ) )
                    : '$' . number_format( floatval( $item['product_price'] ), 2 );
            } else {
                $price = '';
            }

            $products[] = [
                'name'         => $item['product_name'] ?? '',
                'image'        => $image_url,
                'price'        => $price,
                'price_prefix' => $item['product_price_prefix'] ?? '',
                'features'     => array_values( $features ),
                'url'          => $url,
            ];
        }

        return $products;
    }

    // -------------------------------------------------------------------------
    // Editor live preview template (JS / Backbone)
    // -------------------------------------------------------------------------

    protected function content_template() {
        ?>
        <#
        var placeholder = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iI2FhYSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPlByb2R1Y3QgSW1hZ2U8L3RleHQ+PC9zdmc+';

        var products = [];

        if ( 'woocommerce' === settings.source_type ) {
            products = [
                { name: 'Roller Blinds',          price: '$57',  prefix: 'Starting from', features: ['Great for blocking out light','Provides ultimate privacy','Perfect for bedrooms','Great insulation from outdoor temperatures','You can add motorisation for wireless interaction'], image: placeholder, url: '#' },
                { name: 'Double Roller Blinds',   price: '$133', prefix: 'Starting from', features: ['Great for blocking out light','Provides ultimate privacy','Perfect for bedrooms','Great insulation from outdoor temperatures','You can add motorisation for wireless interaction'], image: placeholder, url: '#' },
                { name: 'Full Blackout Cassette', price: '$183', prefix: 'Starting from', features: ['Great for blocking out light','Provides ultimate privacy','Perfect for bedrooms','Great insulation from outdoor temperatures','You can add motorisation for wireless interaction'], image: placeholder, url: '#' }
            ];
        } else {
            _.each( settings.demo_products, function( item ) {
                var img = ( item.product_image && item.product_image.url ) ? item.product_image.url : placeholder;
                var rawFeatures = item.product_features ? item.product_features.split('\n') : [];
                var features = _.filter( _.map( rawFeatures, function(f){ return f.trim(); } ), function(f){ return f.length > 0; } );
                products.push({
                    name:     item.product_name    || 'Product Name',
                    price:    item.product_price   ? '$' + item.product_price : '',
                    prefix:   item.product_price_prefix || '',
                    features: features,
                    image:    img,
                    url:      ( item.product_url && item.product_url.url ) ? item.product_url.url : '#'
                });
            });
        }

        var columns = settings.columns || 3;
        #>

        <div class="mdb-widget mdb-pc-widget">
            <div class="mdb-pc-grid" data-columns="{{{ columns }}}" style="--mdb-pc-columns:{{{ columns }}};">
                <# _.each( products, function( product, idx ) { #>
                    <div class="mdb-pc-card <# if ( idx === 0 ) { #>is-selected<# } #>"
                         data-product-url="{{{ product.url }}}"
                         data-product-name="{{{ product.name }}}">

                        <div class="mdb-pc-check">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="#ffffff"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        </div>

                        <div class="mdb-pc-image">
                            <img src="{{{ product.image }}}" alt="{{{ product.name }}}">
                        </div>

                        <div class="mdb-pc-content">
                            <h3 class="mdb-pc-product-name">{{{ product.name }}}</h3>
                            <# if ( product.price ) { #>
                                <p class="mdb-pc-price">
                                    <# if ( product.prefix ) { #><span class="mdb-pc-price-prefix">{{{ product.prefix }}}&nbsp;</span><# } #>
                                    <span class="mdb-pc-price-amount">{{{ product.price }}}</span>
                                </p>
                            <# } #>
                            <# if ( product.features && product.features.length ) { #>
                                <ul class="mdb-pc-features">
                                    <# _.each( product.features, function( f ) { #>
                                        <li>{{{ f }}}</li>
                                    <# }); #>
                                </ul>
                            <# } #>
                        </div>

                    </div>
                <# }); #>
            </div>

            <# if ( 'yes' === settings.show_button ) { #>
                <div class="mdb-pc-footer">
                    <a class="mdb-pc-btn has-selection" href="#">{{{ settings.button_text }}}</a>
                </div>
            <# } #>
        </div>
        <?php
    }
}
