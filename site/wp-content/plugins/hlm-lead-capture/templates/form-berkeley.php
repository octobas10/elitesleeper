<?php
/**
 * Berkeley direct application form (3-step wizard).
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

$grad_years       = hlm_lead_grad_years();
$berkeley_campuses = HLM_Institution_Data::get_campus_data()['BERKELEY'] ?? array();
?>

<form class="hlm-form-section hlm-lead-form hlm-form-wizard" id="<?php echo esc_attr( $form_id ); ?>-berkeley" data-type="berkeley" data-category="BERKELEY" novalidate>
	<div class="hlm-form-progress" aria-hidden="true">
		<div class="hlm-form-progress-track">
			<span class="hlm-form-progress-fill" style="width: 33.333%;"></span>
		</div>
		<p class="hlm-form-progress-label">
			<?php esc_html_e( 'Step', 'hlm-lead-capture' ); ?>
			<span class="hlm-step-current">1</span>
			<?php esc_html_e( 'of', 'hlm-lead-capture' ); ?>
			<span class="hlm-step-total">3</span>
		</p>
	</div>

	<div class="hlm-form-step active" data-step="1">
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Your contact details', 'hlm-lead-capture' ); ?></p>
		<p class="hlm-form-intro"><?php esc_html_e( 'Apply to Berkeley College in a few quick steps. Fields marked required must be completed.', 'hlm-lead-capture' ); ?></p>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-berk-first"><?php esc_html_e( 'First name', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-berk-first" name="first_name" autocomplete="given-name" placeholder="<?php esc_attr_e( 'John', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-berk-last"><?php esc_html_e( 'Last name', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-berk-last" name="last_name" autocomplete="family-name" placeholder="<?php esc_attr_e( 'Doe', 'hlm-lead-capture' ); ?>" required>
			</div>
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-berk-email"><?php esc_html_e( 'Email', 'hlm-lead-capture' ); ?></label>
				<input type="email" id="<?php echo esc_attr( $form_id ); ?>-berk-email" name="email" autocomplete="email" placeholder="<?php esc_attr_e( 'john@example.com', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-berk-phone"><?php esc_html_e( 'Phone', 'hlm-lead-capture' ); ?></label>
				<input type="tel" id="<?php echo esc_attr( $form_id ); ?>-berk-phone" name="phone" autocomplete="tel" inputmode="tel" placeholder="<?php esc_attr_e( '(555) 123-4567', 'hlm-lead-capture' ); ?>" aria-describedby="<?php echo esc_attr( $form_id ); ?>-berk-phone-hint" required>
				<?php hlm_lead_field_hint( $form_id . '-berk-phone-hint', __( '10-digit U.S. phone number.', 'hlm-lead-capture' ) ); ?>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-berk-address"><?php esc_html_e( 'Address', 'hlm-lead-capture' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-berk-address" name="address" placeholder="<?php esc_attr_e( '123 Main St', 'hlm-lead-capture' ); ?>" required>
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-berk-city"><?php esc_html_e( 'City', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-berk-city" name="city" placeholder="<?php esc_attr_e( 'Newark', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-berk-state"><?php esc_html_e( 'State', 'hlm-lead-capture' ); ?></label>
				<select id="<?php echo esc_attr( $form_id ); ?>-berk-state" name="state" required>
					<?php hlm_lead_render_select_options( hlm_lead_us_states(), __( 'Select state', 'hlm-lead-capture' ) ); ?>
				</select>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-berk-zip"><?php esc_html_e( 'ZIP code', 'hlm-lead-capture' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-berk-zip" name="zip" placeholder="<?php esc_attr_e( '07102', 'hlm-lead-capture' ); ?>" required>
		</div>
		<button type="button" class="hlm-btn hlm-btn--primary hlm-btn--full hlm-form-next"><?php esc_html_e( 'Continue', 'hlm-lead-capture' ); ?></button>
	</div>

	<div class="hlm-form-step" data-step="2" hidden>
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Education background', 'hlm-lead-capture' ); ?></p>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-berk-grad"><?php esc_html_e( 'High school graduation year', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-berk-grad" name="grad_year" required>
				<option value=""><?php esc_html_e( 'Select year', 'hlm-lead-capture' ); ?></option>
				<?php foreach ( $grad_years as $year ) : ?>
					<option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-berk-edu"><?php esc_html_e( 'Highest education completed', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-berk-edu" name="education_level" required>
				<option value=""><?php esc_html_e( 'Select level', 'hlm-lead-capture' ); ?></option>
				<option value="1"><?php esc_html_e( 'GED', 'hlm-lead-capture' ); ?></option>
				<option value="2"><?php esc_html_e( 'High School', 'hlm-lead-capture' ); ?></option>
				<option value="3"><?php esc_html_e( 'Some College (1–23 credits)', 'hlm-lead-capture' ); ?></option>
				<option value="4"><?php esc_html_e( 'Some College (24–47 credits)', 'hlm-lead-capture' ); ?></option>
				<option value="5"><?php esc_html_e( 'Some College (48+ credits)', 'hlm-lead-capture' ); ?></option>
				<option value="6"><?php esc_html_e( 'Associate Degree', 'hlm-lead-capture' ); ?></option>
				<option value="7"><?php esc_html_e( "Bachelor's Degree", 'hlm-lead-capture' ); ?></option>
				<option value="8"><?php esc_html_e( "Master's Degree", 'hlm-lead-capture' ); ?></option>
				<option value="9"><?php esc_html_e( 'Doctorate', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-berk-degree"><?php esc_html_e( 'Desired degree', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-berk-degree" name="desired_degree" required>
				<option value=""><?php esc_html_e( 'Select degree', 'hlm-lead-capture' ); ?></option>
				<option value="1"><?php esc_html_e( 'Diploma', 'hlm-lead-capture' ); ?></option>
				<option value="2"><?php esc_html_e( 'Associate', 'hlm-lead-capture' ); ?></option>
				<option value="3"><?php esc_html_e( "Bachelor's", 'hlm-lead-capture' ); ?></option>
				<option value="4"><?php esc_html_e( "Master's", 'hlm-lead-capture' ); ?></option>
				<option value="5"><?php esc_html_e( 'Doctorate', 'hlm-lead-capture' ); ?></option>
				<option value="6"><?php esc_html_e( 'Certificate', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<div class="hlm-form-step-actions">
			<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back"><?php esc_html_e( 'Back', 'hlm-lead-capture' ); ?></button>
			<button type="button" class="hlm-btn hlm-btn--primary hlm-form-next"><?php esc_html_e( 'Continue', 'hlm-lead-capture' ); ?></button>
		</div>
	</div>

	<div class="hlm-form-step" data-step="3" hidden>
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Berkeley program details', 'hlm-lead-capture' ); ?></p>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-berk-campus"><?php esc_html_e( 'Berkeley campus', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-berk-campus" name="campus" class="hlm-berkeley-campus" required>
				<option value=""><?php esc_html_e( 'Select campus', 'hlm-lead-capture' ); ?></option>
				<?php foreach ( $berkeley_campuses as $code => $label ) : ?>
					<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
		$suffix   = 'berk';
		$required = true;
		include HLM_LC_PLUGIN_DIR . 'templates/partials/program-combobox.php';
		?>
		<div class="hlm-form-group hlm-form-group--checkbox">
			<input type="hidden" name="tcpa_text" value="<?php echo esc_attr( HLM_Lead_Capture_Settings::tcpa_text( 'berkeley' ) ); ?>">
			<input type="checkbox" name="tcpa_consent" id="<?php echo esc_attr( $form_id ); ?>-tcpa-berkeley" value="1" required>
			<label for="<?php echo esc_attr( $form_id ); ?>-tcpa-berkeley"><?php echo esc_html( HLM_Lead_Capture_Settings::tcpa_text( 'berkeley' ) ); ?></label>
		</div>
		<div class="hlm-form-step-actions">
			<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back"><?php esc_html_e( 'Back', 'hlm-lead-capture' ); ?></button>
			<button type="submit" class="hlm-btn hlm-btn--primary hlm-form-submit"><?php esc_html_e( 'Apply to Berkeley', 'hlm-lead-capture' ); ?></button>
		</div>
		<p class="hlm-form-note">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
			<?php esc_html_e( 'Berkeley College direct application. Secure & confidential.', 'hlm-lead-capture' ); ?>
		</p>
	</div>
</form>
