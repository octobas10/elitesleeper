<?php
/**
 * HLM page hero section.
 *
 * @package HLM_Kadence
 *
 * @var array $args Template arguments.
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'id'               => 'hlm-page-hero',
		'overline'         => '',
		'headline'         => '',
		'description'      => '',
		'icon'             => '',
		'tags'             => '',
		'headline_tag'     => 'h1',
		'headline_class'   => 'hlm-h1',
		'animate'          => true,
		'show_breadcrumbs' => true,
		'primary_cta_text' => '',
		'primary_cta_url'  => '/contact-us/#form',
		'media_type'       => 'image',
		'media_image_url'  => '',
		'media_image_id'   => 0,
		'media_image_alt'  => '',
		'media_video_url'  => '',
		'media_poster_url'        => '',
		'media_placeholder_label' => HLM_HERO_VIDEO_PLACEHOLDER_LABEL,
		'media_show_overlay'      => true,
		'media_overlay_strength'  => 'medium',
	)
);

if ( '' === $args['headline'] ) {
	return;
}
?>

<section id="<?php echo esc_attr( $args['id'] ); ?>" class="hlm-page-hero">
	<?php
	hlm_render_section_media(
		array(
			'type'              => $args['media_type'],
			'image_url'         => $args['media_image_url'],
			'image_id'          => (int) $args['media_image_id'],
			'image_alt'         => $args['media_image_alt'],
			'video_url'         => $args['media_video_url'],
			'poster_url'        => $args['media_poster_url'],
			'wrapper_class'     => 'hlm-page-hero__media',
			'inner_class'       => 'hlm-page-hero__media-placeholder',
			'placeholder_label' => $args['media_placeholder_label'],
			'show_overlay'      => (bool) $args['media_show_overlay'],
			'overlay_strength'  => $args['media_overlay_strength'],
		)
	);
	?>
	<div class="hlm-page-hero__bg" aria-hidden="true"></div>
	<div class="hlm-page-hero__grid" aria-hidden="true"></div>
	<div class="hlm-container hlm-page-hero__inner<?php echo $args['animate'] ? ' hlm-animate' : ''; ?>">
		<?php if ( $args['show_breadcrumbs'] && ! is_front_page() ) : ?>
			<div class="hlm-page-hero__breadcrumbs">
				<?php hlm_kadence_render_hero_breadcrumbs(); ?>
			</div>
		<?php endif; ?>
		<?php if ( $args['icon'] ) : ?>
			<div class="hlm-page-hero__icon" aria-hidden="true"><?php echo hlm_render_icon( $args['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
		<?php if ( $args['overline'] ) : ?>
			<div class="hlm-overline"><?php echo esc_html( $args['overline'] ); ?></div>
		<?php endif; ?>
		<?php hlm_block_headline( $args['headline'], $args['headline_class'], $args['headline_tag'] ); ?>
		<?php if ( $args['description'] ) : ?>
			<p class="hlm-body-lg"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php if ( $args['primary_cta_text'] && $args['primary_cta_url'] ) : ?>
			<div class="hlm-page-hero__cta">
				<a href="<?php echo esc_url( $args['primary_cta_url'] ); ?>" class="hlm-btn hlm-btn-gold hlm-btn-arrow">
					<?php echo esc_html( $args['primary_cta_text'] ); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( $args['tags'] ) : ?>
			<div class="hlm-page-hero__tags">
				<?php foreach ( array_map( 'trim', explode( ',', (string) $args['tags'] ) ) as $tag ) : ?>
					<?php if ( '' === $tag ) { continue; } ?>
					<span class="hlm-card__tag"><?php echo esc_html( $tag ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php hlm_kadence_render_seo_hero_links(); ?>
	</div>
</section>
