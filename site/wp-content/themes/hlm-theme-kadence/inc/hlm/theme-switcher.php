<?php
/**
 * HLM light / dark / system theme switcher.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline script to apply theme before first paint (prevents flash).
 */
function hlm_kadence_theme_inline_script() {
	if ( ! hlm_kadence_is_theme_shell() ) {
		return;
	}
	?>
	<script>
	(function () {
		var key = 'hlm-theme-preference';
		var modes = ['system', 'light', 'dark'];
		var pref = localStorage.getItem(key);
		if (modes.indexOf(pref) === -1) pref = 'system';
		var dark = pref === 'dark' || (pref === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
		var theme = dark ? 'dark' : 'light';
		document.documentElement.setAttribute('data-hlm-theme-pref', pref);
		document.documentElement.setAttribute('data-theme', theme);
		document.documentElement.style.colorScheme = theme;
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'hlm_kadence_theme_inline_script', 0 );

/**
 * Enqueue theme switcher script on HLM pages.
 */
function hlm_kadence_enqueue_theme_script() {
	if ( ! hlm_kadence_is_theme_shell() ) {
		return;
	}

	wp_enqueue_script(
		'hlm-theme',
		HLM_KADENCE_URI . '/assets/hlm/hlm-theme.js',
		array(),
		HLM_KADENCE_VERSION,
		false
	);
}
add_action( 'wp_enqueue_scripts', 'hlm_kadence_enqueue_theme_script', 21 );

/**
 * Render theme toggle control markup.
 *
 * @return string
 */
function hlm_kadence_render_theme_toggle() {
	ob_start();
	?>
	<button
		type="button"
		class="hlm-theme-toggle"
		data-hlm-theme-toggle
		data-current-mode="system"
		aria-label="<?php esc_attr_e( 'Theme: System. Click to change.', 'hlm-kadence' ); ?>"
		title="<?php esc_attr_e( 'Theme: System', 'hlm-kadence' ); ?>"
	>
		<span class="hlm-theme-toggle__icon hlm-theme-toggle__icon--system" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 3v2.2M12 18.8V21M4.22 4.22l1.56 1.56M18.22 18.22l1.56 1.56M3 12h2.2M18.8 12H21M4.22 19.78l1.56-1.56M18.22 5.78l1.56-1.56" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
				<circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="1.6"/>
			</svg>
		</span>
		<span class="hlm-theme-toggle__icon hlm-theme-toggle__icon--light" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="12" cy="12" r="4.5" stroke="currentColor" stroke-width="1.6"/>
				<path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
			</svg>
		</span>
		<span class="hlm-theme-toggle__icon hlm-theme-toggle__icon--dark" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M21 14.5A8.5 8.5 0 0 1 9.5 3 7 7 0 1 0 21 14.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
			</svg>
		</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Toggle color theme', 'hlm-kadence' ); ?></span>
	</button>
	<?php
	return (string) ob_get_clean();
}

