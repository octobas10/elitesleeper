<?php
/**
 * Process block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline       = hlm_block_attr( $attributes, 'overline', 'How It Works' );
$headline       = hlm_block_attr( $attributes, 'headline', 'Three Simple Steps' );
$description    = hlm_block_attr( $attributes, 'description', '' );
$variant        = hlm_block_attr( $attributes, 'variant', 'home' );
$steps          = hlm_block_attr( $attributes, 'steps', array() );
$footer_cta_text = hlm_block_attr( $attributes, 'footerCtaText', '' );
$footer_cta_url  = hlm_block_attr( $attributes, 'footerCtaUrl', '#form' );

$section_class = 'hlm-process hlm-section';
if ( 'strip' === $variant ) {
	$section_class .= ' hlm-process--strip';
} elseif ( 'cream' === $variant ) {
	$section_class .= ' hlm-process--cream hlm-bg-cream';
}

$block_id  = hlm_block_id( 'hlm-process', $block );
$layout_mode = hlm_block_attr( $attributes, 'layoutMode', 'steps' );
$use_cards   = in_array( $variant, array( 'home', 'default', 'cream' ), true );
$use_inner   = 'blocks' === $layout_mode && '' !== trim( $content );

/**
 * Render a process step CTA link.
 *
 * @param array $step Step data.
 */
$render_step_cta = static function ( array $step ) {
	if ( empty( $step['ctaText'] ) || empty( $step['ctaUrl'] ) ) {
		return;
	}

	$url = $step['ctaUrl'];
	if ( ! str_starts_with( $url, '#' ) && ! str_starts_with( $url, '/' ) && ! str_starts_with( $url, 'http' ) ) {
		$url = home_url( '/' . ltrim( $url, '/' ) );
	}
	?>
	<a class="hlm-process-card__cta hlm-btn-arrow" href="<?php echo esc_url( $url ); ?>">
		<?php echo esc_html( $step['ctaText'] ); ?>
	</a>
	<?php
};
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

		<?php if ( $use_inner ) : ?>
		<div class="hlm-process-inner">
			<div class="hlm-process-connector" aria-hidden="true"></div>
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- InnerBlocks HTML. ?>
		</div>
		<?php elseif ( $use_cards ) : ?>
			<?php
			$step_count = count( $steps );
			if ( $step_count < 1 ) {
				$step_count = 1;
			}

			$grid_class = 'hlm-process-grid';
			if ( $step_count >= 4 ) {
				$grid_class .= ' hlm-process-grid--4';
			}

			$grid_columns = array();
			for ( $i = 0; $i < $step_count; $i++ ) {
				$grid_columns[] = 'minmax(0, 1fr)';
				if ( $i < $step_count - 1 ) {
					$grid_columns[] = 'var(--hlm-process-bridge)';
				}
			}
			?>
		<div class="<?php echo esc_attr( $grid_class ); ?>" style="--hlm-process-cols: <?php echo esc_attr( min( $step_count, 4 ) ); ?>; --hlm-process-steps: <?php echo esc_attr( $step_count ); ?>; grid-template-columns: <?php echo esc_attr( implode( ' ', $grid_columns ) ); ?>;">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="hlm-process-grid__step hlm-animate hlm-animate-delay-<?php echo esc_attr( min( $index + 1, 4 ) ); ?>">
					<div class="hlm-process-card">
						<div class="hlm-process-card__head">
							<?php if ( ! empty( $step['number'] ) ) : ?>
								<div class="hlm-process-num" aria-hidden="true"><?php echo esc_html( $step['number'] ); ?></div>
							<?php endif; ?>
							<div class="hlm-process-icon">
								<?php echo hlm_icon( $step['icon'] ?? 'document' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
						<h4><?php echo esc_html( $step['title'] ?? '' ); ?></h4>
						<p><?php echo esc_html( $step['description'] ?? '' ); ?></p>
						<?php $render_step_cta( $step ); ?>
					</div>
				</div>
				<?php if ( $index < $step_count - 1 ) : ?>
					<div class="hlm-process-grid__bridge" aria-hidden="true"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
			<?php
			$step_count   = count( $steps );
			$steps_class  = 'hlm-process__steps';
			if ( $step_count >= 4 ) {
				$steps_class .= ' hlm-process__steps--4';
			}
			?>
		<div class="<?php echo esc_attr( $steps_class ); ?>" style="--hlm-process-cols: <?php echo esc_attr( $step_count ); ?>;">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="hlm-process__step hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
					<h3 class="hlm-h4"><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
					<p class="hlm-body"><?php echo esc_html( $step['description'] ?? '' ); ?></p>
					<?php $render_step_cta( $step ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $footer_cta_text && $footer_cta_url ) : ?>
			<?php
			$footer_url = $footer_cta_url;
			if ( ! str_starts_with( $footer_url, '#' ) && ! str_starts_with( $footer_url, '/' ) && ! str_starts_with( $footer_url, 'http' ) ) {
				$footer_url = home_url( '/' . ltrim( $footer_url, '/' ) );
			}
			?>
			<div class="hlm-process__footer hlm-animate">
				<a class="hlm-btn hlm-btn-primary hlm-btn-arrow" href="<?php echo esc_url( $footer_url ); ?>">
					<?php echo esc_html( $footer_cta_text ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
