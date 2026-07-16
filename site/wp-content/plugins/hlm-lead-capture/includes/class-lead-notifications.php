<?php
/**
 * Email alerts for new lead submissions.
 *
 * Sends via wp_mail() — use FluentSMTP or WP Mail SMTP for delivery.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sends admin notifications when leads are stored.
 */
class HLM_Lead_Notifications {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'hlm_lead_capture_saved', array( __CLASS__, 'maybe_send' ), 10, 3 );
	}

	/**
	 * Send notification email for a stored lead.
	 *
	 * @param string   $form_type Form type slug.
	 * @param array    $data      Sanitized submission.
	 * @param int|null $lead_id   Database row ID.
	 */
	public static function maybe_send( $form_type, array $data, $lead_id = null ) {
		if ( ! HLM_Lead_Capture_Settings::notify_enabled() ) {
			return;
		}

		$lead = $lead_id ? HLM_Lead_Storage::get( (int) $lead_id ) : null;

		if ( $lead && 'rejected' === $lead->status && ! HLM_Lead_Capture_Settings::notify_include_rejected() ) {
			return;
		}

		$recipients = HLM_Lead_Capture_Settings::notify_recipients();

		if ( empty( $recipients ) ) {
			return;
		}

		$subject = self::build_subject( $form_type, $data, $lead );
		$body    = self::build_body( $form_type, $data, $lead );
		$sent    = wp_mail( $recipients, $subject, $body, self::mail_headers() );

		if ( ! $sent ) {
			self::log_mail_failure( $recipients, $subject );
		}
	}

	/**
	 * Send a test notification to configured recipients.
	 *
	 * @return true|\WP_Error
	 */
	public static function send_test() {
		$recipients = HLM_Lead_Capture_Settings::notify_recipients();

		if ( empty( $recipients ) ) {
			return new WP_Error(
				'hlm_no_recipients',
				__( 'No notification recipients are configured.', 'hlm-lead-capture' )
			);
		}

		$subject = sprintf(
			/* translators: %s: site name */
			__( '[%s] Lead capture test email', 'hlm-lead-capture' ),
			wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES )
		);

		$body = __( 'This is a test email from HLM Lead Capture.', 'hlm-lead-capture' ) . "\n\n"
			. sprintf(
				/* translators: %s: site URL */
				__( 'Site: %s', 'hlm-lead-capture' ),
				home_url( '/' )
			) . "\n"
			. sprintf(
				/* translators: %s: recipient list */
				__( 'Recipients: %s', 'hlm-lead-capture' ),
				implode( ', ', $recipients )
			);

		$sent = wp_mail( $recipients, $subject, $body, self::mail_headers() );

		if ( ! $sent ) {
			self::log_mail_failure( $recipients, $subject );

			$message = __( 'Email could not be sent.', 'hlm-lead-capture' );

			if ( ! HLM_Lead_Capture_Settings::smtp_plugin() ) {
				$message .= ' ' . __( 'Install and configure FluentSMTP or WP Mail SMTP, then try again.', 'hlm-lead-capture' );
			} else {
				$message .= ' ' . __( 'Check your SMTP plugin settings and try again.', 'hlm-lead-capture' );
			}

			return new WP_Error( 'hlm_mail_failed', $message );
		}

		return true;
	}

	/**
	 * Build headers with a valid From address (avoids wordpress@localhost failures).
	 *
	 * @return string[]
	 */
	private static function mail_headers() {
		$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
		$from    = HLM_Lead_Capture_Settings::mail_from_email();

		if ( is_email( $from ) ) {
			$name      = HLM_Lead_Capture_Settings::mail_from_name();
			$headers[] = sprintf( 'From: %s <%s>', $name, $from );
		}

		return $headers;
	}

	/**
	 * Log wp_mail() failures for debugging.
	 *
	 * @param string[] $recipients Recipient emails.
	 * @param string   $subject    Email subject.
	 */
	private static function log_mail_failure( array $recipients, $subject ) {
		$detail = '';

		if ( isset( $GLOBALS['phpmailer'] ) && is_object( $GLOBALS['phpmailer'] ) && ! empty( $GLOBALS['phpmailer']->ErrorInfo ) ) {
			$detail = $GLOBALS['phpmailer']->ErrorInfo;
		}

		error_log(
			sprintf(
				'HLM Lead Capture: wp_mail failed for [%s] subject "%s"%s',
				implode( ', ', $recipients ),
				$subject,
				$detail ? ' — ' . $detail : ''
			)
		);
	}

	/**
	 * Build email subject line.
	 *
	 * @param string      $form_type Form type.
	 * @param array       $data      Submission data.
	 * @param object|null $lead      DB row.
	 * @return string
	 */
	private static function build_subject( $form_type, array $data, $lead ) {
		$template = HLM_Lead_Capture_Settings::get( 'notify_subject', '' );

		if ( $template ) {
			return self::replace_tokens( $template, $form_type, $data, $lead );
		}

		$name = trim( ( $data['first_name'] ?? '' ) . ' ' . ( $data['last_name'] ?? '' ) );

		if ( 'university' === $form_type && ! empty( $data['institution_name'] ) ) {
			$name = $data['institution_name'];
		}

		$label = sprintf(
			/* translators: 1: form type 2: submitter name */
			__( 'New %1$s lead: %2$s', 'hlm-lead-capture' ),
			ucfirst( $form_type ),
			$name ?: __( 'Unknown', 'hlm-lead-capture' )
		);

		if ( $lead && 'failed' === $lead->elite_status ) {
			$label .= ' [' . __( 'Elite API failed', 'hlm-lead-capture' ) . ']';
		}

		return $label;
	}

	/**
	 * Build plain-text email body.
	 *
	 * @param string      $form_type Form type.
	 * @param array       $data      Submission data.
	 * @param object|null $lead      DB row.
	 * @return string
	 */
	private static function build_body( $form_type, array $data, $lead ) {
		$lines   = array();
		$lines[] = __( 'A new lead was submitted on your site.', 'hlm-lead-capture' );
		$lines[] = '';
		$lines[] = '---';
		$lines[] = sprintf( '%s: %s', __( 'Form', 'hlm-lead-capture' ), ucfirst( $form_type ) );

		if ( $lead ) {
			$lines[] = sprintf( '%s: #%d', __( 'Lead ID', 'hlm-lead-capture' ), (int) $lead->id );
			$lines[] = sprintf( '%s: %s', __( 'Submitted', 'hlm-lead-capture' ), $lead->created_at );

			if ( $lead->elite_status ) {
				$lines[] = sprintf( '%s: %s', __( 'Elite API', 'hlm-lead-capture' ), $lead->elite_status );
			}
			if ( $lead->elite_note ) {
				$lines[] = sprintf( '%s: %s', __( 'Elite note', 'hlm-lead-capture' ), $lead->elite_note );
			}
		}

		$lines[] = '---';
		$lines[] = sprintf( '%s: %s %s', __( 'Name', 'hlm-lead-capture' ), $data['first_name'] ?? '', $data['last_name'] ?? '' );
		$lines[] = sprintf( '%s: %s', __( 'Email', 'hlm-lead-capture' ), $data['email'] ?? '' );
		$lines[] = sprintf( '%s: %s', __( 'Phone', 'hlm-lead-capture' ), $data['phone'] ?? '' );

		if ( ! empty( $data['address'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Address', 'hlm-lead-capture' ), $data['address'] );
		}

		$location = array_filter(
			array(
				$data['city'] ?? '',
				$data['state'] ?? '',
				$data['zip'] ?? '',
			)
		);
		if ( $location ) {
			$lines[] = sprintf( '%s: %s', __( 'Location', 'hlm-lead-capture' ), implode( ', ', $location ) );
		}

		if ( ! empty( $data['institution_name'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Institution', 'hlm-lead-capture' ), $data['institution_name'] );
		}
		if ( ! empty( $data['category'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'School category', 'hlm-lead-capture' ), $data['category'] );
		}
		if ( ! empty( $data['campus'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Campus', 'hlm-lead-capture' ), $data['campus'] );
		}
		if ( ! empty( $data['program_of_interest'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Program', 'hlm-lead-capture' ), $data['program_of_interest'] );
		}
		if ( ! empty( $data['education_level'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Education level', 'hlm-lead-capture' ), $data['education_level'] );
		}
		if ( ! empty( $data['desired_degree'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Desired degree', 'hlm-lead-capture' ), $data['desired_degree'] );
		}
		if ( ! empty( $data['grad_year'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Grad year', 'hlm-lead-capture' ), $data['grad_year'] );
		}
		if ( ! empty( $data['page_url'] ) ) {
			$lines[] = sprintf( '%s: %s', __( 'Source page', 'hlm-lead-capture' ), $data['page_url'] );
		}

		if ( $lead && ! empty( $lead->id ) ) {
			$lines[] = '';
			$lines[] = sprintf(
				'%s: %s',
				__( 'View in admin', 'hlm-lead-capture' ),
				HLM_Lead_Storage::admin_view_url( (int) $lead->id )
			);
		}

		return implode( "\n", $lines );
	}

	/**
	 * Replace simple tokens in a custom subject template.
	 *
	 * @param string      $template  Subject template.
	 * @param string      $form_type Form type.
	 * @param array       $data      Submission data.
	 * @param object|null $lead      DB row.
	 * @return string
	 */
	private static function replace_tokens( $template, $form_type, array $data, $lead ) {
		$tokens = array(
			'{form_type}' => ucfirst( $form_type ),
			'{first_name}' => $data['first_name'] ?? '',
			'{last_name}'  => $data['last_name'] ?? '',
			'{email}'      => $data['email'] ?? '',
			'{campus}'     => $data['campus'] ?? '',
			'{program}'    => $data['program_of_interest'] ?? '',
		);

		return str_replace( array_keys( $tokens ), array_values( $tokens ), $template );
	}
}
