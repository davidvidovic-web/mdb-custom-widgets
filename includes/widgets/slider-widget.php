<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor is loaded
if (! class_exists('\Elementor\Widget_Base')) {
    return;
}

/**
 * MDB Slider Widget
 *
 * Custom slider widget with split-screen layout and triple-image display
 *
 * @since 1.0.0
 */
class MDB_Slider_Widget extends MDB_Widget_Base
{

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'mdb-slider';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('MDB Slider', 'mdb-custom-widgets');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-media-carousel';
    }

    /**
     * Get widget keywords.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['slider', 'carousel', 'images', 'mdb', 'content'];
    }

    /**
     * Register widget script dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends()
    {
        return ['mdb-custom-widgets', 'mdb-slider-widget', 'swiper'];
    }

    /**
     * Register widget style dependencies.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends()
    {
        return ['mdb-custom-widgets', 'mdb-slider-widget'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        // Content Tab - Slides
        $this->start_controls_section(
            'slides_section',
            [
                'label' => esc_html__('Slides', 'mdb-custom-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_title',
            [
                'label' => esc_html__('Slide Title', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Slide Title', 'mdb-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'image_1',
            [
                'label' => esc_html__('Image 1 (Left)', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'image_2',
            [
                'label' => esc_html__('Image 2 (Center)', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'image_3',
            [
                'label' => esc_html__('Image 3 (Right)', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'slide_text',
            [
                'label' => esc_html__('Slide Text', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => esc_html__('Enter your slide content here...', 'mdb-custom-widgets'),
                'placeholder' => esc_html__('Type your text here', 'mdb-custom-widgets'),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Learn More', 'mdb-custom-widgets'),
                'placeholder' => esc_html__('Enter button text', 'mdb-custom-widgets'),
            ]
        );

        $repeater->add_control(
            'button_url',
            [
                'label' => esc_html__('Button URL', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mdb-custom-widgets'),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => esc_html__('Slides', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_title' => esc_html__('Slide #1', 'mdb-custom-widgets'),
                    ],
                    [
                        'slide_title' => esc_html__('Slide #2', 'mdb-custom-widgets'),
                    ],
                    [
                        'slide_title' => esc_html__('Slide #3', 'mdb-custom-widgets'),
                    ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->end_controls_section();

        // Content Tab - Slider Settings
        $this->start_controls_section(
            'slider_settings_section',
            [
                'label' => esc_html__('Slider Settings', 'mdb-custom-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Auto-play', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mdb-custom-widgets'),
                'label_off' => esc_html__('No', 'mdb-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Auto-play Speed (ms)', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 100,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => esc_html__('Transition Speed (ms)', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 500,
                'min' => 100,
                'max' => 2000,
                'step' => 50,
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mdb-custom-widgets'),
                'label_off' => esc_html__('No', 'mdb-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop_slides',
            [
                'label' => esc_html__('Loop Slides', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mdb-custom-widgets'),
                'label_off' => esc_html__('No', 'mdb-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Content Tab - Progress & Counter
        $this->start_controls_section(
            'progress_section',
            [
                'label' => esc_html__('Progress & Counter', 'mdb-custom-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_progress_bars',
            [
                'label' => esc_html__('Show Progress Bars', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mdb-custom-widgets'),
                'label_off' => esc_html__('Hide', 'mdb-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_slide_counter',
            [
                'label' => esc_html__('Show Slide Counter', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mdb-custom-widgets'),
                'label_off' => esc_html__('Hide', 'mdb-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'counter_format',
            [
                'label' => esc_html__('Counter Format', 'mdb-custom-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'x_of_y' => esc_html__('X of Y', 'mdb-custom-widgets'),
                    'x_slash_y' => esc_html__('X / Y', 'mdb-custom-widgets'),
                    'x_pipe_y' => esc_html__('X | Y', 'mdb-custom-widgets'),
                ],
                'default' => 'x_of_y',
                'condition' => [
                    'show_slide_counter' => 'yes',
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $slides = $settings['slides'];

        if (empty($slides)) {
            return;
        }

        $widget_id = 'mdb-slider-' . $this->get_id();
        // Add data attributes for JavaScript configuration
        $slide_count = count($slides);
        $widget_settings = array(
            'autoplay' => 'yes' === $settings['autoplay'],
            'autoplay_speed' => intval($settings['autoplay_speed'] ?? 3000),
            'transition_speed' => intval($settings['transition_speed'] ?? 500),
            'pause_on_hover' => 'yes' === ($settings['pause_on_hover'] ?? 'yes'),
            'loop' => 'yes' === ($settings['loop_slides'] ?? 'yes'),
            'totalSlides' => $slide_count,
            'counter_format' => $settings['counter_format'] ?? 'x_of_y'
        );
?>
        <div class="mdb-widget mdb-slider-widget"
            data-widget-type="slider"
            data-settings="<?php echo esc_attr(json_encode($widget_settings)); ?>"
            id="<?php echo esc_attr($widget_id); ?>">
            <div class="mdb-slider-container">
                <div class="mdb-slider-content">
                    <div class="mdb-slider-content-inner">
                        <?php foreach ($slides as $index => $slide) : ?>
                            <div class="mdb-slide-content <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>">
                                <div class="mdb-slide-content-wrapper">
                                    <?php if (! empty($slide['slide_title'])) : ?>
                                        <h3 class="mdb-slide-title"><?php echo esc_html($slide['slide_title']); ?></h3>
                                    <?php endif; ?>



                                    <?php if (! empty($slide['button_text'])) : ?>
                                        <?php
                                        $button_url = ! empty($slide['button_url']['url']) ? $slide['button_url']['url'] : '#';
                                        $target = ! empty($slide['button_url']['is_external']) ? ' target="_blank"' : '';
                                        $nofollow = ! empty($slide['button_url']['nofollow']) ? ' rel="nofollow"' : '';
                                        ?>
                                        <div class="mdb-slide-button-wrapper">
                                            <a href="<?php echo esc_url($button_url); ?>" class="mdb-slide-button" <?php echo $target . $nofollow; ?>>
                                                <?php echo esc_html($slide['button_text']); ?>
                                            </a>
                                            <?php if (! empty($slide['slide_text'])) : ?>
                                                <div class="mdb-slide-text">
                                                    <?php echo wp_kses_post($slide['slide_text']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ('yes' === ($settings['show_progress_bars'] ?? 'yes') || 'yes' === ($settings['show_slide_counter'] ?? 'yes')) : ?>
                        <div class="mdb-slider-controls">
                            <?php if ('yes' === ($settings['show_progress_bars'] ?? 'yes')) : ?>
                                <div class="mdb-progress-bars">
                                    <?php foreach ($slides as $index => $slide) : ?>
                                        <div class="mdb-progress-bar <?php echo $index === 0 ? 'active' : ''; ?>"
                                            data-slide="<?php echo esc_attr($index); ?>"
                                            role="button"
                                            tabindex="0"
                                            aria-label="<?php echo esc_attr(sprintf(__('Go to slide %d', 'mdb-custom-widgets'), $index + 1)); ?>"></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ('yes' === ($settings['show_slide_counter'] ?? 'yes')) : ?>
                                <div class="mdb-slide-counter">
                                    <?php echo $this->get_counter_text(1, count($slides), $settings['counter_format'] ?? 'x_of_y'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mdb-slider-images">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <div class="mdb-slide-images <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>">
                            <div class="mdb-slider-images-container">
                                <?php if (! empty($slide['image_1']['url'])) : ?>
                                    <div class="mdb-image-wrapper mdb-image-1-wrapper">
                                        <img src="<?php echo esc_url($slide['image_1']['url']); ?>"
                                            alt="<?php echo esc_attr($slide['image_1']['alt'] ?? $slide['slide_title'] . ' - Image 1'); ?>"
                                            class="mdb-slider-image mdb-image-1"
                                            loading="lazy">
                                    </div>
                                <?php endif; ?>

                                <?php if (! empty($slide['image_2']['url'])) : ?>
                                    <div class="mdb-image-wrapper mdb-image-2-wrapper">
                                        <img src="<?php echo esc_url($slide['image_2']['url']); ?>"
                                            alt="<?php echo esc_attr($slide['image_2']['alt'] ?? $slide['slide_title'] . ' - Image 2'); ?>"
                                            class="mdb-slider-image mdb-image-2"
                                            loading="lazy">
                                    </div>
                                <?php endif; ?>

                                <?php if (! empty($slide['image_3']['url'])) : ?>
                                    <div class="mdb-image-wrapper mdb-image-3-wrapper">
                                        <img src="<?php echo esc_url($slide['image_3']['url']); ?>"
                                            alt="<?php echo esc_attr($slide['image_3']['alt'] ?? $slide['slide_title'] . ' - Image 3'); ?>"
                                            class="mdb-slider-image mdb-image-3"
                                            loading="lazy">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Configuration for JavaScript (fallback functionality) -->
            <script type="application/json" class="mdb-slider-config">
                <?php
                // Ensure totalSlides is included in JSON config as well
                $json_config = $widget_settings;
                $json_config['totalSlides'] = $slide_count;
                echo json_encode($json_config, JSON_UNESCAPED_SLASHES);
                ?>
            </script>
        </div>
    <?php
    }

    /**
     * Get counter text based on format
     *
     * @since 1.0.0
     * @access private
     */
    private function get_counter_text($current, $total, $format)
    {
        switch ($format) {
            case 'x_slash_y':
                return $current . ' / ' . $total;
            case 'x_pipe_y':
                return $current . ' | ' . $total;
            case 'x_of_y':
            default:
                return $current . ' of ' . $total;
        }
    }

    /**
     * Render widget output in the editor.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function content_template()
    {
    ?>
        <#
            var slides=settings.slides;
            if ( slides.length===0 ) {
            return;
            }

            function getCounterText( current, total, format ) {
            switch ( format ) {
            case 'x_slash_y' :
            return current + ' / ' + total;
            case 'x_pipe_y' :
            return current + ' | ' + total;
            case 'x_of_y' :
            default:
            return current + ' of ' + total;
            }
            }
            #>
            <div class="mdb-widget mdb-slider-widget" data-widget-type="slider">
                <div class="mdb-slider-container">
                    <div class="mdb-slider-content">
                        <div class="mdb-slider-content-inner">
                            <# _.each( slides, function( slide, index ) { #>
                                <div class="mdb-slide-content {{{ index === 0 ? 'active' : '' }}}" data-slide="{{{ index }}}">
                                    <div class="mdb-slide-content-wrapper">
                                        <# if ( slide.slide_title ) { #>
                                            <h3 class="mdb-slide-title">{{{ slide.slide_title }}}</h3>
                                            <# } #>

                                                <# if ( slide.button_text ) { #>
                                                    <# var button_url=slide.button_url && slide.button_url.url ? slide.button_url.url : '#' ; #>
                                                        <# var target=slide.button_url && slide.button_url.is_external ? ' target="_blank"' : '' ; #>
                                                            <# var nofollow=slide.button_url && slide.button_url.nofollow ? ' rel="nofollow"' : '' ; #>
                                                                <div class="mdb-slide-button-wrapper">
                                                                    <a href="{{{ button_url }}}" class="mdb-slide-button" {{{ target }}}{{{ nofollow }}}>
                                                                        {{{ slide.button_text }}}
                                                                    </a>
                                                                    <# if ( slide.slide_text ) { #>
                                                                        <div class="mdb-slide-text">
                                                                            {{{ slide.slide_text }}}
                                                                        </div>
                                                                        <# } #>
                                                                </div>
                                                                <# } #>
                                    </div>
                                </div>
                                <# } ); #>
                        </div>

                        <# if ( 'yes'===settings.show_progress_bars || 'yes'===settings.show_slide_counter ) { #>
                            <div class="mdb-slider-controls">
                                <# if ( 'yes'===settings.show_progress_bars ) { #>
                                    <div class="mdb-progress-bars">
                                        <# _.each( slides, function( slide, index ) { #>
                                            <div class="mdb-progress-bar {{{ index === 0 ? 'active' : '' }}}" data-slide="{{{ index }}}"></div>
                                            <# } ); #>
                                    </div>
                                    <# } #>

                                        <# if ( 'yes'===settings.show_slide_counter ) { #>
                                            <div class="mdb-slide-counter">
                                                {{{ getCounterText( 1, slides.length, settings.counter_format ) }}}
                                            </div>
                                            <# } #>
                            </div>
                            <# } #>
                    </div>

                    <div class="mdb-slider-images">
                        <# _.each( slides, function( slide, index ) { #>
                            <div class="mdb-slide-images {{{ index === 0 ? 'active' : '' }}}" data-slide="{{{ index }}}">
                                <div class="mdb-slider-images-container">
                                    <# if ( slide.image_1 && slide.image_1.url ) { #>
                                        <div class="mdb-image-wrapper mdb-image-1-wrapper">
                                            <img src="{{{ slide.image_1.url }}}"
                                                alt="{{{ slide.image_1.alt || slide.slide_title + ' - Image 1' }}}"
                                                class="mdb-slider-image mdb-image-1">
                                        </div>
                                        <# } #>

                                            <# if ( slide.image_2 && slide.image_2.url ) { #>
                                                <div class="mdb-image-wrapper mdb-image-2-wrapper">
                                                    <img src="{{{ slide.image_2.url }}}"
                                                        alt="{{{ slide.image_2.alt || slide.slide_title + ' - Image 2' }}}"
                                                        class="mdb-slider-image mdb-image-2">
                                                </div>
                                                <# } #>

                                                    <# if ( slide.image_3 && slide.image_3.url ) { #>
                                                        <div class="mdb-image-wrapper mdb-image-3-wrapper">
                                                            <img src="{{{ slide.image_3.url }}}"
                                                                alt="{{{ slide.image_3.alt || slide.slide_title + ' - Image 3' }}}"
                                                                class="mdb-slider-image mdb-image-3">
                                                        </div>
                                                        <# } #>
                                </div>
                            </div>
                            <# } ); #>
                    </div>
                </div>
            </div>
    <?php
    }
}
