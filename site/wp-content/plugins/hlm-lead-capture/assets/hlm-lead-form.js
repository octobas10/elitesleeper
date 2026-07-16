(function () {
	'use strict';

	if (typeof hlmLeadForm === 'undefined') {
		return;
	}

	const campusData = hlmLeadForm.campusData || {};
	const programData = hlmLeadForm.programData || {};
	const messages = hlmLeadForm.messages || {};
	const timingToken = hlmLeadForm.timingToken || '';
	const turnstileSiteKey = hlmLeadForm.turnstileSiteKey || '';
	const pageUrl = hlmLeadForm.pageUrl || '';
	const compliance = hlmLeadForm.compliance || {};
	const turnstileTokens = new WeakMap();

	document.querySelectorAll('.hlm-lead-capture').forEach(function (root) {
		if (turnstileSiteKey) {
			if (typeof window.turnstile !== 'undefined') {
				initTurnstile(root);
			} else {
				window.addEventListener('load', function () {
					initTurnstile(root);
				}, { once: true });
			}
		}

		initTabs(root);
		initFormWizards(root);
		initStudentCascades(root);
		initProgramComboboxes(root);
		initFieldValidation(root);
		initForms(root);
		initCompliance(root);
	});

	initStickyFormCta();

	function showFormAlert(form, type, text) {
		var alert = form.querySelector('.hlm-form-alert');

		if (!alert) {
			alert = document.createElement('div');
			alert.className = 'hlm-form-alert';
			alert.setAttribute('role', type === 'error' ? 'alert' : 'status');
			form.insertBefore(alert, form.firstChild);
		}

		alert.className = 'hlm-form-alert hlm-form-alert--' + type;
		alert.textContent = text;
		alert.hidden = false;
	}

	function clearFormAlert(form) {
		var alert = form.querySelector('.hlm-form-alert');
		if (alert) {
			alert.hidden = true;
		}
	}

	function normalizeErrorMessage(raw) {
		if (!raw) {
			return '';
		}

		var div = document.createElement('div');
		div.innerHTML = String(raw);
		var text = (div.textContent || div.innerText || '').trim();

		if (/critical error on this website/i.test(text)) {
			return messages.error || 'Submission failed. Please try again.';
		}

		return text;
	}

	function setFieldInvalid(field, isInvalid) {
		var group = field.closest('.hlm-form-group') || field.closest('.hlm-combobox');
		if (!group) {
			return;
		}
		group.classList.toggle('hlm-form-group--invalid', isInvalid);
	}

	function digitsOnly(value) {
		return String(value || '').replace(/\D/g, '');
	}

	function formatPhoneDisplay(value) {
		var digits = digitsOnly(value).slice(0, 10);
		if (digits.length <= 3) {
			return digits;
		}
		if (digits.length <= 6) {
			return '(' + digits.slice(0, 3) + ') ' + digits.slice(3);
		}
		return '(' + digits.slice(0, 3) + ') ' + digits.slice(3, 6) + '-' + digits.slice(6);
	}

	function isValidZip(zip) {
		var z = String(zip || '').trim();
		return /^\d{5}(-\d{4})?$/.test(z) || /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/.test(z);
	}

	function isValidName(name) {
		return /^[A-Za-z\u00C0-\u024F\s'.-]{2,}$/.test(String(name || '').trim());
	}

	function ensureFieldErrorEl(field) {
		var group = field.closest('.hlm-form-group') || field.closest('.hlm-combobox');
		if (!group) {
			return null;
		}
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
				if (comboboxInput) {
					anchor = comboboxInput;
				}
			}

			if (anchor && anchor.parentNode) {
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
				if (hint && hint.id) {
					describedBy.push(hint.id);
				}
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
		if (group) {
			var label = group.querySelector('label');
			if (label) {
				return label.textContent.replace(/\*/g, '').trim();
			}
		}
		return field.name || 'This field';
	}

	function validateField(field) {
		if (field.closest('.hlm-hp-field')) {
			return '';
		}
		if (field.disabled) {
			return '';
		}
		if (field.type === 'hidden' && field.name !== 'program_of_interest') {
			return '';
		}

		var value = field.type === 'checkbox' ? field.checked : String(field.value || '').trim();
		var label = getFieldLabel(field);

		if (field.name === 'program_of_interest' && field.required && !value) {
			return messages.selectProgram || 'Please select a program from the list.';
		}

		if (field.required && !value) {
			if (field.type === 'checkbox') {
				return messages.consentRequired || 'Please check the box to continue.';
			}
			if (field.tagName === 'SELECT') {
				return (messages.selectRequired || 'Please select %s.').replace('%s', label);
			}
			return (messages.required || 'Please enter %s.').replace('%s', label);
		}

		if (!value && !field.required) {
			return '';
		}

		if (field.type === 'email' || field.name === 'email') {
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
				return messages.invalidEmail || 'Please enter a valid email address.';
			}
		}

		if (field.type === 'tel' || field.name === 'phone') {
			if (digitsOnly(value).length !== 10) {
				return messages.invalidPhone || 'Please enter a 10-digit phone number.';
			}
		}

		if (field.name === 'zip') {
			if (!isValidZip(value)) {
				return messages.invalidZip || 'Enter a valid ZIP (12345) or postal code (A1A 1A1).';
			}
		}

		if (field.name === 'first_name' || field.name === 'last_name' || field.name === 'institution_name') {
			if (!isValidName(value)) {
				return messages.invalidName || 'Please enter at least 2 characters.';
			}
		}

		if ((field.type === 'url' || field.name === 'website') && value) {
			if (!/^https?:\/\/.+\..+/i.test(value)) {
				return messages.invalidUrl || 'Please enter a full website URL starting with https://';
			}
		}

		return '';
	}

	function validateFields(fields) {
		var firstInvalid = null;
		var firstMessage = '';

		fields.forEach(function (field) {
			var msg = validateField(field);
			setFieldError(field, msg);
			if (msg && !firstInvalid) {
				firstInvalid = field;
				firstMessage = msg;
			}
		});

		return {
			valid: !firstInvalid,
			field: firstInvalid,
			message: firstMessage,
		};
	}

	function focusField(field) {
		if (!field) {
			return;
		}
		if (field.type === 'hidden') {
			var combobox = field.closest('.hlm-combobox');
			var input = combobox ? combobox.querySelector('.hlm-combobox-input') : null;
			if (input) {
				input.focus();
				return;
			}
		}
		field.focus();
	}

	function initFieldValidation(root) {
		root.querySelectorAll('.hlm-lead-form').forEach(function (form) {
			form.querySelectorAll('input, select, textarea').forEach(function (field) {
				if (field.type === 'tel' || field.name === 'phone') {
					field.addEventListener('input', function () {
						var formatted = formatPhoneDisplay(field.value);
						if (field.value !== formatted) {
							field.value = formatted;
						}
						if (!validateField(field)) {
							setFieldError(field, '');
						}
					});
				}

				field.addEventListener('blur', function () {
					if (field.type === 'hidden' || field.closest('.hlm-hp-field')) {
						return;
					}
					var msg = validateField(field);
					setFieldError(field, msg);
				});

				field.addEventListener('input', function () {
					if (field.type === 'tel' || field.name === 'phone') {
						return;
					}
					if (!validateField(field)) {
						setFieldError(field, '');
					}
				});

				field.addEventListener('change', function () {
					if (!validateField(field)) {
						setFieldError(field, '');
					}
				});
			});
		});
	}

	function initStickyFormCta() {
		if (window.matchMedia('(min-width: 769px)').matches) {
			return;
		}

		var capture = document.querySelector('.hlm-lead-capture');
		if (!capture) {
			return;
		}

		var target = document.getElementById('form') || capture;
		var bar = document.createElement('div');
		bar.className = 'hlm-sticky-form-cta';
		bar.innerHTML =
			'<button type="button" class="hlm-btn hlm-btn-primary hlm-sticky-form-cta__button">' +
			(messages.stickyCta || 'Start Your Inquiry') +
			'</button>';
		document.body.appendChild(bar);

		bar.querySelector('button').addEventListener('click', function () {
			var behavior = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';
			target.scrollIntoView({ behavior: behavior, block: 'start' });
		});

		if ('IntersectionObserver' in window) {
			var observer = new IntersectionObserver(
				function (entries) {
					var visible = entries.some(function (entry) {
						return entry.isIntersecting;
					});
					bar.classList.toggle('is-visible', !visible);
				},
				{ threshold: 0.12, rootMargin: '0px 0px -12px 0px' }
			);
			observer.observe(target);
		} else {
			bar.classList.add('is-visible');
		}
	}

	function initFormWizards(root) {
		root.querySelectorAll('.hlm-form-wizard').forEach(function (form) {
			initWizardForm(form);
		});
	}

	function initWizardForm(form) {
		if (form.dataset.hlmWizardInit === '1') {
			return;
		}
		form.dataset.hlmWizardInit = '1';

		const steps = Array.from(form.querySelectorAll('.hlm-form-step'));
		const progressFill = form.querySelector('.hlm-form-progress-fill');
		const stepCurrent = form.querySelector('.hlm-step-current');
		const total = steps.length;
		let current = 1;

		function validateStep(stepEl) {
			const fields = stepEl.querySelectorAll('input, select, textarea');
			const result = validateFields(fields);

			if (!result.valid) {
				stepEl.classList.add('hlm-form-step--validated');
				focusField(result.field);
				clearFormAlert(form);
				return false;
			}

			clearFormAlert(form);
			return true;
		}

		function goToStep(stepNum) {
			current = Math.max(1, Math.min(total, stepNum));

			steps.forEach(function (step) {
				const isActive = parseInt(step.dataset.step, 10) === current;
				step.classList.toggle('active', isActive);
				step.hidden = !isActive;
			});

			if (progressFill) {
				progressFill.style.width = ((current / total) * 100) + '%';
			}

			if (stepCurrent) {
				stepCurrent.textContent = String(current);
			}

			if (current === total && requiresCompliance(form)) {
				refreshComplianceTokens();
			}

			if (current === total) {
				form.dispatchEvent(new CustomEvent('hlm:step-final'));
			}
		}

		form.querySelectorAll('.hlm-form-next').forEach(function (button) {
			button.addEventListener('click', function () {
				const stepEl = button.closest('.hlm-form-step');
				if (!stepEl || !validateStep(stepEl)) {
					return;
				}
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
			if (event.detail && event.detail.step) {
				goToStep(parseInt(event.detail.step, 10));
			}
		});

		goToStep(1);
	}

	function switchFormTab(root, target) {
		const tabs = root.querySelectorAll('.hlm-form-tab');
		const sections = root.querySelectorAll('.hlm-form-section');
		const select = root.querySelector('.hlm-form-tabs-select');

		tabs.forEach(function (t) {
			const isActive = t.dataset.tab === target;
			t.classList.toggle('active', isActive);
			t.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});

		sections.forEach(function (section) {
			section.classList.remove('active');
		});

		const section = root.querySelector('#' + root.id + '-' + target);
		if (section) {
			section.classList.add('active');
		}

		if (select && select.value !== target) {
			select.value = target;
		}

		const activeForm = root.querySelector('#' + root.id + '-' + target + '.hlm-form-wizard');
		if (activeForm) {
			activeForm.dispatchEvent(new CustomEvent('hlm:reset-steps'));
		}
	}

	function initTabs(root) {
		const select = root.querySelector('.hlm-form-tabs-select');

		root.querySelectorAll('.hlm-form-tab').forEach(function (tab) {
			tab.addEventListener('click', function () {
				switchFormTab(root, tab.dataset.tab);
			});
		});

		if (select) {
			select.addEventListener('change', function () {
				switchFormTab(root, select.value);
			});
		}
	}

	function populateSelect(select, options, placeholder) {
		select.innerHTML = '';
		const empty = document.createElement('option');
		empty.value = '';
		empty.textContent = placeholder;
		select.appendChild(empty);

		Object.entries(options).forEach(function (entry) {
			const opt = document.createElement('option');
			opt.value = entry[0];
			opt.textContent = entry[1];
			select.appendChild(opt);
		});
	}

	function getCategoryForForm(form) {
		if (form.dataset.category) {
			return form.dataset.category;
		}
		const categorySelect = form.querySelector('.hlm-student-category');
		return categorySelect ? categorySelect.value : '';
	}

	function initProgramComboboxes(root) {
		root.querySelectorAll('.hlm-program-combobox').forEach(function (combobox) {
			initProgramCombobox(combobox);
		});
	}

	function initProgramCombobox(combobox) {
		if (combobox.dataset.hlmComboboxInit === '1') {
			return;
		}
		combobox.dataset.hlmComboboxInit = '1';

		const hidden = combobox.querySelector('input[type="hidden"][name="program_of_interest"]');
		const input = combobox.querySelector('.hlm-combobox-input');
		const list = combobox.querySelector('.hlm-combobox-list');
		const countEl = combobox.querySelector('.hlm-combobox-count');
		const form = combobox.closest('.hlm-lead-form');
		let options = [];
		let filtered = [];
		let activeIndex = -1;

		function setOptions(programMap) {
			options = Object.entries(programMap || {}).map(function (entry) {
				return { code: entry[0], label: entry[1] };
			}).sort(function (a, b) {
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
			input.placeholder = messages.selectProgram || 'Select campus first, then search programs...';
			closeList();
			options = [];
			filtered = [];
			renderList();
			updateCount();
		}

		function updateCount() {
			if (!countEl) {
				return;
			}
			if (!options.length) {
				countEl.hidden = true;
				return;
			}
			countEl.hidden = false;
			countEl.textContent = options.length + ' ' + (messages.programsAvailable || 'programs available');
		}

		function closeList() {
			list.hidden = true;
			input.setAttribute('aria-expanded', 'false');
			activeIndex = -1;
		}

		function openList() {
			if (!filtered.length || input.disabled) {
				return;
			}
			list.hidden = false;
			input.setAttribute('aria-expanded', 'true');
		}

		function renderList() {
			list.innerHTML = '';
			filtered.forEach(function (item, index) {
				const li = document.createElement('li');
				li.className = 'hlm-combobox-option';
				li.setAttribute('role', 'option');
				li.dataset.code = item.code;
				li.textContent = item.label;
				if (index === activeIndex) {
					li.classList.add('is-active');
					li.setAttribute('aria-selected', 'true');
				}
				li.addEventListener('mousedown', function (event) {
					event.preventDefault();
					selectOption(item);
				});
				list.appendChild(li);
			});

			if (!filtered.length && input.value.trim()) {
				const empty = document.createElement('li');
				empty.className = 'hlm-combobox-empty';
				empty.textContent = messages.noPrograms || 'No matching programs';
				list.appendChild(empty);
			}
		}

		function selectOption(item) {
			hidden.value = item.code;
			input.value = item.label;
			closeList();
			setFieldError(hidden, '');
			setFieldError(input, '');
			hidden.dispatchEvent(new Event('change', { bubbles: true }));
		}

		function filterOptions(query) {
			const q = query.trim().toLowerCase();
			if (!q) {
				filtered = options.slice();
			} else {
				filtered = options.filter(function (item) {
					return item.label.toLowerCase().indexOf(q) !== -1 || item.code.toLowerCase().indexOf(q) !== -1;
				});
			}
			activeIndex = filtered.length ? 0 : -1;
			renderList();
			openList();
		}

		input.addEventListener('focus', function () {
			if (!input.disabled) {
				filterOptions(input.value);
			}
		});

		input.addEventListener('input', function () {
			hidden.value = '';
			filterOptions(input.value);
		});

		input.addEventListener('keydown', function (event) {
			if (input.disabled) {
				return;
			}
			if (event.key === 'ArrowDown') {
				event.preventDefault();
				if (list.hidden) {
					openList();
					return;
				}
				activeIndex = Math.min(activeIndex + 1, filtered.length - 1);
				renderList();
			} else if (event.key === 'ArrowUp') {
				event.preventDefault();
				activeIndex = Math.max(activeIndex - 1, 0);
				renderList();
			} else if (event.key === 'Enter' && activeIndex >= 0 && filtered[activeIndex]) {
				event.preventDefault();
				selectOption(filtered[activeIndex]);
			} else if (event.key === 'Escape') {
				closeList();
			}
		});

		document.addEventListener('click', function (event) {
			if (!combobox.contains(event.target)) {
				closeList();
			}
		});

		combobox.hlmSetPrograms = setOptions;
		combobox.hlmClearPrograms = clearSelection;

		if (form) {
			form.addEventListener('hlm:programs-updated', function (event) {
				if (event.detail && event.detail.combobox === combobox) {
					setOptions(event.detail.programs || {});
					input.disabled = false;
					input.placeholder = messages.searchPrograms || 'Search or select a program...';
					filtered = options.slice();
					renderList();
					openList();
				}
			});

			form.addEventListener('hlm:programs-cleared', function () {
				clearSelection();
			});
		}
	}

	function dispatchPrograms(form, combobox, programs) {
		form.dispatchEvent(new CustomEvent('hlm:programs-updated', {
			detail: { combobox: combobox, programs: programs },
		}));
	}

	function syncFormPrograms(form) {
		if (!form) {
			return;
		}

		const combobox = form.querySelector('.hlm-program-combobox');
		if (!combobox) {
			return;
		}

		const formType = form.dataset.type;

		if (formType === 'student') {
			const category = form.querySelector('.hlm-student-category');
			const campus = form.querySelector('.hlm-student-campus');

			if (!category || !campus) {
				return;
			}

			const cat = category.value;
			const camp = campus.value;

			if (cat && camp && programData[cat] && programData[cat][camp]) {
				dispatchPrograms(form, combobox, programData[cat][camp]);
			}
		}
	}

	function initStudentCascades(root) {
		const form = root.querySelector('[data-type="student"]');
		if (!form) {
			return;
		}

		const category = form.querySelector('.hlm-student-category');
		const campus = form.querySelector('.hlm-student-campus');
		const combobox = form.querySelector('.hlm-program-combobox');

		if (!category || !campus || !combobox) {
			return;
		}

		category.addEventListener('change', function () {
			const cat = category.value;
			form.dispatchEvent(new CustomEvent('hlm:programs-cleared'));

			if (cat && campusData[cat]) {
				populateSelect(campus, campusData[cat], campus.querySelector('option')?.textContent || 'Select Campus');
				campus.disabled = false;
				campus.value = '';
			} else {
				populateSelect(campus, {}, 'Select Campus');
				campus.disabled = true;
				campus.value = '';
			}
		});

		campus.addEventListener('change', function () {
			const cat = category.value;
			const camp = campus.value;

			if (cat && camp && programData[cat] && programData[cat][camp]) {
				dispatchPrograms(form, combobox, programData[cat][camp]);
			} else {
				form.dispatchEvent(new CustomEvent('hlm:programs-cleared'));
			}
		});

		form.addEventListener('hlm:step-final', function () {
			syncFormPrograms(form);
		});
	}

	function mergeComplianceFields(root, data) {
		['trustedformcerturl', 'universal_leadid', 'page_url'].forEach(function (name) {
			const field = root.querySelector('[name="' + name + '"]');
			if (field && field.value) {
				data[name] = field.value;
			}
		});

		if (!data.page_url && pageUrl) {
			data.page_url = pageUrl;
		}

		return data;
	}

	function initCompliance(root) {
		if (!compliance.trustedform && !compliance.leadid) {
			return;
		}

		refreshComplianceTokens();

		root.querySelectorAll('.hlm-form-wizard').forEach(function (form) {
			form.addEventListener('hlm:goto-step', function (event) {
				if (!event.detail || !event.detail.step) {
					return;
				}

				var total = form.querySelectorAll('.hlm-form-step').length;
				if (parseInt(event.detail.step, 10) === total && requiresCompliance(form)) {
					refreshComplianceTokens();
				}
			});
		});
	}

	function refreshComplianceTokens() {
		if (compliance.leadid) {
			bindLeadId();
		}
	}

	function bindLeadId() {
		if (typeof LeadiD !== 'undefined' && typeof LeadiD.token === 'function') {
			LeadiD.token();
			return;
		}

		var attempts = 0;
		var timer = setInterval(function () {
			attempts += 1;

			if (typeof LeadiD !== 'undefined' && typeof LeadiD.token === 'function') {
				clearInterval(timer);
				LeadiD.token();
				return;
			}

			if (attempts >= 20) {
				clearInterval(timer);
			}
		}, 500);
	}

	function requiresCompliance(form) {
		if (!compliance.trustedform && !compliance.leadid) {
			return false;
		}

		var formType = form.dataset.type;
		return formType === 'student';
	}

	function serializeForm(form) {
		const formData = new FormData(form);
		const data = Object.fromEntries(formData.entries());
		data.form_type = form.dataset.type;
		data.submitted_at = new Date().toISOString();
		data.source = window.location.hostname;
		data.hlm_timing = timingToken;

		const root = form.closest('.hlm-lead-capture');
		mergeComplianceFields(root, data);

		const turnstileToken = root ? turnstileTokens.get(root) : '';
		if (turnstileToken) {
			data.hlm_turnstile = turnstileToken;
		}

		form.querySelectorAll('select[multiple]').forEach(function (select) {
			data[select.name] = Array.from(select.selectedOptions).map(function (option) {
				return option.value;
			});
		});

		form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
			if (!checkbox.checked) {
				delete data[checkbox.name];
			}
		});

		if (data.phone) {
			data.phone = data.phone.replace(/\D+/g, '').slice(0, 10);
		}

		return data;
	}

	function initTurnstile(root) {
		if (!turnstileSiteKey || typeof window.turnstile === 'undefined') {
			return;
		}

		const wrap = root.querySelector('.hlm-turnstile-wrap');
		if (!wrap) {
			return;
		}

		wrap.hidden = false;
		const widget = document.createElement('div');
		wrap.appendChild(widget);

		window.turnstile.render(widget, {
			sitekey: turnstileSiteKey,
			callback: function (token) {
				turnstileTokens.set(root, token);
			},
			'expired-callback': function () {
				turnstileTokens.delete(root);
			},
		});
	}

	function initForms(root) {
		root.querySelectorAll('.hlm-lead-form').forEach(function (form) {
			form.addEventListener('submit', async function (event) {
				event.preventDefault();
				clearFormAlert(form);

				const wizardSteps = form.querySelectorAll('.hlm-form-step');

				if (wizardSteps.length) {
					for (let i = 0; i < wizardSteps.length; i++) {
						const result = validateFields(
							wizardSteps[i].querySelectorAll('input, select, textarea')
						);

						if (!result.valid) {
							const stepNum = parseInt(wizardSteps[i].dataset.step, 10);
							wizardSteps[i].classList.add('hlm-form-step--validated');
							form.dispatchEvent(new CustomEvent('hlm:goto-step', { detail: { step: stepNum } }));
							focusField(result.field);
							clearFormAlert(form);
							return;
						}
					}
				} else {
					const result = validateFields(form.querySelectorAll('input, select, textarea'));
					if (!result.valid) {
						const stepEl = result.field ? result.field.closest('.hlm-form-step') : null;
						if (stepEl) {
							stepEl.classList.add('hlm-form-step--validated');
						}
						focusField(result.field);
						clearFormAlert(form);
						return;
					}
				}

				const button = form.querySelector('.hlm-form-submit');
				const captureRoot = form.closest('.hlm-lead-capture');

				if (turnstileSiteKey) {
					if (!captureRoot || !turnstileTokens.get(captureRoot)) {
						if (button) {
							button.classList.add('hlm-form-submit--error');
							showFormAlert(form, 'error', messages.error || 'Submission failed. Please try again.');
							setTimeout(function () {
								button.classList.remove('hlm-form-submit--error');
							}, 3000);
						}
						return;
					}
				}

				const originalText = button.textContent;
				button.textContent = messages.submitting || 'Submitting...';
				button.disabled = true;

				try {
					const response = await fetch(hlmLeadForm.restUrl, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-WP-Nonce': hlmLeadForm.nonce,
						},
						body: JSON.stringify(serializeForm(form)),
					});

					const payload = await response.json().catch(function () {
						return {};
					});

					if (!response.ok) {
						var apiMessage = normalizeErrorMessage(payload.message);
						throw new Error(apiMessage || messages.error || 'Submission failed');
					}

					button.textContent = messages.success || 'Submitted Successfully!';
					button.classList.add('hlm-form-submit--success');
					showFormAlert(form, 'success', messages.success || 'Submitted Successfully!');
					form.reset();

					if (form.classList.contains('hlm-form-wizard')) {
						form.dispatchEvent(new CustomEvent('hlm:reset-steps'));
					}

					form.querySelectorAll('.hlm-student-campus').forEach(function (select) {
						select.disabled = true;
					});

					form.dispatchEvent(new CustomEvent('hlm:programs-cleared'));

					setTimeout(function () {
						button.textContent = originalText;
						button.classList.remove('hlm-form-submit--success');
						button.disabled = false;
					}, 3000);
				} catch (error) {
					console.error('HLM lead form error:', error);
					button.textContent = messages.error || 'Submission failed. Please try again.';
					button.classList.add('hlm-form-submit--error');
					showFormAlert(form, 'error', error.message || messages.error || 'Submission failed. Please try again.');

					setTimeout(function () {
						button.textContent = originalText;
						button.classList.remove('hlm-form-submit--error');
						button.disabled = false;
					}, 3000);
				}
			});
		});
	}
})();
