<?php
/**
 * Contact block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant      = hlm_block_attr( $attributes, 'variant', 'cards' );
$overline     = hlm_block_attr( $attributes, 'overline', 'Get In Touch' );
$headline     = hlm_block_attr( $attributes, 'headline', 'Contact Us' );
$description  = hlm_block_attr( $attributes, 'description', '' );
$form_headline = hlm_block_attr( $attributes, 'formHeadline', 'Start Your Inquiry' );
$response_note = hlm_block_attr( $attributes, 'responseNote', 'We respond within one business day.' );
$hours        = hlm_block_attr( $attributes, 'hours', 'Monday – Friday, 9:00 AM – 6:00 PM ET' );
$email        = hlm_block_attr( $attributes, 'email', 'support@higherlearningmarketers.com' );
$phone        = hlm_block_attr( $attributes, 'phone', '+1 (718) 655-2573' );
$address      = hlm_block_attr( $attributes, 'address', "138-07 82nd Drive\nBriarwood, NY 11435" );
$block_id     = hlm_block_id( 'hlm-contact', $block );

if ( 'inquiry' === $variant ) {
	$section_class = 'hlm-contact hlm-contact--inquiry hlm-section hlm-bg-cream';
	?>
	<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $section_class ); ?>">
		<div class="hlm-container">
			<div class="hlm-contact-inquiry">
				<div class="hlm-contact-inquiry__aside hlm-animate">
					<?php if ( $overline ) : ?>
						<p class="hlm-overline"><?php echo esc_html( $overline ); ?></p>
					<?php endif; ?>
					<?php if ( $headline ) : ?>
						<h2 class="hlm-h2"><?php echo esc_html( $headline ); ?></h2>
					<?php endif; ?>
					<?php if ( $description ) : ?>
						<p class="hlm-body-lg hlm-contact-inquiry__intro"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<ul class="hlm-contact-inquiry__channels">
						<li class="hlm-contact-inquiry__channel">
							<span class="hlm-contact-inquiry__channel-icon" aria-hidden="true"><?php echo hlm_render_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<span class="hlm-contact-inquiry__channel-label"><?php esc_html_e( 'Email', 'hlm-kadence' ); ?></span>
								<a class="hlm-contact-inquiry__channel-value" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
							</div>
						</li>
						<li class="hlm-contact-inquiry__channel">
							<span class="hlm-contact-inquiry__channel-icon" aria-hidden="true"><?php echo hlm_render_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<span class="hlm-contact-inquiry__channel-label"><?php esc_html_e( 'Phone', 'hlm-kadence' ); ?></span>
								<a class="hlm-contact-inquiry__channel-value" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
							</div>
						</li>
						<li class="hlm-contact-inquiry__channel">
							<span class="hlm-contact-inquiry__channel-icon" aria-hidden="true"><?php echo hlm_render_icon( 'location' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<span class="hlm-contact-inquiry__channel-label"><?php esc_html_e( 'Office', 'hlm-kadence' ); ?></span>
								<span class="hlm-contact-inquiry__channel-value"><?php echo nl2br( esc_html( $address ) ); ?></span>
							</div>
						</li>
					</ul>

					<?php if ( $response_note || $hours ) : ?>
						<div class="hlm-contact-inquiry__meta">
							<?php if ( $response_note ) : ?>
								<p class="hlm-contact-inquiry__note"><?php echo esc_html( $response_note ); ?></p>
							<?php endif; ?>
							<?php if ( $hours ) : ?>
								<p class="hlm-contact-inquiry__hours"><?php echo esc_html( $hours ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<div id="form" class="hlm-contact-inquiry__form hlm-form-card hlm-animate hlm-animate--delay-2">
					<?php if ( $form_headline ) : ?>
						<h3 class="hlm-h3 hlm-contact-inquiry__form-title"><?php echo esc_html( $form_headline ); ?></h3>
					<?php endif; ?>
					<?php if ( shortcode_exists( 'hlm_lead_form' ) ) : ?>
						<?php echo do_shortcode( '[hlm_lead_form]' ); ?>
					<?php else : ?>
						<p class="hlm-form-placeholder"><?php esc_html_e( 'Activate the HLM Lead Capture plugin to display the lead form.', 'hlm-kadence' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-contact hlm-section hlm-bg-cream">
	<div class="hlm-container">
		<div class="hlm-text-center hlm-animate" style="margin-bottom: var(--hlm-space-xl);">
			<p class="hlm-overline"><?php echo esc_html( $overline ); ?></p>
			<h2 class="hlm-h2"><?php echo esc_html( $headline ); ?></h2>
		</div>

		<div class="hlm-contact__grid">
			<div class="hlm-contact__item hlm-card hlm-animate">
				<div class="hlm-contact__item-icon" aria-hidden="true"><?php echo hlm_render_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<h3 class="hlm-h4"><?php esc_html_e( 'Email', 'hlm-kadence' ); ?></h3>
				<p class="hlm-body"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
			</div>
			<div class="hlm-contact__item hlm-card hlm-animate hlm-animate--delay-2">
				<div class="hlm-contact__item-icon" aria-hidden="true"><?php echo hlm_render_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<h3 class="hlm-h4"><?php esc_html_e( 'Phone', 'hlm-kadence' ); ?></h3>
				<p class="hlm-body"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
			</div>
			<div class="hlm-contact__item hlm-card hlm-animate hlm-animate--delay-3">
				<div class="hlm-contact__item-icon" aria-hidden="true"><?php echo hlm_render_icon( 'location' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<h3 class="hlm-h4"><?php esc_html_e( 'Office', 'hlm-kadence' ); ?></h3>
				<p class="hlm-body"><?php echo nl2br( esc_html( $address ) ); ?></p>
			</div>
		</div>
	</div>
</section>
