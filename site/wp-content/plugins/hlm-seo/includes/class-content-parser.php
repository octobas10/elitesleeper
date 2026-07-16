<?php
/**
 * Block-aware content parsing for SEO plugins and registry fallbacks.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Renders Gutenberg storage markup into analyzable text/HTML for SEO tooling.
 */
class Content_Parser {

	/**
	 * Render block storage markup to HTML (for Slim SEO and similar plugins).
	 *
	 * @param string         $content Post content.
	 * @param \WP_Post|null  $post    Optional post context for dynamic blocks.
	 * @return string
	 */
	public static function get_rendered_content( string $content, ?\WP_Post $post = null ): string {
		if ( '' === trim( $content ) ) {
			return '';
		}

		if ( ! function_exists( 'has_blocks' ) || ! has_blocks( $content ) ) {
			return $content;
		}

		$previous_post = null;
		if ( $post instanceof \WP_Post ) {
			$previous_post = $GLOBALS['post'] ?? null;
			$GLOBALS['post'] = $post;
			setup_postdata( $post );
		}

		$rendered = (string) do_blocks( $content );

		if ( $post instanceof \WP_Post ) {
			if ( $previous_post instanceof \WP_Post ) {
				$GLOBALS['post'] = $previous_post;
				setup_postdata( $previous_post );
			} else {
				wp_reset_postdata();
			}
		}

		/**
		 * Filter rendered block HTML before SEO analysis.
		 *
		 * @param string        $rendered Rendered HTML.
		 * @param string        $content  Raw storage markup.
		 * @param \WP_Post|null $post     Post context.
		 */
		return (string) apply_filters( 'hlm_seo_rendered_content', $rendered, $content, $post );
	}

	/**
	 * Extract plain text from post content, including dynamic block attributes via render.
	 *
	 * @param string        $content Post content.
	 * @param \WP_Post|null $post    Optional post context.
	 * @return string
	 */
	public static function get_plain_text( string $content, ?\WP_Post $post = null ): string {
		$rendered = self::get_rendered_content( $content, $post );
		$source   = '' !== trim( $rendered ) ? $rendered : $content;
		$plain    = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $source ) ) );

		/**
		 * Filter plain text extracted for SEO descriptions and analysis.
		 *
		 * @param string        $plain   Plain text.
		 * @param string        $content Raw storage markup.
		 * @param \WP_Post|null $post    Post context.
		 */
		return (string) apply_filters( 'hlm_seo_plain_text', $plain, $content, $post );
	}

	/**
	 * Slim SEO post content filter - return rendered HTML for analysis.
	 *
	 * @param string   $content Raw post content.
	 * @param \WP_Post $post    Post object.
	 * @return string
	 */
	public static function filter_slim_post_content( string $content, \WP_Post $post ): string {
		$rendered = self::get_rendered_content( $content, $post );

		return '' !== trim( $rendered ) ? $rendered : $content;
	}

	/**
	 * Whether content is stored as native blocks (not classic HTML fragments).
	 *
	 * @param string $content Post content.
	 * @return bool
	 */
	public static function is_native_block_storage( string $content ): bool {
		if ( '' === trim( $content ) ) {
			return false;
		}

		if ( ! function_exists( 'has_blocks' ) || ! has_blocks( $content ) ) {
			return false;
		}

		return ! self::has_legacy_classic_markup( $content );
	}

	/**
	 * Detect classic HTML fragments or freeform blocks inside block storage.
	 *
	 * @param string $content Post content.
	 * @return bool
	 */
	public static function has_legacy_classic_markup( string $content ): bool {
		if ( ! function_exists( 'parse_blocks' ) ) {
			return false;
		}

		foreach ( parse_blocks( $content ) as $block ) {
			if ( self::block_is_classic_fragment( $block ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array<string, mixed> $block Parsed block.
	 * @return bool
	 */
	private static function block_is_classic_fragment( array $block ): bool {
		if ( empty( $block['blockName'] ) && '' !== trim( (string) ( $block['innerHTML'] ?? '' ) ) ) {
			return true;
		}

		if ( 'core/freeform' === ( $block['blockName'] ?? '' ) ) {
			return true;
		}

		if ( 'core/html' === ( $block['blockName'] ?? '' ) ) {
			return true;
		}

		foreach ( $block['innerBlocks'] ?? array() as $inner ) {
			if ( is_array( $inner ) && self::block_is_classic_fragment( $inner ) ) {
				return true;
			}
		}

		return false;
	}
}
