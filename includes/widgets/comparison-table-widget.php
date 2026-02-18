<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor is loaded
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * MDB Comparison Table Widget
 *
 * A widget to display a comparison table with tabs/categories.
 *
 * @since 1.0.0
 */
class MDB_Comparison_Table_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-comparison-table';
    }

    /**
     * Get widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'MDB Comparison Table', 'mdb-custom-widgets' );
    }

    /**
     * Get widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-table';
    }

    /**
     * Get widget keywords.
     *
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'comparison', 'table', 'price', 'chart', 'tabs' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'mdb-comparison-table-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-comparison-table-widget' ];
    }

    /**
     * Register widget controls.
     *
     * @access protected
     */
    protected function register_controls() {

        // --- Tabs / Categories Section ---
        $this->start_controls_section(
            'section_tabs',
            [
                'label' => esc_html__( 'Categories (Tabs)', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater_tabs = new \Elementor\Repeater();

        $repeater_tabs->add_control(
            'tab_title',
            [
                'label' => esc_html__( 'Category Title', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Category', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tabs_list',
            [
                'label' => esc_html__( 'Categories', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater_tabs->get_controls(),
                'default' => [
                    [ 'tab_title' => 'Battery' ],
                    [ 'tab_title' => 'Wired' ],
                    [ 'tab_title' => 'Accessories' ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();

        // --- Header Section ---
        $this->start_controls_section(
            'section_header',
            [
                'label' => esc_html__( 'Table Header', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'header_col_1_type',
            [
                'label' => esc_html__( 'Our Column Header Type', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'text' => [
                        'title' => esc_html__( 'Text', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-font',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'default' => 'image',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'header_col_1_text',
            [
                'label' => esc_html__( 'Our Header Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'MyDirect Blinds', 'mdb-custom-widgets' ),
                'condition' => [
                    'header_col_1_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'header_col_1_image',
            [
                'label' => esc_html__( 'Our Header Logo', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'header_col_1_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'header_col_2_type',
            [
                'label' => esc_html__( 'Competitor Header Type', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'text' => [
                        'title' => esc_html__( 'Text', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-font',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'mdb-custom-widgets' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'default' => 'text',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'header_col_2_text',
            [
                'label' => esc_html__( 'Competitor Header Text', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'DIY Competitors', 'mdb-custom-widgets' ),
                'condition' => [
                    'header_col_2_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'header_col_2_image',
            [
                'label' => esc_html__( 'Competitor Header Logo', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'header_col_2_type' => 'image',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Comparison Data Section ---
        $this->start_controls_section(
            'section_data',
            [
                'label' => esc_html__( 'Comparison Data', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater_data = new \Elementor\Repeater();

        $repeater_data->add_control(
            'row_category',
            [
                'label' => esc_html__( 'Category (Must match Tab Title)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Battery',
                'description' => esc_html__('Enter the exact name of the category tab this row belongs to.', 'mdb-custom-widgets'),
            ]
        );

        $repeater_data->add_control(
            'row_label',
            [
                'label' => esc_html__( 'Feature Label', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Price', 'mdb-custom-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater_data->add_control(
            'row_value_1',
            [
                'label' => esc_html__( 'Our Value', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$193-196',
            ]
        );

        $repeater_data->add_control(
            'row_value_2',
            [
                'label' => esc_html__( 'Competitor Value', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$210',
            ]
        );

        $this->add_control(
            'comparison_rows',
            [
                'label' => esc_html__( 'Comparison Rows', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater_data->get_controls(),
                'default' => [
                    [
                        'row_category' => 'Battery',
                        'row_label' => 'Price',
                        'row_value_1' => '$193-196',
                        'row_value_2' => '$210',
                    ],
                    [
                        'row_category' => 'Battery',
                        'row_label' => 'Blind Control Size',
                        'row_value_1' => 'Any size up to W300mm x H300mm',
                        'row_value_2' => 'Limited approx. 4m²',
                    ],
                    [
                        'row_category' => 'Battery',
                        'row_label' => 'Torque',
                        'row_value_1' => '1.8 Nm',
                        'row_value_2' => '1.2 Nm',
                    ],
                    [
                        'row_category' => 'Battery',
                        'row_label' => 'Warranty',
                        'row_value_1' => '5 Years',
                        'row_value_2' => '3 Years',
                    ],
                    [
                        'row_category' => 'Wired',
                        'row_label' => 'Price',
                        'row_value_1' => 'From $250',
                        'row_value_2' => 'From $300',
                    ],
                ],
                'title_field' => '{{{ row_category }}} - {{{ row_label }}}',
            ]
        );

        $this->end_controls_section();

        // --- Style Section ---
        
        // Tabs Style
        $this->start_controls_section(
            'section_style_tabs',
            [
                'label' => esc_html__( 'Tabs', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tabs_bg_color',
            [
                'label' => esc_html__( 'Container Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f1f3f6',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-tabs' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_inactive_color',
            [
                'label' => esc_html__( 'Inactive Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-tab' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_bg_color',
            [
                'label' => esc_html__( 'Active Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4a5363',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-tab.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_text_color',
            [
                'label' => esc_html__( 'Active Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-tab.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_typography',
                'selector' => '{{WRAPPER}} .mdb-comparison-tab',
            ]
        );

        $this->end_controls_section();

        // Header Style
        $this->start_controls_section(
            'section_style_header',
            [
                'label' => esc_html__( 'Table Headers', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_text_color',
            [
                'label' => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4a5363',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-header-col' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .mdb-comparison-header-col',
            ]
        );

        $this->add_control(
            'header_logo_height',
            [
                'label' => esc_html__( 'Logo Height', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-header-logo img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Rows Style
        $this->start_controls_section(
            'section_style_rows',
            [
                'label' => esc_html__( 'Table Rows', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_text_color',
            [
                'label' => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4a5363',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-row' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'row_typography',
                'selector' => '{{WRAPPER}} .mdb-comparison-row',
            ]
        );

        $this->add_control(
            'row_odd_bg',
            [
                'label' => esc_html__( 'Odd Row Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f1f3f6',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-row:nth-child(odd)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_even_bg',
            [
                'label' => esc_html__( 'Even Row Background', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-row:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'cell_padding',
            [
                'label' => esc_html__( 'Cell Padding', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-comparison-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 20,
                    'right' => 10,
                    'bottom' => 20,
                    'left' => 10,
                    'unit' => 'px',
                    'isLinked' => false,
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

        $tabs = $settings['tabs_list'];
        $rows = $settings['comparison_rows'];

        // Get the first tab as default active
        $active_tab = !empty($tabs) ? $tabs[0]['tab_title'] : '';

        ?>
        <div class="mdb-comparison-table-widget">
            
            <!-- Tabs -->
            <?php if ( ! empty( $tabs ) ) : ?>
                <div class="mdb-comparison-tabs-wrapper">
                    <div class="mdb-comparison-tabs">
                        <?php foreach ( $tabs as $index => $tab ) : 
                            $is_active = ($index === 0) ? 'active' : '';
                            ?>
                            <div class="mdb-comparison-tab <?php echo esc_attr($is_active); ?>" 
                                 data-tab="<?php echo esc_attr($tab['tab_title']); ?>">
                                <?php echo esc_html($tab['tab_title']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Table Header -->
            <div class="mdb-comparison-table">
                <div class="mdb-comparison-header">
                    <div class="mdb-comparison-header-col mdb-col-label">
                        <!-- Empty Spacer for Layout -->
                    </div>
                    
                    <div class="mdb-comparison-header-col mdb-col-our">
                        <?php if ( $settings['header_col_1_type'] === 'image' && !empty($settings['header_col_1_image']['url']) ) : ?>
                            <div class="mdb-comparison-header-logo">
                                <img src="<?php echo esc_url($settings['header_col_1_image']['url']); ?>" alt="Our Brand">
                            </div>
                        <?php else : ?>
                            <h3><?php echo esc_html($settings['header_col_1_text']); ?></h3>
                        <?php endif; ?>
                    </div>

                    <div class="mdb-comparison-header-col mdb-col-comp">
                         <?php if ( $settings['header_col_2_type'] === 'image' && !empty($settings['header_col_2_image']['url']) ) : ?>
                            <div class="mdb-comparison-header-logo">
                                <img src="<?php echo esc_url($settings['header_col_2_image']['url']); ?>" alt="Competitor">
                            </div>
                        <?php else : ?>
                            <h3><?php echo esc_html($settings['header_col_2_text']); ?></h3>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Table Rows -->
                <div class="mdb-comparison-body">
                    <?php if ( ! empty( $rows ) ) : ?>
                        <?php foreach ( $rows as $row ) : 
                            // Determine if row is visible (matches active tab)
                            // We will handle visibility via JS but good to set initial state
                            $row_cat = $row['row_category'];
                            ?>
                            <div class="mdb-comparison-row" data-category="<?php echo esc_attr($row_cat); ?>">
                                <div class="mdb-comparison-cell mdb-col-label">
                                    <?php echo esc_html($row['row_label']); ?>
                                </div>
                                <div class="mdb-comparison-cell mdb-col-our">
                                    <?php echo esc_html($row['row_value_1']); ?>
                                </div>
                                <div class="mdb-comparison-cell mdb-col-comp">
                                    <?php echo esc_html($row['row_value_2']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>

        </div>
        <?php
    }
}
