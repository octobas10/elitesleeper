<?php
$sent = !empty($removeme_sent);
$error = (string) ($removeme_error ?? '');
partial('components/page_hero', [
    'overline' => 'Privacy',
    'headline' => 'Do Not Sell or Share Request',
    'description' => 'Submit your information so we can locate your record and process your opt-out request.',
]);
?>
<section class="hlm-section hlm-bg-cream">
	<div class="hlm-container" style="max-width: 720px;">
		<?php if ($sent): ?>
		<p class="hlm-body-lg">Thank you. Your Do Not Sell or Share request has been received. We will process it within 15 business days.</p>
		<p><a class="hlm-btn hlm-btn-gold" href="/do-not-sell/">Back to Privacy Choices</a></p>
		<?php else: ?>
		<?php if ($error !== ''): ?><p class="hlm-body" role="alert"><?= e($error) ?></p><?php endif; ?>
		<p class="hlm-body">Include the name, email address, telephone number, and zip code you used on our forms so we can locate your record.</p>
		<form class="hlm-lead-capture hlm-lead-form" method="post" action="/removeme/" novalidate>
			<input type="hidden" name="removeme" value="1" />
			<div class="hlm-form-group">
				<label for="rm-name">Full name</label>
				<input id="rm-name" name="name" type="text" required autocomplete="name" />
			</div>
			<div class="hlm-form-group">
				<label for="rm-email">Email</label>
				<input id="rm-email" name="email" type="email" required autocomplete="email" />
			</div>
			<div class="hlm-form-group">
				<label for="rm-phone">Telephone</label>
				<input id="rm-phone" name="phone" type="tel" required autocomplete="tel" />
			</div>
			<div class="hlm-form-group">
				<label for="rm-zip">ZIP / Postal code</label>
				<input id="rm-zip" name="zip" type="text" required autocomplete="postal-code" />
			</div>
			<button class="hlm-btn hlm-btn-primary" type="submit">Submit Opt-Out Request</button>
		</form>
		<p class="hlm-body" style="margin-top:1.5rem;">You can also email <strong>support@higherlearningmarketers.com</strong> with the subject line "Do Not Sell or Share", call 718 655-2573, or mail Higher Learning Marketers, Privacy Requests, 138-07 82nd Drive, Briarwood, NY 11435.</p>
		<?php endif; ?>
	</div>
</section>
