<?php
/**
 * Services block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline    = hlm_block_attr( $attributes, 'overline', 'What We Offer' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Comprehensive Education Services' );
$description = hlm_block_attr( $attributes, 'description', '' );
$services    = hlm_block_attr( $attributes, 'services', array() );
$columns     = hlm_block_attr( $attributes, 'columns', '3' );
$grid_class  = 'hlm-grid-' . esc_attr( $columns );
$block_id    = hlm_block_id( 'hlm-services', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-services hlm-section hlm-bg-cream">
	<div class="hlm-container">
		<div class="hlm-services__header hlm-animate">
			<?php if ( $overline ) : ?>
				<p class="hlm-overline"><?php echo esc_html( $overline ); ?></p>
			<?php endif; ?>
			<h2 class="hlm-h2"><?php echo esc_html( $headline ); ?></h2>
			<?php if ( $description ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="<?php echo esc_attr( $grid_class ); ?> hlm-stagger-grid">
			<?php foreach ( $services as $index => $service ) : ?>
				<div class="hlm-card hlm-service-card hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
					<?php if ( ! empty( $service['icon'] ) ) : ?>
						<div class="hlm-card__icon"><?php echo hlm_render_icon( $service['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
					<h3 class="hlm-h3"><?php echo esc_html( $service['title'] ?? '' ); ?></h3>
					<p class="hlm-body"><?php echo esc_html( $service['description'] ?? '' ); ?></p>
					<?php if ( ! empty( $service['tags'] ) ) : ?>
						<div class="hlm-service-card__tags">
							<?php foreach ( array_map( 'trim', explode( ',', $service['tags'] ) ) as $tag ) : ?>
								<span class="hlm-card__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php
					$service_link = trim( (string) ( $service['link'] ?? '' ) );
					if ( '' === $service_link && ! empty( $service['title'] ) && function_exists( 'hlm_service_url' ) ) {
						$service_link = hlm_service_url( $service['title'] );
					}
					if ( $service_link ) :
						?>
						<a href="<?php echo esc_url( $service_link ); ?>" class="hlm-btn hlm-btn-outline hlm-btn-arrow hlm-service-card__cta">
							<?php esc_html_e( 'Learn More', 'hlm-kadence' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
