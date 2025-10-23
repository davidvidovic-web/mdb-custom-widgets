<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor is loaded
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * MDB Category Scroller Widget
 *
 * WooCommerce category slider with images and text
 *
 * @since 1.0.0
 */
class MDB_Category_Scroller_Widget extends MDB_Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mdb-category-scroller';
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
        return esc_html__( 'MDB Category Scroller', 'mdb-custom-widgets' );
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
        return 'eicon-posts-carousel';
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
        return [ 'woocommerce', 'category', 'scroller', 'carousel', 'products', 'mdb' ];
    }

    /**
     * Register widget script dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends() {
        return [ 'mdb-custom-widgets', 'mdb-category-scroller-widget' ];
    }

    /**
     * Register widget style dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends() {
        return [ 'mdb-custom-widgets', 'mdb-category-scroller-widget' ];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // Content Tab - Categories
        $this->start_controls_section(
            'categories_section',
            [
                'label' => esc_html__( 'Categories', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source_type',
            [
                'label' => esc_html__( 'Source', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'woocommerce',
                'options' => [
                    'woocommerce' => esc_html__( 'WooCommerce Categories', 'mdb-custom-widgets' ),
                    'custom' => esc_html__( 'Custom Categories', 'mdb-custom-widgets' ),
                ],
            ]
        );

        // WooCommerce Categories - simplified for editor
        $woo_categories = [];
        if ( class_exists( 'WooCommerce' ) && function_exists( 'get_terms' ) ) {
            // Only load categories when WooCommerce is present and it's safe to run DB queries.
            $can_load_terms = false;

            // Ensure Elementor plugin instance and editor exist before calling is_edit_mode()
            if ( defined( 'ELEMENTOR_VERSION' ) && isset( \Elementor\Plugin::$instance ) && property_exists( \Elementor\Plugin::$instance, 'editor' ) && is_object( \Elementor\Plugin::$instance->editor ) && method_exists( \Elementor\Plugin::$instance->editor, 'is_edit_mode' ) ) {
                $can_load_terms = ! \Elementor\Plugin::$instance->editor->is_edit_mode();
            } else {
                // If we can't determine editor mode safely, assume it's not editor so backend logic can run.
                $can_load_terms = true;
            }

            if ( $can_load_terms ) {
                try {
                    $woo_categories = $this->get_woocommerce_categories();
                } catch ( Exception $e ) {
                    $woo_categories = [];
                }
            } else {
                // Provide sample options for editor preview safely
                $woo_categories = [
                    '' => esc_html__( 'Select Categories...', 'mdb-custom-widgets' ),
                ];
            }
        }
        
        $this->add_control(
            'woo_categories',
            [
                'label' => esc_html__( 'Select Categories', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $woo_categories,
                'condition' => [
                    'source_type' => 'woocommerce',
                ],
            ]
        );

        $this->add_control(
            'exclude_empty',
            [
                'label' => esc_html__( 'Exclude Empty Categories', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'source_type' => 'woocommerce',
                ],
            ]
        );

        $this->add_control(
            'exclude_uncategorized',
            [
                'label' => esc_html__( 'Exclude Uncategorized', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'source_type' => 'woocommerce',
                ],
            ]
        );

        // Custom Categories Repeater
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'category_name',
            [
                'label' => esc_html__( 'Category Name', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Category Name', 'mdb-custom-widgets' ),
                'placeholder' => esc_html__( 'Enter category name', 'mdb-custom-widgets' ),
            ]
        );

        $repeater->add_control(
            'category_image',
            [
                'label' => esc_html__( 'Category Image', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'category_url',
            [
                'label' => esc_html__( 'Category URL', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'mdb-custom-widgets' ),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $this->add_control(
            'custom_categories',
            [
                'label' => esc_html__( 'Custom Categories', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'category_name' => esc_html__( 'Category #1', 'mdb-custom-widgets' ),
                    ],
                    [
                        'category_name' => esc_html__( 'Category #2', 'mdb-custom-widgets' ),
                    ],
                    [
                        'category_name' => esc_html__( 'Category #3', 'mdb-custom-widgets' ),
                    ],
                ],
                'title_field' => '{{{ category_name }}}',
                'condition' => [
                    'source_type' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        // Scroller Settings
        $this->start_controls_section(
            'scroller_settings',
            [
                'label' => esc_html__( 'Scroller Settings', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'items_per_view',
            [
                'label' => esc_html__( 'Items Per View', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'items_per_view_tablet',
            [
                'label' => esc_html__( 'Items Per View (Tablet)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'items_per_view_mobile',
            [
                'label' => esc_html__( 'Items Per View (Mobile)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 2,
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between Items', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__( 'Auto-play', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__( 'Autoplay Speed (ms)', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'default' => 3000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__( 'Loop', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'mdb-custom-widgets' ),
                'label_off' => esc_html__( 'No', 'mdb-custom-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Category Items
        $this->start_controls_section(
            'category_style',
            [
                'label' => esc_html__( 'Category Items', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label' => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mdb-category-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-category-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'mdb-custom-widgets' ),
                'selector' => '{{WRAPPER}} .mdb-category-item',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 2,
                            'blur' => 10,
                            'spread' => 0,
                            'color' => 'rgba(0, 0, 0, 0.1)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mdb-category-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__( 'Typography', 'mdb-custom-widgets' ),
                'selector' => '{{WRAPPER}} .mdb-category-name',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Navigation
        $this->start_controls_section(
            'navigation_style',
            [
                'label' => esc_html__( 'Navigation', 'mdb-custom-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => esc_html__( 'Navigation Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mdb-category-nav-btn' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => esc_html__( 'Navigation Hover Color', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#005a87',
                'selectors' => [
                    '{{WRAPPER}} .mdb-category-nav-btn:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}; background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_size',
            [
                'label' => esc_html__( 'Navigation Size', 'mdb-custom-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 60,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdb-category-nav-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} * 0.4);',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get WooCommerce categories for dropdown
     *
     * @since 1.0.0
     * @access private
     */
    private function get_woocommerce_categories() {
        $categories = [];

        // Check if WooCommerce is available and functions exist
        if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'get_terms' ) ) {
            return $categories;
        }

        try {
            $terms = get_terms( [
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
            ] );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $categories[ $term->term_id ] = $term->name;
                }
            }
        } catch ( Exception $e ) {
            // Log error if needed
            error_log( 'MDB Category Scroller: Error getting WooCommerce categories - ' . $e->getMessage() );
        }

        return $categories;
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get categories based on source type
        if ( 'woocommerce' === $settings['source_type'] ) {
            $categories = $this->get_woocommerce_category_data( $settings );
        } else {
            $categories = $this->get_custom_category_data( $settings );
        }

        if ( empty( $categories ) ) {
            echo '<div class="mdb-category-scroller-empty">' . esc_html__( 'No categories found.', 'mdb-custom-widgets' ) . '</div>';
            return;
        }

        $widget_id = 'mdb-category-scroller-' . $this->get_id();
        $widget_settings = array(
            'itemsPerView' => intval( $settings['items_per_view'] ?? 3 ),
            'itemsPerViewTablet' => intval( $settings['items_per_view_tablet'] ?? 3 ),
            'itemsPerViewMobile' => intval( $settings['items_per_view_mobile'] ?? 2 ),
            'spaceBetween' => intval( $settings['space_between']['size'] ?? 20 ),
            'autoplay' => 'yes' === $settings['autoplay'],
            'autoplaySpeed' => intval( $settings['autoplay_speed'] ?? 3000 ),
            'loop' => 'yes' === ($settings['loop'] ?? 'yes'),
        );
        ?>
        <div class="mdb-widget mdb-category-scroller-widget" 
             data-widget-type="category-scroller" 
             data-settings="<?php echo esc_attr( json_encode( $widget_settings ) ); ?>"
             id="<?php echo esc_attr( $widget_id ); ?>">
            
            <div class="mdb-category-scroller-header">
                <div class="mdb-category-scroller-navigation">
                    <button class="mdb-category-nav-btn mdb-category-prev" aria-label="<?php esc_attr_e( 'Previous categories', 'mdb-custom-widgets' ); ?>">
                        <i class="eicon-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button class="mdb-category-nav-btn mdb-category-next" aria-label="<?php esc_attr_e( 'Next categories', 'mdb-custom-widgets' ); ?>">
                        <i class="eicon-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="mdb-category-scroller-container">
                <div class="mdb-category-scroller-wrapper">
                    <?php foreach ( $categories as $category ) : ?>
                        <div class="mdb-category-slide">
                            <div class="mdb-category-item">
                                <?php if ( ! empty( $category['url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $category['url'] ); ?>" class="mdb-category-link">
                                <?php endif; ?>
                                
                                <div class="mdb-category-image">
                                    <img src="<?php echo esc_url( $category['image'] ); ?>" 
                                         alt="<?php echo esc_attr( $category['name'] ); ?>"
                                         loading="lazy">
                                </div>
                                
                                <div class="mdb-category-content">
                                    <h2 class="mdb-category-name"><?php echo esc_html( $category['name'] ); ?></h2>
                                </div>

                                <?php if ( ! empty( $category['url'] ) ) : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get WooCommerce category data
     *
     * @since 1.0.0
     * @access private
     */
    private function get_woocommerce_category_data( $settings ) {
        $categories = [];

        if ( ! class_exists( 'WooCommerce' ) ) {
            return $categories;
        }

        $args = [
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false, // Temporarily show all categories including empty ones
        ];

        // Exclude uncategorized category if setting is enabled
        if ( 'yes' === ($settings['exclude_uncategorized'] ?? 'yes') ) {
            $exclude_ids = [];
            
            // Get default WooCommerce uncategorized category
            $default_cat_id = get_option( 'default_product_cat', 0 );
            if ( $default_cat_id > 0 ) {
                $exclude_ids[] = $default_cat_id;
            }
            
            // Also exclude by slug as a fallback
            $uncategorized_term = get_term_by( 'slug', 'uncategorized', 'product_cat' );
            if ( $uncategorized_term && ! is_wp_error( $uncategorized_term ) ) {
                $exclude_ids[] = $uncategorized_term->term_id;
            }
            
            if ( ! empty( $exclude_ids ) ) {
                $args['exclude'] = array_unique( $exclude_ids );
            }
        }

        if ( ! empty( $settings['woo_categories'] ) ) {
            $args['include'] = $settings['woo_categories'];
            // Remove exclude when specific categories are selected (user choice override)
            unset( $args['exclude'] );
        } else {
            // If no specific categories selected, limit to first 12 to prevent performance issues
            $args['number'] = 12;
        }

        $terms = get_terms( $args );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                $image_url = wp_get_attachment_image_url( $thumbnail_id, 'medium' );
                
                if ( ! $image_url ) {
                    $image_url = \Elementor\Utils::get_placeholder_image_src();
                }

                $categories[] = [
                    'name' => $term->name,
                    'image' => $image_url,
                    'url' => get_term_link( $term ),
                ];
            }
        } else {
            // Fallback: Create sample categories if no WooCommerce categories found
            $placeholder_image = \Elementor\Utils::get_placeholder_image_src();
            $categories[] = [
                'name' => 'Sample Category 1',
                'image' => $placeholder_image,
                'url' => '#',
            ];
            $categories[] = [
                'name' => 'Sample Category 2', 
                'image' => $placeholder_image,
                'url' => '#',
            ];
            $categories[] = [
                'name' => 'Sample Category 3',
                'image' => $placeholder_image,
                'url' => '#',
            ];
        }

        return $categories;
    }

    /**
     * Get custom category data
     *
     * @since 1.0.0
     * @access private
     */
    private function get_custom_category_data( $settings ) {
        $categories = [];

        if ( ! empty( $settings['custom_categories'] ) ) {
            foreach ( $settings['custom_categories'] as $category ) {
                $image_url = ! empty( $category['category_image']['url'] ) 
                    ? $category['category_image']['url'] 
                    : \Elementor\Utils::get_placeholder_image_src();

                $url = ! empty( $category['category_url']['url'] ) 
                    ? $category['category_url']['url'] 
                    : '';

                $categories[] = [
                    'name' => $category['category_name'] ?? '',
                    'image' => $image_url,
                    'url' => $url,
                ];
            }
        }

        return $categories;
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
        var categories = [];
        
        if ( 'woocommerce' === settings.source_type ) {
            // For preview, show placeholder data with safe asset URL handling
            var placeholderImage = (typeof elementor !== 'undefined' && elementor.config && elementor.config.urls && elementor.config.urls.assets) 
                ? elementor.config.urls.assets + 'images/placeholder.png'
                : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNhdGVnb3J5PC90ZXh0Pjwvc3ZnPg==';
            
            categories = [
                { name: 'Category 1', image: placeholderImage, url: '#' },
                { name: 'Category 2', image: placeholderImage, url: '#' },
                { name: 'Category 3', image: placeholderImage, url: '#' },
                { name: 'Category 4', image: placeholderImage, url: '#' }
            ];
        } else {
            _.each( settings.custom_categories, function( category ) {
                var placeholderImage = (typeof elementor !== 'undefined' && elementor.config && elementor.config.urls && elementor.config.urls.assets) 
                    ? elementor.config.urls.assets + 'images/placeholder.png'
                    : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNhdGVnb3J5PC90ZXh0Pjwvc3ZnPg==';
                
                var image = category.category_image && category.category_image.url ? category.category_image.url : placeholderImage;
                var url = category.category_url && category.category_url.url ? category.category_url.url : '#';
                
                categories.push({
                    name: category.category_name || 'Category Name',
                    image: image,
                    url: url
                });
            });
        }
        
        if ( categories.length === 0 ) {
        #>
            <div class="mdb-category-scroller-empty">No categories found.</div>
        <#
            return;
        }
        #>
        
        <div class="mdb-widget mdb-category-scroller-widget">
            <div class="mdb-category-scroller-header">
                <div class="mdb-category-scroller-navigation">
                    <button class="mdb-category-nav-btn mdb-category-prev">
                        <i class="eicon-chevron-left"></i>
                    </button>
                    <button class="mdb-category-nav-btn mdb-category-next">
                        <i class="eicon-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="mdb-category-scroller-container">
                <div class="mdb-category-scroller-wrapper">
                    <# _.each( categories, function( category ) { #>
                        <div class="mdb-category-slide">
                            <div class="mdb-category-item">
                                <# if ( category.url ) { #>
                                    <a href="{{{ category.url }}}" class="mdb-category-link">
                                <# } #>
                                
                                <div class="mdb-category-image">
                                    <img src="{{{ category.image }}}" alt="{{{ category.name }}}">
                                </div>
                                
                                <div class="mdb-category-content">
                                    <h2 class="mdb-category-name">{{{ category.name }}}</h2>
                                </div>

                                <# if ( category.url ) { #>
                                    </a>
                                <# } #>
                            </div>
                        </div>
                    <# }); #>
                </div>
            </div>
        </div>
        <?php
    }
}