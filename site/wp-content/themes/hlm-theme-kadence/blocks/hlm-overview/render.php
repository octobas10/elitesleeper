<?php
/**
 * Overview block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline = hlm_block_attr( $attributes, 'overline', 'About Us' );
$headline = hlm_block_attr( $attributes, 'headline', 'Empowering Students to Reach Their Full Potential' );
$content  = hlm_block_attr( $attributes, 'content', '' );
$image_url = hlm_block_attr( $attributes, 'imageUrl', '' );
$image_alt = hlm_block_attr( $attributes, 'imageAlt', $headline );
$block_id = hlm_block_id( 'hlm-overview', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-overview hlm-section">
	<div class="hlm-container">
		<div class="hlm-grid-2" style="align-items: center;">
			<div class="hlm-animate">
				<p class="hlm-overline"><?php echo esc_html( $overline ); ?></p>
				<h2 class="hlm-h2"><?php echo esc_html( $headline ); ?></h2>
				<div class="hlm-body-lg"><?php echo wp_kses_post( wpautop( $content ) ); ?></div>
			</div>
			<?php if ( $image_url ) : ?>
				<div class="hlm-animate hlm-animate--delay-2">
					<img src="<?php echo esc_url( $image_url ); ?>"
						alt="<?php echo esc_attr( $image_alt ); ?>"
						style="border-radius: var(--hlm-radius-lg); box-shadow: var(--hlm-shadow);">
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
