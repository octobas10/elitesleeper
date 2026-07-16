<?php
/**
 * Timeline block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant     = hlm_block_attr( $attributes, 'variant', 'dark' );
$overline    = hlm_block_attr( $attributes, 'overline', 'Our Journey' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Key Milestones Through the Years' );
$description = hlm_block_attr( $attributes, 'description', '' );
$milestones  = hlm_block_attr( $attributes, 'milestones', array() );
$block_id    = hlm_block_id( 'hlm-timeline', $block );

$section_class = 'hlm-timeline hlm-section';
if ( 'light' === $variant ) {
	$section_class .= ' hlm-timeline--light';
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

		<div class="hlm-timeline__inner">
			<div class="hlm-timeline__line" aria-hidden="true"></div>
			<?php foreach ( $milestones as $index => $item ) : ?>
				<div class="hlm-timeline__item hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
					<div class="hlm-timeline__dot" aria-hidden="true"></div>
					<?php if ( $index % 2 === 1 ) : ?>
						<div class="hlm-timeline__content">
							<h3 class="hlm-h4"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
							<p class="hlm-body"><?php echo esc_html( $item['description'] ?? '' ); ?></p>
						</div>
						<?php if ( ! empty( $item['year'] ) ) : ?>
							<div class="hlm-timeline__year"><?php echo esc_html( $item['year'] ); ?></div>
						<?php endif; ?>
					<?php else : ?>
						<?php if ( ! empty( $item['year'] ) ) : ?>
							<div class="hlm-timeline__year"><?php echo esc_html( $item['year'] ); ?></div>
						<?php endif; ?>
						<div class="hlm-timeline__content">
							<h3 class="hlm-h4"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
							<p class="hlm-body"><?php echo esc_html( $item['description'] ?? '' ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
