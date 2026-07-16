<?php
/**
 * Article compare (two-column) render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$headline         = hlm_block_attr( $attributes, 'headline', '' );
$left_title       = hlm_block_attr( $attributes, 'leftTitle', '' );
$right_title      = hlm_block_attr( $attributes, 'rightTitle', '' );
$left_paragraphs  = hlm_block_attr( $attributes, 'leftParagraphs', array() );
$right_paragraphs = hlm_block_attr( $attributes, 'rightParagraphs', array() );
$footnote         = hlm_block_attr( $attributes, 'footnote', array() );
$block_id         = hlm_block_id( 'hlm-article-compare', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-article-compare">
	<?php if ( $headline ) : ?>
		<h2 class="hlm-article-compare__headline"><?php echo esc_html( $headline ); ?></h2>
	<?php endif; ?>

	<div class="hlm-article-compare__grid">
		<div class="hlm-article-compare__column hlm-article-compare__column--left">
			<?php if ( $left_title ) : ?>
				<h3 class="hlm-article-compare__title"><?php echo esc_html( $left_title ); ?></h3>
			<?php endif; ?>
			<?php foreach ( $left_paragraphs as $paragraph ) : ?>
				<?php if ( '' !== trim( (string) $paragraph ) ) : ?>
					<p class="hlm-article-compare__text"><?php echo esc_html( (string) $paragraph ); ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="hlm-article-compare__column hlm-article-compare__column--right">
			<?php if ( $right_title ) : ?>
				<h3 class="hlm-article-compare__title"><?php echo esc_html( $right_title ); ?></h3>
			<?php endif; ?>
			<?php foreach ( $right_paragraphs as $paragraph ) : ?>
				<?php if ( '' !== trim( (string) $paragraph ) ) : ?>
					<p class="hlm-article-compare__text"><?php echo esc_html( (string) $paragraph ); ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>

	<?php foreach ( $footnote as $paragraph ) : ?>
		<?php if ( '' !== trim( (string) $paragraph ) ) : ?>
			<p class="hlm-article-compare__footnote"><?php echo esc_html( (string) $paragraph ); ?></p>
		<?php endif; ?>
	<?php endforeach; ?>
</section>
