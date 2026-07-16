<?php
/**
 * HLM site header markup.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

$cta          = hlm_kadence_header_cta();
$contact      = hlm_site_contact();
$transparent  = hlm_kadence_header_is_transparent();
$header_class = 'hlm-site-header site-header';

if ( $transparent ) {
	$header_class .= ' hlm-site-header--transparent';
}
?>

<header id="masthead" class="<?php echo esc_attr( $header_class ); ?>" role="banner" itemscope itemtype="https://schema.org/WPHeader">
	<div class="hlm-site-header__bar">
		<div class="hlm-container hlm-site-header__inner">
			<div class="hlm-site-header__brand-wrap">
				<?php if ( has_custom_logo() ) : ?>
					<div class="hlm-site-header__logo">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<a class="hlm-site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<span class="hlm-site-header__brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
						<?php if ( get_bloginfo( 'description' ) ) : ?>
							<span class="hlm-site-header__brand-tag"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav class="hlm-site-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'hlm-kadence' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'hlm-site-header__menu',
							'items_wrap'     => '<ul id="hlm-primary-menu" class="%2$s" role="list">%3$s</ul>',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="hlm-site-header__actions">
				<div class="hlm-site-header__actions-desktop">
					<?php echo hlm_kadence_render_theme_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<a class="hlm-site-header__phone" href="<?php echo esc_url( $contact['phone_href'] ); ?>">
						<?php echo hlm_render_icon( 'phone', 'hlm-site-header__phone-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="hlm-site-header__phone-text"><?php echo esc_html( $contact['phone_display'] ); ?></span>
					</a>
					<a class="hlm-btn hlm-btn-primary hlm-btn--compact hlm-site-header__cta" href="<?php echo esc_url( $cta['url'] ); ?>">
						<?php echo esc_html( $cta['label'] ); ?>
					</a>
				</div>

				<div class="hlm-site-header__actions-mobile">
					<a
						class="hlm-site-header__phone hlm-site-header__phone--bar"
						href="<?php echo esc_url( $contact['phone_href'] ); ?>"
						aria-label="<?php echo esc_attr( sprintf( /* translators: %s: phone number */ __( 'Call %s', 'hlm-kadence' ), $contact['phone_display'] ) ); ?>"
					>
						<?php echo hlm_render_icon( 'phone', 'hlm-site-header__phone-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				</div>

				<button
					type="button"
					class="hlm-site-header__toggle"
					data-hlm-nav-toggle
					aria-controls="hlm-mobile-nav"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Open menu', 'hlm-kadence' ); ?>"
				>
					<span class="hlm-site-header__toggle-box" aria-hidden="true">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</button>
			</div>
		</div>
	</div>

	<div id="hlm-mobile-nav" class="hlm-site-header__drawer" data-hlm-nav-drawer hidden>
		<button type="button" class="hlm-site-header__overlay" data-hlm-nav-close tabindex="-1" aria-hidden="true"></button>
		<div class="hlm-site-header__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile menu', 'hlm-kadence' ); ?>">
			<div class="hlm-site-header__panel-head">
				<span class="hlm-site-header__panel-title"><?php esc_html_e( 'Menu', 'hlm-kadence' ); ?></span>
				<button type="button" class="hlm-site-header__close" data-hlm-nav-close aria-label="<?php esc_attr_e( 'Close menu', 'hlm-kadence' ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
					</svg>
				</button>
			</div>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav class="hlm-site-header__mobile-nav" aria-label="<?php esc_attr_e( 'Primary mobile', 'hlm-kadence' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'hlm-site-header__mobile-menu',
							'items_wrap'     => '<ul id="hlm-mobile-menu" class="%2$s" role="list">%3$s</ul>',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="hlm-site-header__panel-foot">
				<?php echo hlm_kadence_render_theme_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<a class="hlm-btn hlm-btn-primary hlm-btn--full hlm-site-header__cta" href="<?php echo esc_url( $cta['url'] ); ?>">
					<?php echo esc_html( $cta['label'] ); ?>
				</a>
			</div>
		</div>
	</div>
</header>
