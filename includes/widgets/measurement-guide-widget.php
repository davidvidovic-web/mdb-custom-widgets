<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MDB Measurement Guide Widget
 *
 * A widget displaying a background image with two video overlay cards.
 *
 * @since 1.0.0
 */
class MDB_Measurement_Guide_Widget extends MDB_Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mdb-measurement-guide';
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
		return esc_html__( 'MDB Measurement Guide', 'mdb-custom-widgets' );
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
		return 'eicon-video-playlist';
	}

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
		return [ 'measurement', 'guide', 'video', 'box' ];
	}

	/**
	 * Register widget styles.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget styles.
	 */
	public function get_style_depends() {
		return [ 'mdb-measurement-guide-widget' ];
	}

	/**
	 * Register widget scripts.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget scripts.
	 */
	public function get_script_depends() {
		return [ 'mdb-measurement-guide-widget' ];
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
			'section_content',
			[
				'label' => esc_html__( 'Content', 'mdb-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'background_image',
			[
				'label' => esc_html__( 'Background Image', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'background_height',
			[
				'label' => esc_html__( 'Height', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1000,
						'step' => 10,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 600,
				],
				'selectors' => [
					'{{WRAPPER}} .mdb-measurement-guide' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Video 1 Section
		$this->start_controls_section(
			'section_video_1',
			[
				'label' => esc_html__( 'Video 1 (Top)', 'mdb-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'video_1_title',
			[
				'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'How to Measure:', 'mdb-custom-widgets' ),
			]
		);

		$this->add_control(
			'video_1_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Face Mount Measuring', 'mdb-custom-widgets' ),
			]
		);

		$this->add_control(
			'video_1_duration',
			[
				'label' => esc_html__( 'Duration', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1:35', 'mdb-custom-widgets' ),
			]
		);

		$this->add_control(
			'video_1_link',
			[
				'label' => esc_html__( 'Video Link', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mdb-custom-widgets' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'video_1_thumbnail',
			[
				'label' => esc_html__( 'Thumbnail Color/Image', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR, // Using color for simplicity based on image, but could be image
				'default' => '#3bc0c3', // Teal color from design
			]
		);

		$this->end_controls_section();

		// Video 2 Section
		$this->start_controls_section(
			'section_video_2',
			[
				'label' => esc_html__( 'Video 2 (Bottom)', 'mdb-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'video_2_title',
			[
				'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'How to Measure:', 'mdb-custom-widgets' ),
			]
		);

		$this->add_control(
			'video_2_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Inside Mount Measuring', 'mdb-custom-widgets' ),
			]
		);

		$this->add_control(
			'video_2_duration',
			[
				'label' => esc_html__( 'Duration', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1:35', 'mdb-custom-widgets' ),
			]
		);

		$this->add_control(
			'video_2_link',
			[
				'label' => esc_html__( 'Video Link', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mdb-custom-widgets' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'video_2_thumbnail',
			[
				'label' => esc_html__( 'Thumbnail Color/Image', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#3bc0c3',
			]
		);

		$this->end_controls_section();

		// Style Tab
		$this->start_controls_section(
			'section_style_cards',
			[
				'label' => esc_html__( 'Cards', 'mdb-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'cards_position_horizontal',
			[
				'label' => esc_html__( 'Horizontal Position', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mdb-mg-videos-container' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cards_position_vertical',
			[
				'label' => esc_html__( 'Vertical Position', 'mdb-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mdb-mg-videos-container' => 'bottom: {{SIZE}}{{UNIT}};',
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

		$video_1_url = $settings['video_1_link']['url'];
		$video_2_url = $settings['video_2_link']['url'];

		$this->add_render_attribute( 'video_1_link', 'href', $video_1_url );
		$this->add_render_attribute( 'video_2_link', 'href', $video_2_url );

		if ( $settings['video_1_link']['is_external'] ) {
			$this->add_render_attribute( 'video_1_link', 'target', '_blank' );
		}
		if ( $settings['video_1_link']['nofollow'] ) {
			$this->add_render_attribute( 'video_1_link', 'rel', 'nofollow' );
		}

		if ( $settings['video_2_link']['is_external'] ) {
			$this->add_render_attribute( 'video_2_link', 'target', '_blank' );
		}
		if ( $settings['video_2_link']['nofollow'] ) {
			$this->add_render_attribute( 'video_2_link', 'rel', 'nofollow' );
		}
		?>
		<div class="mdb-measurement-guide" style="background-image: url('<?php echo esc_url( $settings['background_image']['url'] ); ?>');">
			<div class="mdb-mg-overlay"></div>
			
			<div class="mdb-mg-videos-container">
				
				<!-- Video 1 -->
				<a <?php echo $this->get_render_attribute_string( 'video_1_link' ); ?> class="mdb-mg-video-card">
					<div class="mdb-mg-video-thumbnail" style="background-color: <?php echo esc_attr( $settings['video_1_thumbnail'] ); ?>;">
						<i class="eicon-play" aria-hidden="true"></i>
					</div>
					<div class="mdb-mg-video-info">
						<h4 class="mdb-mg-video-title"><?php echo esc_html( $settings['video_1_title'] ); ?></h4>
						<p class="mdb-mg-video-subtitle"><?php echo esc_html( $settings['video_1_subtitle'] ); ?></p>
						<span class="mdb-mg-video-duration"><?php echo esc_html( $settings['video_1_duration'] ); ?></span>
					</div>
				</a>

				<!-- Video 2 -->
				<a <?php echo $this->get_render_attribute_string( 'video_2_link' ); ?> class="mdb-mg-video-card">
					<div class="mdb-mg-video-thumbnail" style="background-color: <?php echo esc_attr( $settings['video_2_thumbnail'] ); ?>;">
						<i class="eicon-play" aria-hidden="true"></i>
					</div>
					<div class="mdb-mg-video-info">
						<h4 class="mdb-mg-video-title"><?php echo esc_html( $settings['video_2_title'] ); ?></h4>
						<p class="mdb-mg-video-subtitle"><?php echo esc_html( $settings['video_2_subtitle'] ); ?></p>
						<span class="mdb-mg-video-duration"><?php echo esc_html( $settings['video_2_duration'] ); ?></span>
					</div>
				</a>

			</div>
		</div>
		<?php
	}
}
