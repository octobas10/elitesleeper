<?php
/**
 * Inline SVG icon helpers for block templates.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a preset SVG icon by slug.
 *
 * @param string $slug Icon identifier.
 * @return string
 */
function hlm_icon( $slug ) {
	$icons = array(
		'graduation' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
		'location'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
		'clock'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
		'activity'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
		'monitor'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>',
		'document'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>',
		'info'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
		'check'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>',
		'star'       => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2Z"/></svg>',
		'users'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		'globe'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/></svg>',
		'megaphone'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="m3 11 18-5v12L3 14v-3Z"/><path d="M11 6v12"/><path d="M7 14h1a4 4 0 0 0 4-4V6"/></svg>',
		'chart'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 3v18h18"/><path d="M7 16V8"/><path d="M12 16V5"/><path d="M17 16v-6"/></svg>',
		'network'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="M12 7v4"/><path d="m6.5 17.5 3-4.5"/><path d="m17.5 17.5-3-4.5"/></svg>',
		'target'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
		'handshake'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="m11 17 2 2a1 1 0 0 0 1.4 0l2-2"/><path d="M14 14l-1.5-1.5"/><path d="m16 12 3-3a2.1 2.1 0 0 0-3-3l-3 3"/><path d="m8 12-3-3a2.1 2.1 0 0 1 3-3l3 3"/><path d="M10 14 8.5 15.5"/><path d="M7 17l-2 2a1 1 0 0 1-1.4 0l-2-2"/></svg>',
		'award'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="8" r="6"/><path d="M8.21 13.89 7 22l5-3 5 3-1.21-8.11"/></svg>',
		'zap'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8Z"/></svg>',
		'shield'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>',
		'play'       => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7-11-7Z"/></svg>',
		'layers'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="m12 2 8 4-8 4-8-4 8-4Z"/><path d="m4 10 8 4 8-4"/><path d="m4 16 8 4 8-4"/></svg>',
		'mail'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
		'phone'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>',
	);

	return $icons[ $slug ] ?? $icons['graduation'];
}

/**
 * Map legacy emoji or aliases to icon slugs.
 *
 * @param string $icon Icon slug, emoji, or alias.
 * @return string
 */
function hlm_resolve_icon_slug( $icon ) {
	$icon = trim( (string) $icon );

	if ( '' === $icon ) {
		return 'graduation';
	}

	$map = array(
		'👥' => 'users',
		'🌐' => 'globe',
		'📣' => 'megaphone',
		'📊' => 'chart',
		'🔗' => 'network',
		'🎯' => 'target',
		'🤝' => 'handshake',
		'✓'  => 'check',
		'✔'  => 'check',
		'◎'  => 'award',
		'⚡' => 'zap',
		'🛡' => 'shield',
		'🛡️' => 'shield',
	);

	if ( isset( $map[ $icon ] ) ) {
		return $map[ $icon ];
	}

	return $icon;
}

/**
 * Render an icon wrapped for card / pillar layouts.
 *
 * @param string $icon Icon slug or legacy emoji.
 * @param string $class Optional extra class names.
 * @return string
 */
function hlm_render_icon( $icon, $class = '' ) {
	$slug  = hlm_resolve_icon_slug( $icon );
	$extra = $class ? ' ' . esc_attr( $class ) : '';

	return '<span class="hlm-icon' . $extra . '">' . hlm_icon( $slug ) . '</span>';
}

/**
 * Render star rating icons.
 *
 * @param int $count Number of stars.
 * @return void
 */
function hlm_render_stars( $count = 5 ) {
	$count = max( 1, min( 5, (int) $count ) );
	echo '<div class="hlm-stars">';
	for ( $i = 0; $i < $count; $i++ ) {
		echo hlm_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</div>';
}
