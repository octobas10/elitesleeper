<!doctype html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title>Leads | HLM Admin</title>
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
				<h1 class="hlm-admin__title">Leads</h1>
				<p class="hlm-admin__subtitle">Full submissions from leads.db — open a row for every field</p>
			</div>
			<div class="hlm-admin__actions">
				<form method="post" action="/admin/logout/" style="display:inline;">
					<button type="submit" class="hlm-btn hlm-btn--outline">Sign out</button>
				</form>
			</div>
		</header>

		<?php if (!empty($not_found)): ?>
		<p class="hlm-admin-login__error" role="alert">That lead was not found.</p>
		<?php endif; ?>

		<section class="hlm-admin__stats" aria-label="Lead counts">
			<div class="hlm-admin__stat"><p class="hlm-admin__stat-label">Total</p><p class="hlm-admin__stat-value"><?= e((string) ($counts['total'] ?? 0)) ?></p></div>
			<div class="hlm-admin__stat"><p class="hlm-admin__stat-label">Student</p><p class="hlm-admin__stat-value"><?= e((string) ($counts['student'] ?? 0)) ?></p></div>
			<div class="hlm-admin__stat"><p class="hlm-admin__stat-label">University</p><p class="hlm-admin__stat-value"><?= e((string) ($counts['university'] ?? 0)) ?></p></div>
		</section>

		<form class="hlm-admin__filters" method="get" action="/admin/">
			<div class="hlm-admin__field">
				<label for="q">Search</label>
				<input id="q" name="q" type="search" value="<?= e((string) ($q ?? '')) ?>" placeholder="Email, name, phone, ID…" />
			</div>
			<div class="hlm-admin__field" style="flex: 0 0 10rem; min-width: 8rem;">
				<label for="form_type">Type</label>
				<select id="form_type" name="form_type">
					<option value=""<?= empty($form_type) ? ' selected' : '' ?>>All</option>
					<option value="student"<?= ($form_type ?? '') === 'student' ? ' selected' : '' ?>>Student</option>
					<option value="university"<?= ($form_type ?? '') === 'university' ? ' selected' : '' ?>>University</option>
				</select>
			</div>
			<button type="submit" class="hlm-btn hlm-btn--primary">Filter</button>
			<?php if (!empty($form_type) || !empty($q)): ?>
			<a class="hlm-btn hlm-btn--outline" href="/admin/">Clear</a>
			<?php endif; ?>
		</form>

		<div class="hlm-admin__table-wrap">
			<?php $leads = is_array($leads ?? null) ? $leads : []; ?>
			<?php if (!$leads): ?>
			<p class="hlm-admin__empty">No leads found.</p>
			<?php else: ?>
			<table class="hlm-admin__table hlm-admin__table--rich">
				<thead>
					<tr><th>When</th><th>Type</th><th>Name / contact</th><th>Location</th><th>Education</th><th>Program / institution</th><th></th></tr>
				</thead>
				<tbody>
					<?php foreach ($leads as $lead): ?>
					<tr>
						<td><?= e((string) ($lead['when'] ?? ($lead['created_at'] ?? '—'))) ?></td>
						<td><span class="hlm-admin__badge hlm-admin__badge--<?= e((string) ($lead['form_type'] ?? 'student')) ?>"><?= e((string) ($lead['form_type'] ?? '—')) ?></span></td>
						<td>
							<strong><?= e(trim((string) (($lead['name'] ?? (($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? ''))) ?: '—'))) ?></strong><br />
							<?php if (!empty($lead['email'])): ?><a href="mailto:<?= e((string) $lead['email']) ?>"><?= e((string) $lead['email']) ?></a><br /><?php endif; ?>
							<?php if (!empty($lead['phone'])): ?><a href="tel:<?= e((string) $lead['phone']) ?>"><?= e((string) $lead['phone']) ?></a><?php endif; ?>
						</td>
						<td><?= e((string) ($lead['location'] ?? '—')) ?></td>
						<td><?= e((string) ($lead['education'] ?? '—')) ?></td>
						<td><?= e((string) ($lead['program'] ?? ($lead['institution'] ?? ($lead['institution_name'] ?? '—')))) ?></td>
						<td><a class="hlm-btn hlm-btn--outline hlm-btn--compact" href="/admin/leads/<?= e((string) ($lead['id'] ?? '')) ?>/">View</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
