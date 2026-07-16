<?php
/**
 * HLM site footer markup.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

$site_name = get_bloginfo( 'name' );
$year      = gmdate( 'Y' );
$contact   = hlm_site_contact();
?>

<footer id="hlm-site-footer" class="hlm-footer">
	<div class="hlm-container">
		<div class="hlm-footer__grid">
			<div class="hlm-footer__brand">
				<div class="hlm-footer__brand-name"><?php echo esc_html( $site_name ); ?></div>
				<p><?php esc_html_e( 'Premium education lead generation connecting institutions with high-intent prospective students. Over a decade of proven results in the higher education sector.', 'hlm-kadence' ); ?></p>
			</div>

			<div class="hlm-footer__col">
				<h4><?php esc_html_e( 'Company', 'hlm-kadence' ); ?></h4>
				<ul class="hlm-footer__links">
					<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About Us', 'hlm-kadence' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Our Services', 'hlm-kadence' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/affiliations/' ) ); ?>"><?php esc_html_e( 'Affiliations', 'hlm-kadence' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'hlm-kadence' ); ?></a></li>
				</ul>
			</div>

			<div class="hlm-footer__col">
				<h4><?php esc_html_e( 'Legal', 'hlm-kadence' ); ?></h4>
				<ul class="hlm-footer__links">
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'hlm-kadence' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'hlm-kadence' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Compliance', 'hlm-kadence' ); ?></a></li>
				</ul>
			</div>

			<div class="hlm-footer__col">
				<h4><?php esc_html_e( 'Contact', 'hlm-kadence' ); ?></h4>
				<ul class="hlm-footer__contact">
					<li>
						<a class="hlm-footer__contact-btn" href="<?php echo esc_url( 'mailto:' . $contact['email'] ); ?>">
							<?php echo hlm_render_icon( 'mail', 'hlm-footer__contact-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php esc_html_e( 'Email Us', 'hlm-kadence' ); ?></span>
						</a>
					</li>
					<li class="hlm-footer__contact-item">
						<?php echo hlm_render_icon( 'phone', 'hlm-footer__contact-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<a href="<?php echo esc_url( $contact['phone_href'] ); ?>"><?php echo esc_html( $contact['phone_display'] ); ?></a>
					</li>
					<li class="hlm-footer__contact-item">
						<?php echo hlm_render_icon( 'location', 'hlm-footer__contact-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<a href="<?php echo esc_url( $contact['address_href'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( $contact['address_display'] ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="hlm-footer__bottom">
			<p>
				<?php
				printf(
					/* translators: %1$s: year, %2$s: site name */
					esc_html__( '%1$s %2$s. All rights reserved. TCPA & GDPR Compliant.', 'hlm-kadence' ),
					esc_html( $year ),
					esc_html( $site_name )
				);
				?>
			</p>
		</div>
	</div>
</footer>
