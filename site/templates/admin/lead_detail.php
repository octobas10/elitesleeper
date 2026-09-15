<?php
$lead = is_array($lead ?? null) ? $lead : [];
$summary = is_array($summary ?? null) ? $summary : [];
$back_href = $back_href ?? '/admin/';
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?= e((string) ($lead['name'] ?? ($lead['id'] ?? 'Lead'))) ?> | HLM Admin</title>
	<link rel="icon" href="<?= e(asset('favicon.ico')) ?>" sizes="32x32" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-tokens.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-blocks.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-admin.css')) ?>" />
</head>
<body class="hlm-theme hlm-admin">
	<div class="hlm-admin__shell hlm-admin__shell--wide">
		<header class="hlm-admin__top">
			<div>
				<p class="hlm-admin__brand">Higher Learning Marketers</p>
				<p class="hlm-admin__crumb"><a href="<?= e((string) $back_href) ?>">Leads</a><span aria-hidden="true"> / </span><span><?= e((string) ($lead['id'] ?? '')) ?></span></p>
				<h1 class="hlm-admin__title"><?= e((string) ($summary['name'] ?? ($lead['name'] ?? ($lead['id'] ?? '')))) ?></h1>
				<p class="hlm-admin__subtitle">
					<span class="hlm-admin__badge hlm-admin__badge--<?= e((string) ($lead['form_type'] ?? 'student')) ?>"><?= e((string) ($lead['form_type'] ?? '—')) ?></span>
					<span class="hlm-admin__meta-inline"><?= e((string) ($summary['when'] ?? ($lead['created_at'] ?? ''))) ?></span>
					<?php if (!empty($lead['status'])): ?><span class="hlm-admin__meta-inline">Status: <?= e((string) $lead['status']) ?></span><?php endif; ?>
				</p>
			</div>
			<div class="hlm-admin__actions">
				<a class="hlm-btn hlm-btn--outline" href="<?= e((string) $back_href) ?>">Back to list</a>
				<form method="post" action="/admin/logout/" style="display:inline;"><button type="submit" class="hlm-btn hlm-btn--outline">Sign out</button></form>
			</div>
		</header>

		<section class="hlm-admin__hero-card" aria-label="Lead overview">
			<div class="hlm-admin__hero-grid">
				<div><p class="hlm-admin__stat-label">Email</p><p class="hlm-admin__hero-value"><?php if (!empty($lead['email'])): ?><a href="mailto:<?= e((string) $lead['email']) ?>"><?= e((string) $lead['email']) ?></a><?php else: ?>—<?php endif; ?></p></div>
				<div><p class="hlm-admin__stat-label">Phone</p><p class="hlm-admin__hero-value"><?php if (!empty($lead['phone'])): ?><a href="tel:<?= e((string) $lead['phone']) ?>"><?= e((string) $lead['phone']) ?></a><?php else: ?>—<?php endif; ?></p></div>
				<div>
					<p class="hlm-admin__stat-label"><?= (($lead['form_type'] ?? '') === 'university') ? 'Institution' : 'Program' ?></p>
					<p class="hlm-admin__hero-value">
						<?php if (($lead['form_type'] ?? '') === 'university'): ?>
						<?= e((string) ($summary['institution'] ?? ($lead['institution_name'] ?? '—'))) ?>
						<?php else: ?>
						<?= e((string) ($summary['program'] ?? ($lead['program_of_interest'] ?? '—'))) ?>
						<?php endif; ?>
					</p>
				</div>
				<div><p class="hlm-admin__stat-label">Location</p><p class="hlm-admin__hero-value"><?= e((string) ($summary['location'] ?? ($lead['city'] ?? '—'))) ?></p></div>
			</div>
		</section>

		<?php foreach (($sections ?? []) as $section): ?>
		<section class="hlm-admin__section-card" aria-labelledby="sec-<?= e((string) ($section['id'] ?? '')) ?>">
			<h2 class="hlm-admin__section-title" id="sec-<?= e((string) ($section['id'] ?? '')) ?>"><?= e((string) ($section['title'] ?? '')) ?></h2>
			<dl class="hlm-admin__dl">
				<?php foreach (($section['fields'] ?? []) as $f): ?>
				<div class="hlm-admin__dl-row">
					<dt><?= e((string) ($f['label'] ?? '')) ?></dt>
					<dd>
						<?php if (!empty($f['href'])): ?>
						<a href="<?= e((string) $f['href']) ?>"<?= str_starts_with((string) $f['href'], 'http') ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?= e((string) ($f['value'] ?? '')) ?></a>
						<?php else: ?>
						<?= e((string) ($f['value'] ?? '')) ?>
						<?php endif; ?>
						<?php if (!empty($f['code']) && ($f['code'] !== ($f['value'] ?? null))): ?><span class="hlm-admin__code">code: <?= e((string) $f['code']) ?></span><?php endif; ?>
					</dd>
				</div>
				<?php endforeach; ?>
			</dl>
		</section>
		<?php endforeach; ?>

		<section class="hlm-admin__section-card" aria-labelledby="sec-raw">
			<h2 class="hlm-admin__section-title" id="sec-raw">Raw payload</h2>
			<pre class="hlm-admin__payload hlm-admin__payload--full"><?= e((string) ($payload_pretty ?? json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))) ?></pre>
		</section>
	</div>
