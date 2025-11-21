export function setupConditionalFields() {
	const showWhenCheckedEls = document.querySelectorAll(
		"[data-show-when-checked]",
	) as NodeListOf<HTMLElement>;

	const fields: HTMLInputElement[] = [];
	const fieldNames: string[] = [];
	const conditionalEls: HTMLElement[] = [];

	showWhenCheckedEls.forEach(function (showWhenCheckedEl) {
		const rawFieldSelector = showWhenCheckedEl.dataset.showWhenChecked;
		const fieldName = rawFieldSelector ? rawFieldSelector : "";
		if (!fieldName) return;

		const field = document.querySelector(
			'[name="' + fieldName + '"]',
		) as HTMLInputElement;
		if (!field) return;

		if (!fieldNames.includes(fieldName)) {
			fieldNames.push(fieldName);
			fields.push(field);
		}

		conditionalEls.push(showWhenCheckedEl);

		if (field.checked) {
			showWhenCheckedEl.style.display = "block";
		} else {
			showWhenCheckedEl.style.display = "none";
		}
	});

	fields.forEach(function (field) {
		field.addEventListener("change", function (e) {
			if (field.checked) {
				conditionalEls.forEach(function (conditionalEl) {
					const rawFieldSelector = conditionalEl.dataset.showWhenChecked;
					const fieldName = rawFieldSelector ? rawFieldSelector : "";
					if (!fieldName) return;

					if (fieldName === field.name) {
						conditionalEl.style.display = "block";
					}
				});
			} else {
				conditionalEls.forEach(function (conditionalEl) {
					const rawFieldSelector = conditionalEl.dataset.showWhenChecked;
					const fieldName = rawFieldSelector ? rawFieldSelector : "";
					if (!fieldName) return;

					if (fieldName === field.name) {
						conditionalEl.style.display = "none";
					}
				});
			}
		});
	});

	// Handle mailer type radio button conditional fields
	const mailerTypeConditionalEls = document.querySelectorAll(
		"[data-show-when-mailer-type]",
	) as NodeListOf<HTMLElement>;

	const mailerTypeRadios = document.querySelectorAll(
		'[name="weed_settings[mailer_type]"]',
	) as NodeListOf<HTMLInputElement>;

	function updateMailerTypeFields() {
		const selectedMailerType =
			document.querySelector<HTMLInputElement>(
				'[name="weed_settings[mailer_type]"]:checked',
			)?.value || "smtp";

		mailerTypeConditionalEls.forEach(function (conditionalEl) {
			const showWhen = conditionalEl.dataset.showWhenMailerType;
			if (!showWhen) return;

			if (showWhen === selectedMailerType) {
				conditionalEl.style.display = "block";
			} else {
				conditionalEl.style.display = "none";
			}
		});
	}

	// Initialize field visibility on page load
	updateMailerTypeFields();

	// Update field visibility when mailer type changes
	mailerTypeRadios.forEach(function (radio) {
		radio.addEventListener("change", updateMailerTypeFields);
	});

	const smtpEncryptionFields = document.querySelectorAll(
		'[name="weed_settings[smtp_encryption]"]',
	);

	smtpEncryptionFields.forEach(function (smtpEncryptionField) {
		smtpEncryptionField.addEventListener(
			"change",
			handleSmtpEncryptionFieldChange,
		);
	});

	function handleSmtpEncryptionFieldChange(e: Event) {
		const value = (e.target as HTMLInputElement).value;
		const smtpPortField = document.querySelector(
			'[name="weed_settings[smtp_port]"]',
		) as HTMLInputElement;
		if (!smtpPortField) return;

		if (value === "ssl") {
			smtpPortField.value = "465";
		} else if (value === "tls") {
			smtpPortField.value = "587";
		} else {
			smtpPortField.value = "25";
		}
	}
}
