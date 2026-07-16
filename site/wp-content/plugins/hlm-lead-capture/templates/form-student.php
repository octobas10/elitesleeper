<?php
/**
 * Student lead capture form (3-step wizard).
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

$institutions = HLM_Institution_Data::get_institutions();
$grad_years   = hlm_lead_grad_years();
?>

<form class="hlm-form-section active hlm-lead-form hlm-form-wizard" id="<?php echo esc_attr( $form_id ); ?>-student" data-type="student" novalidate>
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
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-student-first"><?php esc_html_e( 'First name', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-student-first" name="first_name" autocomplete="given-name" placeholder="<?php esc_attr_e( 'John', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-student-last"><?php esc_html_e( 'Last name', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-student-last" name="last_name" autocomplete="family-name" placeholder="<?php esc_attr_e( 'Doe', 'hlm-lead-capture' ); ?>" required>
			</div>
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-student-email"><?php esc_html_e( 'Email', 'hlm-lead-capture' ); ?></label>
				<input type="email" id="<?php echo esc_attr( $form_id ); ?>-student-email" name="email" autocomplete="email" placeholder="<?php esc_attr_e( 'john@example.com', 'hlm-lead-capture' ); ?>" aria-describedby="<?php echo esc_attr( $form_id ); ?>-student-email-hint" required>
				<?php hlm_lead_field_hint( $form_id . '-student-email-hint', __( 'Use an email you check regularly. Schools will reply here.', 'hlm-lead-capture' ) ); ?>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-student-phone"><?php esc_html_e( 'Phone', 'hlm-lead-capture' ); ?></label>
				<input type="tel" id="<?php echo esc_attr( $form_id ); ?>-student-phone" name="phone" autocomplete="tel" inputmode="tel" placeholder="<?php esc_attr_e( '(555) 123-4567', 'hlm-lead-capture' ); ?>" aria-describedby="<?php echo esc_attr( $form_id ); ?>-student-phone-hint" required>
				<?php hlm_lead_field_hint( $form_id . '-student-phone-hint', __( '10-digit U.S. mobile or home number.', 'hlm-lead-capture' ) ); ?>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-student-address"><?php esc_html_e( 'Address', 'hlm-lead-capture' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-student-address" name="address" autocomplete="street-address" placeholder="<?php esc_attr_e( '123 Main St', 'hlm-lead-capture' ); ?>" required>
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-student-city"><?php esc_html_e( 'City', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-student-city" name="city" autocomplete="address-level2" placeholder="<?php esc_attr_e( 'New York', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-student-state"><?php esc_html_e( 'State / Province', 'hlm-lead-capture' ); ?></label>
				<select id="<?php echo esc_attr( $form_id ); ?>-student-state" name="state" class="hlm-state-select" autocomplete="address-level1" required>
					<?php hlm_lead_render_select_options( array_merge( hlm_lead_us_states(), hlm_lead_ca_provinces() ), __( 'Select state', 'hlm-lead-capture' ) ); ?>
				</select>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-student-zip"><?php esc_html_e( 'ZIP / Postal code', 'hlm-lead-capture' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-student-zip" name="zip" autocomplete="postal-code" placeholder="<?php esc_attr_e( '10001', 'hlm-lead-capture' ); ?>" aria-describedby="<?php echo esc_attr( $form_id ); ?>-student-zip-hint" required>
			<?php hlm_lead_field_hint( $form_id . '-student-zip-hint', __( 'U.S. ZIP (12345) or Canadian postal code (A1A 1A1).', 'hlm-lead-capture' ) ); ?>
		</div>
		<button type="button" class="hlm-btn hlm-btn--primary hlm-btn--full hlm-form-next"><?php esc_html_e( 'Continue', 'hlm-lead-capture' ); ?></button>
	</div>

	<div class="hlm-form-step" data-step="2" hidden>
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Education background', 'hlm-lead-capture' ); ?></p>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-student-grad"><?php esc_html_e( 'High school graduation year', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-student-grad" name="grad_year" required>
				<option value=""><?php esc_html_e( 'Select year', 'hlm-lead-capture' ); ?></option>
				<?php foreach ( $grad_years as $year ) : ?>
					<option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-student-edu"><?php esc_html_e( 'Highest education completed', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-student-edu" name="education_level" required>
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
			<label for="<?php echo esc_attr( $form_id ); ?>-student-degree"><?php esc_html_e( 'Desired degree', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-student-degree" name="desired_degree" required>
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
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Choose your program', 'hlm-lead-capture' ); ?></p>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-student-category"><?php esc_html_e( 'School or institution', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-student-category" name="category" class="hlm-student-category" required>
				<option value=""><?php esc_html_e( 'Select school', 'hlm-lead-capture' ); ?></option>
				<?php foreach ( $institutions as $code => $label ) : ?>
					<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-student-campus"><?php esc_html_e( 'Campus / location', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-student-campus" name="campus" class="hlm-student-campus" required disabled>
				<option value=""><?php esc_html_e( 'Select campus', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<?php
		$suffix   = 'student';
		$required = true;
		include HLM_LC_PLUGIN_DIR . 'templates/partials/program-combobox.php';
		?>
		<div class="hlm-form-group hlm-form-group--checkbox">
			<input type="hidden" name="tcpa_text" value="<?php echo esc_attr( HLM_Lead_Capture_Settings::tcpa_text( 'student' ) ); ?>">
			<input type="checkbox" name="tcpa_consent" id="<?php echo esc_attr( $form_id ); ?>-tcpa-student" value="1" required>
			<label for="<?php echo esc_attr( $form_id ); ?>-tcpa-student"><?php echo esc_html( HLM_Lead_Capture_Settings::tcpa_text( 'student' ) ); ?></label>
		</div>
		<div class="hlm-form-step-actions">
			<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back"><?php esc_html_e( 'Back', 'hlm-lead-capture' ); ?></button>
			<button type="submit" class="hlm-btn hlm-btn--primary hlm-form-submit"><?php esc_html_e( 'Find My Program', 'hlm-lead-capture' ); ?></button>
		</div>
		<p class="hlm-form-note">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
			<?php esc_html_e( '100% secure. We never share your data without consent.', 'hlm-lead-capture' ); ?>
		</p>
	</div>
</form>