</body>
</html>
<?php
$lead = is_array($lead ?? null) ? $lead : [];
$summary = is_array($summary ?? null) ? $summary : [];
$back_href = $back_href ?? '/admin/';
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?= e((string) ($lead['name'] ?? ($lead['id'] ?? 'Lead'))) ?> | HLM Admin</title>
	<link rel="icon" href="<?= e(asset('favicon.ico')) ?>" sizes="32x32" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-tokens.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-blocks.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-admin.css')) ?>" />
</head>
<body class="hlm-theme hlm-admin">
	<div class="hlm-admin__shell hlm-admin__shell--wide">
		<header class="hlm-admin__top">
			<div>
				<p class="hlm-admin__brand">Higher Learning Marketers</p>
				<p class="hlm-admin__crumb"><a href="<?= e((string) $back_href) ?>">Leads</a><span aria-hidden="true"> / </span><span><?= e((string) ($lead['id'] ?? '')) ?></span></p>
				<h1 class="hlm-admin__title"><?= e((string) ($summary['name'] ?? ($lead['name'] ?? ($lead['id'] ?? '')))) ?></h1>
				<p class="hlm-admin__subtitle"><span class="hlm-admin__badge hlm-admin__badge--<?= e((string) ($lead['form_type'] ?? 'student')) ?>"><?= e((string) ($lead['form_type'] ?? '-')) ?></span><span class="hlm-admin__meta-inline"><?= e((string) ($summary['when'] ?? ($lead['created_at'] ?? ''))) ?></span><?php if (!empty($lead['status'])): ?><span class="hlm-admin__meta-inline">Status: <?= e((string) $lead['status']) ?></span><?php endif; ?></p>
			</div>
			<div class="hlm-admin__actions"><a class="hlm-btn hlm-btn--outline" href="<?= e((string) $back_href) ?>">Back to list</a><form method="post" action="/admin/logout/" style="display:inline;"><button type="submit" class="hlm-btn hlm-btn--outline">Sign out</button></form></div>
		</header>
		<section class="hlm-admin__hero-card" aria-label="Lead overview">
			<div class="hlm-admin__hero-grid">
				<div><p class="hlm-admin__stat-label">Email</p><p class="hlm-admin__hero-value"><?php if (!empty($lead['email'])): ?><a href="mailto:<?= e((string) $lead['email']) ?>"><?= e((string) $lead['email']) ?></a><?php else: ?>-<?php endif; ?></p></div>
				<div><p class="hlm-admin__stat-label">Phone</p><p class="hlm-admin__hero-value"><?php if (!empty($lead['phone'])): ?><a href="tel:<?= e((string) $lead['phone']) ?>"><?= e((string) $lead['phone']) ?></a><?php else: ?>-<?php endif; ?></p></div>
				<div><p class="hlm-admin__stat-label"><?= (($lead['form_type'] ?? '') === 'university') ? 'Institution' : 'Program' ?></p><p class="hlm-admin__hero-value"><?= (($lead['form_type'] ?? '') === 'university') ? e((string) ($summary['institution'] ?? ($lead['institution_name'] ?? '-'))) : e((string) ($summary['program'] ?? ($lead['program_of_interest'] ?? '-'))) ?></p></div>
				<div><p class="hlm-admin__stat-label">Location</p><p class="hlm-admin__hero-value"><?= e((string) ($summary['location'] ?? ($lead['city'] ?? '-'))) ?></p></div>
			</div>
		</section>
		<?php foreach (($sections ?? []) as $section): ?>
		<section class="hlm-admin__section-card" aria-labelledby="sec-<?= e((string) ($section['id'] ?? '')) ?>">
			<h2 class="hlm-admin__section-title" id="sec-<?= e((string) ($section['id'] ?? '')) ?>"><?= e((string) ($section['title'] ?? '')) ?></h2>
			<dl class="hlm-admin__dl">
				<?php foreach (($section['fields'] ?? []) as $f): ?>
				<div class="hlm-admin__dl-row"><dt><?= e((string) ($f['label'] ?? '')) ?></dt><dd><?php if (!empty($f['href'])): ?><a href="<?= e((string) $f['href']) ?>"<?= str_starts_with((string) $f['href'], 'http') ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?= e((string) ($f['value'] ?? '')) ?></a><?php else: ?><?= e((string) ($f['value'] ?? '')) ?><?php endif; ?><?php if (!empty($f['code']) && ($f['code'] !== ($f['value'] ?? null))): ?><span class="hlm-admin__code">code: <?= e((string) $f['code']) ?></span><?php endif; ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</section>
		<?php endforeach; ?>
		<section class="hlm-admin__section-card" aria-labelledby="sec-raw"><h2 class="hlm-admin__section-title" id="sec-raw">Raw payload</h2><pre class="hlm-admin__payload hlm-admin__payload--full"><?= e((string) ($payload_pretty ?? json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))) ?></pre></section>
	</div>
</body>
</html>
