<!doctype html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?= e((string) (($title ?? 'Login'))) ?> | HLM Admin</title>
	<link rel="icon" href="<?= e(asset('favicon.ico')) ?>" sizes="32x32" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-tokens.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-blocks.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-admin.css')) ?>" />
</head>
<body class="hlm-theme hlm-admin">
	<main class="hlm-admin-login">
		<div class="hlm-admin-login__card">
			<p class="hlm-admin__brand">Higher Learning Marketers</p>
			<h1 class="hlm-admin__title">Admin login</h1>
			<p class="hlm-admin__subtitle">View lead submissions from the forms.</p>

			<?php $error_message = $error_message ?? ($error ?? null); ?>
			<?php if (!empty($error_message)): ?><p class="hlm-admin-login__error" role="alert"><?= e((string) $error_message) ?></p><?php endif; ?>

			<form class="hlm-admin-login__form" method="post" action="/admin/login/">
				<input type="hidden" name="next" value="<?= e((string) ($next ?? '/admin/')) ?>" />
				<label>
					Password
					<input type="password" name="password" autocomplete="current-password" required autofocus />
				</label>
				<?php if (!empty($turnstile_site_key)): ?>
				<div class="hlm-admin-login__turnstile cf-turnstile" data-sitekey="<?= e((string) $turnstile_site_key) ?>" data-theme="light"></div>
				<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
				<?php endif; ?>
				<button type="submit" class="hlm-btn hlm-btn--primary hlm-btn--full">Sign in</button>
			</form>
		</div>
	</main>
</body>
</html>
