<?php
/**
 * Section media helpers (hero backgrounds: image, video, placeholder).
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/** Relative path to bundled hero demo video (MP4). */
define( 'HLM_DEMO_HERO_VIDEO', 'assets/hlm/media/hero-demo.mp4' );

/** Default label for the university video placeholder surface. */
define( 'HLM_HERO_VIDEO_PLACEHOLDER_LABEL', 'University Campus Video' );

/**
 * Theme-relative media reference for block attributes (resolved at render time).
 *
 * @param string $relative_path Path relative to theme root.
 * @return string
 */
function hlm_theme_media_ref( $relative_path ) {
	return 'theme:' . ltrim( $relative_path, '/' );
}

/**
 * Resolve a media URL from a block attribute value.
 *
 * Supports theme-relative refs: theme:assets/hlm/media/hero-demo.mp4
 *
 * @param string $url Media URL or theme ref.
 * @return string
 */
function hlm_resolve_media_url( $url ) {
	$url = (string) $url;

	if ( '' === $url ) {
		return '';
	}

	if ( 0 === strpos( $url, 'theme:' ) ) {
		$relative = ltrim( substr( $url, 6 ), '/' );

		if ( $relative && file_exists( get_theme_file_path( $relative ) ) ) {
			return get_theme_file_uri( $relative );
		}

		return '';
	}

	return $url;
}

/**
 * Default bundled hero demo video reference for block attributes.
 *
 * @return string
 */
function hlm_default_hero_video_ref() {
	return hlm_theme_media_ref( HLM_DEMO_HERO_VIDEO );
}

/**
 * Normalize hero media type from block attributes.
 *
 * @param string $type Raw media type.
 * @return string image|video|empty string for placeholder.
 */
function hlm_normalize_media_type( $type ) {
	$type = sanitize_key( (string) $type );

	if ( 'placeholder' === $type ) {
		return '';
	}

	if ( in_array( $type, array( 'image', 'video' ), true ) ) {
		return $type;
	}

	return '';
}

/**
 * Valid overlay strength slug.
 *
 * @param string $strength Strength value.
 * @return string light|medium|strong
 */
function hlm_media_overlay_strength( $strength ) {
	$strength = sanitize_key( (string) $strength );

	if ( in_array( $strength, array( 'light', 'medium', 'strong' ), true ) ) {
		return $strength;
	}

	return 'medium';
}

/**
 * Render a color scrim over hero / page-hero media.
 *
 * @param string $strength Overlay strength.
 * @return void
 */
function hlm_render_media_scrim( $strength = 'medium' ) {
	printf(
		'<span class="hlm-media-scrim hlm-media-scrim--%1$s" aria-hidden="true"></span>',
		esc_attr( hlm_media_overlay_strength( $strength ) )
	);
}

/**
 * Resolve image URL from block media attributes.
 *
 * @param array $args Media arguments.
 * @return string
 */
function hlm_media_image_url( array $args ) {
	if ( ! empty( $args['image_id'] ) ) {
		$attachment_url = wp_get_attachment_image_url( (int) $args['image_id'], 'full' );

		if ( $attachment_url ) {
			return $attachment_url;
		}
	}

	return hlm_resolve_media_url( (string) ( $args['image_url'] ?? '' ) );
}

/**
 * Render a full-bleed hero / page-hero media layer.
 *
 * @param array $args {
 *     @type string $type             image|video|empty for placeholder.
 *     @type string $image_url        External or library image URL.
 *     @type int    $image_id         Attachment ID.
 *     @type string $image_alt        Alt text.
 *     @type string $video_url        Self-hosted video URL (mp4/webm).
 *     @type string $poster_url       Video poster image URL.
 *     @type string $wrapper_class    Outer wrapper class.
 *     @type string $inner_class      Inner surface class.
 *     @type string $placeholder_label Label when no media set.
 *     @type bool   $show_play        Show play affordance on video/placeholder.
 *     @type bool   $show_overlay     Color scrim over image/video for text contrast.
 *     @type string $overlay_strength light|medium|strong.
 * }
 * @return void
 */
