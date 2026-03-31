<?php
/**
 * MDB Feature Grid Widget
 *
 * Responsive card grid with per-card image, title, description, and optional
 * link. Cards can optionally show an animated hover arrow.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

class MDB_Feature_Grid_Widget extends MDB_Widget_Base {

	public function get_name()  { return 'mdb-feature-grid'; }
	public function get_title() { return esc_html__( 'MDB Feature Grid', 'mdb-custom-widgets' ); }
	public function get_icon()  { return 'eicon-posts-grid'; }

	public function get_keywords() {
		return [ 'grid', 'features', 'cards', 'image', 'mdb', 'feature', 'benefits' ];
	}

	public function get_script_depends() {
		return [ 'mdb-custom-widgets', 'mdb-feature-grid-widget' ];
	}

	public function get_style_depends() {
		return [ 'mdb-custom-widgets', 'mdb-feature-grid-widget' ];
	}

	// ─────────────────────────────────────────────────────────────────────────
	// CONTROLS
	// ─────────────────────────────────────────────────────────────────────────

	protected function register_controls() {

		// ── Content: Cards ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_cards', [
			'label' => esc_html__( 'Cards', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Image', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
			'dynamic' => [ 'active' => true ],
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'mdb-custom-widgets' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Feature Title', 'mdb-custom-widgets' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'description', [
			'label'   => esc_html__( 'Description', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Briefly describe this feature in a sentence or two.', 'mdb-custom-widgets' ),
			'rows'    => 4,
			'dynamic' => [ 'active' => true ],
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Card Link', 'mdb-custom-widgets' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => esc_html__( 'https://your-link.com', 'mdb-custom-widgets' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'label_block' => true,
		] );

		$this->add_control( 'cards', [
			'label'       => esc_html__( 'Cards', 'mdb-custom-widgets' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[ 'title' => esc_html__( 'Feature One',   'mdb-custom-widgets' ), 'description' => esc_html__( 'Briefly describe this feature.', 'mdb-custom-widgets' ) ],
				[ 'title' => esc_html__( 'Feature Two',   'mdb-custom-widgets' ), 'description' => esc_html__( 'Briefly describe this feature.', 'mdb-custom-widgets' ) ],
				[ 'title' => esc_html__( 'Feature Three', 'mdb-custom-widgets' ), 'description' => esc_html__( 'Briefly describe this feature.', 'mdb-custom-widgets' ) ],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();

		// ── Content: Layout ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'columns', [
			'label'          => esc_html__( 'Columns', 'mdb-custom-widgets' ),
			'type'           => Controls_Manager::SELECT,
			'options'        => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ],
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'selectors'      => [ '{{WRAPPER}} .mdb-feature-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
		] );

		$this->add_responsive_control( 'column_gap', [
			'label'     => esc_html__( 'Column Gap', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default'   => [ 'size' => 20, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid' => 'column-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'row_gap', [
			'label'     => esc_html__( 'Row Gap', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default'   => [ 'size' => 20, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid' => 'row-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'content_align', [
			'label'     => esc_html__( 'Content Alignment', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left',   'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => esc_html__( 'Center', 'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => esc_html__( 'Right',  'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-card' => 'text-align: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Card ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_card', [
			'label' => esc_html__( 'Card', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'card_padding', [
			'label'      => esc_html__( 'Padding', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'default'    => [ 'top' => '30', 'right' => '30', 'bottom' => '30', 'left' => '30', 'unit' => 'px', 'isLinked' => true ],
			'selectors'  => [ '{{WRAPPER}} .mdb-feature-grid-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'card_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .mdb-feature-grid-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'card_style_tabs' );

		$this->start_controls_tab( 'card_style_normal', [
			'label' => esc_html__( 'Normal', 'mdb-custom-widgets' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'card_background',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .mdb-feature-grid-card',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'card_border',
			'selector' => '{{WRAPPER}} .mdb-feature-grid-card',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'card_box_shadow',
			'selector' => '{{WRAPPER}} .mdb-feature-grid-card',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'card_style_hover', [
			'label' => esc_html__( 'Hover', 'mdb-custom-widgets' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'card_hover_background',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .mdb-feature-grid-card:hover',
		] );

		$this->add_control( 'card_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-card:hover' => 'border-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'card_hover_box_shadow',
			'selector' => '{{WRAPPER}} .mdb-feature-grid-card:hover',
		] );

		$this->add_control( 'card_hover_transition', [
			'label'     => esc_html__( 'Transition Duration (ms)', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ] ],
			'default'   => [ 'size' => 300 ],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-card' => 'transition-duration: {{SIZE}}ms;' ],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── Style: Image ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_image', [
			'label' => esc_html__( 'Image', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_alignment', [
			'label'     => esc_html__( 'Alignment', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => esc_html__( 'Left',   'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => esc_html__( 'Center', 'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => esc_html__( 'Right',  'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-start',
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-image' => 'justify-content: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'image_width', [
			'label'      => esc_html__( 'Width', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 400 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
			'default'    => [ 'unit' => '%', 'size' => 100 ],
			'selectors'  => [ '{{WRAPPER}} .mdb-feature-grid-image img' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'image_height', [
			'label'      => esc_html__( 'Height', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 800 ], 'vh' => [ 'min' => 5, 'max' => 100 ] ],
			'selectors'  => [ '{{WRAPPER}} .mdb-feature-grid-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: var(--mdb-img-fit, cover);' ],
		] );

		$this->add_control( 'image_fit', [
			'label'     => esc_html__( 'Object Fit', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'cover'   => esc_html__( 'Cover',   'mdb-custom-widgets' ),
				'contain' => esc_html__('Contain', 'mdb-custom-widgets' ),
				'fill'    => esc_html__( 'Fill',    'mdb-custom-widgets' ),
				'none'    => esc_html__( 'None',    'mdb-custom-widgets' ),
			],
			'default'   => 'cover',
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-image img' => '--mdb-img-fit: {{VALUE}}; object-fit: {{VALUE}};' ],
			'condition' => [ 'image_height[size]!' => '' ],
		] );

		$this->add_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'mdb-custom-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .mdb-feature-grid-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'image_spacing', [
			'label'     => esc_html__( 'Bottom Spacing', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'   => [ 'size' => 20, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-image' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Title ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_title', [
			'label' => esc_html__( 'Title', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_tag', [
			'label'   => esc_html__( 'HTML Tag', 'mdb-custom-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [ 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'p' => 'p', 'span' => 'span' ],
			'default' => 'h3',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .mdb-feature-grid-title',
		] );

		$this->add_responsive_control( 'title_spacing', [
			'label'     => esc_html__( 'Bottom Spacing', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 12, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-title' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'title_align', [
			'label'     => esc_html__( 'Alignment', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left',   'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => esc_html__( 'Center', 'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => esc_html__( 'Right',  'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-title' => 'text-align: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Description ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_description', [
			'label' => esc_html__( 'Description', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-description' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'selector' => '{{WRAPPER}} .mdb-feature-grid-description',
		] );

		$this->add_responsive_control( 'description_align', [
			'label'     => esc_html__( 'Alignment', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left',   'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => esc_html__( 'Center', 'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => esc_html__( 'Right',  'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-description' => 'text-align: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Hover Arrow ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_arrow', [
			'label' => esc_html__( 'Hover Arrow', 'mdb-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'show_hover_arrow', [
			'label'        => esc_html__( 'Show Arrow on Hover', 'mdb-custom-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'mdb-custom-widgets' ),
			'label_off'    => esc_html__( 'No', 'mdb-custom-widgets' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'arrow_color', [
			'label'     => esc_html__( 'Color', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#4ecdc4',
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-arrow' => 'color: {{VALUE}};' ],
			'condition' => [ 'show_hover_arrow' => 'yes' ],
		] );

		$this->add_control( 'arrow_size', [
			'label'     => esc_html__( 'Size', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
			'default'   => [ 'size' => 24, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
			'condition' => [ 'show_hover_arrow' => 'yes' ],
		] );

		$this->add_responsive_control( 'arrow_alignment', [
			'label'     => esc_html__( 'Alignment', 'mdb-custom-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => esc_html__( 'Left',   'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => esc_html__( 'Center', 'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => esc_html__( 'Right',  'mdb-custom-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [ '{{WRAPPER}} .mdb-feature-grid-arrow' => 'justify-content: {{VALUE}};' ],
			'condition' => [ 'show_hover_arrow' => 'yes' ],
		] );

		$this->end_controls_section();
	}

	// ─────────────────────────────────────────────────────────────────────────
	// RENDER
	// ─────────────────────────────────────────────────────────────────────────

	protected function render() {
		$settings   = $this->get_settings_for_display();
		$cards      = ! empty( $settings['cards'] ) ? $settings['cards'] : [];
		$show_arrow = ! empty( $settings['show_hover_arrow'] ) && 'yes' === $settings['show_hover_arrow'];
		$title_tag  = ! empty( $settings['title_tag'] ) ? tag_escape( $settings['title_tag'] ) : 'h3';
		?>
		<div class="mdb-feature-grid-wrapper">
			<div class="mdb-feature-grid">
				<?php foreach ( $cards as $index => $card ) :
					$has_link   = ! empty( $card['link']['url'] );
					$card_tag   = $has_link ? 'a' : 'div';
					$card_key   = 'card_' . $index;
					$card_class = 'mdb-feature-grid-card elementor-repeater-item-' . esc_attr( $card['_id'] );
					if ( $show_arrow ) { $card_class .= ' has-hover-arrow'; }

					$this->add_render_attribute( $card_key, 'class', $card_class );
					if ( $has_link ) {
						$this->add_link_attributes( $card_key, $card['link'] );
					}
				?>
					<<?php echo esc_attr( $card_tag ); ?> <?php echo $this->get_render_attribute_string( $card_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

						<?php if ( ! empty( $card['image']['url'] ) ) : ?>
							<div class="mdb-feature-grid-image">
								<?php
								$img = wp_get_attachment_image(
									absint( $card['image']['id'] ),
									'large',
									false,
									[ 'alt' => esc_attr( $card['title'] ), 'loading' => 'lazy' ]
								);
								if ( $img ) {
									echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									printf(
										'<img src="%s" alt="%s" loading="lazy">',
										esc_url( $card['image']['url'] ),
										esc_attr( $card['title'] )
									);
								}
								?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $card['title'] ) ) : ?>
							<<?php echo esc_attr( $title_tag ); ?> class="mdb-feature-grid-title">
								<?php echo esc_html( $card['title'] ); ?>
							</<?php echo esc_attr( $title_tag ); ?>>
						<?php endif; ?>

						<?php if ( ! empty( $card['description'] ) ) : ?>
							<p class="mdb-feature-grid-description"><?php echo wp_kses_post( $card['description'] ); ?></p>
						<?php endif; ?>

						<?php if ( $show_arrow ) : ?>
							<div class="mdb-feature-grid-arrow" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
									<line x1="12" y1="5" x2="12" y2="19"></line>
									<polyline points="19 12 12 19 5 12"></polyline>
								</svg>
							</div>
						<?php endif; ?>

					</<?php echo esc_attr( $card_tag ); ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	// ─────────────────────────────────────────────────────────────────────────
	// EDITOR PREVIEW TEMPLATE
	// ─────────────────────────────────────────────────────────────────────────

	protected function content_template() {
		?>
		<#
		const showArrow = settings.show_hover_arrow === 'yes';
		const cardClass = 'mdb-feature-grid-card' + ( showArrow ? ' has-hover-arrow' : '' );
		const titleTag  = settings.title_tag || 'h3';
		const arrowSvg  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>';
		#>
		<div class="mdb-feature-grid-wrapper">
			<div class="mdb-feature-grid">
				<# _.each( settings.cards, function( card ) { #>
					<div class="{{ cardClass }}">
						<# if ( card.image && card.image.url ) { #>
							<div class="mdb-feature-grid-image">
								<img src="{{ card.image.url }}" alt="{{ card.title }}" loading="lazy">
							</div>
						<# } #>
						<# if ( card.title ) { #>
							<{{{ titleTag }}} class="mdb-feature-grid-title">{{{ card.title }}}</{{{ titleTag }}}>
						<# } #>
						<# if ( card.description ) { #>
							<p class="mdb-feature-grid-description">{{{ card.description }}}</p>
						<# } #>
						<# if ( showArrow ) { #>
							<div class="mdb-feature-grid-arrow" aria-hidden="true">{{{ arrowSvg }}}</div>
						<# } #>
					</div>
				<# } ); #>
			</div>
		</div>
		<?php
	}
}
