<?php
/**
 * Universities block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline     = hlm_block_attr( $attributes, 'overline', 'Partner Institutions' );
$headline     = hlm_block_attr( $attributes, 'headline', 'Top Universities & Programs' );
$description  = hlm_block_attr( $attributes, 'description', '' );
$institutions = hlm_block_attr( $attributes, 'institutions', array() );

$block_id = hlm_block_id( 'hlm-universities', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-unis hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-animate">
			<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
			<?php hlm_block_headline( $headline ); ?>
			<?php if ( $description ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>
		<div class="hlm-unis-grid">
		<?php foreach ( $institutions as $index => $institution ) : ?>
			<?php
			$image_url = hlm_media_image_url(
				array(
					'image_url' => $institution['imageUrl'] ?? '',
					'image_id'  => (int) ( $institution['imageId'] ?? 0 ),
				)
			);
			$image_alt = $institution['imageAlt'] ?? ( $institution['title'] ?? '' );
			?>
			<div class="hlm-uni-card hlm-animate hlm-animate-delay-<?php echo esc_attr( min( $index + 1, 4 ) ); ?>">
				<?php if ( $image_url ) : ?>
					<div class="hlm-uni-card__media">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" decoding="async" />
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $institution['badge'] ) ) : ?>
					<div class="hlm-uni-badge">
						<?php echo hlm_icon( 'graduation' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo esc_html( $institution['badge'] ); ?>
					</div>
				<?php endif; ?>
				<h3><?php echo esc_html( $institution['title'] ?? '' ); ?></h3>
				<p><?php echo esc_html( $institution['description'] ?? '' ); ?></p>
				<?php if ( ! empty( $institution['meta'] ) ) : ?>
					<div class="hlm-uni-meta">
						<?php foreach ( $institution['meta'] as $meta ) : ?>
							<span>
								<?php echo hlm_icon( $meta['icon'] ?? 'location' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( $meta['text'] ?? '' ); ?>
							</span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</div>
		<?php
		$view_all_text = hlm_block_attr( $attributes, 'viewAllText', 'View All Universities' );
		$view_all_url  = hlm_block_attr( $attributes, 'viewAllUrl', '/affiliations/' );
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
