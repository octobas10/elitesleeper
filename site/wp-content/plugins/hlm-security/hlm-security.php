<?php
/**
 * Plugin Name:       HLM Security Shield
 * Description:       Ecosystem-wide protection - security headers, rate limiting, bot defense, comment lockdown, login guard, and form abuse prevention.
 * Version:           1.0.2
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Higher Learning Marketers
 * License:           GPL-2.0-or-later
 * Text Domain:       hlm-security
 *
 * @package HLM_Security
 */

defined( 'ABSPATH' ) || exit;

define( 'HLM_SEC_VERSION', '1.0.2' );
define( 'HLM_SEC_FILE', __FILE__ );
define( 'HLM_SEC_DIR', plugin_dir_path( __FILE__ ) );
define( 'HLM_SEC_URL', plugin_dir_url( __FILE__ ) );

require_once HLM_SEC_DIR . 'includes/class-rate-limiter.php';
require_once HLM_SEC_DIR . 'includes/class-form-shield.php';
require_once HLM_SEC_DIR . 'includes/class-login-guard.php';
require_once HLM_SEC_DIR . 'includes/class-comment-guard.php';
require_once HLM_SEC_DIR . 'includes/class-hardening.php';
require_once HLM_SEC_DIR . 'includes/class-plugin.php';

HLM_Security\Plugin::instance();
