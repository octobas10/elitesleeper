<?php
/**
 * Block pattern category registration.
 *
 * Pattern files in /patterns are auto-discovered by WordPress from file headers.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register block pattern categories.
 */
function hlm_kadence_register_pattern_categories() {
	register_block_pattern_category(
		'hlm-pages',
		array(
			'label' => __( 'HLM Page Layouts', 'hlm-kadence' ),
		)
	);

	register_block_pattern_category(
		'hlm-sections',
		array(
			'label' => __( 'HLM Sections', 'hlm-kadence' ),
		)
	);

	register_block_pattern_category(
		'hlm-articles',
		array(
			'label' => __( 'HLM Article Layouts', 'hlm-kadence' ),
		)
	);
}
add_action( 'init', 'hlm_kadence_register_pattern_categories' );
