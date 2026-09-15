<?php
$transparent = !empty($transparent_header);
$logoSrc = (empty($site['logo']) || (string) $site['logo'] === '/favicon.svg')
    ? asset('favicon.svg')
    : (string) $site['logo'];
$headerClass = $transparent ? 'hlm-site-header site-header hlm-site-header--transparent' : 'hlm-site-header site-header';
?>
<header id="masthead" class="<?= e($headerClass) ?>" role="banner" <?php if ($transparent): ?>style="position:absolute;left:0;right:0;top:0;width:100%;z-index:1000;"<?php endif; ?>>
	<div class="hlm-site-header__bar">
		<div class="hlm-container hlm-site-header__inner">
			<div class="hlm-site-header__brand-wrap">
				<a class="hlm-site-header__brand" href="/" rel="home">
					<img class="hlm-site-header__brand-mark" src="<?= e($logoSrc) ?>" alt="" width="40" height="40" decoding="async" />
					<span class="hlm-site-header__brand-text">
						<span class="hlm-site-header__brand-name"><?= e((string) ($site['name'] ?? '')) ?></span>
						<span class="hlm-site-header__brand-tag"><?= e((string) ($site['tagline'] ?? '')) ?></span>
					</span>
				</a>
			</div>

			<nav class="hlm-site-header__nav" aria-label="Primary">
				<ul id="hlm-primary-menu" class="hlm-site-header__menu" role="list">
					<?php foreach (($nav ?? []) as $item): ?>
					<li><a href="<?= e((string) ($item['href'] ?? '#')) ?>"><?= e((string) ($item['label'] ?? '')) ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<div class="hlm-site-header__actions">
				<div class="hlm-site-header__actions-desktop">
					<a class="hlm-site-header__phone" href="<?= e((string) ($contact['phoneHref'] ?? 'tel:+10000000000')) ?>">
						<span class="hlm-site-header__phone-text"><?= e((string) ($contact['phoneDisplay'] ?? '')) ?></span>
					</a>
					<a class="hlm-btn hlm-btn-primary hlm-btn--compact hlm-site-header__cta" href="/contact-us/#form">Get Started</a>
				</div>
				<div class="hlm-site-header__actions-mobile">
					<a class="hlm-site-header__phone hlm-site-header__phone--bar" href="<?= e((string) ($contact['phoneHref'] ?? 'tel:+10000000000')) ?>" aria-label="Call <?= e((string) ($contact['phoneDisplay'] ?? '')) ?>">
						<span aria-hidden="true">☎</span>
					</a>
				</div>
				<button type="button" class="hlm-site-header__toggle" data-hlm-nav-toggle aria-controls="hlm-mobile-nav" aria-expanded="false" aria-label="Open menu">
					<span class="hlm-site-header__toggle-box" aria-hidden="true"><span></span><span></span><span></span></span>
				</button>
			</div>
		</div>
	</div>

	<div id="hlm-mobile-nav" class="hlm-site-header__drawer" data-hlm-nav-drawer hidden>
		<button type="button" class="hlm-site-header__overlay" data-hlm-nav-close tabindex="-1" aria-hidden="true"></button>
		<div class="hlm-site-header__panel" role="dialog" aria-modal="true" aria-label="Mobile menu">
			<div class="hlm-site-header__panel-head">
				<span class="hlm-site-header__panel-title">Menu</span>
				<button type="button" class="hlm-site-header__close" data-hlm-nav-close aria-label="Close menu">✕</button>
			</div>
			<nav class="hlm-site-header__mobile-nav" aria-label="Primary mobile">
				<ul id="hlm-mobile-menu" class="hlm-site-header__mobile-menu" role="list">
					<?php foreach (($nav ?? []) as $item): ?>
					<li><a href="<?= e((string) ($item['href'] ?? '#')) ?>"><?= e((string) ($item['label'] ?? '')) ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<div class="hlm-site-header__panel-foot"><a class="hlm-btn hlm-btn-primary hlm-btn--full hlm-site-header__cta" href="/contact-us/#form">Get Started</a></div>
		</div>
	</div>
</header>
