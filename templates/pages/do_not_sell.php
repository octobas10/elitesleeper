<?php partial('components/page_hero', [
    'overline' => 'Your Privacy Choices',
    'headline' => 'Do Not Sell or Share My Personal Information',
    'description' => 'Opt out of the sale and sharing of personal information under CCPA/CPRA and other U.S. state privacy laws.',
]); ?>
<section class="hlm-policy hlm-section">
	<div class="hlm-container hlm-policy__inner">
		<p class="hlm-body-lg">Effective date: September 22, 2026. Last updated: September 22, 2026.</p>
		<p>This page is Higher Learning Marketers's notice of your right to opt out of the sale and sharing of personal information under the California Consumer Privacy Act, as amended by the California Privacy Rights Act (together, the "CCPA"), and under the consumer privacy laws of Colorado, Connecticut, Delaware, Florida, Indiana, Iowa, Kentucky, Maryland, Minnesota, Montana, Nebraska, Nevada, New Hampshire, New Jersey, Oregon, Rhode Island, Tennessee, Texas, Utah, Virginia and every other state. We honor these opt-out rights for all U.S. consumers, regardless of state of residence. Our full Privacy Policy is at <a href="/privacy-policy/">https://higherlearningmarketers.com/privacy-policy</a>.</p>
		<?php foreach (($ccpa_sections ?? []) as $section): ?>
		<article class="hlm-policy__section">
			<h2 class="hlm-h2"><?= e((string) ($section['title'] ?? '')) ?></h2>
			<?= nl2br(e((string) ($section['content'] ?? ''))) ?>
		</article>
		<?php endforeach; ?>
		<p class="hlm-policy__contact">Ready to opt out online? <a href="/removeme/">Submit the Do Not Sell request form</a>.</p>
	</div>
</section>
