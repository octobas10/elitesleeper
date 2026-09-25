<?php partial('components/page_hero', [
    'overline' => 'Legal',
    'headline' => 'Terms of Service',
    'description' => 'HigherLearningMarketers.com Terms Of Service - Effective as of 9/10/2026',
]); ?>
<section class="hlm-policy hlm-section">
	<div class="hlm-container hlm-policy__inner">
		<div class="hlm-policy__section hlm-body">
			<?= nl2br(e((string) ($terms_text ?? ''))) ?>
		</div>
	</div>
</section>
