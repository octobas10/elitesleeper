<?php
/**
 * Breadcrumb template.
 *
 * @package HLM_SEO
 *
 * @var array $args Template args.
 */

defined( 'ABSPATH' ) || exit;

$trail = $args['trail'] ?? array();
if ( count( $trail ) < 2 ) {
	return;
}
?>
<nav class="hlm-seo-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'hlm-seo' ); ?>">
	<ol class="hlm-seo-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php foreach ( $trail as $index => $crumb ) : ?>
			<li class="hlm-seo-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<?php if ( ! empty( $crumb['url'] ) ) : ?>
					<a href="<?php echo esc_url( $crumb['url'] ); ?>" itemprop="item">
						<span itemprop="name"><?php echo esc_html( $crumb['label'] ); ?></span>
					</a>
				<?php else : ?>
					<span itemprop="name"><?php echo esc_html( $crumb['label'] ); ?></span>
				<?php endif; ?>
				<meta itemprop="position" content="<?php echo esc_attr( (string) ( $index + 1 ) ); ?>" />
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
