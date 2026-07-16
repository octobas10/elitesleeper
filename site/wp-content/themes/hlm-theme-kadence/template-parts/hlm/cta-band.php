<?php
/**
 * HLM CTA band section.
 *
 * @package HLM_Kadence
 *
 * @var array $args Template arguments.
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'id'             => 'hlm-cta',
		'overline'       => 'Get Started',
		'headline'       => 'Start Your Journey Today',
		'description'    => '',
		'primary_text'   => 'Get Started',
		'primary_url'    => '/contact-us/#form',
		'secondary_text' => 'Learn About Us',
		'secondary_url'  => '/about-us/',
		'animate'        => true,
		'variant'        => 'brand',
	)
);

$section_class = 'hlm-cta hlm-section';
if ( 'soft' === $args['variant'] ) {
	$section_class .= ' hlm-cta--soft';
}
?>

<section id="<?php echo esc_attr( $args['id'] ); ?>" class="<?php echo esc_attr( $section_class ); ?>">
	<div class="hlm-container">
		<div class="hlm-cta-inner<?php echo $args['animate'] ? ' hlm-animate' : ''; ?>">
			<?php if ( $args['overline'] ) : ?>
				<div class="hlm-overline"><?php echo esc_html( $args['overline'] ); ?></div>
			<?php endif; ?>
			<?php hlm_block_headline( $args['headline'] ); ?>
			<?php if ( $args['description'] ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $args['description'] ); ?></p>
			<?php endif; ?>
			<div class="hlm-cta-btns">
				<?php if ( $args['primary_text'] && $args['primary_url'] ) : ?>
					<a href="<?php echo esc_url( $args['primary_url'] ); ?>" class="hlm-btn <?php echo 'soft' === $args['variant'] ? 'hlm-btn-gold' : 'hlm-btn-primary'; ?> hlm-btn-arrow">
						<?php echo esc_html( $args['primary_text'] ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $args['secondary_text'] && $args['secondary_url'] ) : ?>
					<a href="<?php echo esc_url( $args['secondary_url'] ); ?>" class="hlm-btn hlm-btn-outline">
						<?php echo esc_html( $args['secondary_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
