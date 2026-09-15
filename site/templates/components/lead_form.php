<?php $form_id = 'hlm-lead'; ?>
<?php $institutions = $institutions ?? []; ?>
<?php $state_options = $state_options ?? []; ?>
<?php $grad_years = $grad_years ?? []; ?>
<?php $tcpa_text = $tcpa_text ?? 'I agree to be contacted by phone, email, and text message regarding educational opportunities. I understand consent is not required to apply.'; ?>
<?php $turnstile_site_key = $turnstile_site_key ?? ''; ?>
<div class="hlm-lead-capture" id="<?= e((string)($form_id)) ?>" data-hlm-lead-capture>
	<script src="https://api.trustedform.com/trustedform.js?provide_tag=true&field=trustedformcerturl" async></script>
	<script src="https://create.leadid.com/leadid.js" async></script>
	<?php if ($turnstile_site_key): ?>
	<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
	<?php endif; ?>

	<div class="hlm-form-tabs" role="tablist" aria-label="Lead capture form type" data-hlm-form-tabs>
		<button type="button" class="hlm-form-tab active" data-tab="student" role="tab" aria-selected="true" aria-controls="<?= e((string)($form_id)) ?>-student" title="I am a Student">
			<span class="hlm-form-tab-label">Student</span>
		</button>
		<button type="button" class="hlm-form-tab" data-tab="university" role="tab" aria-selected="false" aria-controls="<?= e((string)($form_id)) ?>-university" title="I am a University or School">
			<span class="hlm-form-tab-label">University</span>
		</button>
	</div>

	<div class="hlm-form-tabs-select-wrap">
		<label class="hlm-form-tabs-select-label" for="<?= e((string)($form_id)) ?>-type">I am a</label>
		<select id="<?= e((string)($form_id)) ?>-type" class="hlm-form-tabs-select" aria-label="Lead capture form type" data-hlm-form-tabs-select>
			<option value="student" selected>Student</option>
			<option value="university">University</option>
		</select>
	</div>

	<form
		class="hlm-form-section active hlm-lead-form hlm-form-wizard"
		id="<?= e((string)($form_id)) ?>-student"
		data-type="student"
		novalidate
	>
		<input type="text" name="hlm_hp_url" class="hlm-hp" tabindex="-1" autocomplete="off" aria-hidden="true" />
		<input type="hidden" name="trustedformcerturl" id="trustedformcerturl" value="" />
		<input type="hidden" name="universal_leadid" id="leadid_token" value="" />
		<input type="hidden" name="page_url" value="" />
		<div class="hlm-form-progress" aria-hidden="true">
			<div class="hlm-form-progress-track">
				<span class="hlm-form-progress-fill" style="width: 33.333%;"></span>
			</div>
			<p class="hlm-form-progress-label">
				Step <span class="hlm-step-current">1</span> of <span class="hlm-step-total">3</span>
			</p>
		</div>

		<div class="hlm-form-step active" data-step="1">
			<p class="hlm-form-step-heading">Your contact details</p>
			<div class="hlm-form-row">
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-student-first">First name</label>
					<input type="text" id="<?= e((string)($form_id)) ?>-student-first" name="first_name" autocomplete="given-name" required />
				</div>
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-student-last">Last name</label>
					<input type="text" id="<?= e((string)($form_id)) ?>-student-last" name="last_name" autocomplete="family-name" required />
				</div>
			</div>
			<div class="hlm-form-row">
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-student-email">Email</label>
					<input type="email" id="<?= e((string)($form_id)) ?>-student-email" name="email" autocomplete="email" required />
				</div>
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-student-phone">Phone</label>
					<input type="tel" id="<?= e((string)($form_id)) ?>-student-phone" name="phone" autocomplete="tel" inputmode="tel" required />
				</div>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-address">Address</label>
				<input type="text" id="<?= e((string)($form_id)) ?>-student-address" name="address" autocomplete="street-address" required />
			</div>
			<div class="hlm-form-row">
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-student-city">City</label>
					<input type="text" id="<?= e((string)($form_id)) ?>-student-city" name="city" autocomplete="address-level2" required />
				</div>
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-student-state">State / Province</label>
					<select id="<?= e((string)($form_id)) ?>-student-state" name="state" class="hlm-state-select" required>
						<option value="">Select state</option>
						<?php foreach ($state_options as $code => $label): ?>
						<option value="<?= e((string)($code)) ?>"><?= e((string)($label)) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-zip">ZIP / Postal code</label>
				<input type="text" id="<?= e((string)($form_id)) ?>-student-zip" name="zip" autocomplete="postal-code" required />
			</div>
			<button type="button" class="hlm-btn hlm-btn--primary hlm-btn--full hlm-form-next">Continue</button>
		</div>

		<div class="hlm-form-step" data-step="2" hidden>
			<p class="hlm-form-step-heading">Education background</p>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-grad">High school graduation year</label>
				<select id="<?= e((string)($form_id)) ?>-student-grad" name="grad_year" required>
					<option value="">Select year</option>
					<?php foreach ($grad_years as $year): ?>
					<option value="<?= e((string)($year)) ?>"><?= e((string)($year)) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-edu">Highest education completed</label>
				<select id="<?= e((string)($form_id)) ?>-student-edu" name="education_level" required>
					<option value="">Select level</option>
					<option value="1">GED</option>
					<option value="2">High School</option>
					<option value="3">Some College (1–23 credits)</option>
					<option value="4">Some College (24–47 credits)</option>
					<option value="5">Some College (48+ credits)</option>
					<option value="6">Associate Degree</option>
					<option value="7">Bachelor's Degree</option>
					<option value="8">Master's Degree</option>
					<option value="9">Doctorate</option>
				</select>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-degree">Desired degree</label>
				<select id="<?= e((string)($form_id)) ?>-student-degree" name="desired_degree" required>
					<option value="">Select degree</option>
					<option value="1">Diploma</option>
					<option value="2">Associate</option>
					<option value="3">Bachelor's</option>
					<option value="4">Master's</option>
					<option value="5">Doctorate</option>
					<option value="6">Certificate</option>
				</select>
			</div>
			<div class="hlm-form-step-actions">
				<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back">Back</button>
				<button type="button" class="hlm-btn hlm-btn--primary hlm-form-next">Continue</button>
			</div>
		</div>

		<div class="hlm-form-step" data-step="3" hidden>
			<p class="hlm-form-step-heading">Choose your program</p>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-category">School or institution</label>
				<select id="<?= e((string)($form_id)) ?>-student-category" name="category" class="hlm-student-category" required>
					<option value="">Select school</option>
					<?php foreach ($institutions as $code => $label): ?>
					<option value="<?= e((string)($code)) ?>"><?= e((string)($label)) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-student-campus">Campus / location</label>
				<select id="<?= e((string)($form_id)) ?>-student-campus" name="campus" class="hlm-student-campus" required disabled>
					<option value="">Select campus</option>
				</select>
			</div>
			<div class="hlm-combobox hlm-program-combobox" data-required="1">
				<label for="<?= e((string)($form_id)) ?>-student-program-search">
					Program of interest <span class="hlm-form-required" aria-hidden="true">*</span>
				</label>
				<input type="hidden" name="program_of_interest" id="<?= e((string)($form_id)) ?>-student-program" value="" required />
				<div class="hlm-combobox-control">
					<input
						type="text"
						class="hlm-combobox-input"
						id="<?= e((string)($form_id)) ?>-student-program-search"
						placeholder="Select school and campus first..."
						autocomplete="off"
						role="combobox"
						aria-autocomplete="list"
						aria-expanded="false"
						aria-controls="<?= e((string)($form_id)) ?>-student-program-list"
						disabled
					/>
					<span class="hlm-combobox-count" hidden></span>
				</div>
				<ul class="hlm-combobox-list" id="<?= e((string)($form_id)) ?>-student-program-list" role="listbox" hidden></ul>
			</div>
			<div class="hlm-form-group hlm-form-group--checkbox">
				<input type="hidden" name="tcpa_text" value="<?= e((string)($tcpa_text)) ?>" />
				<input type="checkbox" name="tcpa_consent" id="<?= e((string)($form_id)) ?>-tcpa-student" value="1" required />
				<label for="<?= e((string)($form_id)) ?>-tcpa-student"><?= e((string)($tcpa_text)) ?></label>
			</div>
			<?php if ($turnstile_site_key): ?>
			<div class="hlm-turnstile-wrap">
				<div
					class="cf-turnstile"
					data-sitekey="<?= e((string)($turnstile_site_key)) ?>"
					data-theme="light"
					data-size="flexible"
				></div>
			</div>
			<?php endif; ?>
			<div class="hlm-form-step-actions">
				<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back">Back</button>
				<button type="submit" class="hlm-btn hlm-btn--primary hlm-form-submit">Find My Program</button>
			</div>
			<p class="hlm-form-note">100% secure. We never share your data without consent.</p>
		</div>
		<p class="hlm-form-status" data-form-status hidden></p>
	</form>

	<form
		class="hlm-form-section hlm-lead-form hlm-form-wizard"
		id="<?= e((string)($form_id)) ?>-university"
		data-type="university"
		hidden
		novalidate
	>
		<input type="text" name="hlm_hp_url" class="hlm-hp" tabindex="-1" autocomplete="off" aria-hidden="true" />
		<input type="hidden" name="trustedformcerturl" value="" />
		<input type="hidden" name="universal_leadid" value="" />
		<input type="hidden" name="page_url" value="" />
		<div class="hlm-form-progress" aria-hidden="true">
			<div class="hlm-form-progress-track">
				<span class="hlm-form-progress-fill" style="width: 50%;"></span>
			</div>
			<p class="hlm-form-progress-label">
				Step <span class="hlm-step-current">1</span> of <span class="hlm-step-total">2</span>
			</p>
		</div>

		<div class="hlm-form-step active" data-step="1">
			<p class="hlm-form-step-heading">Your institution</p>
			<p class="hlm-form-intro">University and college teams: tell us who you are. Step 2 covers partnership goals and is optional.</p>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-name">Institution name</label>
				<input type="text" id="<?= e((string)($form_id)) ?>-uni-name" name="institution_name" required />
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-website">Website <span class="hlm-form-optional-tag">optional</span></label>
				<input type="url" id="<?= e((string)($form_id)) ?>-uni-website" name="website" placeholder="https://www.university.edu" />
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-type">Institution type <span class="hlm-form-optional-tag">optional</span></label>
				<select id="<?= e((string)($form_id)) ?>-uni-type" name="institution_type">
					<option value="">Select type</option>
					<option value="university">University</option>
					<option value="college">College</option>
					<option value="trade_school">Trade school</option>
					<option value="online_program">Online program</option>
				</select>
			</div>
			<div class="hlm-form-row">
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-uni-first">First name</label>
					<input type="text" id="<?= e((string)($form_id)) ?>-uni-first" name="first_name" required />
				</div>
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-uni-last">Last name</label>
					<input type="text" id="<?= e((string)($form_id)) ?>-uni-last" name="last_name" required />
				</div>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-title">Title <span class="hlm-form-optional-tag">optional</span></label>
				<input type="text" id="<?= e((string)($form_id)) ?>-uni-title" name="title" placeholder="Admissions Director" />
			</div>
			<div class="hlm-form-row">
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-uni-email">Work email</label>
					<input type="email" id="<?= e((string)($form_id)) ?>-uni-email" name="email" required />
				</div>
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-uni-phone">Phone</label>
					<input type="tel" id="<?= e((string)($form_id)) ?>-uni-phone" name="phone" required />
				</div>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-dept">Department <span class="hlm-form-optional-tag">optional</span></label>
				<select id="<?= e((string)($form_id)) ?>-uni-dept" name="department">
					<option value="">Select department</option>
					<option value="admissions">Admissions</option>
					<option value="marketing">Marketing</option>
					<option value="enrollment">Enrollment management</option>
					<option value="communications">Communications</option>
					<option value="other">Other</option>
				</select>
			</div>
			<button type="button" class="hlm-btn hlm-btn--primary hlm-btn--full hlm-form-next">Continue</button>
		</div>

		<div class="hlm-form-step" data-step="2" hidden>
			<p class="hlm-form-step-heading">
				Partnership goals <span class="hlm-form-optional-tag">optional</span>
			</p>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-services">Service interest</label>
				<select id="<?= e((string)($form_id)) ?>-uni-services" name="service_interest" multiple class="hlm-form-select--multi">
					<option value="lead_generation">Lead generation</option>
					<option value="brand_awareness">Brand awareness</option>
					<option value="multi_channel">Multi-channel marketing</option>
					<option value="seo">SEO</option>
					<option value="social_media">Social media</option>
					<option value="email">Email marketing</option>
				</select>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-target">Target student types</label>
				<select id="<?= e((string)($form_id)) ?>-uni-target" name="target_students" multiple class="hlm-form-select--multi">
					<option value="undergraduate">Undergraduate</option>
					<option value="graduate">Graduate</option>
					<option value="online">Online</option>
					<option value="certificate">Certificate</option>
					<option value="trade">Trade / vocational</option>
				</select>
			</div>
			<div class="hlm-form-row">
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-uni-volume">Monthly lead volume</label>
					<select id="<?= e((string)($form_id)) ?>-uni-volume" name="lead_volume">
						<option value="">Select volume</option>
						<option value="1-500">1 - 500</option>
						<option value="500-2000">500 - 2,000</option>
						<option value="2000-5000">2,000 - 5,000</option>
						<option value="5000+">5,000+</option>
					</select>
				</div>
				<div class="hlm-form-group">
					<label for="<?= e((string)($form_id)) ?>-uni-budget">Budget range</label>
					<select id="<?= e((string)($form_id)) ?>-uni-budget" name="budget">
						<option value="">Select budget</option>
						<option value="under-5k">Under $5,000/mo</option>
						<option value="5k-15k">$5,000 - $15,000/mo</option>
						<option value="15k-50k">$15,000 - $50,000/mo</option>
						<option value="50k+">$50,000+/mo</option>
					</select>
				</div>
			</div>
			<div class="hlm-form-group">
				<label for="<?= e((string)($form_id)) ?>-uni-message">Additional details / goals</label>
				<input type="text" id="<?= e((string)($form_id)) ?>-uni-message" name="message" placeholder="Tell us about your goals..." />
			</div>
			<?php if ($turnstile_site_key): ?>
			<div class="hlm-turnstile-wrap">
				<div
					class="cf-turnstile"
					data-sitekey="<?= e((string)($turnstile_site_key)) ?>"
					data-theme="light"
					data-size="flexible"
				></div>
			</div>
			<?php endif; ?>
			<div class="hlm-form-step-actions">
				<button type="button" class="hlm-btn hlm-btn--outline hlm-form-back">Back</button>
				<button type="submit" class="hlm-btn hlm-btn--primary hlm-form-submit">Request Partnership Info</button>
			</div>
			<p class="hlm-form-note">Our team will respond within 24 hours.</p>
		</div>
		<p class="hlm-form-status" data-form-status hidden></p>
	</form>
</div>