<!doctype html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title>Leads | HLM Admin</title>
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
			<div><p class="hlm-admin__brand">Higher Learning Marketers</p><h1 class="hlm-admin__title">Leads</h1><p class="hlm-admin__subtitle">Full submissions from leads.db - open a row for every field</p></div>
			<div class="hlm-admin__actions"><form method="post" action="/admin/logout/" style="display:inline;"><button type="submit" class="hlm-btn hlm-btn--outline">Sign out</button></form></div>
		</header>
		<?php if (!empty($not_found)): ?><p class="hlm-admin-login__error" role="alert">That lead was not found.</p><?php endif; ?>
		<section class="hlm-admin__stats" aria-label="Lead counts"><div class="hlm-admin__stat"><p class="hlm-admin__stat-label">Total</p><p class="hlm-admin__stat-value"><?= e((string) ($counts['total'] ?? 0)) ?></p></div><div class="hlm-admin__stat"><p class="hlm-admin__stat-label">Student</p><p class="hlm-admin__stat-value"><?= e((string) ($counts['student'] ?? 0)) ?></p></div><div class="hlm-admin__stat"><p class="hlm-admin__stat-label">University</p><p class="hlm-admin__stat-value"><?= e((string) ($counts['university'] ?? 0)) ?></p></div></section>
		<form class="hlm-admin__filters" method="get" action="/admin/">
			<div class="hlm-admin__field"><label for="q">Search</label><input id="q" name="q" type="search" value="<?= e((string) ($q ?? '')) ?>" placeholder="Email, name, phone, ID..." /></div>
			<div class="hlm-admin__field" style="flex: 0 0 10rem; min-width: 8rem;">
				<label for="form_type">Type</label>
				<select id="form_type" name="form_type"><option value="" <?= empty($form_type) ? 'selected' : '' ?>>All</option><option value="student" <?= (($form_type ?? '') === 'student') ? 'selected' : '' ?>>Student</option><option value="university" <?= (($form_type ?? '') === 'university') ? 'selected' : '' ?>>University</option></select>
			</div>
			<button type="submit" class="hlm-btn hlm-btn--primary">Filter</button>
			<?php if (!empty($form_type) || !empty($q)): ?><a class="hlm-btn hlm-btn--outline" href="/admin/">Clear</a><?php endif; ?>
		</form>
		<div class="hlm-admin__table-wrap">
			<?php $leads = is_array($leads ?? null) ? $leads : []; ?>
			<?php if (!$leads): ?>
			<p class="hlm-admin__empty">No leads found.</p>
			<?php else: ?>
			<table class="hlm-admin__table hlm-admin__table--rich">
				<thead><tr><th>When</th><th>Type</th><th>Name / contact</th><th>Location</th><th>Education</th><th>Program / institution</th><th></th></tr></thead>
				<tbody>
					<?php foreach ($leads as $lead): ?>
					<?php $name = trim((string) (($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? ''))); ?>
					<tr>
						<td><?= e((string) ($lead['when'] ?? ($lead['created_at'] ?? '-'))) ?></td>
						<td><span class="hlm-admin__badge hlm-admin__badge--<?= e((string) ($lead['form_type'] ?? 'student')) ?>"><?= e((string) ($lead['form_type'] ?? '-')) ?></span></td>
						<td><strong><?= e((string) ($lead['name'] ?? ($name !== '' ? $name : '-'))) ?></strong><br /><?php if (!empty($lead['email'])): ?><a href="mailto:<?= e((string) $lead['email']) ?>"><?= e((string) $lead['email']) ?></a><br /><?php endif; ?><?php if (!empty($lead['phone'])): ?><a href="tel:<?= e((string) $lead['phone']) ?>"><?= e((string) $lead['phone']) ?></a><?php endif; ?></td>
						<td><?= e((string) ($lead['location'] ?? '-')) ?></td>
						<td><?= e((string) ($lead['education'] ?? '-')) ?></td>
						<td><?= e((string) ($lead['program'] ?? ($lead['institution'] ?? ($lead['institution_name'] ?? '-')))) ?></td>
						<td><a class="hlm-btn hlm-btn--outline hlm-btn--compact" href="/admin/leads/<?= e((string) ($lead['id'] ?? '')) ?>/">View</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
