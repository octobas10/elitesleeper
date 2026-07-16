<?php
/**
 * Blog posts block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline      = hlm_block_attr( $attributes, 'overline', 'Latest Insights' );
$headline      = hlm_block_attr( $attributes, 'headline', 'From Our Blog' );
$description   = hlm_block_attr( $attributes, 'description', '' );
$post_count    = max( 1, min( 6, (int) hlm_block_attr( $attributes, 'postCount', 3 ) ) );
$view_all_text = hlm_block_attr( $attributes, 'viewAllText', 'View All Articles' );
$view_all_url  = hlm_block_attr( $attributes, 'viewAllUrl', '/blog/' );
$block_id      = hlm_block_id( 'hlm-blog', $block );

$query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $post_count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="hlm-blog hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-animate">
			<?php if ( $overline ) : ?>
				<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
			<?php endif; ?>
			<?php hlm_block_headline( $headline ); ?>
			<?php if ( $description ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $query->have_posts() ) : ?>
			<div class="hlm-blog-grid">
				<?php
				$index = 0;
				while ( $query->have_posts() ) :
					$query->the_post();
					++$index;
					hlm_render_blog_card( array( 'index' => $index ) );
				endwhile;
				?>
			</div>
			<?php wp_reset_postdata(); ?>

			<?php if ( $view_all_text && $view_all_url ) : ?>
				<div class="hlm-blog__actions hlm-animate">
					<a href="<?php echo esc_url( $view_all_url ); ?>" class="hlm-btn hlm-btn-outline hlm-btn-arrow">
						<?php echo esc_html( $view_all_text ); ?>
					</a>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<p class="hlm-blog__empty hlm-body-lg"><?php esc_html_e( 'No blog posts yet. Publish posts to see them here.', 'hlm-kadence' ); ?></p>
		<?php endif; ?>
	</div>
</section>
