<?php
/**
 * Why Us block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant     = hlm_block_attr( $attributes, 'variant', 'default' );
$overline    = hlm_block_attr( $attributes, 'overline', 'Why Choose Us' );
$headline    = hlm_block_attr( $attributes, 'headline', 'The Higher Education Elite Difference' );
$description = hlm_block_attr( $attributes, 'description', '' );
$pillars     = hlm_block_attr( $attributes, 'pillars', array() );
$block_id    = hlm_block_id( 'hlm-why-us', $block );

$section_class = 'hlm-why-us hlm-section';
if ( 'values' === $variant ) {
	$section_class .= ' hlm-why-us--values';
} else {
	$section_class .= ' hlm-bg-white';
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $section_class ); ?>">
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

		<div class="<?php echo 'values' === $variant ? 'hlm-why-us__values-grid' : 'hlm-grid-4 hlm-stagger-grid'; ?>">
			<?php foreach ( $pillars as $index => $pillar ) : ?>
				<div class="<?php echo 'values' === $variant ? 'hlm-value-card' : 'hlm-pillar'; ?> hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
					<?php if ( ! empty( $pillar['icon'] ) ) : ?>
						<div class="<?php echo 'values' === $variant ? 'hlm-value-card__icon' : 'hlm-pillar__icon'; ?>"><?php echo hlm_render_icon( $pillar['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
					<h3 class="hlm-h3"><?php echo esc_html( $pillar['title'] ?? '' ); ?></h3>
					<p class="hlm-body"><?php echo esc_html( $pillar['description'] ?? '' ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
