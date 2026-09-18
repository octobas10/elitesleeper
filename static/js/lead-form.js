(function () {
	'use strict';

	function getCatalog() {
		var data = window.HLM_INSTITUTION_DATA || {};
		return {
			campus: data.campus || {},
			programs: data.programs || {},
		};
	}

	function digitsOnly(value) {
		return String(value || '').replace(/\D/g, '');
	}

	function formatPhoneDisplay(value) {
		var digits = digitsOnly(value).slice(0, 10);
		if (digits.length <= 3) return digits;
		if (digits.length <= 6) return '(' + digits.slice(0, 3) + ') ' + digits.slice(3);
		return '(' + digits.slice(0, 3) + ') ' + digits.slice(3, 6) + '-' + digits.slice(6);
	}

	function formatZipDisplay(value) {
		var digits = String(value || '').replace(/\D/g, '').slice(0, 9);
		if (digits.length <= 5) {
			return digits;
		}
		return digits.slice(0, 5) + '-' + digits.slice(5);
	}

	function isValidZip(zip) {
		// US ZIP only: 12345 or 12345-6789 (max length 10)
		var z = String(zip || '').trim();
		return /^\d{5}(-\d{4})?$/.test(z);
	}

	function isValidName(name) {
		return /^[A-Za-z\u00C0-\u024F\s'.-]{2,}$/.test(String(name || '').trim());
	}

	function setFieldInvalid(field, isInvalid) {
		var group = field.closest('.hlm-form-group') || field.closest('.hlm-combobox');
		if (group) group.classList.toggle('hlm-form-group--invalid', isInvalid);
	}

	function ensureFieldErrorEl(field) {
		var group = field.closest('.hlm-form-group') || field.closest('.hlm-combobox');
		if (!group) return null;

		var el = group.querySelector('.hlm-field-error');
		if (!el) {
			el = document.createElement('p');
			el.className = 'hlm-field-error';
			el.setAttribute('role', 'alert');
			el.hidden = true;
			el.id = 'hlm-err-' + Math.random().toString(36).slice(2, 9);

			var anchor = field;
			if (field.type === 'hidden') {
				var comboboxInput = group.querySelector('.hlm-combobox-input');
				if (comboboxInput) anchor = comboboxInput;
			}

			if (anchor.parentNode) {
				anchor.insertAdjacentElement('afterend', el);
			} else {
				group.appendChild(el);
			}
		}
		return el;
	}

	function setFieldError(field, message) {
		var group = field.closest('.hlm-form-group') || field.closest('.hlm-combobox');
		var errorEl = ensureFieldErrorEl(field);

		if (message) {
			setFieldInvalid(field, true);
			field.setAttribute('aria-invalid', 'true');
			if (errorEl) {
				errorEl.textContent = message;
				errorEl.hidden = false;
				var hint = group ? group.querySelector('.hlm-field-hint') : null;
				var describedBy = [];
				if (hint && hint.id) describedBy.push(hint.id);
				describedBy.push(errorEl.id);
				field.setAttribute('aria-describedby', describedBy.join(' '));
			}
			return;
		}

		setFieldInvalid(field, false);
		field.removeAttribute('aria-invalid');
		if (errorEl) {
			errorEl.hidden = true;
			errorEl.textContent = '';
		}
	}

	function getFieldLabel(field) {
		var group = field.closest('.hlm-form-group') || field.closest('.hlm-combobox');
		var label = group ? group.querySelector('label') : null;
		if (label) {
			return (label.textContent || '').replace(/\*/g, '').replace(/optional/gi, '').trim();
		}
		return field.name || 'This field';
	}

	function validateField(field) {
		if (field.classList.contains('hlm-hp') || field.closest('.hlm-hp-field')) return '';
		if (field.disabled) return '';
		if (field.type === 'hidden' && field.name !== 'program_of_interest') return '';

		var value =
			field.type === 'checkbox' ? field.checked : String(field.value || '').trim();
		var label = getFieldLabel(field);

		if (field.name === 'program_of_interest' && field.required && !value) {
			return 'Please select a program from the list.';
		}

		if (field.required && !value) {
			if (field.type === 'checkbox') return 'Please check the box to continue.';
			if (field.tagName === 'SELECT') return 'Please select ' + label + '.';
			return 'Please enter ' + label + '.';
		}

		if (!value && !field.required) return '';

		if (field.type === 'email' || field.name === 'email') {
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value))) {
				return 'Please enter a valid email address.';
			}
		}

		if (field.type === 'tel' || field.name === 'phone') {
			if (digitsOnly(String(value)).length !== 10) {
				return 'Please enter a 10-digit phone number.';
			}
		}

		if (field.name === 'zip' && !isValidZip(String(value))) {
			return 'Enter a valid 5-digit U.S. ZIP (12345).';
		}

		if (
			(field.name === 'first_name' ||
				field.name === 'last_name' ||
				field.name === 'institution_name') &&
			!isValidName(String(value))
		) {
			return 'Please enter at least 2 characters.';
		}

		if ((field.type === 'url' || field.name === 'website') && value) {
			if (!/^https?:\/\/.+\..+/i.test(String(value))) {
				return 'Please enter a full website URL starting with https://';
			}
		}

		return '';
	}

	function validateFields(fields) {
		var firstInvalid = null;
		var firstMessage = '';

		Array.prototype.forEach.call(fields, function (field) {
			var msg = validateField(field);
			setFieldError(field, msg);
			if (msg && !firstInvalid) {
				firstInvalid = field;
				firstMessage = msg;
			}
		});

		return { valid: !firstInvalid, field: firstInvalid, message: firstMessage };
	}

	function focusField(field) {
		if (!field) return;
		if (field.type === 'hidden') {
			var input = field.closest('.hlm-combobox');
			var visible = input ? input.querySelector('.hlm-combobox-input') : null;
			if (visible) visible.focus();
			return;
		}
		field.focus();
	}

	function initFieldValidation(root) {
		root.querySelectorAll('.hlm-lead-form').forEach(function (form) {
			form.querySelectorAll('input, select, textarea').forEach(function (field) {
				if (field.type === 'tel' || field.name === 'phone') {
					field.addEventListener('input', function () {
						var formatted = formatPhoneDisplay(field.value);
						if (field.value !== formatted) field.value = formatted;
						if (!validateField(field)) setFieldError(field, '');
					});
				}

				if (field.name === 'zip') {
					field.setAttribute('maxlength', '10');
					field.addEventListener('input', function () {
						var formatted = formatZipDisplay(field.value);
						if (field.value !== formatted) field.value = formatted;
						if (!validateField(field)) setFieldError(field, '');
					});
				}

				field.addEventListener('blur', function () {
					if (field.type === 'hidden' || field.classList.contains('hlm-hp')) return;
					setFieldError(field, validateField(field));
				});

				field.addEventListener('input', function () {
					if (field.type === 'tel' || field.name === 'phone' || field.name === 'zip') return;
					if (!validateField(field)) setFieldError(field, '');
				});

				field.addEventListener('change', function () {
					if (!validateField(field)) setFieldError(field, '');
				});
			});
		});
	}

	function populateSelect(select, options, placeholder) {
		select.innerHTML = '';
		var empty = document.createElement('option');
		empty.value = '';
		empty.textContent = placeholder;
		select.appendChild(empty);
		Object.keys(options || {}).forEach(function (value) {
			var opt = document.createElement('option');
			opt.value = value;
			opt.textContent = options[value];
			select.appendChild(opt);
		});
	}

	function initWizard(form) {
		var steps = Array.prototype.slice.call(form.querySelectorAll('.hlm-form-step'));
		var progressFill = form.querySelector('.hlm-form-progress-fill');
		var stepCurrent = form.querySelector('.hlm-step-current');
		var total = steps.length;
		var current = 1;

		function validateStep(stepEl) {
			var result = validateFields(stepEl.querySelectorAll('input, select, textarea'));
			if (!result.valid) {
				stepEl.classList.add('hlm-form-step--validated');
				focusField(result.field);
				return false;
			}
			return true;
		}

		function goToStep(stepNum) {
			current = Math.max(1, Math.min(total, stepNum));
			steps.forEach(function (step) {
				var isActive = parseInt(step.dataset.step || '0', 10) === current;
				step.classList.toggle('active', isActive);
				step.hidden = !isActive;
			});
			if (progressFill) progressFill.style.width = (current / total) * 100 + '%';
			if (stepCurrent) stepCurrent.textContent = String(current);
			if (current === total) {
				form.dispatchEvent(new CustomEvent('hlm:step-final'));
			}
		}

		form.querySelectorAll('.hlm-form-next').forEach(function (button) {
			button.addEventListener('click', function () {
				var stepEl = button.closest('.hlm-form-step');
				if (!stepEl || !validateStep(stepEl)) return;
				goToStep(current + 1);
			});
		});

		form.querySelectorAll('.hlm-form-back').forEach(function (button) {
			button.addEventListener('click', function () {
				goToStep(current - 1);
			});
		});

		form.addEventListener('hlm:reset-steps', function () {
			goToStep(1);
		});
		form.addEventListener('hlm:goto-step', function (event) {
			if (event.detail && event.detail.step) goToStep(event.detail.step);
		});
		goToStep(1);
	}

	function initProgramCombobox(combobox) {
		var hidden = combobox.querySelector('input[type="hidden"][name="program_of_interest"]');
		var input = combobox.querySelector('.hlm-combobox-input');
		var list = combobox.querySelector('.hlm-combobox-list');
		var countEl = combobox.querySelector('.hlm-combobox-count');
		var form = combobox.closest('form');
		if (!hidden || !input || !list) return;

		var options = [];
		var filtered = [];
		var activeIndex = -1;

		function setOptions(programMap) {
			options = Object.keys(programMap || {})
				.map(function (code) {
					return { code: code, label: programMap[code] };
				})
				.sort(function (a, b) {
					return a.label.localeCompare(b.label);
				});
			filtered = options.slice();
			renderList();
			updateCount();
		}

		function clearSelection() {
			hidden.value = '';
			input.value = '';
			input.disabled = true;
			input.placeholder = 'Select campus first, then search programs...';
			closeList();
			options = [];
			filtered = [];
			renderList();
			updateCount();
			setFieldError(hidden, '');
		}

		function updateCount() {
			if (!countEl) return;
			if (!options.length) {
				countEl.hidden = true;
				return;
			}
			countEl.hidden = false;
			countEl.textContent = options.length + ' programs available';
		}

		function closeList() {
			list.hidden = true;
			input.setAttribute('aria-expanded', 'false');
			activeIndex = -1;
		}

		function openList() {
			if (!filtered.length || input.disabled) return;
			list.hidden = false;
			input.setAttribute('aria-expanded', 'true');
		}

		function renderList() {
			list.innerHTML = '';
			filtered.forEach(function (item, index) {
				var li = document.createElement('li');
				li.className = 'hlm-combobox-option';
				li.setAttribute('role', 'option');
				li.dataset.code = item.code;
				li.textContent = item.label;
				if (index === activeIndex) {
					li.classList.add('is-active');
					li.setAttribute('aria-selected', 'true');
				}
				li.addEventListener('mousedown', function (e) {
					e.preventDefault();
					hidden.value = item.code;
					input.value = item.label;
					setFieldError(hidden, '');
					closeList();
				});
				list.appendChild(li);
			});
		}

		function filterOptions(query) {
			var q = query.trim().toLowerCase();
			filtered = !q
				? options.slice()
				: options.filter(function (item) {
						return (
							item.label.toLowerCase().indexOf(q) !== -1 ||
							item.code.toLowerCase().indexOf(q) !== -1
						);
					});
			activeIndex = filtered.length ? 0 : -1;
			renderList();
			openList();
		}

		input.addEventListener('focus', function () {
			if (!input.disabled) filterOptions(input.value);
		});
		input.addEventListener('input', function () {
			hidden.value = '';
			filterOptions(input.value);
		});
		input.addEventListener('keydown', function (event) {
			if (input.disabled) return;
			if (event.key === 'ArrowDown') {
				event.preventDefault();
				if (list.hidden) openList();
				else activeIndex = Math.min(activeIndex + 1, filtered.length - 1);
				renderList();
			} else if (event.key === 'ArrowUp') {
				event.preventDefault();
				activeIndex = Math.max(activeIndex - 1, 0);
				renderList();
			} else if (event.key === 'Enter' && activeIndex >= 0 && filtered[activeIndex]) {
				event.preventDefault();
				hidden.value = filtered[activeIndex].code;
				input.value = filtered[activeIndex].label;
				setFieldError(hidden, '');
				closeList();
			} else if (event.key === 'Escape') {
				closeList();
			}
		});

		document.addEventListener('click', function (event) {
			if (!combobox.contains(event.target)) closeList();
		});

		if (form) {
			form.addEventListener('hlm:programs-updated', function (event) {
				var detail = event.detail || {};
				if (detail.combobox === combobox) {
					setOptions(detail.programs || {});
					input.disabled = false;
					input.placeholder = 'Search or select a program...';
				}
			});
			form.addEventListener('hlm:programs-cleared', function () {
				clearSelection();
			});
		}

		combobox.hlmClearPrograms = clearSelection;
	}

	function dispatchPrograms(form, combobox, programs) {
		form.dispatchEvent(
			new CustomEvent('hlm:programs-updated', {
				detail: { combobox: combobox, programs: programs },
			}),
		);
	}

	function initStudentCascades(root) {
		var form = root.querySelector('[data-type="student"]');
		if (!form) return;

		var category = form.querySelector('.hlm-student-category');
		var campus = form.querySelector('.hlm-student-campus');
		var combobox = form.querySelector('.hlm-program-combobox');
		if (!category || !campus || !combobox) return;

		category.addEventListener('change', function () {
			var catalog = getCatalog();
			form.dispatchEvent(new CustomEvent('hlm:programs-cleared'));
			var cat = category.value;
			if (cat && catalog.campus[cat]) {
				populateSelect(campus, catalog.campus[cat], 'Select campus');
				campus.disabled = false;
				campus.value = '';
			} else {
				populateSelect(campus, {}, 'Select campus');
				campus.disabled = true;
				campus.value = '';
			}
		});

		campus.addEventListener('change', function () {
			var catalog = getCatalog();
			var cat = category.value;
			var camp = campus.value;
			if (cat && camp && catalog.programs[cat] && catalog.programs[cat][camp]) {
				dispatchPrograms(form, combobox, catalog.programs[cat][camp]);
			} else {
				form.dispatchEvent(new CustomEvent('hlm:programs-cleared'));
			}
		});

		form.addEventListener('hlm:step-final', function () {
			var catalog = getCatalog();
			var cat = category.value;
			var camp = campus.value;
			if (cat && camp && catalog.programs[cat] && catalog.programs[cat][camp]) {
				dispatchPrograms(form, combobox, catalog.programs[cat][camp]);
			}
		});
	}

	function mergeCompliance(root, payload) {
		['trustedformcerturl', 'universal_leadid', 'page_url'].forEach(function (name) {
			var field = root.querySelector('[name="' + name + '"]');
			if (field && field.value) payload[name] = field.value;
		});
		if (!payload.page_url) payload.page_url = window.location.href;
	}

	function getTurnstileApi() {
		return window.turnstile;
	}

	function loadTurnstileScript() {
		if (getTurnstileApi()) return Promise.resolve();
		var existing = document.querySelector('script[src*="challenges.cloudflare.com/turnstile"]');
		if (existing) {
			return new Promise(function (resolve) {
				existing.addEventListener('load', function () {
					resolve();
				}, { once: true });
				if (getTurnstileApi()) resolve();
			});
		}
		return new Promise(function (resolve, reject) {
			var script = document.createElement('script');
			script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
			script.async = true;
			script.onload = function () {
				resolve();
			};
			script.onerror = function () {
				reject(new Error('Failed to load security check'));
			};
			document.head.appendChild(script);
		});
	}

	function ensureTurnstileWidgets(root) {
		return fetch('/api/public-config/')
			.then(function (res) {
				return res.json().catch(function () {
					return {};
				});
			})
			.then(function (data) {
				var siteKey = (data.turnstileSiteKey || '').trim();
				if (!siteKey) return;
				return loadTurnstileScript().then(function () {
					var api = getTurnstileApi();
					if (!api) return;
					root.querySelectorAll('.hlm-lead-form').forEach(function (form) {
						if (form.querySelector('.cf-turnstile, .hlm-turnstile-wrap')) return;
						var actions = form.querySelector(
							'.hlm-form-step:last-of-type .hlm-form-step-actions',
						);
						if (!actions) return;
						var wrap = document.createElement('div');
						wrap.className = 'hlm-turnstile-wrap';
						var widget = document.createElement('div');
						widget.className = 'cf-turnstile';
						wrap.appendChild(widget);
						if (actions.parentElement) {
							actions.parentElement.insertBefore(wrap, actions);
						}
						api.render(widget, { sitekey: siteKey, theme: 'light', size: 'flexible' });
					});
				});
			})
			.catch(function (err) {
				console.warn('[hlm-turnstile] bootstrap failed', err);
			});
	}

	function applyServerFieldErrors(form, fields) {
		if (!Array.isArray(fields)) return;
		fields.forEach(function (name) {
			if (typeof name !== 'string') return;
			var field = form.querySelector('[name="' + CSS.escape(name) + '"]');
			if (!field) return;
			var stepEl = field.closest('.hlm-form-step');
			if (stepEl) stepEl.classList.add('hlm-form-step--validated');
			setFieldError(field, validateField(field) || 'Please complete this field.');
		});
	}

	function formDataToPayload(form) {
		var formData = new FormData(form);
		var payload = { form_type: form.dataset.type };
		var multi = {};
		formData.forEach(function (value, key) {
			if (key === 'hlm_hp_url') return;
			if (Object.prototype.hasOwnProperty.call(payload, key) || multi[key]) {
				if (!multi[key]) {
					multi[key] = [payload[key]];
					delete payload[key];
				}
				multi[key].push(value);
			} else {
				payload[key] = value;
			}
		});
		Object.keys(multi).forEach(function (key) {
			payload[key] = multi[key];
		});
		return payload;
	}

	function initLeadCapture() {
		var captureRoot = document.querySelector('[data-hlm-lead-capture]');
		if (!captureRoot) return;

		ensureTurnstileWidgets(captureRoot);

		var tabButtons = captureRoot.querySelectorAll('[data-tab]');
		var forms = captureRoot.querySelectorAll('.hlm-lead-form');
		var typeSelect = captureRoot.querySelector('[data-hlm-form-tabs-select]');

		function activateType(type) {
			if (!type) return;
			tabButtons.forEach(function (b) {
				var active = b.dataset.tab === type;
				b.classList.toggle('active', active);
				b.setAttribute('aria-selected', active ? 'true' : 'false');
			});
			forms.forEach(function (form) {
				var show = form.dataset.type === type;
				form.hidden = !show;
				form.classList.toggle('active', show);
			});
			if (typeSelect && typeSelect.value !== type) typeSelect.value = type;
		}

		tabButtons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				activateType(btn.dataset.tab);
			});
		});

		if (typeSelect) {
			typeSelect.addEventListener('change', function () {
				activateType(typeSelect.value);
			});
		}

		captureRoot.querySelectorAll('.hlm-program-combobox').forEach(function (box) {
			initProgramCombobox(box);
		});
		initStudentCascades(captureRoot);
		initFieldValidation(captureRoot);

		forms.forEach(function (form) {
			if (form.classList.contains('hlm-form-wizard')) initWizard(form);

			form.addEventListener('submit', function (event) {
				event.preventDefault();
				var status = form.querySelector('[data-form-status]');
				var submitBtn = form.querySelector('.hlm-form-submit, button[type="submit"]');
				if (!status || !submitBtn) return;

				var hp = form.querySelector('input[name="hlm_hp_url"]');
				if (hp && hp.value) return;

				var wizardSteps = form.querySelectorAll('.hlm-form-step');
				if (wizardSteps.length) {
					for (var i = 0; i < wizardSteps.length; i++) {
						var step = wizardSteps[i];
						var result = validateFields(step.querySelectorAll('input, select, textarea'));
						if (!result.valid) {
							var stepNum = parseInt(step.dataset.step || '1', 10);
							step.classList.add('hlm-form-step--validated');
							form.dispatchEvent(
								new CustomEvent('hlm:goto-step', { detail: { step: stepNum } }),
							);
							focusField(result.field);
							status.hidden = true;
							return;
						}
					}
				} else {
					var allResult = validateFields(form.querySelectorAll('input, select, textarea'));
					if (!allResult.valid) {
						var invalidStep = allResult.field
							? allResult.field.closest('.hlm-form-step')
							: null;
						if (invalidStep) invalidStep.classList.add('hlm-form-step--validated');
						focusField(allResult.field);
						status.hidden = true;
						return;
					}
				}

				var payload = formDataToPayload(form);
				mergeCompliance(captureRoot, payload);

				var turnstileInput = form.querySelector(
					'textarea[name="cf-turnstile-response"], input[name="cf-turnstile-response"]',
				);
				if (form.querySelector('.cf-turnstile') && !(turnstileInput && turnstileInput.value)) {
					status.hidden = false;
					status.textContent = 'Please complete the security check.';
					status.className = 'hlm-form-status is-error';
					return;
				}
				if (turnstileInput && turnstileInput.value) {
					payload['cf-turnstile-response'] = turnstileInput.value;
				}

				submitBtn.disabled = true;
				status.hidden = false;
				status.textContent = 'Submitting…';
				status.className = 'hlm-form-status';

				fetch('/api/submit/', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(payload),
				})
					.then(function (res) {
						return res.json().catch(function () {
							return {};
						}).then(function (data) {
							return { res: res, data: data };
						});
					})
					.then(function (_ref) {
						var res = _ref.res;
						var data = _ref.data;
						if (!res.ok) {
							applyServerFieldErrors(form, data.fields);
							var widget = form.querySelector('.cf-turnstile');
							var api = getTurnstileApi();
							if (widget && api && api.reset) {
								api.reset(widget);
							}
							throw new Error(
								typeof data.error === 'string'
									? data.error
									: 'Submission failed. Please try again.',
							);
						}
						var formType = form.dataset.type || 'student';
						var thankYou = new URL('/thank-you/', window.location.origin);
						thankYou.searchParams.set('type', formType);
						if (typeof data.lead_id === 'string' && data.lead_id) {
							thankYou.searchParams.set('ref', data.lead_id);
						}
						window.location.assign(thankYou.toString());
					})
					.catch(function (err) {
						status.textContent =
							err && err.message ? err.message : 'Submission failed. Please try again.';
						status.className = 'hlm-form-status is-error';
					})
					.finally(function () {
						submitBtn.disabled = false;
					});
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initLeadCapture);
	} else {
		initLeadCapture();
	}
})();
