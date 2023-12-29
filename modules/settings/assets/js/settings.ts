import van from "vanjs-core";
import jQuery from "jquery";

declare var ajaxurl: string;

declare var weedSettings: {
	nonces: {
		adminWelcomeEmail: string;
		userWelcomeEmail: string;
		resetPasswordEmail: string;
	};
	warningMessages: {
		resetSettings: string;
	};
};

(function () {
	let isRequesting = false;

	function init() {
		// @ts-ignore
		jQuery(document).on('click', '.weed-reset-settings-button', resetSettings);

		// @ts-ignore
		jQuery(document).on('click', '.weed-test-email-button', sendTestEmail);
	}

	function resetSettings(e: MouseEvent) {
		if (!confirm(weedSettings.warningMessages.resetSettings)) e.preventDefault();
	}

	/**
	 * Send test email via ajax request.
	 */
	function sendTestEmail(e: MouseEvent) {
		e.preventDefault();

		// @ts-ignore
		const button = this as HTMLButtonElement | null;
		if (!button) return;

		if (isRequesting) return;
		isRequesting = true;
		startLoading(button);

		const data: Record<string, string | undefined | null> = {};

		data.email_type = button.dataset.emailType;
		data.action = 'weed_test_emails'

		switch (data.email_type) {
			case 'admin_new_user_notif_email':
				data.nonce = weedSettings.nonces.adminWelcomeEmail;
				break;

			case 'user_welcome_email':
				data.nonce = weedSettings.nonces.userWelcomeEmail;
				break;

			case 'reset_password_email':
				data.nonce = weedSettings.nonces.resetPasswordEmail;
				break;
		}

		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'json',
			data: data
		}).always(function () {
			isRequesting = false;
			stopLoading(button);
		})
	}

	function startLoading(button: HTMLButtonElement | null) {
		if (!button) return;
		button.classList.add('is-loading');
	}

	function stopLoading(button: HTMLButtonElement | null) {
		if (!button) return;
		button.classList.remove('is-loading');
	}

	init();
})();