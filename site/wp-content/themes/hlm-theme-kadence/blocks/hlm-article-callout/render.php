<?php
/**
 * Article callout render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant    = hlm_block_attr( $attributes, 'variant', 'help' );
$headline   = hlm_block_attr( $attributes, 'headline', '' );
$paragraphs = hlm_block_attr( $attributes, 'paragraphs', array() );
$body       = hlm_block_attr( $attributes, 'body', '' );
$block_id   = hlm_block_id( 'hlm-article-callout', $block );

if ( empty( $paragraphs ) && '' !== trim( $body ) ) {
	$paragraphs = array_filter( array_map( 'trim', preg_split( '/<\/p>\s*<p[^>]*>/i', wp_kses_post( $body ) ) ?: array() ) );
	$paragraphs = array_map(
		static function ( string $chunk ): string {
			return trim( preg_replace( '/^<p[^>]*>|<\/p>$/i', '', $chunk ) );
		},
		$paragraphs
	);
}

$variant_class = in_array( $variant, array( 'help', 'cta', 'tip' ), true ) ? $variant : 'help';
?>

<aside id="<?php echo esc_attr( $block_id ); ?>" class="hlm-article-callout hlm-article-callout--<?php echo esc_attr( $variant_class ); ?>">
	<?php if ( $headline ) : ?>
		<h2 class="hlm-article-callout__headline"><?php echo esc_html( $headline ); ?></h2>
	<?php endif; ?>
	<?php if ( ! empty( $paragraphs ) ) : ?>
		<div class="hlm-article-callout__body">
			<?php foreach ( $paragraphs as $paragraph ) : ?>
				<?php if ( '' !== trim( (string) $paragraph ) ) : ?>
					<p><?php echo wp_kses_post( (string) $paragraph ); ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</aside>
