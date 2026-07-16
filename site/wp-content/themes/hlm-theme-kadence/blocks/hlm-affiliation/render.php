<?php
/**
 * Affiliation block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline    = hlm_block_attr( $attributes, 'overline', 'Why Partner With Us' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Benefits of Affiliation' );
$description = hlm_block_attr( $attributes, 'description', '' );
$benefits    = hlm_block_attr( $attributes, 'benefits', array() );
$block_id    = hlm_block_id( 'hlm-affiliation', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-affiliation hlm-section hlm-affiliation--benefits">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-animate">
			<?php if ( $overline ) : ?>
				<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
			<?php endif; ?>
			<?php hlm_block_headline( $headline ); ?>
			<?php if ( $description ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="hlm-affiliation-benefits-grid">
			<?php foreach ( $benefits as $index => $benefit ) : ?>
				<div class="hlm-affiliation-benefit hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
					<?php if ( ! empty( $benefit['icon'] ) ) : ?>
						<div class="hlm-affiliation-benefit__icon" aria-hidden="true"><?php echo hlm_render_icon( $benefit['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
					<div class="hlm-affiliation-benefit__content">
						<h3 class="hlm-h4"><?php echo esc_html( $benefit['title'] ?? '' ); ?></h3>
						<p class="hlm-body"><?php echo esc_html( $benefit['description'] ?? '' ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
