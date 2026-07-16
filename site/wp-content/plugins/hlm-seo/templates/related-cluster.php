<?php
/**
 * Related cluster posts template.
 *
 * @package HLM_SEO
 *
 * @var array $args Template args.
 */

defined( 'ABSPATH' ) || exit;

$cluster_label = $args['cluster_label'] ?? '';
$related       = $args['related'] ?? array();

if ( empty( $related ) ) {
	return;
}
?>
<section class="hlm-seo-related" aria-labelledby="hlm-seo-related-title">
	<h2 id="hlm-seo-related-title" class="hlm-seo-related__title">
		<?php
		if ( $cluster_label ) {
			printf(
				/* translators: %s: cluster label */
				esc_html__( 'More in %s', 'hlm-seo' ),
				esc_html( $cluster_label )
			);
		} else {
			esc_html_e( 'Related articles', 'hlm-seo' );
		}
		?>
	</h2>
	<ul class="hlm-seo-related__list">
		<?php foreach ( $related as $post ) : ?>
			<li>
				<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
					<?php echo esc_html( get_the_title( $post ) ); ?>
				</a>
				<?php if ( $post->post_excerpt ) : ?>
					<p><?php echo esc_html( wp_trim_words( $post->post_excerpt, 18 ) ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
