<footer id="hlm-site-footer" class="hlm-footer">
	<div class="hlm-container">
		<div class="hlm-footer__grid">
			<div class="hlm-footer__brand">
				<a class="hlm-footer__brand-link" href="/" rel="home">
					<img
						class="hlm-footer__brand-mark"
						src="<?= e((string) ((!empty($site['logo']) && $site['logo'] !== '/favicon.svg') ? $site['logo'] : asset('favicon.svg'))) ?>"
						alt=""
						width="40"
						height="40"
						decoding="async"
					/>
					<span class="hlm-footer__brand-name"><?= e((string) ($site['name'] ?? 'Higher Learning Marketers')) ?></span>
				</a>
				<p>
					Premium education lead generation connecting institutions with high-intent prospective
					students. Over a decade of proven results in the higher education sector.
				</p>
			</div>

			<div class="hlm-footer__col">
				<h4>Company</h4>
				<ul class="hlm-footer__links">
					<li><a href="/about-us/">About Us</a></li>
					<li><a href="/services/">Our Services</a></li>
					<li><a href="/affiliations/">Affiliations</a></li>
					<li><a href="/blog/">Blog</a></li>
				</ul>
			</div>

			<div class="hlm-footer__col">
				<h4>Legal</h4>
				<ul class="hlm-footer__links">
					<li><a href="/privacy-policy/">Privacy Policy</a></li>
					<li><a href="/privacy-policy/">Terms of Service</a></li>
					<li><a href="/privacy-policy/">Compliance</a></li>
				</ul>
			</div>

			<div class="hlm-footer__col">
				<h4>Contact</h4>
				<ul class="hlm-footer__contact">
					<li>
						<a class="hlm-footer__contact-btn" href="mailto:<?= e((string) ($contact['email'] ?? '')) ?>">
							<span>Email Us</span>
						</a>
					</li>
					<li class="hlm-footer__contact-item">
						<a href="<?= e((string) ($contact['phoneHref'] ?? 'tel:+10000000000')) ?>"><?= e((string) ($contact['phoneDisplay'] ?? '')) ?></a>
					</li>
					<li class="hlm-footer__contact-item">
						<a href="<?= e((string) ($contact['addressHref'] ?? '#')) ?>" target="_blank" rel="noopener noreferrer">
							<?= e((string) ($contact['addressDisplay'] ?? '')) ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="hlm-footer__bottom">
			<p><?= e((string) ($current_year ?? 2026)) ?> <?= e((string) ($site['name'] ?? 'Higher Learning Marketers')) ?>. All rights reserved. TCPA &amp; GDPR Compliant.</p>
		</div>
	</div>
</footer>
<?php
$logoSrc = (empty($site['logo']) || (string) $site['logo'] === '/favicon.svg')
    ? asset('favicon.svg')
    : (string) $site['logo'];
?>
<footer id="hlm-site-footer" class="hlm-footer">
	<div class="hlm-container">
		<div class="hlm-footer__grid">
			<div class="hlm-footer__brand">
				<a class="hlm-footer__brand-link" href="/" rel="home">
					<img class="hlm-footer__brand-mark" src="<?= e($logoSrc) ?>" alt="" width="40" height="40" decoding="async" />
					<span class="hlm-footer__brand-name"><?= e((string) ($site['name'] ?? '')) ?></span>
				</a>
				<p>Premium education lead generation connecting institutions with high-intent prospective students. Over a decade of proven results in the higher education sector.</p>
			</div>

			<div class="hlm-footer__col">
				<h4>Company</h4>
				<ul class="hlm-footer__links"><li><a href="/about-us/">About Us</a></li><li><a href="/services/">Our Services</a></li><li><a href="/affiliations/">Affiliations</a></li><li><a href="/blog/">Blog</a></li></ul>
			</div>

			<div class="hlm-footer__col">
				<h4>Legal</h4>
				<ul class="hlm-footer__links"><li><a href="/privacy-policy/">Privacy Policy</a></li><li><a href="/privacy-policy/">Terms of Service</a></li><li><a href="/privacy-policy/">Compliance</a></li></ul>
			</div>

			<div class="hlm-footer__col">
				<h4>Contact</h4>
				<ul class="hlm-footer__contact">
					<li><a class="hlm-footer__contact-btn" href="mailto:<?= e((string) ($contact['email'] ?? '')) ?>"><span>Email Us</span></a></li>
					<li class="hlm-footer__contact-item"><a href="<?= e((string) ($contact['phoneHref'] ?? 'tel:+10000000000')) ?>"><?= e((string) ($contact['phoneDisplay'] ?? '')) ?></a></li>
					<li class="hlm-footer__contact-item"><a href="<?= e((string) ($contact['addressHref'] ?? '#')) ?>" target="_blank" rel="noopener noreferrer"><?= e((string) ($contact['addressDisplay'] ?? '')) ?></a></li>
				</ul>
			</div>
		</div>
		<div class="hlm-footer__bottom"><p><?= e((string) ($current_year ?? 2026)) ?> <?= e((string) ($site['name'] ?? '')) ?>. All rights reserved. TCPA &amp; GDPR Compliant.</p></div>
	</div>
</footer>
