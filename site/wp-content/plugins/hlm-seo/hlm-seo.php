<?php
/**
 * Plugin Name:       HLM SEO Ecosystem
 * Plugin URI:        https://higherlearningmarketers.com
 * Description:       Theme-agnostic SEO foundation - page structure, schema, internal linking, topic clusters, and lead-generation architecture. Defers meta output to Slim SEO when active.
 * Version:           1.0.5
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Higher Learning Marketers
 * License:           GPL-2.0-or-later
 * Text Domain:       hlm-seo
 *
 * @package HLM_SEO
 */

defined( 'ABSPATH' ) || exit;

define( 'HLM_SEO_VERSION', '1.0.5' );
define( 'HLM_SEO_FILE', __FILE__ );
define( 'HLM_SEO_DIR', plugin_dir_path( __FILE__ ) );
define( 'HLM_SEO_URL', plugin_dir_url( __FILE__ ) );

require_once HLM_SEO_DIR . 'includes/class-plugin.php';

HLM_SEO\Plugin::instance();
