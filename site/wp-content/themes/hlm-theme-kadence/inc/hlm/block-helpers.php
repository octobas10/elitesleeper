<?php
/**
 * Shared helpers for HLM Gutenberg block render templates.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a block attribute with fallback.
 *
 * @param array  $attributes Block attributes.
 * @param string $key        Attribute key.
 * @param mixed  $default    Default value.
 * @return mixed
 */
function hlm_block_attr( array $attributes, $key, $default = '' ) {
	if ( ! array_key_exists( $key, $attributes ) || null === $attributes[ $key ] ) {
		return $default;
	}

	$value = $attributes[ $key ];

	if ( is_string( $value ) && '' === $value && '' !== $default && ! is_array( $default ) && ! is_bool( $default ) ) {
		return $default;
	}

	if ( is_array( $value ) && empty( $value ) && ! empty( $default ) ) {
		return $default;
	}

	return $value;
}

/**
 * Render headline HTML allowing limited markup.
 *
 * @param string $headline Headline text.
 * @param string $class    CSS class.
 * @param string $tag      HTML tag.
 * @return void
 */
function hlm_block_headline( $headline, $class = 'hlm-h2', $tag = 'h2' ) {
	$html = nl2br( esc_html( $headline ) );

	if ( false !== strpos( $headline, '<' ) ) {
		$html = wp_kses(
			$headline,
			array(
				'br' => array(),
				'em' => array(),
			)
		);
	}

	printf( '<%1$s class="%2$s">%3$s</%1$s>', esc_attr( $tag ), esc_attr( $class ), $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Unique block section id.
 *
 * @param string          $prefix Id prefix.
 * @param array|WP_Block|null $block  Block instance.
 * @return string
 */
function hlm_block_id( $prefix, $block = null ) {
	if ( is_array( $block ) && ! empty( $block['id'] ) ) {
		return $prefix . '-' . $block['id'];
	}

	if ( $block instanceof WP_Block && ! empty( $block->attributes['anchor'] ) ) {
		return sanitize_title( $block->attributes['anchor'] );
	}

	return $prefix . '-' . wp_unique_id();
}

/**
 * Normalize multiline text from block attributes (JSON newlines may be lost).
 *
 * @param string $text Raw text.
 * @return string
 */
function hlm_normalize_multiline( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}

	if ( str_contains( $text, '\n' ) ) {
		$text = stripcslashes( $text );
	}

	// Block comment JSON sometimes stores "\n\n" as literal "nn".
	$text = preg_replace( '/\.nn/u', ".\n\n", $text );

	return $text;
}

/**
 * Render the HLM page hero section.
 *
 * @param array $args Hero arguments.
 * @return void
 */
function hlm_render_page_hero( array $args = array() ) {
	get_template_part( 'template-parts/hlm/page-hero', null, $args );
}

/**
 * Render the HLM CTA band section.
 *
 * @param array $args CTA arguments.
 * @return void
 */
function hlm_render_cta_band( array $args = array() ) {
	get_template_part( 'template-parts/hlm/cta-band', null, $args );
}

/**
 * Render a blog post card for the current post in the loop.
 *
 * @param array $args Card arguments.
 * @return void
 */
function hlm_render_blog_card( array $args = array() ) {
	get_template_part( 'template-parts/hlm/blog-card', null, $args );
}

/**
 * Render a blog card for a specific post (outside the main loop).
 *
 * @param WP_Post $post  Post object.
 * @param array   $args  Card arguments.
 * @return void
 */
function hlm_render_blog_card_for_post( WP_Post $post, array $args = array() ) {
	$previous = $GLOBALS['post'] ?? null;

	$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	setup_postdata( $post );
	hlm_render_blog_card( $args );

	if ( $previous instanceof WP_Post ) {
		$GLOBALS['post'] = $previous; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $previous );
	} else {
		wp_reset_postdata();
	}
}
