<?php
/**
 * Admin settings page template.
 *
 * @package HLM_Lead_Capture
 *
 * @var array  $settings
 * @var array  $catalog
 * @var string $source
 * @var bool   $promo_locked
 * @var int    $lead_count
 */

defined( 'ABSPATH' ) || exit;

$is_live   = '1' === HLM_Lead_Capture_Settings::elite_lead_mode();
$is_active = HLM_Lead_Capture_Settings::elite_is_enabled();
$option    = HLM_Lead_Capture_Settings::OPTION;

$tabs = array(
	'elite'    => __( 'Elite API', 'hlm-lead-capture' ),
	'compliance' => __( 'Compliance', 'hlm-lead-capture' ),
	'messages' => __( 'Messages', 'hlm-lead-capture' ),
	'email'    => __( 'Email Alerts', 'hlm-lead-capture' ),
	'storage'  => __( 'Storage', 'hlm-lead-capture' ),
);
?>
<div class="wrap hlm-lc-admin">
	<h1><?php esc_html_e( 'Lead Capture Settings', 'hlm-lead-capture' ); ?></h1>

	<?php if ( isset( $_GET['settings-updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'hlm-lead-capture' ); ?></p></div>
	<?php endif; ?>

	<?php if ( 'sent' === ( $_GET['hlm_email_test'] ?? '' ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Test email sent. Check your inbox (and spam folder).', 'hlm-lead-capture' ); ?></p></div>
	<?php endif; ?>

	<?php if ( 'failed' === ( $_GET['hlm_email_test'] ?? '' ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="notice notice-error is-dismissible"><p>
			<?php
			$error = sanitize_text_field( wp_unslash( $_GET['hlm_email_error'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo esc_html( $error ?: __( 'Test email could not be sent.', 'hlm-lead-capture' ) );
			?>
		</p></div>
	<?php endif; ?>

	<div class="hlm-lc-admin__status">
		<div class="hlm-lc-admin__badge <?php echo $is_active ? 'is-active' : 'is-inactive'; ?>">
			<strong><?php echo $is_active ? esc_html__( 'Elite API: Active', 'hlm-lead-capture' ) : esc_html__( 'Elite API: Inactive', 'hlm-lead-capture' ); ?></strong>
			<span>
				<?php
				if ( $is_active ) {
					echo $is_live
						? esc_html__( 'Posting live leads', 'hlm-lead-capture' )
						: esc_html__( 'Test mode — leads tagged lead_mode=0', 'hlm-lead-capture' );
				} else {
					esc_html_e( 'Forms save locally only', 'hlm-lead-capture' );
				}
				?>
			</span>
		</div>
		<div class="hlm-lc-admin__badge hlm-lc-admin__badge--muted">
			<strong><?php esc_html_e( 'Program catalog', 'hlm-lead-capture' ); ?></strong>
			<span>
				<?php
				printf(
					/* translators: 1: institutions 2: campuses 3: programs */
					esc_html__( '%1$d schools · %2$d campuses · %3$d programs', 'hlm-lead-capture' ),
					(int) $catalog['institutions'],
					(int) $catalog['campuses'],
					(int) $catalog['programs']
				);
				?>
			</span>
		</div>
		<a class="hlm-lc-admin__badge hlm-lc-admin__badge--link" href="<?php echo esc_url( admin_url( 'admin.php?page=hlm-leads' ) ); ?>">
			<strong><?php esc_html_e( 'View leads', 'hlm-lead-capture' ); ?></strong>
			<span><?php printf( esc_html__( '%d stored submissions', 'hlm-lead-capture' ), (int) $lead_count ); ?></span>
		</a>
	</div>

	<form method="post" action="options.php" class="hlm-lc-settings">
		<?php settings_fields( 'hlm_lead_capture' ); ?>

		<nav class="hlm-lc-settings__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Settings sections', 'hlm-lead-capture' ); ?>">
			<?php foreach ( $tabs as $id => $label ) : ?>
				<button
					type="button"
					class="hlm-lc-settings__tab"
					role="tab"
					id="hlm-tab-<?php echo esc_attr( $id ); ?>"
					data-tab="<?php echo esc_attr( $id ); ?>"
					aria-controls="hlm-panel-<?php echo esc_attr( $id ); ?>"
					aria-selected="false"
					tabindex="-1"
				><?php echo esc_html( $label ); ?></button>
			<?php endforeach; ?>
		</nav>

		<div class="hlm-lc-settings__panels">
			<section
				class="hlm-lc-settings__panel"
				role="tabpanel"
				id="hlm-panel-elite"
				data-tab="elite"
				aria-labelledby="hlm-tab-elite"
				hidden
			>
				<header class="hlm-lc-settings__panel-head">
					<h2><?php esc_html_e( 'Elite Biz Panel API', 'hlm-lead-capture' ); ?></h2>
					<p><?php esc_html_e( 'Connects student and Berkeley submissions to elitebizpanel.com/edu/postprocess. Start in test mode until Elite activates your campaign.', 'hlm-lead-capture' ); ?></p>
				</header>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable API posting', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[elite_enabled]" value="1" <?php checked( $settings['elite_enabled'] ); ?>>
								<?php esc_html_e( 'Post student & Berkeley leads to Elite Biz Panel', 'hlm-lead-capture' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hlm-elite-promo"><?php esc_html_e( 'Promo code', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<input type="text" class="regular-text" id="hlm-elite-promo" name="<?php echo esc_attr( $option ); ?>[elite_promo_code]" value="<?php echo esc_attr( $promo_locked ? '••••••••' : $settings['elite_promo_code'] ); ?>" <?php disabled( $promo_locked ); ?>>
							<?php if ( $promo_locked ) : ?>
								<p class="description"><?php esc_html_e( 'Overridden by HLM_ELITE_PROMO_CODE in wp-config.php.', 'hlm-lead-capture' ); ?></p>
							<?php else : ?>
								<p class="description"><?php esc_html_e( 'Provided after Higher Education Marketers registration.', 'hlm-lead-capture' ); ?></p>
							<?php endif; ?>
							<p class="description"><?php printf( esc_html__( 'Config source: %s', 'hlm-lead-capture' ), esc_html( $source ) ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Lead mode', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[elite_lead_mode]" value="1" <?php checked( $settings['elite_lead_mode'], '1' ); ?> <?php disabled( defined( 'HLM_ELITE_LEAD_MODE' ) ); ?>>
								<?php esc_html_e( 'Live leads (lead_mode=1)', 'hlm-lead-capture' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Leave unchecked for test leads. Only enable after Elite confirms campaign activation.', 'hlm-lead-capture' ); ?></p>
							<?php if ( $is_live && $is_active ) : ?>
								<p class="hlm-lc-admin__warning">⚠ <?php esc_html_e( 'Live mode is on — submissions count as real leads.', 'hlm-lead-capture' ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hlm-elite-sub-id"><?php esc_html_e( 'Default sub_id', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<input type="text" class="regular-text" id="hlm-elite-sub-id" name="<?php echo esc_attr( $option ); ?>[elite_sub_id]" value="<?php echo esc_attr( $settings['elite_sub_id'] ); ?>" placeholder="web-hlm">
							<p class="description"><?php esc_html_e( 'Optional affiliate tracking code. Falls back to form-type + hostname when empty.', 'hlm-lead-capture' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'API error handling', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[elite_fail_silent]" value="1" <?php checked( $settings['elite_fail_silent'] ); ?>>
								<?php esc_html_e( 'Show success to visitor even if Elite rejects the lead', 'hlm-lead-capture' ); ?>
							</label>
						</td>
					</tr>
				</table>
			</section>

			<section
				class="hlm-lc-settings__panel"
				role="tabpanel"
				id="hlm-panel-compliance"
				data-tab="compliance"
				aria-labelledby="hlm-tab-compliance"
				hidden
			>
				<header class="hlm-lc-settings__panel-head">
					<h2><?php esc_html_e( 'TCPA & Compliance', 'hlm-lead-capture' ); ?></h2>
					<p><?php esc_html_e( 'TrustedForm and Jornaya scripts populate hidden fields when they load. Submissions still go through if a visitor’s browser blocks those scripts (ad blockers, VPN privacy, etc.).', 'hlm-lead-capture' ); ?></p>
				</header>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Scripts', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[enable_trustedform]" value="1" <?php checked( $settings['enable_trustedform'] ); ?>>
								<?php esc_html_e( 'Load TrustedForm (trustedformcerturl)', 'hlm-lead-capture' ); ?>
							</label>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[enable_leadid]" value="1" <?php checked( $settings['enable_leadid'] ); ?>>
								<?php esc_html_e( 'Load Jornaya LeadiD (universal_leadid)', 'hlm-lead-capture' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hlm-tcpa-student"><?php esc_html_e( 'Student TCPA text', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<textarea class="large-text" rows="3" maxlength="200" id="hlm-tcpa-student" name="<?php echo esc_attr( $option ); ?>[tcpa_text_student]"><?php echo esc_textarea( $settings['tcpa_text_student'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Max 200 characters — shown on the consent checkbox and sent as tcpa_text.', 'hlm-lead-capture' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hlm-tcpa-berkeley"><?php esc_html_e( 'Berkeley TCPA text', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<textarea class="large-text" rows="3" maxlength="200" id="hlm-tcpa-berkeley" name="<?php echo esc_attr( $option ); ?>[tcpa_text_berkeley]"><?php echo esc_textarea( $settings['tcpa_text_berkeley'] ); ?></textarea>
						</td>
					</tr>
				</table>
			</section>

			<section
				class="hlm-lc-settings__panel"
				role="tabpanel"
				id="hlm-panel-messages"
				data-tab="messages"
				aria-labelledby="hlm-tab-messages"
				hidden
			>
				<header class="hlm-lc-settings__panel-head">
					<h2><?php esc_html_e( 'Form Messages', 'hlm-lead-capture' ); ?></h2>
					<p><?php esc_html_e( 'Text shown to visitors after submit or when validation fails.', 'hlm-lead-capture' ); ?></p>
				</header>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="hlm-msg-success"><?php esc_html_e( 'Success message', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<input type="text" class="large-text" id="hlm-msg-success" name="<?php echo esc_attr( $option ); ?>[message_success]" value="<?php echo esc_attr( $settings['message_success'] ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hlm-msg-error"><?php esc_html_e( 'Error message', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<input type="text" class="large-text" id="hlm-msg-error" name="<?php echo esc_attr( $option ); ?>[message_error]" value="<?php echo esc_attr( $settings['message_error'] ); ?>">
						</td>
					</tr>
				</table>
			</section>

			<section
				class="hlm-lc-settings__panel"
				role="tabpanel"
				id="hlm-panel-email"
				data-tab="email"
				aria-labelledby="hlm-tab-email"
				hidden
			>
				<header class="hlm-lc-settings__panel-head">
					<h2><?php esc_html_e( 'Email Notifications', 'hlm-lead-capture' ); ?></h2>
					<p><?php esc_html_e( 'Choose who gets alerted when a lead is saved. Mail delivery is handled by FluentSMTP or WP Mail SMTP when installed.', 'hlm-lead-capture' ); ?></p>
				</header>

				<?php
				$smtp_plugins = HLM_Lead_Capture_Settings::smtp_plugins();
				$active_smtp  = HLM_Lead_Capture_Settings::smtp_plugin();
				?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Mail delivery', 'hlm-lead-capture' ); ?></th>
						<td>
							<div class="hlm-smtp-plugins">
								<?php foreach ( $smtp_plugins as $smtp ) : ?>
									<div class="hlm-smtp-plugin hlm-smtp-plugin--<?php echo esc_attr( $smtp['status'] ); ?>">
										<div class="hlm-smtp-plugin__head">
											<strong class="hlm-smtp-plugin__name"><?php echo esc_html( $smtp['label'] ); ?></strong>
											<?php if ( 'active' === $smtp['status'] ) : ?>
												<span class="hlm-smtp-plugin__badge hlm-smtp-plugin__badge--active"><?php esc_html_e( 'Active', 'hlm-lead-capture' ); ?></span>
											<?php elseif ( 'inactive' === $smtp['status'] ) : ?>
												<span class="hlm-smtp-plugin__badge"><?php esc_html_e( 'Installed', 'hlm-lead-capture' ); ?></span>
											<?php else : ?>
												<span class="hlm-smtp-plugin__badge"><?php esc_html_e( 'Not installed', 'hlm-lead-capture' ); ?></span>
											<?php endif; ?>
										</div>
										<div class="hlm-smtp-plugin__actions">
											<?php if ( 'active' === $smtp['status'] ) : ?>
												<a href="<?php echo esc_url( $smtp['settings_url'] ); ?>" class="button button-primary"><?php esc_html_e( 'SMTP settings', 'hlm-lead-capture' ); ?></a>
												<?php if ( ! empty( $smtp['test_url'] ) ) : ?>
													<a href="<?php echo esc_url( $smtp['test_url'] ); ?>" class="button button-secondary"><?php esc_html_e( 'SMTP test email', 'hlm-lead-capture' ); ?></a>
												<?php endif; ?>
											<?php elseif ( 'inactive' === $smtp['status'] ) : ?>
												<a href="<?php echo esc_url( $smtp['activate_url'] ); ?>" class="button button-secondary"><?php esc_html_e( 'Activate plugin', 'hlm-lead-capture' ); ?></a>
												<a href="<?php echo esc_url( $smtp['plugins_url'] ); ?>" class="button-link"><?php esc_html_e( 'Plugins screen', 'hlm-lead-capture' ); ?></a>
											<?php else : ?>
												<a href="<?php echo esc_url( $smtp['install_url'] ); ?>" class="button button-secondary"><?php esc_html_e( 'Install plugin', 'hlm-lead-capture' ); ?></a>
											<?php endif; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<p class="description">
								<?php if ( $active_smtp ) : ?>
									<?php
									printf(
										/* translators: %s: SMTP plugin name */
										esc_html__( '%s is handling delivery for lead alerts and all other site email.', 'hlm-lead-capture' ),
										esc_html( $active_smtp['label'] )
									);
									?>
								<?php else : ?>
									<?php esc_html_e( 'Install and activate one SMTP plugin above, connect Gmail or Microsoft 365, then use the lead test email below.', 'hlm-lead-capture' ); ?>
								<?php endif; ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable alerts', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[notify_enabled]" value="1" <?php checked( $settings['notify_enabled'] ); ?>>
								<?php esc_html_e( 'Email admins when a lead is saved', 'hlm-lead-capture' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hlm-notify-recipients"><?php esc_html_e( 'Recipients', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<?php
							$admin_email         = sanitize_email( (string) get_option( 'admin_email' ) );
							$effective_recipients = HLM_Lead_Capture_Settings::notify_recipients();
							?>
							<input type="text" class="large-text" id="hlm-notify-recipients" name="<?php echo esc_attr( $option ); ?>[notify_recipients]" value="<?php echo esc_attr( $settings['notify_recipients'] ); ?>" placeholder="<?php echo esc_attr( $admin_email ); ?>" autocomplete="off">
							<p class="description">
								<?php esc_html_e( 'Optional. Comma-separated addresses for lead alerts only — not your SMTP login.', 'hlm-lead-capture' ); ?>
							</p>
							<p class="description">
								<?php if ( trim( (string) $settings['notify_recipients'] ) ) : ?>
									<?php
									printf(
										/* translators: %s: comma-separated email addresses */
										esc_html__( 'Alerts will be sent to: %s', 'hlm-lead-capture' ),
										'<strong>' . esc_html( implode( ', ', $effective_recipients ) ) . '</strong>'
									);
									?>
								<?php elseif ( $admin_email ) : ?>
									<?php
									printf(
										/* translators: 1: admin email 2: settings URL */
										esc_html__( 'Leave blank to use the WordPress admin email (%1$s). Change it under %2$s.', 'hlm-lead-capture' ),
										'<strong>' . esc_html( $admin_email ) . '</strong>',
										'<a href="' . esc_url( admin_url( 'options-general.php' ) ) . '">' . esc_html__( 'Settings → General', 'hlm-lead-capture' ) . '</a>'
									);
									?>
								<?php else : ?>
									<?php esc_html_e( 'Enter at least one email address, or set an admin email under Settings → General.', 'hlm-lead-capture' ); ?>
								<?php endif; ?>
							</p>
						</td>
					</tr>
				</table>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="hlm-notify-subject"><?php esc_html_e( 'Custom subject', 'hlm-lead-capture' ); ?></label></th>
						<td>
							<input type="text" class="large-text" id="hlm-notify-subject" name="<?php echo esc_attr( $option ); ?>[notify_subject]" value="<?php echo esc_attr( $settings['notify_subject'] ); ?>" placeholder="<?php esc_attr_e( 'New {form_type} lead: {first_name} {last_name}', 'hlm-lead-capture' ); ?>">
							<p class="description"><?php esc_html_e( 'Tokens: {form_type}, {first_name}, {last_name}, {email}, {campus}, {program}', 'hlm-lead-capture' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Rejected leads', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[notify_include_rejected]" value="1" <?php checked( $settings['notify_include_rejected'] ); ?>>
								<?php esc_html_e( 'Notify when Elite validation rejects a lead', 'hlm-lead-capture' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Test delivery', 'hlm-lead-capture' ); ?></th>
						<td>
							<?php
							$test_recipients = HLM_Lead_Capture_Settings::notify_recipients();
							$test_url        = wp_nonce_url(
								admin_url( 'admin-post.php?action=hlm_test_lead_email' ),
								'hlm_test_lead_email'
							);
							?>
							<a href="<?php echo esc_url( $test_url ); ?>" class="button button-secondary"><?php esc_html_e( 'Send test email', 'hlm-lead-capture' ); ?></a>
							<p class="description">
								<?php
								if ( $test_recipients ) {
									printf(
										/* translators: %s: comma-separated email addresses */
										esc_html__( 'Sends to: %s', 'hlm-lead-capture' ),
										esc_html( implode( ', ', $test_recipients ) )
									);
								} else {
									esc_html_e( 'Save a recipient address first, or leave blank to use the site admin email.', 'hlm-lead-capture' );
								}
								?>
							</p>
						</td>
					</tr>
				</table>
			</section>

			<section
				class="hlm-lc-settings__panel"
				role="tabpanel"
				id="hlm-panel-storage"
				data-tab="storage"
				aria-labelledby="hlm-tab-storage"
				hidden
			>
				<header class="hlm-lc-settings__panel-head">
					<h2><?php esc_html_e( 'Local Storage', 'hlm-lead-capture' ); ?></h2>
					<p><?php esc_html_e( 'Keep a full copy of every submission in your WordPress database.', 'hlm-lead-capture' ); ?></p>
				</header>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Database', 'hlm-lead-capture' ); ?></th>
						<td>
							<label class="hlm-lc-settings__check">
								<input type="checkbox" name="<?php echo esc_attr( $option ); ?>[store_leads_locally]" value="1" <?php checked( HLM_Lead_Capture_Settings::store_leads_locally() ); ?>>
								<?php esc_html_e( 'Save all form submissions to the database', 'hlm-lead-capture' ); ?>
							</label>
							<p class="description">
								<?php
								printf(
									/* translators: 1: table name 2: leads admin URL */
									esc_html__( 'Table: %1$s · %2$s', 'hlm-lead-capture' ),
									'<code>' . esc_html( HLM_Lead_Storage::table_name() ) . '</code>',
									'<a href="' . esc_url( admin_url( 'admin.php?page=hlm-leads' ) ) . '">' . esc_html__( 'Export CSV', 'hlm-lead-capture' ) . '</a>'
								);
								?>
							</p>
						</td>
					</tr>
				</table>
			</section>
		</div>

		<div class="hlm-lc-settings__footer">
			<?php submit_button( __( 'Save Changes', 'hlm-lead-capture' ), 'primary', 'submit', false ); ?>
			<p class="hlm-lc-settings__hint description">
				<?php
				printf(
					/* translators: %s: shortcode */
					esc_html__( 'Shortcode: %s', 'hlm-lead-capture' ),
					'<code>[hlm_lead_form]</code>'
				);
				?>
				·
				<code>python3 scripts/build-elite-catalog.py</code>
			</p>
		</div>
	</form>
</div>
