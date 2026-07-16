<?php
/**
 * Partner showcase block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant         = hlm_block_attr( $attributes, 'variant', 'partner' );
$image_position  = hlm_block_attr( $attributes, 'imagePosition', 'right' );
$overline        = hlm_block_attr( $attributes, 'overline', 'Featured Partnership' );
$headline        = hlm_block_attr( $attributes, 'headline', '' );
$content         = hlm_normalize_multiline( hlm_block_attr( $attributes, 'content', '' ) );
$image_url       = hlm_media_image_url(
	array(
		'image_id'  => (int) hlm_block_attr( $attributes, 'imageId', 0 ),
		'image_url' => hlm_block_attr( $attributes, 'imageUrl', '' ),
	)
);
$image_alt       = hlm_block_attr( $attributes, 'imageAlt', $headline );
$badge           = hlm_block_attr( $attributes, 'badge', '' );
$stats           = hlm_block_attr( $attributes, 'stats', array() );
$block_id        = hlm_block_id( 'hlm-partner-showcase', $block );

$section_class = 'hlm-partner-showcase hlm-section';
if ( 'story' === $variant ) {
	$section_class .= ' hlm-partner-showcase--story';
}

$grid_class = 'hlm-partner-showcase__grid';
if ( 'left' === $image_position ) {
	$grid_class .= ' hlm-partner-showcase__grid--image-left';
}

$stats_class = 'hlm-partner-showcase__stats';
if ( 'story' === $variant || count( $stats ) >= 4 ) {
	$stats_class .= ' hlm-partner-showcase__stats--row';
}

$stats_below = 'story' === $variant && ! empty( $stats );

ob_start();
?>
<?php if ( $overline ) : ?>
	<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
<?php endif; ?>
<?php if ( $headline ) : ?>
	<?php hlm_block_headline( $headline ); ?>
<?php endif; ?>
<?php if ( $content ) : ?>
	<div class="hlm-partner-showcase__copy hlm-body-lg">
		<?php echo wp_kses_post( wpautop( $content ) ); ?>
	</div>
<?php endif; ?>
<?php if ( ! $stats_below && ! empty( $stats ) ) : ?>
	<div class="<?php echo esc_attr( $stats_class ); ?>">
		<?php foreach ( $stats as $stat ) : ?>
			<div class="hlm-partner-showcase__stat">
				<div class="hlm-partner-showcase__stat-number"><?php echo esc_html( $stat['number'] ?? '' ); ?></div>
				<div class="hlm-partner-showcase__stat-label"><?php echo esc_html( $stat['label'] ?? '' ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
$content_html = ob_get_clean();

ob_start();
if ( $image_url ) :
	?>
	<div class="hlm-partner-showcase__visual hlm-animate hlm-animate--delay-2">
		<img class="hlm-partner-showcase__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
		<?php if ( $badge ) : ?>
			<div class="hlm-partner-showcase__badge"><?php echo esc_html( $badge ); ?></div>
		<?php endif; ?>
	</div>
	<?php
endif;
$visual_html = ob_get_clean();
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $section_class ); ?>">
	<div class="hlm-container">
		<div class="<?php echo esc_attr( $grid_class ); ?>">
			<?php if ( 'left' === $image_position ) : ?>
				<?php echo $visual_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="hlm-partner-showcase__content hlm-animate">
					<?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php else : ?>
				<div class="hlm-partner-showcase__content hlm-animate">
					<?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php echo $visual_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
		</div>
		<?php if ( $stats_below ) : ?>
			<div class="<?php echo esc_attr( $stats_class ); ?> hlm-partner-showcase__stats--full">
				<?php foreach ( $stats as $stat ) : ?>
					<div class="hlm-partner-showcase__stat">
						<div class="hlm-partner-showcase__stat-number"><?php echo esc_html( $stat['number'] ?? '' ); ?></div>
						<div class="hlm-partner-showcase__stat-label"><?php echo esc_html( $stat['label'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
