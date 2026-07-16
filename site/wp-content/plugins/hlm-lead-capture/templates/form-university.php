<?php
/**
 * University partnership lead capture form (2-step wizard).
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;
?>

<form class="hlm-form-section hlm-lead-form hlm-form-wizard" id="<?php echo esc_attr( $form_id ); ?>-university" data-type="university" novalidate>
	<div class="hlm-form-progress" aria-hidden="true">
		<div class="hlm-form-progress-track">
			<span class="hlm-form-progress-fill" style="width: 50%;"></span>
		</div>
		<p class="hlm-form-progress-label">
			<?php esc_html_e( 'Step', 'hlm-lead-capture' ); ?>
			<span class="hlm-step-current">1</span>
			<?php esc_html_e( 'of', 'hlm-lead-capture' ); ?>
			<span class="hlm-step-total">2</span>
		</p>
	</div>

	<div class="hlm-form-step active" data-step="1">
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Your institution', 'hlm-lead-capture' ); ?></p>
		<p class="hlm-form-intro"><?php esc_html_e( 'University and college teams: tell us who you are. Step 2 covers partnership goals and is optional.', 'hlm-lead-capture' ); ?></p>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-name"><?php esc_html_e( 'Institution name', 'hlm-lead-capture' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-uni-name" name="institution_name" autocomplete="organization" placeholder="<?php esc_attr_e( 'University Name', 'hlm-lead-capture' ); ?>" required>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-website"><?php esc_html_e( 'Website', 'hlm-lead-capture' ); ?> <span class="hlm-form-optional-tag"><?php esc_html_e( 'optional', 'hlm-lead-capture' ); ?></span></label>
			<input type="url" id="<?php echo esc_attr( $form_id ); ?>-uni-website" name="website" placeholder="https://www.university.edu">
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-type"><?php esc_html_e( 'Institution type', 'hlm-lead-capture' ); ?> <span class="hlm-form-optional-tag"><?php esc_html_e( 'optional', 'hlm-lead-capture' ); ?></span></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-uni-type" name="institution_type">
				<option value=""><?php esc_html_e( 'Select type', 'hlm-lead-capture' ); ?></option>
				<option value="university"><?php esc_html_e( 'University', 'hlm-lead-capture' ); ?></option>
				<option value="college"><?php esc_html_e( 'College', 'hlm-lead-capture' ); ?></option>
				<option value="trade_school"><?php esc_html_e( 'Trade school', 'hlm-lead-capture' ); ?></option>
				<option value="online_program"><?php esc_html_e( 'Online program', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-uni-first"><?php esc_html_e( 'First name', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-uni-first" name="first_name" placeholder="<?php esc_attr_e( 'John', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-uni-last"><?php esc_html_e( 'Last name', 'hlm-lead-capture' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-uni-last" name="last_name" placeholder="<?php esc_attr_e( 'Doe', 'hlm-lead-capture' ); ?>" required>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-title"><?php esc_html_e( 'Title', 'hlm-lead-capture' ); ?> <span class="hlm-form-optional-tag"><?php esc_html_e( 'optional', 'hlm-lead-capture' ); ?></span></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-uni-title" name="title" placeholder="<?php esc_attr_e( 'Admissions Director', 'hlm-lead-capture' ); ?>">
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-uni-email"><?php esc_html_e( 'Work email', 'hlm-lead-capture' ); ?></label>
				<input type="email" id="<?php echo esc_attr( $form_id ); ?>-uni-email" name="email" autocomplete="work email" placeholder="<?php esc_attr_e( 'john@university.edu', 'hlm-lead-capture' ); ?>" required>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-uni-phone"><?php esc_html_e( 'Phone', 'hlm-lead-capture' ); ?></label>
				<input type="tel" id="<?php echo esc_attr( $form_id ); ?>-uni-phone" name="phone" autocomplete="tel" inputmode="tel" placeholder="<?php esc_attr_e( '(555) 123-4567', 'hlm-lead-capture' ); ?>" aria-describedby="<?php echo esc_attr( $form_id ); ?>-uni-phone-hint" required>
				<?php hlm_lead_field_hint( $form_id . '-uni-phone-hint', __( '10-digit U.S. phone number.', 'hlm-lead-capture' ) ); ?>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-dept"><?php esc_html_e( 'Department', 'hlm-lead-capture' ); ?> <span class="hlm-form-optional-tag"><?php esc_html_e( 'optional', 'hlm-lead-capture' ); ?></span></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-uni-dept" name="department">
				<option value=""><?php esc_html_e( 'Select department', 'hlm-lead-capture' ); ?></option>
				<option value="admissions"><?php esc_html_e( 'Admissions', 'hlm-lead-capture' ); ?></option>
				<option value="marketing"><?php esc_html_e( 'Marketing', 'hlm-lead-capture' ); ?></option>
				<option value="enrollment"><?php esc_html_e( 'Enrollment management', 'hlm-lead-capture' ); ?></option>
				<option value="communications"><?php esc_html_e( 'Communications', 'hlm-lead-capture' ); ?></option>
				<option value="other"><?php esc_html_e( 'Other', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<button type="button" class="hlm-btn hlm-btn--primary hlm-btn--full hlm-form-next"><?php esc_html_e( 'Continue', 'hlm-lead-capture' ); ?></button>
	</div>

	<div class="hlm-form-step" data-step="2" hidden>
		<p class="hlm-form-step-heading"><?php esc_html_e( 'Partnership goals', 'hlm-lead-capture' ); ?> <span class="hlm-form-optional-tag"><?php esc_html_e( 'optional', 'hlm-lead-capture' ); ?></span></p>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-services"><?php esc_html_e( 'Service interest', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-uni-services" name="service_interest" multiple class="hlm-form-select--multi">
				<option value="lead_generation"><?php esc_html_e( 'Lead generation', 'hlm-lead-capture' ); ?></option>
				<option value="brand_awareness"><?php esc_html_e( 'Brand awareness', 'hlm-lead-capture' ); ?></option>
				<option value="multi_channel"><?php esc_html_e( 'Multi-channel marketing', 'hlm-lead-capture' ); ?></option>
				<option value="seo"><?php esc_html_e( 'SEO', 'hlm-lead-capture' ); ?></option>
				<option value="social_media"><?php esc_html_e( 'Social media', 'hlm-lead-capture' ); ?></option>
				<option value="email"><?php esc_html_e( 'Email marketing', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-target"><?php esc_html_e( 'Target student types', 'hlm-lead-capture' ); ?></label>
			<select id="<?php echo esc_attr( $form_id ); ?>-uni-target" name="target_students" multiple class="hlm-form-select--multi">
				<option value="undergraduate"><?php esc_html_e( 'Undergraduate', 'hlm-lead-capture' ); ?></option>
				<option value="graduate"><?php esc_html_e( 'Graduate', 'hlm-lead-capture' ); ?></option>
				<option value="online"><?php esc_html_e( 'Online', 'hlm-lead-capture' ); ?></option>
				<option value="certificate"><?php esc_html_e( 'Certificate', 'hlm-lead-capture' ); ?></option>
				<option value="trade"><?php esc_html_e( 'Trade / vocational', 'hlm-lead-capture' ); ?></option>
			</select>
		</div>
		<div class="hlm-form-row">
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-uni-volume"><?php esc_html_e( 'Monthly lead volume', 'hlm-lead-capture' ); ?></label>
				<select id="<?php echo esc_attr( $form_id ); ?>-uni-volume" name="lead_volume">
					<option value=""><?php esc_html_e( 'Select volume', 'hlm-lead-capture' ); ?></option>
					<option value="1-500">1 - 500</option>
					<option value="500-2000">500 - 2,000</option>
					<option value="2000-5000">2,000 - 5,000</option>
					<option value="5000+">5,000+</option>
				</select>
			</div>
			<div class="hlm-form-group">
				<label for="<?php echo esc_attr( $form_id ); ?>-uni-budget"><?php esc_html_e( 'Budget range', 'hlm-lead-capture' ); ?></label>
				<select id="<?php echo esc_attr( $form_id ); ?>-uni-budget" name="budget">
					<option value=""><?php esc_html_e( 'Select budget', 'hlm-lead-capture' ); ?></option>
					<option value="under-5k"><?php esc_html_e( 'Under $5,000/mo', 'hlm-lead-capture' ); ?></option>
					<option value="5k-15k"><?php esc_html_e( '$5,000 - $15,000/mo', 'hlm-lead-capture' ); ?></option>
					<option value="15k-50k"><?php esc_html_e( '$15,000 - $50,000/mo', 'hlm-lead-capture' ); ?></option>
					<option value="50k+"><?php esc_html_e( '$50,000+/mo', 'hlm-lead-capture' ); ?></option>
				</select>
			</div>
		</div>
		<div class="hlm-form-group">
			<label for="<?php echo esc_attr( $form_id ); ?>-uni-message"><?php esc_html_e( 'Additional details / goals', 'hlm-lead-capture' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $form_id ); ?>-uni-message" name="message" placeholder="<?php esc_attr_e( 'Tell us about your goals...', 'hlm-lead-capture' ); ?>">
		</div>
		<div class="hlm-form-step-actions">
			<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back"><?php esc_html_e( 'Back', 'hlm-lead-capture' ); ?></button>
			<button type="submit" class="hlm-btn hlm-btn--primary hlm-form-submit"><?php esc_html_e( 'Request Partnership Info', 'hlm-lead-capture' ); ?></button>
		</div>
		<p class="hlm-form-note">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
			<?php esc_html_e( 'Our team will respond within 24 hours.', 'hlm-lead-capture' ); ?>
		</p>
	</div>
</form>
