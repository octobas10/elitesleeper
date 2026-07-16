<?php
/**
 * Comment and UGC lockdown.
 *
 * @package HLM_Security
 */

namespace HLM_Security;

defined( 'ABSPATH' ) || exit;

/**
 * Prevents public comment spam by closing comments site-wide.
 */
class Comment_Guard {

	/**
	 * Register comment guard hooks.
	 */
	public static function init() {
		add_filter( 'comments_open', array( __CLASS__, 'close_comments' ), 20, 2 );
		add_filter( 'pings_open', array( __CLASS__, 'close_comments' ), 20, 2 );
		add_action( 'pre_comment_on_post', array( __CLASS__, 'reject_comment_post' ) );
	}

	/**
	 * Close comments on all front-end posts.
	 *
	 * @param bool $open    Whether comments are open.
	 * @param int  $post_id Post ID.
	 * @return bool
	 */
	public static function close_comments( $open, $post_id ) {
		unset( $post_id );

		if ( is_admin() ) {
			return $open;
		}

		return false;
	}

	/**
	 * Reject direct comment POST attempts from the front end.
	 *
	 * @param int $post_id Post ID.
	 */
	public static function reject_comment_post( $post_id ) {
		unset( $post_id );

		if ( is_admin() ) {
			return;
		}

		wp_die(
			esc_html__( 'Comments are disabled on this site.', 'hlm-security' ),
			esc_html__( 'Comment Blocked', 'hlm-security' ),
			array( 'response' => 403 )
		);
	}
}