function hlm_render_section_media( array $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'type'              => '',
			'image_url'         => '',
			'image_id'          => 0,
			'image_alt'         => '',
			'video_url'         => '',
			'poster_url'        => '',
			'wrapper_class'     => 'hlm-hero-media',
			'inner_class'       => 'hlm-hero-media__surface',
			'placeholder_label' => HLM_HERO_VIDEO_PLACEHOLDER_LABEL,
			'show_play'         => true,
			'show_overlay'      => true,
			'overlay_strength'  => 'medium',
		)
	);

	$type      = hlm_normalize_media_type( $args['type'] );
	$image_url = hlm_media_image_url( $args );
	$video_url = hlm_resolve_media_url( $args['video_url'] );
	$poster    = hlm_resolve_media_url( $args['poster_url'] ?: $image_url );
	$alt       = $args['image_alt'] ? $args['image_alt'] : $args['placeholder_label'];

	$wrapper_class = esc_attr( $args['wrapper_class'] );
	$inner_class   = esc_attr( $args['inner_class'] );
	$show_overlay  = ! empty( $args['show_overlay'] );
	$overlay_class = $show_overlay ? ' hlm-media-wrap--overlay' : ' hlm-media-wrap--overlay-off';

	echo '<div class="' . $wrapper_class . $overlay_class . '" aria-hidden="true">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( 'video' === $type && $video_url ) {
		printf(
			'<div class="%1$s %1$s--video"><video class="hlm-media-video" autoplay muted loop playsinline preload="metadata"%2$s><source src="%3$s" type="%4$s" /></video></div>',
			$inner_class,
			$poster ? ' poster="' . esc_url( $poster ) . '"' : '',
			esc_url( $video_url ),
			esc_attr( hlm_media_video_mime( $video_url ) )
		);
		if ( $show_overlay ) {
			hlm_render_media_scrim( $args['overlay_strength'] );
		}
	} elseif ( 'image' === $type && $image_url ) {
		printf(
			'<div class="%1$s %1$s--image"><img class="hlm-media-image" src="%2$s" alt="%3$s" loading="eager" decoding="async" /></div>',
			$inner_class,
			esc_url( $image_url ),
			esc_attr( $alt )
		);
		if ( $show_overlay ) {
			hlm_render_media_scrim( $args['overlay_strength'] );
		}
	} else {
		$label = $args['placeholder_label'] ? $args['placeholder_label'] : HLM_HERO_VIDEO_PLACEHOLDER_LABEL;
		printf( '<div class="%1$s %1$s--placeholder">', $inner_class );
		if ( $args['show_play'] ) {
			echo '<span class="hlm-hero-media__play hlm-media-play-badge">';
			echo hlm_render_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</span>';
		}
		printf(
			'<span class="hlm-hero-media__label">%s</span>',
			esc_html( $label )
		);
		echo '</div>';
	}

	echo '</div>';
}

/**
 * Guess video MIME type from file extension.
 *
 * @param string $url Video URL.
 * @return string
 */
function hlm_media_video_mime( $url ) {
	$ext = strtolower( pathinfo( wp_parse_url( $url, PHP_URL_PATH ) ?? '', PATHINFO_EXTENSION ) );

	if ( 'webm' === $ext ) {
		return 'video/webm';
	}

	return 'video/mp4';
}

/**
 * Build media args from hero / page-hero block attributes.
 *
 * @param array $attributes Block attributes.
 * @return array
 */
function hlm_media_args_from_block( array $attributes ) {
	$type      = hlm_normalize_media_type( hlm_block_attr( $attributes, 'mediaType', '' ) );
	$video_url = hlm_block_attr( $attributes, 'mediaVideoUrl', '' );

	if ( 'video' === $type && ! $video_url ) {
		$video_url = hlm_default_hero_video_ref();
	}

	$placeholder_label = hlm_block_attr( $attributes, 'mediaPlaceholderLabel', '' );
	if ( ! $placeholder_label ) {
		$placeholder_label = HLM_HERO_VIDEO_PLACEHOLDER_LABEL;
	}

	return array(
		'type'              => $type,
		'image_url'         => hlm_block_attr( $attributes, 'mediaImageUrl', '' ),
		'image_id'          => (int) hlm_block_attr( $attributes, 'mediaImageId', 0 ),
		'image_alt'         => hlm_block_attr( $attributes, 'mediaImageAlt', '' ),
		'video_url'         => $video_url,
		'poster_url'        => hlm_block_attr( $attributes, 'mediaPosterUrl', '' ),
		'placeholder_label' => $placeholder_label,
		'show_overlay'      => (bool) hlm_block_attr( $attributes, 'mediaShowOverlay', true ),
		'overlay_strength'  => hlm_media_overlay_strength( hlm_block_attr( $attributes, 'mediaOverlayStrength', 'medium' ) ),
	);
}
