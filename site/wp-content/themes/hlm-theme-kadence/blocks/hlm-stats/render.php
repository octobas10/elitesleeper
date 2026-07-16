<?php
/**
 * Stats block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant     = hlm_block_attr( $attributes, 'variant', 'bar' );
$overline    = hlm_block_attr( $attributes, 'overline', 'Our Reach' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Network By The Numbers' );
$description = hlm_block_attr( $attributes, 'description', '' );
$stats       = hlm_block_attr( $attributes, 'stats', array() );
$block_id    = hlm_block_id( 'hlm-stats', $block );

if ( 'network' === $variant ) :
	?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-stats hlm-stats--network hlm-section">
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
		<div class="hlm-network-visual hlm-animate">
			<div class="hlm-stats-grid hlm-stats-grid--network">
				<?php foreach ( $stats as $stat ) : ?>
					<div class="hlm-stat-item">
						<div class="hlm-stat-number"
							data-count="<?php echo esc_attr( $stat['number'] ?? 0 ); ?>"
							data-prefix="<?php echo esc_attr( $stat['prefix'] ?? '' ); ?>"
							data-suffix="<?php echo esc_attr( $stat['suffix'] ?? '' ); ?>">
							0
						</div>
						<div class="hlm-stat-label"><?php echo esc_html( $stat['label'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
	<?php
	return;
endif;
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-stats">
	<div class="hlm-stats-grid">
		<?php foreach ( $stats as $stat ) : ?>
			<div class="hlm-stat-item">
				<div class="hlm-stat-number"
					data-count="<?php echo esc_attr( $stat['number'] ?? 0 ); ?>"
					data-prefix="<?php echo esc_attr( $stat['prefix'] ?? '' ); ?>"
					data-suffix="<?php echo esc_attr( $stat['suffix'] ?? '' ); ?>">
					0
				</div>
				<div class="hlm-stat-label"><?php echo esc_html( $stat['label'] ?? '' ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
