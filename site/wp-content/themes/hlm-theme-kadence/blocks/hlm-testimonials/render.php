<?php
/**
 * Testimonials block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline     = hlm_block_attr( $attributes, 'overline', 'Success Stories' );
$headline     = hlm_block_attr( $attributes, 'headline', 'What Our Partners Say' );
$description  = hlm_block_attr( $attributes, 'description', '' );
$testimonials = hlm_block_attr( $attributes, 'testimonials', array() );
$block_id     = hlm_block_id( 'hlm-testimonials', $block );
?>

<section id="testimonials" class="hlm-testimonials hlm-section">
	<div class="hlm-section-header hlm-animate">
		<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
		<?php hlm_block_headline( $headline ); ?>
		<?php if ( $description ) : ?>
			<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
	<div class="hlm-testimonials-grid">
		<?php foreach ( $testimonials as $index => $item ) : ?>
			<div class="hlm-testimonial-card hlm-animate hlm-animate-delay-<?php echo esc_attr( min( $index + 1, 4 ) ); ?>">
				<?php hlm_render_stars( $item['stars'] ?? 5 ); ?>
				<p class="hlm-testimonial-quote"><?php echo esc_html( $item['quote'] ?? '' ); ?></p>
				<div class="hlm-testimonial-author">
					<?php if ( ! empty( $item['photoUrl'] ) ) : ?>
						<img src="<?php echo esc_url( $item['photoUrl'] ); ?>" alt="<?php echo esc_attr( $item['author'] ?? '' ); ?>">
					<?php endif; ?>
					<div>
						<h5><?php echo esc_html( $item['author'] ?? '' ); ?></h5>
						<?php if ( ! empty( $item['meta'] ) ) : ?>
							<span><?php echo esc_html( $item['meta'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
