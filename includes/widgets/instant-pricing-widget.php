<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor is loaded
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * MDB Instant Pricing Widget
 *
 * Custom widget for displaying instant pricing calculator
 *
 * @since 1.0.0
 */
class MDB_Instant_Pricing_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-instant-pricing';
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
        return esc_html__( 'MDB Instant Pricing', 'mdb-custom-widgets' );
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
        return 'eicon-price-table';
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
        return [ 'pricing', 'calculator', 'estimate', 'mdb' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'mdb-instant-pricing-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-instant-pricing-widget' ];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'width_label',
            [
                'label' => esc_html__( 'Width Label', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Width (mm)', 'mdb-custom-widgets' ),
            ]
        );
        
        $this->add_control(
            'width_help',
            [
                'label' => esc_html__( 'Width Help Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Min width: 250mm, Max width 3000mm', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'drop_label',
            [
                'label' => esc_html__( 'Drop Label', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Drop (mm)', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'drop_help',
            [
                'label' => esc_html__( 'Drop Help Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Min drop: 300mm, Max drop 3000mm', 'mdb-custom-widgets' ),
            ]
        );

        $repeater_features = new \Elementor\Repeater();

        $repeater_features->add_control(
            'feature_text',
            [
                'label' => esc_html__( 'Feature Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Feature Item', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'features_list',
            [
                'label' => esc_html__( 'Features List', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater_features->get_controls(),
                'default' => [
                    [ 'feature_text' => 'Link Multiple Roller Blinds together' ],
                    [ 'feature_text' => 'Double your Roller Blinds on the Same Bracket' ],
                    [ 'feature_text' => 'Motorisation Available from $X' ],
                    [ 'feature_text' => 'Full Blockout Cassette Enclosures available from $X' ],
                    [ 'feature_text' => 'Pelmets available from $X/m' ],
                ],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->end_controls_section();

        // Pricing Cards Section
        $this->start_controls_section(
            'cards_section',
            [
                'label' => esc_html__( 'Pricing Cards', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater_cards = new \Elementor\Repeater();

        $repeater_cards->add_control(
            'card_image',
            [
                'label' => esc_html__( 'Image', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater_cards->add_control(
            'card_title',
            [
                'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Card Title', 'mdb-custom-widgets' ),
            ]
        );

        $repeater_cards->add_control(
            'card_price_prefix',
            [
                'label' => esc_html__( 'Price Prefix', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'From', 'mdb-custom-widgets' ),
            ]
        );

        $repeater_cards->add_control(
            'card_price',
            [
                'label' => esc_html__( 'Price', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '$57', 'mdb-custom-widgets' ),
            ]
        );
        
        $repeater_cards->add_control(
            'card_description',
            [
                'label' => esc_html__( 'Description', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => esc_html__( '<ul><li>Feature 1</li><li>Feature 2</li></ul>', 'mdb-custom-widgets' ),
            ]
        );

        $this->add_control(
            'cards_list',
            [
                'label' => esc_html__( 'Cards', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater_cards->get_controls(),
                'default' => [
                    [
                        'card_title' => 'Blockout',
                        'card_price' => '$57',
                        'card_price_prefix' => 'From',
                    ],
                    [
                        'card_title' => 'Sunscreen',
                        'card_price' => '$133',
                        'card_price_prefix' => 'From',
                    ],
                    [
                        'card_title' => 'Light Filter',
                        'card_price' => '$57',
                        'card_price_prefix' => 'From',
                    ],
                ],
                'title_field' => '{{{ card_title }}}',
            ]
        );
        
        $this->add_control(
            'disclaimer_text',
            [
                'label' => esc_html__( 'Disclaimer Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( '* Delivery charges calculated at checkout', 'mdb-custom-widgets' ),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Styles
        $this->start_controls_section(
            'style_general_section',
            [
                'label' => esc_html__( 'General', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'disclaimer_color',
            [
                'label' => esc_html__( 'Disclaimer Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-disclaimer' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'disclaimer_typography',
                'selector' => '{{WRAPPER}} .mdb-pricing-disclaimer',
            ]
        );

        $this->end_controls_section();

        // Style - Inputs
        $this->start_controls_section(
            'style_inputs_section',
            [
                'label' => esc_html__( 'Inputs', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-input' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-input' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__( 'Border Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#7a828e',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-input' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_border_width',
            [
                'label' => esc_html__( 'Border Width', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .mdb-pricing-input',
            ]
        );
        
        $this->add_control(
            'help_text_color',
            [
                'label' => esc_html__( 'Help Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .mdb-input-help' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'help_text_typography',
                'selector' => '{{WRAPPER}} .mdb-input-help',
            ]
        );

        $this->end_controls_section();

        // Style - Features List
        $this->start_controls_section(
            'style_features_section',
            [
                'label' => esc_html__( 'Features List', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'feature_text_color',
            [
                'label' => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#444D5F',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-features li' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'feature_typography',
                'selector' => '{{WRAPPER}} .mdb-pricing-features li',
            ]
        );

        $this->add_control(
            'feature_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mdb-feature-icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'feature_spacing',
            [
                'label' => esc_html__( 'Spacing Between Items', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-features li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Cards
        $this->start_controls_section(
            'style_cards_section',
            [
                'label' => esc_html__( 'Cards', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'card_bg_color',
            [
                'label' => esc_html__( 'Card Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'card_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-pricing-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mdb-pricing-card',
            ]
        );
        
        $this->add_control(
            'card_title_heading',
            [
                'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'card_title_color',
            [
                'label' => esc_html__( 'Title Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#444D5F',
                'selectors' => [
                    '{{WRAPPER}} .mdb-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'card_title_typography',
                'selector' => '{{WRAPPER}} .mdb-card-title',
            ]
        );
        
        $this->add_control(
            'card_price_heading',
            [
                'label' => esc_html__( 'Price', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'card_price_color',
            [
                'label' => esc_html__( 'Price Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#49c3c9',
                'selectors' => [
                    '{{WRAPPER}} .mdb-card-price' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'card_price_typography',
                'selector' => '{{WRAPPER}} .mdb-card-price',
            ]
        );
        
        $this->add_control(
            'card_prefix_color',
            [
                'label' => esc_html__( 'Prefix Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mdb-card-price-prefix' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'card_prefix_typography',
                'selector' => '{{WRAPPER}} .mdb-card-price-prefix',
            ]
        );

        $this->add_control(
            'card_desc_heading',
            [
                'label' => esc_html__( 'Description', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'card_desc_color',
            [
                'label' => esc_html__( 'Description Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .mdb-card-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'card_desc_typography',
                'selector' => '{{WRAPPER}} .mdb-card-description',
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
        ?>
        <div class="mdb-instant-pricing-widget">
            <div class="mdb-pricing-container">
                <div class="mdb-pricing-left">
                    
                    <div class="mdb-pricing-inputs">
                        <div class="mdb-input-group">
                            <div class="mdb-input-wrapper">
                                <input type="number" placeholder="<?php echo esc_attr( $settings['width_label'] ); ?>" class="mdb-pricing-input">
                                <div class="mdb-select-arrow"></div>
                            </div>
                            <div class="mdb-input-help"><?php echo esc_html( $settings['width_help'] ); ?></div>
                        </div>
                        
                        <div class="mdb-input-group">
                            <div class="mdb-input-wrapper">
                                <input type="number" placeholder="<?php echo esc_attr( $settings['drop_label'] ); ?>" class="mdb-pricing-input">
                                <div class="mdb-select-arrow"></div>
                            </div>
                            <div class="mdb-input-help"><?php echo esc_html( $settings['drop_help'] ); ?></div>
                        </div>
                    </div>
                    
                        <?php foreach ( $settings['features_list'] as $item ) : ?>
                            <li>
                                <span class="mdb-feature-icon">✓</span>
                                <span class="mdb-feature-text"><?php echo esc_html( $item['feature_text'] ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="mdb-pricing-right">
                    <div class="mdb-pricing-cards">
                        <?php foreach ( $settings['cards_list'] as $card ) : ?>
                            <div class="mdb-pricing-card">
                                <div class="mdb-card-image">
                                    <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $card, 'thumbnail', 'card_image' ); ?>
                                </div>
                                <div class="mdb-card-content">
                                    <h3 class="mdb-card-title"><?php echo esc_html( $card['card_title'] ); ?></h3>
                                    <div class="mdb-card-price-wrapper">
                                        <span class="mdb-card-price-prefix"><?php echo esc_html( $card['card_price_prefix'] ); ?></span>
                                        <span class="mdb-card-price"><?php echo esc_html( $card['card_price'] ); ?></span>
                                    </div>
                                    <div class="mdb-card-description">
                                        <?php echo wp_kses_post( $card['card_description'] ); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <?php if ( ! empty( $settings['disclaimer_text'] ) ) : ?>
                <div class="mdb-pricing-disclaimer"><?php echo esc_html( $settings['disclaimer_text'] ); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }
}
