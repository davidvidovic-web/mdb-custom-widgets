<?php
/**
 * Plugin Name: MDB Custom Widgets
 * Description: Custom Elementor widgets for MyDirectBlinds
 * Version: 1.0.2
 * Author: David Vidovic
 * Author URI: https://davidvidovic.com
 * Text Domain: mdb-custom-widgets
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 8.2
 * Elementor tested up to: 3.16.0
 * Elementor Pro tested up to: 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Main MDB Custom Widgets Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class MDB_Custom_Widgets {

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var MDB_Custom_Widgets The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return MDB_Custom_Widgets An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {

        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n() {

        load_plugin_textdomain( 'mdb-custom-widgets' );

    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Add Plugin actions
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
        add_action( 'elementor/controls/register', [ $this, 'init_controls' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );

        // Register Widget Styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

        // Register Widget Scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'mdb-custom-widgets' ),
            '<strong>' . esc_html__( 'MDB Custom Widgets', 'mdb-custom-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'mdb-custom-widgets' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mdb-custom-widgets' ),
            '<strong>' . esc_html__( 'MDB Custom Widgets', 'mdb-custom-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'mdb-custom-widgets' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mdb-custom-widgets' ),
            '<strong>' . esc_html__( 'MDB Custom Widgets', 'mdb-custom-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'mdb-custom-widgets' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Add Elementor Widget Categories
     *
     * @since 1.0.0
     * @access public
     */
    public function add_elementor_widget_categories( $elements_manager ) {

        $elements_manager->add_category(
            'mdb-widgets',
            [
                'title' => esc_html__( 'MDB Widgets', 'mdb-custom-widgets' ),
                'icon' => 'fa fa-plug',
            ]
        );

    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_widgets( $widgets_manager ) {

        // Include Widget files
        require_once( __DIR__ . '/includes/widgets/widget-base.php' );
        require_once( __DIR__ . '/includes/widgets/slider-widget.php' );
        require_once( __DIR__ . '/includes/widgets/reviews-slider-widget.php' );
        require_once( __DIR__ . '/includes/widgets/reviews-navigation-widget.php' );
        require_once( __DIR__ . '/includes/widgets/category-scroller-widget.php' );
        require_once( __DIR__ . '/includes/widgets/instant-pricing-widget.php' );
        require_once( __DIR__ . '/includes/widgets/simple-reviews-widget.php' );
        require_once( __DIR__ . '/includes/widgets/comparison-table-widget.php' );
        require_once( __DIR__ . '/includes/widgets/unique-features-slider-widget.php' );
        require_once( __DIR__ . '/includes/widgets/measurement-guide-widget.php' );
        require_once( __DIR__ . '/includes/widgets/product-customizer-widget.php' );
        require_once( __DIR__ . '/includes/widgets/feature-grid-widget.php' );
        require_once( __DIR__ . '/includes/widgets/scroll-zoom-widget.php' );

        // Register widgets with error handling
        try {
            $widgets_manager->register( new MDB_Slider_Widget() );
            $widgets_manager->register( new MDB_Reviews_Slider_Widget() );
            $widgets_manager->register( new MDB_Reviews_Navigation_Widget() );
            $widgets_manager->register( new MDB_Category_Scroller_Widget() );
            $widgets_manager->register( new MDB_Instant_Pricing_Widget() );
            $widgets_manager->register( new MDB_Simple_Reviews_Widget() );
            $widgets_manager->register( new MDB_Comparison_Table_Widget() );
            $widgets_manager->register( new MDB_Unique_Features_Slider_Widget() );
            $widgets_manager->register( new MDB_Measurement_Guide_Widget() );
            $widgets_manager->register( new MDB_Product_Customizer_Widget() );
            $widgets_manager->register( new MDB_Feature_Grid_Widget() );
            $widgets_manager->register( new MDB_Scroll_Zoom_Widget() );
            
            // Debug: Log successful registration
            if ( WP_DEBUG ) {
                error_log( 'MDB Custom Widgets: Successfully registered widgets' );
            }
        } catch ( Exception $e ) {
            // Debug: Log any errors
            if ( WP_DEBUG ) {
                error_log( 'MDB Custom Widgets Error: ' . $e->getMessage() );
            }
        }

    }

    /**
     * Init Controls
     *
     * Include controls files and register them
     *
     * @since 1.0.2
     *
     * @access public
     */
    public function init_controls( $controls_manager ) {

        // Include Control files
        // require_once( __DIR__ . '/controls/test-control.php' );

        // Register controls
        // $controls_manager->register( new \Test_Control() );

    }

    /**
     * Widget Styles
     *
     * Load required plugin styles.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_styles() {

        wp_register_style( 'mdb-custom-widgets', plugins_url( 'assets/css/widgets.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-slider-widget', plugins_url( 'assets/css/slider-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-reviews-slider-widget', plugins_url( 'assets/css/reviews-slider-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-reviews-navigation-widget', plugins_url( 'assets/css/reviews-navigation-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-category-scroller-widget', plugins_url( 'assets/css/category-scroller-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-instant-pricing-widget', plugins_url( 'assets/css/instant-pricing-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-simple-reviews-widget', plugins_url( 'assets/css/simple-reviews-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-comparison-table-widget', plugins_url( 'assets/css/comparison-table-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-unique-features-slider-widget', plugins_url( 'assets/css/unique-features-slider-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-measurement-guide-widget', plugins_url( 'assets/css/measurement-guide-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-product-customizer-widget', plugins_url( 'assets/css/product-customizer-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-feature-grid-widget', plugins_url( 'assets/css/feature-grid-widget.css', __FILE__ ), [], self::VERSION );
        wp_register_style( 'mdb-scroll-zoom-widget', plugins_url( 'assets/css/scroll-zoom-widget.css', __FILE__ ), [], self::VERSION );

        // Enqueue main widget styles on all pages with Elementor content
        wp_enqueue_style( 'mdb-custom-widgets' );

    }

    /**
     * Widget Scripts
     *
     * Load required plugin scripts.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_scripts() {

        wp_register_script( 'mdb-custom-widgets', plugins_url( 'assets/js/widgets.js', __FILE__ ), [ 'jquery' ], self::VERSION );
        
        // Register Swiper dependency (use Elementor's Swiper if available)
        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_script( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/swiper.min.js', [], '8.4.5' );
        } else {
            // Fallback to CDN if Elementor not available
            wp_register_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@8.4.5/swiper-bundle.min.js', [], '8.4.5' );
        }
        
        wp_register_script( 'mdb-slider-widget', plugins_url( 'assets/js/slider-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets', 'swiper' ], self::VERSION );
        wp_register_script( 'mdb-reviews-slider-widget', plugins_url( 'assets/js/reviews-slider-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );
        wp_register_script( 'mdb-reviews-navigation-widget', plugins_url( 'assets/js/reviews-navigation-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );
        wp_register_script( 'mdb-category-scroller-widget', plugins_url( 'assets/js/category-scroller-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );
        wp_register_script( 'mdb-simple-reviews-widget', plugins_url( 'assets/js/simple-reviews-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets', 'swiper' ], self::VERSION );
        wp_register_script( 'mdb-instant-pricing-widget', plugins_url( 'assets/js/instant-pricing-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );        wp_register_script( 'mdb-unique-features-slider-widget', plugins_url( 'assets/js/unique-features-slider-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets', 'swiper' ], self::VERSION );        wp_register_script( 'mdb-comparison-table-widget', plugins_url( 'assets/js/comparison-table-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );
        wp_register_script( 'mdb-measurement-guide-widget', plugins_url( 'assets/js/measurement-guide-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );
        wp_register_script( 'mdb-product-customizer-widget', plugins_url( 'assets/js/product-customizer-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );
        wp_register_script( 'mdb-feature-grid-widget', plugins_url( 'assets/js/feature-grid-widget.js', __FILE__ ), [ 'jquery', 'mdb-custom-widgets' ], self::VERSION );

        // GSAP + ScrollTrigger for scroll-zoom widget
        wp_register_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', [], '3.12.5', true );
        wp_register_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', [ 'gsap' ], '3.12.5', true );
        wp_register_script( 'mdb-scroll-zoom-widget', plugins_url( 'assets/js/scroll-zoom-widget.js', __FILE__ ), [ 'gsap', 'gsap-scrolltrigger' ], self::VERSION, true );

        // Enqueue main widget script on all pages with Elementor content
        wp_enqueue_script( 'mdb-custom-widgets' );

    }

    /**
     * Add admin menu for debugging
     *
     * @since 1.0.0
     * @access public
     */
    public function add_admin_menu() {
        add_options_page(
            'MDB Custom Widgets',
            'MDB Widgets',
            'manage_options',
            'mdb-custom-widgets',
            [ $this, 'admin_page' ]
        );
    }

    /**
     * Admin page callback
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>MDB Custom Widgets Status</h1>
            
            <div class="card">
                <h2>Plugin Status</h2>
                <table class="widefat">
                    <tr>
                        <td><strong>Plugin Active:</strong></td>
                        <td><?php echo class_exists( 'MDB_Custom_Widgets' ) ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Elementor Loaded:</strong></td>
                        <td><?php echo did_action( 'elementor/loaded' ) ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Elementor Version:</strong></td>
                        <td><?php echo defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : 'Not Available'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>PHP Version:</strong></td>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                </table>
            </div>

            <?php if ( class_exists( 'Elementor\Plugin' ) ): ?>
            <div class="card">
                <h2>Registered Widgets</h2>
                <?php
                $widgets = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
                $mdb_widgets = array_filter( $widgets, function( $widget ) {
                    return strpos( get_class( $widget ), 'MDB_' ) === 0;
                });
                ?>
                <p><strong>Total MDB Widgets:</strong> <?php echo count( $mdb_widgets ); ?></p>
                
                <?php if ( ! empty( $mdb_widgets ) ): ?>
                    <ul>
                        <?php foreach ( $mdb_widgets as $widget ): ?>
                            <li>
                                <strong><?php echo esc_html( $widget->get_title() ); ?></strong> 
                                (<?php echo esc_html( $widget->get_name() ); ?>) - 
                                <?php echo esc_html( get_class( $widget ) ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: red;">No MDB widgets found! Check for errors in your error log.</p>
                <?php endif; ?>
            </div>

            <div class="card">
                <h2>Widget Categories</h2>
                <?php
                $categories = \Elementor\Plugin::instance()->elements_manager->get_categories();
                $mdb_category = isset( $categories['mdb-widgets'] ) ? $categories['mdb-widgets'] : null;
                ?>
                <p><strong>MDB Category Registered:</strong> <?php echo $mdb_category ? 'Yes' : 'No'; ?></p>
                <?php if ( $mdb_category ): ?>
                    <p><strong>Category Title:</strong> <?php echo esc_html( $mdb_category['title'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="card">
                <h2>Asset Loading Status</h2>
                <?php
                global $wp_scripts, $wp_styles;
                
        // Check registered scripts
                $mdb_scripts = [
                    'mdb-custom-widgets' => 'MDB Main Scripts',
                    'mdb-slider-widget' => 'MDB Slider Scripts',
                    'mdb-reviews-slider-widget' => 'MDB Reviews Slider Scripts',
                    'mdb-reviews-navigation-widget' => 'MDB Reviews Navigation Scripts',
                    'mdb-category-scroller-widget' => 'MDB Category Scroller Scripts',
                    'mdb-instant-pricing-widget' => 'MDB Instant Pricing Scripts',
                    'mdb-simple-reviews-widget' => 'MDB Simple Reviews Scripts',
                    'mdb-comparison-table-widget' => 'MDB Comparison Table Scripts',
                    'mdb-unique-features-slider-widget' => 'MDB Unique Features Scripts',
                    'mdb-product-customizer-widget'     => 'MDB Product Customizer Scripts'
                ];
                
                $mdb_styles = [
                    'mdb-custom-widgets' => 'MDB Main Styles', 
                    'mdb-slider-widget' => 'MDB Slider Styles',
                    'mdb-reviews-slider-widget' => 'MDB Reviews Slider Styles',
                    'mdb-category-scroller-widget' => 'MDB Category Scroller Styles',
                    'mdb-instant-pricing-widget' => 'MDB Instant Pricing Styles',
                    'mdb-simple-reviews-widget' => 'MDB Simple Reviews Styles',
                    'mdb-comparison-table-widget' => 'MDB Comparison Table Styles',
                    'mdb-unique-features-slider-widget' => 'MDB Unique Features Styles',
                    'mdb-product-customizer-widget'     => 'MDB Product Customizer Styles'
                ];
                ?>
                
                <h3>Scripts Status</h3>
                <ul>
                    <?php foreach ( $mdb_scripts as $handle => $label ): ?>
                        <li>
                            <strong><?php echo esc_html( $label ); ?>:</strong>
                            <?php if ( wp_script_is( $handle, 'registered' ) ): ?>
                                <span style="color: green;">✓ Registered</span>
                                <?php if ( wp_script_is( $handle, 'enqueued' ) ): ?>
                                    <span style="color: green;">✓ Enqueued</span>
                                <?php else: ?>
                                    <span style="color: orange;">⚠ Not Enqueued</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: red;">✗ Not Registered</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h3>Styles Status</h3>
                <ul>
                    <?php foreach ( $mdb_styles as $handle => $label ): ?>
                        <li>
                            <strong><?php echo esc_html( $label ); ?>:</strong>
                            <?php if ( wp_style_is( $handle, 'registered' ) ): ?>
                                <span style="color: green;">✓ Registered</span>
                                <?php if ( wp_style_is( $handle, 'enqueued' ) ): ?>
                                    <span style="color: green;">✓ Enqueued</span>
                                <?php else: ?>
                                    <span style="color: orange;">⚠ Not Enqueued</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: red;">✗ Not Registered</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h3>Asset File Paths</h3>
                <ul>
                    <li><strong>Main CSS:</strong> <?php echo esc_url( plugins_url( 'assets/css/widgets.css', __FILE__ ) ); ?></li>
                    <li><strong>Slider CSS:</strong> <?php echo esc_url( plugins_url( 'assets/css/slider-widget.css', __FILE__ ) ); ?></li>
                    <li><strong>Main JS:</strong> <?php echo esc_url( plugins_url( 'assets/js/widgets.js', __FILE__ ) ); ?></li>
                    <li><strong>Slider JS:</strong> <?php echo esc_url( plugins_url( 'assets/js/slider-widget.js', __FILE__ ) ); ?></li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="card">
                <h2>Troubleshooting</h2>
                <p>If widgets are not appearing:</p>
                <ol>
                    <li>Make sure Elementor is installed and activated</li>
                    <li>Check that you're using Elementor 3.0.0 or higher</li>
                    <li>Clear any caches (Elementor, WordPress, hosting)</li>
                    <li>Check your error log for PHP errors</li>
                    <li>Try deactivating and reactivating this plugin</li>
                </ol>
            </div>
        </div>
        <?php
    }

}

MDB_Custom_Widgets::instance();