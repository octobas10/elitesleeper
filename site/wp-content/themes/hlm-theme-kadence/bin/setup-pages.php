<?php
/**
 * CLI runner: create all HLM pages immediately.
 *
 * Usage (from host):
 *   docker exec hlm-wp php /var/www/html/wp-content/themes/hlm-theme-kadence/bin/setup-pages.php
 *
 * @package HLM_Kadence
 */

$wp_load = dirname( __DIR__, 5 ) . '/wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	$wp_load = '/var/www/html/wp-load.php';
}

if ( ! file_exists( $wp_load ) ) {
	fwrite( STDERR, "Could not find wp-load.php\n" );
	exit( 1 );
}

require $wp_load;

delete_option( 'hlm_site_pages_v2' );
delete_option( 'hlm_sample_posts_v1' );
hlm_setup_site_pages();

$pages = get_pages(
	array(
		'post_status' => 'publish',
		'sort_column' => 'post_title',
	)
);

echo "HLM pages ready:\n";
foreach ( $pages as $page ) {
	$front = ( (int) get_option( 'page_on_front' ) === (int) $page->ID ) ? ' [front page]' : '';
	echo "- {$page->post_title} (/{$page->post_name}/){$front}\n";
}
