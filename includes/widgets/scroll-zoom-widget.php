<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

/**
 * MDB Scroll Zoom Widget
 *
 * Displays a heading above an image that scales from a small initial size
 * to 100% as the user scrolls, powered by GSAP ScrollTrigger.
 */
class MDB_Scroll_Zoom_Widget extends MDB_Widget_Base {

	public function get_name()  { return 'mdb-scroll-zoom'; }
	public function get_title() { return esc_html__( 'MDB Scroll Zoom', 'mdb-custom-widgets' ); }
	public function get_icon()  { return 'eicon-image-rollover'; }

	public function get_keywords() {
		return [ 'scroll', 'zoom', 'scale', 'animation', 'parallax', 'gsap', 'image', 'mdb' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'mdb-scroll-zoom-widget' ];
	}

	public function get_style_depends() {
		return [ 'mdb-custom-widgets', 'mdb-scroll-zoom-widget' ];
	}

	// ─────────────────────────────────────────────────────────────────────────
	// CONTROLS
	// ─────────────────────────────────────────────────────────────────────────

	protected function register_controls() {

		// ── Content ──────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'       => esc_html__( 'Heading', 'mdb-custom-widgets' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( "Our blind's won't fray", 'mdb-custom-widgets' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'heading_tag', [
			'label'   => esc_html__( 'HTML Tag', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
				'p'  => 'p',
			],
			'default' => 'h2',
		] );

		$this->add_control( 'image', [
			'label'   => esc_html__( 'Image', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'image_alt', [
			'label'       => esc_html__( 'Image Alt Text', 'mdb-custom-widgets' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$this->end_controls_section();

		// ── Animation Settings ────────────────────────────────────────────────
		$this->start_controls_section( 'section_animation', [
			'label' => esc_html__( 'Animation', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'initial_scale', [
			'label'   => esc_html__( 'Initial Scale (%)', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 1, 'max' => 50 ] ],
			'default' => [ 'size' => 10 ],
			'description' => esc_html__( 'How small the image starts before the scroll animation begins.', 'mdb-custom-widgets' ),
		] );

		$this->add_control( 'scrub_speed', [
			'label'   => esc_html__( 'Scrub Smoothness', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 1, 'max' => 10 ] ],
			'default' => [ 'size' => 2 ],
			'description' => esc_html__( 'Higher = smoother / slower response to scroll.', 'mdb-custom-widgets' ),
		] );

		$this->add_control( 'travel_y', [
			'label'       => esc_html__( 'Travel Distance (px)', 'mdb-custom-widgets' ),
			'type'        => Controls_Manager::SLIDER,
			'range'       => [ 'px' => [ 'min' => 0, 'max' => 400 ] ],
			'default'     => [ 'size' => 150 ],
			'description' => esc_html__( 'How many pixels the image travels downward while growing. 0 = scale only.', 'mdb-custom-widgets' ),
		] );

		$this->end_controls_section();

		// ── Style: Section ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_section', [
			'label' => esc_html__( 'Section', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'section_padding', [
			'label'      => esc_html__( 'Padding', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'vw' ],
			'default'    => [
				'top'    => '80',
				'right'  => '20',
				'bottom' => '80',
				'left'   => '20',
				'unit'   => 'px',
				'isLinked' => false,
			],
			'selectors'  => [ '{{WRAPPER}} .mdb-scroll-zoom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'section_background', [
			'label'     => esc_html__( 'Background Color', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f5f5f5',
			'selectors' => [ '{{WRAPPER}} .mdb-scroll-zoom' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Heading ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_heading', [
			'label' => esc_html__( 'Heading', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .mdb-scroll-zoom__heading',
		] );

		$this->add_control( 'heading_color', [
			'label'     => esc_html__( 'Color', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#3d3d3d',
			'selectors' => [ '{{WRAPPER}} .mdb-scroll-zoom__heading' => 'color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'heading_margin', [
			'label'      => esc_html__( 'Margin Bottom', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default'    => [ 'size' => 32, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .mdb-scroll-zoom__heading' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'heading_align', [
			'label'     => esc_html__( 'Alignment', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left',   'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => esc_html__( 'Center', 'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => esc_html__( 'Right',  'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [ '{{WRAPPER}} .mdb-scroll-zoom__heading' => 'text-align: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Image ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_image', [
			'label' => esc_html__( 'Image', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_max_width', [
			'label'      => esc_html__( 'Max Width', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px'  => [ 'min' => 100, 'max' => 1200 ],
				'%'   => [ 'min' => 10,  'max' => 100 ],
				'vw'  => [ 'min' => 10,  'max' => 100 ],
			],
			'default'    => [ 'size' => 600, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .mdb-scroll-zoom__image-wrap' => 'max-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%', 'isLinked' => true ],
			'selectors'  => [ '{{WRAPPER}} .mdb-scroll-zoom__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

	}

	// ─────────────────────────────────────────────────────────────────────────
	// RENDER
	// ─────────────────────────────────────────────────────────────────────────

	protected function render() {
		$settings = $this->get_settings_for_display();

		$heading       = $settings['heading'] ?? '';
		$heading_tag   = $settings['heading_tag'] ?? 'h2';
		$image_url     = $settings['image']['url'] ?? '';
		$image_alt     = ! empty( $settings['image_alt'] ) ? $settings['image_alt'] : '';
		$initial_scale = ( $settings['initial_scale']['size'] ?? 10 ) / 100;
		$scrub_speed   = $settings['scrub_speed']['size'] ?? 2;
		$travel_y      = $settings['travel_y']['size'] ?? 150;

		// Sanitize heading tag
		$allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ];
		if ( ! in_array( $heading_tag, $allowed_tags, true ) ) {
			$heading_tag = 'h2';
		}

		$widget_id = $this->get_id();

		$data_attrs = sprintf(
			'data-initial-scale="%s" data-scrub="%s" data-travel-y="%s"',
			esc_attr( $initial_scale ),
			esc_attr( $scrub_speed ),
			esc_attr( $travel_y )
		);
		?>
		<div class="mdb-scroll-zoom" id="mdb-scroll-zoom-<?php echo esc_attr( $widget_id ); ?>" <?php echo $data_attrs; ?>>
			<?php if ( $heading ) : ?>
				<<?php echo esc_attr( $heading_tag ); ?> class="mdb-scroll-zoom__heading">
					<?php echo wp_kses_post( $heading ); ?>
				</<?php echo esc_attr( $heading_tag ); ?>>
			<?php endif; ?>

			<?php if ( $image_url ) : ?>
				<div class="mdb-scroll-zoom__image-wrap">
					<img
						class="mdb-scroll-zoom__image"
						src="<?php echo esc_url( $image_url ); ?>"
						alt="<?php echo esc_attr( $image_alt ); ?>"
						loading="lazy"
					/>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

}
