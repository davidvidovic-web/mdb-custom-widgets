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

		// Videos Repeater Section
		$this->start_controls_section(
			'section_videos',
			[
				'label' => esc_html__( 'Videos', 'mdb-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'video_title',
			[
				'label'   => esc_html__( 'Title', 'mdb-custom-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'How to Measure:', 'mdb-custom-widgets' ),
			]
		);

		$repeater->add_control(
			'video_subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'mdb-custom-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Measuring Guide', 'mdb-custom-widgets' ),
			]
		);

		$repeater->add_control(
			'video_duration',
			[
				'label'   => esc_html__( 'Duration', 'mdb-custom-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1:35', 'mdb-custom-widgets' ),
			]
		);

		$repeater->add_control(
			'video_link',
			[
				'label'       => esc_html__( 'Video Link', 'mdb-custom-widgets' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mdb-custom-widgets' ),
				'default'     => [ 'url' => '#' ],
			]
		);

		$repeater->add_control(
			'video_thumbnail_image',
			[
				'label'       => esc_html__( 'Thumbnail Image', 'mdb-custom-widgets' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => esc_html__( 'If set, overrides the thumbnail color.', 'mdb-custom-widgets' ),
			]
		);

		$repeater->add_control(
			'video_thumbnail',
			[
				'label'   => esc_html__( 'Thumbnail Color', 'mdb-custom-widgets' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#3bc0c3',
			]
		);

		$this->add_control(
			'videos',
			[
				'label'       => esc_html__( 'Videos', 'mdb-custom-widgets' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'video_title'    => esc_html__( 'How to Measure:', 'mdb-custom-widgets' ),
						'video_subtitle' => esc_html__( 'Face Mount Measuring', 'mdb-custom-widgets' ),
						'video_duration' => '1:35',
						'video_thumbnail' => '#3bc0c3',
					],
					[
						'video_title'    => esc_html__( 'How to Measure:', 'mdb-custom-widgets' ),
						'video_subtitle' => esc_html__( 'Inside Mount Measuring', 'mdb-custom-widgets' ),
						'video_duration' => '1:35',
						'video_thumbnail' => '#3bc0c3',
					],
				],
				'title_field' => '{{{ video_title }}} — {{{ video_subtitle }}}',
				'min_items'   => 1,
				'max_items'   => 5,
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
		$videos   = $settings['videos'] ?? [];
		?>
		<div class="mdb-measurement-guide" style="background-image: url('<?php echo esc_url( $settings['background_image']['url'] ); ?>');">
			<div class="mdb-mg-overlay"></div>

			<div class="mdb-mg-videos-container">

				<?php foreach ( $videos as $index => $video ) :
					$link_key = 'video_link_' . $index;
					$video_url = ! empty( $video['video_link']['url'] ) ? $video['video_link']['url'] : '#';
					$this->add_render_attribute( $link_key, 'href', $video_url );
					$this->add_render_attribute( $link_key, 'class', 'mdb-mg-video-card' );
					$this->add_render_attribute( $link_key, 'data-video-url', $video_url );
					if ( ! empty( $video['video_link']['nofollow'] ) ) {
						$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
					}
					// Thumbnail: image takes priority over color
					$has_thumb_img = ! empty( $video['video_thumbnail_image']['url'] );
					$thumb_style   = $has_thumb_img ? '' : 'background-color: ' . esc_attr( $video['video_thumbnail'] ) . ';';
				?>
				<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
					<div class="mdb-mg-video-thumbnail" style="<?php echo $thumb_style; ?>">
						<?php if ( $has_thumb_img ) : ?>
							<img src="<?php echo esc_url( $video['video_thumbnail_image']['url'] ); ?>" alt="" loading="lazy">
						<?php endif; ?>
						<i class="eicon-play" aria-hidden="true"></i>
					</div>
					<div class="mdb-mg-video-info">
						<h4 class="mdb-mg-video-title"><?php echo esc_html( $video['video_title'] ); ?></h4>
						<p class="mdb-mg-video-subtitle"><?php echo esc_html( $video['video_subtitle'] ); ?></p>
						<span class="mdb-mg-video-duration"><?php echo esc_html( $video['video_duration'] ); ?></span>
					</div>
				</a>
				<?php endforeach; ?>

			</div>
		</div>
		<?php
	}
}
