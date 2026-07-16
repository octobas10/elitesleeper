<?php
/**
 * Partners block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline    = hlm_block_attr( $attributes, 'overline', 'Partner Ecosystem' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Organizations We Work With' );
$description = hlm_block_attr( $attributes, 'description', '' );
$partners    = hlm_block_attr( $attributes, 'partners', array() );
$block_id    = hlm_block_id( 'hlm-partners', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-partners hlm-section">
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

		<div class="hlm-partners-grid">
			<?php foreach ( $partners as $index => $partner ) : ?>
				<div class="hlm-partner-card hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
					<?php if ( ! empty( $partner['initials'] ) ) : ?>
						<div class="hlm-partner-card__logo"><?php echo esc_html( $partner['initials'] ); ?></div>
					<?php endif; ?>
					<h3 class="hlm-h4"><?php echo esc_html( $partner['title'] ?? '' ); ?></h3>
					<p class="hlm-body"><?php echo esc_html( $partner['description'] ?? '' ); ?></p>
					<?php if ( ! empty( $partner['meta'] ) ) : ?>
						<div class="hlm-partner-card__meta"><?php echo esc_html( $partner['meta'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
