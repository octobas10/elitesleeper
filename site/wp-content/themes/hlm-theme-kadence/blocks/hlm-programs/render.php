<?php
/**
 * Programs block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline    = hlm_block_attr( $attributes, 'overline', 'Popular Programs' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Programs Students Are Searching For' );
$description = hlm_block_attr( $attributes, 'description', '' );
$programs    = hlm_block_attr( $attributes, 'programs', array() );
$block_id    = hlm_block_id( 'hlm-programs', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-programs hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-animate">
			<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
			<?php hlm_block_headline( $headline ); ?>
			<?php if ( $description ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>
		<div class="hlm-programs-grid">
		<?php foreach ( $programs as $index => $program ) : ?>
			<div class="hlm-program-tag hlm-animate hlm-animate-delay-<?php echo esc_attr( min( $index + 1, 4 ) ); ?>">
				<?php echo hlm_icon( $program['icon'] ?? 'activity' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php echo esc_html( $program['name'] ?? '' ); ?></span>
			</div>
		<?php endforeach; ?>
		</div>
		<?php
		$view_all_text = hlm_block_attr( $attributes, 'viewAllText', 'View All Programs' );
		$view_all_url  = hlm_block_attr( $attributes, 'viewAllUrl', '/contact-us/#form' );
		if ( $view_all_text && $view_all_url ) :
			?>
			<div class="hlm-section__actions hlm-animate">
				<a href="<?php echo esc_url( $view_all_url ); ?>" class="hlm-btn hlm-btn-outline hlm-btn-arrow">
					<?php echo esc_html( $view_all_text ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
