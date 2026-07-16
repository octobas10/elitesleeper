<?php
/**
 * Article mistake card render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$number     = hlm_block_attr( $attributes, 'number', '1' );
$title      = hlm_block_attr( $attributes, 'title', '' );
$summary    = hlm_block_attr( $attributes, 'summary', '' );
$recommendation = hlm_block_attr( $attributes, 'recommendation', '' );
$why_it_matters = hlm_block_attr( $attributes, 'whyItMatters', '' );
$paragraphs = hlm_block_attr( $attributes, 'paragraphs', array() );
$block_id   = hlm_block_id( 'hlm-article-mistake', $block );

// Back-compat: map legacy paragraphs array to semantic fields.
if ( '' === $summary && ! empty( $paragraphs[0] ) ) {
	$summary = (string) $paragraphs[0];
}
if ( '' === $recommendation && ! empty( $paragraphs[1] ) ) {
	$recommendation = (string) $paragraphs[1];
}
if ( '' === $why_it_matters && ! empty( $paragraphs[2] ) ) {
	$why_it_matters = (string) $paragraphs[2];
}

$body_paragraphs = array_filter(
	array( $summary, $recommendation, $why_it_matters ),
	static fn( $text ) => '' !== trim( (string) $text )
);

if ( '' === $title && empty( $body_paragraphs ) ) {
	return;
}
?>

<article id="<?php echo esc_attr( $block_id ); ?>" class="hlm-article-mistake">
	<div class="hlm-article-mistake__badge" aria-hidden="true">
		<span class="hlm-article-mistake__number"><?php echo esc_html( $number ); ?></span>
	</div>
	<div class="hlm-article-mistake__body">
		<?php if ( $title ) : ?>
			<h3 class="hlm-article-mistake__title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<?php foreach ( $body_paragraphs as $paragraph ) : ?>
			<p class="hlm-article-mistake__text"><?php echo esc_html( (string) $paragraph ); ?></p>
		<?php endforeach; ?>
	</div>
</article>
