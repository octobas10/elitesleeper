<?php
/**
 * Policy block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$headline     = hlm_block_attr( $attributes, 'headline', 'Privacy Policy' );
$last_updated = hlm_block_attr( $attributes, 'lastUpdated', '' );
$intro        = hlm_block_attr( $attributes, 'intro', '' );
$sections     = hlm_block_attr( $attributes, 'sections', array() );
$contact_info = hlm_block_attr( $attributes, 'contactInfo', '' );

if ( empty( $sections ) && function_exists( 'hlm_kadence_privacy_policy_sections' ) ) {
	$sections = hlm_kadence_privacy_policy_sections();
}
$block_id     = hlm_block_id( 'hlm-policy', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-policy hlm-section">
	<div class="hlm-container-narrow">
		<h1 class="hlm-h1 hlm-animate"><?php echo esc_html( $headline ); ?></h1>
		<?php if ( $last_updated ) : ?>
			<p class="hlm-policy__updated hlm-animate"><?php printf( esc_html__( 'Last updated: %s', 'hlm-kadence' ), esc_html( $last_updated ) ); ?></p>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<div class="hlm-body-lg hlm-animate" style="margin-bottom: var(--hlm-space-xl);"><?php echo wp_kses_post( wpautop( $intro ) ); ?></div>
		<?php endif; ?>

		<?php foreach ( $sections as $index => $section ) : ?>
			<div class="hlm-policy__section hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
				<h3><?php echo esc_html( $section['title'] ?? '' ); ?></h3>
				<div class="hlm-body"><?php echo wp_kses_post( wpautop( $section['content'] ?? '' ) ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if ( $contact_info ) : ?>
			<div class="hlm-policy__section hlm-animate">
				<h3><?php esc_html_e( 'Contact Us', 'hlm-kadence' ); ?></h3>
				<div class="hlm-body"><?php echo wp_kses_post( wpautop( $contact_info ) ); ?></div>
			</div>
		<?php endif; ?>
	</div>
</section>
