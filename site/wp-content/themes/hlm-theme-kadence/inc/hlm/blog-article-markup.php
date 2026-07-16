<?php
/**
 * Helpers for composing SEO blog posts as native Gutenberg block markup.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Serialize a parsed block array to storage markup.
 *
 * @param array<string, mixed> $block Parsed block.
 * @return string
 */
function hlm_serialize_block( array $block ): string {
	if ( function_exists( 'serialize_block' ) ) {
		return (string) serialize_block( $block );
	}

	return '';
}

/**
 * Open a constrained group block with optional className.
 *
 * @param string $class_name CSS class for the group.
 * @return string
 */
function hlm_article_group_open( string $class_name = '' ): string {
	$attrs = array(
		'layout' => array( 'type' => 'constrained' ),
	);

	if ( '' !== $class_name ) {
		$attrs['className'] = $class_name;
	}

	return sprintf(
		"<!-- wp:group %s -->\n<div class=\"wp-block-group%s\">",
		wp_json_encode( $attrs ),
		'' !== $class_name ? ' ' . esc_attr( $class_name ) : ''
	);
}

/**
 * Close a group block.
 *
 * @return string
 */
function hlm_article_group_close(): string {
	return "</div>\n<!-- /wp:group -->";
}

/**
 * Heading block markup.
 *
 * @param string $text  Heading text.
 * @param int    $level Heading level (2-4).
 * @return string
 */
function hlm_article_heading( string $text, int $level = 2 ): string {
	$level = max( 2, min( 4, $level ) );
	$tag   = 'h' . $level;
	$html  = sprintf( '<%1$s class="wp-block-heading">%2$s</%1$s>', $tag, esc_html( $text ) );

	return hlm_serialize_block(
		array(
			'blockName'    => 'core/heading',
			'attrs'        => array( 'level' => $level ),
			'innerBlocks'  => array(),
			'innerHTML'    => $html,
			'innerContent' => array( $html ),
		)
	);
}

/**
 * Paragraph block markup.
 *
 * @param string $html Paragraph HTML (links allowed).
 * @return string
 */
function hlm_article_paragraph( string $html ): string {
	$content = sprintf( '<p>%s</p>', wp_kses_post( $html ) );

	return hlm_serialize_block(
		array(
			'blockName'    => 'core/paragraph',
			'attrs'        => array(),
			'innerBlocks'  => array(),
			'innerHTML'    => $content,
			'innerContent' => array( $content ),
		)
	);
}

/**
 * Dynamic HLM block self-closing comment via WordPress serializer.
 *
 * @param string               $block_name Block name without namespace (e.g. article-mistake).
 * @param array<string, mixed> $attributes Block attributes.
 * @return string
 */
function hlm_article_block( string $block_name, array $attributes ): string {
	return hlm_serialize_block(
		array(
			'blockName'    => 'hlm/' . $block_name,
			'attrs'        => $attributes,
			'innerBlocks'  => array(),
			'innerHTML'    => '',
			'innerContent' => array(),
		)
	);
}
