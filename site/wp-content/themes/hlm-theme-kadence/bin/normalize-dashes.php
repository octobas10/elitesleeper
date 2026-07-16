<?php
/**
 * CLI runner: refresh page/post content and replace em/en dashes with hyphens.
 *
 * Usage (from host):
 *   docker exec hlm-wp php /var/www/html/wp-content/themes/hlm-theme-kadence/bin/normalize-dashes.php
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

if ( ! function_exists( 'hlm_migrate_normalize_dashes' ) ) {
	fwrite( STDERR, "hlm_migrate_normalize_dashes() not found. Is hlm-theme-kadence active?\n" );
	exit( 1 );
}

delete_option( 'hlm_normalize_dashes_v1' );
hlm_migrate_normalize_dashes();

$posts = get_posts(
	array(
		'post_type'      => array( 'post', 'page' ),
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	)
);

$dash_count = 0;

foreach ( $posts as $post ) {
	foreach ( array( 'post_content', 'post_title', 'post_excerpt' ) as $field ) {
		if ( str_contains( $post->$field, '—' ) ) {
			++$dash_count;
		}
	}
}

echo "Normalize dashes complete.\n";
echo "Published posts/pages checked: " . count( $posts ) . "\n";
echo "Fields still containing em/en dashes: {$dash_count}\n";
