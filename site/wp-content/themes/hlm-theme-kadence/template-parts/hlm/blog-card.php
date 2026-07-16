<?php
/**
 * HLM blog post card.
 *
 * @package HLM_Kadence
 *
 * @var array $args Template arguments.
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'index'   => 1,
		'animate' => true,
	)
);

$article_class = 'hlm-blog-card';
if ( $args['animate'] ) {
	$article_class .= ' hlm-animate hlm-animate--delay-' . min( (int) $args['index'], 6 );
}
?>

<article <?php post_class( $article_class ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="hlm-blog-card__media" href="<?php the_permalink(); ?>">
			<?php
			the_post_thumbnail(
				'hlm-blog-card',
				array(
					'class'    => 'hlm-blog-card__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => the_title_attribute( array( 'echo' => false ) ),
				)
			);
			?>
		</a>
	<?php else : ?>
		<a class="hlm-blog-card__media hlm-blog-card__media--placeholder" href="<?php the_permalink(); ?>" aria-hidden="true">
			<span class="hlm-blog-card__placeholder"></span>
		</a>
	<?php endif; ?>
	<div class="hlm-blog-card__body">
		<?php
		$categories = get_the_category();
		if ( ! empty( $categories ) ) :
			$primary = $categories[0];
			?>
			<div class="hlm-blog-card__categories">
				<a class="hlm-blog-card__category" href="<?php echo esc_url( get_category_link( $primary->term_id ) ); ?>">
					<?php echo esc_html( $primary->name ); ?>
				</a>
			</div>
		<?php endif; ?>
		<time class="hlm-blog-card__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>
		<h3 class="hlm-h4">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="hlm-body hlm-blog-card__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?>
		</p>
		<a class="hlm-blog-card__link" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read More', 'hlm-kadence' ); ?>
		</a>
	</div>
</article>
