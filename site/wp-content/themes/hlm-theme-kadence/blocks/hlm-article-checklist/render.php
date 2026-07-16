<?php
/**
 * Article checklist render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$headline   = hlm_block_attr( $attributes, 'headline', '' );
$paragraphs = hlm_block_attr( $attributes, 'paragraphs', array() );
$items      = hlm_block_attr( $attributes, 'items', array() );
$outro      = hlm_block_attr( $attributes, 'outro', array() );
$block_id   = hlm_block_id( 'hlm-article-checklist', $block );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-article-checklist">
	<?php if ( $headline ) : ?>
		<h2 class="hlm-article-checklist__headline"><?php echo esc_html( $headline ); ?></h2>
	<?php endif; ?>

	<?php foreach ( $paragraphs as $paragraph ) : ?>
		<?php if ( '' !== trim( (string) $paragraph ) ) : ?>
			<p class="hlm-article-checklist__intro"><?php echo esc_html( (string) $paragraph ); ?></p>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if ( ! empty( $items ) ) : ?>
		<ul class="hlm-article-checklist__list">
			<?php foreach ( $items as $item ) : ?>
				<?php if ( '' !== trim( (string) $item ) ) : ?>
					<li class="hlm-article-checklist__item"><?php echo esc_html( (string) $item ); ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php foreach ( $outro as $paragraph ) : ?>
		<?php if ( '' !== trim( (string) $paragraph ) ) : ?>
			<p class="hlm-article-checklist__outro"><?php echo esc_html( (string) $paragraph ); ?></p>
		<?php endif; ?>
	<?php endforeach; ?>
</section>
