<?php
/**
 * Internal page link groups - layout variants for theme integration.
 *
 * @package HLM_SEO
 *
 * @var array $args Template args.
 */

defined( 'ABSPATH' ) || exit;

$links   = $args['links'] ?? array();
$variant = $args['variant'] ?? 'strip';
$label   = $args['label'] ?? '';

if ( empty( $links ) ) {
	return;
}

$aria = $label ?: __( 'Related pages', 'hlm-seo' );

if ( 'hero' === $variant ) :
	$link = $links[0];
	$label_text = function_exists( 'hlm_kadence_format_seo_link_label' )
		? hlm_kadence_format_seo_link_label( $link['label'] )
		: $link['label'];
	?>
	<div class="hlm-seo-page-links hlm-seo-page-links--hero">
		<a class="hlm-btn hlm-btn--outline hlm-btn--compact hlm-btn-arrow" href="<?php echo esc_url( $link['url'] ); ?>">
			<?php echo esc_html( $label_text ); ?>
		</a>
	</div>
	<?php
	return;
endif;

if ( 'strip' === $variant ) :
	?>
	<section class="hlm-seo-resource-links hlm-bg-white" aria-label="<?php echo esc_attr( $aria ); ?>">
		<div class="hlm-container">
			<div class="hlm-section-header hlm-seo-resource-links__header">
				<div class="hlm-overline"><?php esc_html_e( 'Related Resources', 'hlm-seo' ); ?></div>
			</div>
			<div class="hlm-seo-resource-links__actions">
				<?php foreach ( $links as $link ) : ?>
					<?php
					$label_text = function_exists( 'hlm_kadence_format_seo_link_label' )
						? hlm_kadence_format_seo_link_label( $link['label'] )
						: $link['label'];
					?>
					<a class="hlm-btn hlm-btn-outline hlm-btn--compact" href="<?php echo esc_url( $link['url'] ); ?>">
						<?php echo esc_html( $label_text ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return;
endif;

if ( 'footer' === $variant ) :
	?>
<nav class="hlm-seo-resource-links hlm-seo-resource-links--footer hlm-bg-white" aria-label="<?php echo esc_attr( $aria ); ?>">
		<div class="hlm-container">
			<div class="hlm-section-header hlm-seo-resource-links__header">
				<div class="hlm-overline"><?php esc_html_e( 'Next Steps', 'hlm-seo' ); ?></div>
			</div>
			<div class="hlm-seo-resource-links__actions">
				<?php foreach ( $links as $link ) : ?>
					<?php
					$label_text = function_exists( 'hlm_kadence_format_seo_link_label' )
						? hlm_kadence_format_seo_link_label( $link['label'] )
						: $link['label'];
					?>
					<a class="hlm-btn hlm-btn-outline hlm-btn--compact" href="<?php echo esc_url( $link['url'] ); ?>">
						<?php echo esc_html( $label_text ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</nav>
	<?php
	return;
endif;
