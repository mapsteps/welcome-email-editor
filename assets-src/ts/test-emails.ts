import {startLoading, stopLoading} from "./utils";

declare var ajaxurl: string;

declare var weedSettings: {
	nonces: {
		adminWelcomeEmail: string;
		userWelcomeEmail: string;
		resetPasswordEmail: string;
		testSmtpEmail: string;
	};
	warningMessages: {
		resetSettings: string;
	};
};

export function setupTestEmails() {
	let isRequesting = false;
	const noticeEl = document.querySelector('.weed-submission-notice');

	init();

	function init() {
		// @ts-ignore
		jQuery(document).on("click", ".weed-test-email-button", sendTestEmail);
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
		data.action = "weed_test_emails";

		switch (data.email_type) {
			case "admin_new_user_notif_email":
				data.nonce = weedSettings.nonces.adminWelcomeEmail;
				break;

			case "user_welcome_email":
				data.nonce = weedSettings.nonces.userWelcomeEmail;
				break;

			case "reset_password_email":
				data.nonce = weedSettings.nonces.resetPasswordEmail;
				break;

			case "test_smtp_email":
				data.nonce = weedSettings.nonces.testSmtpEmail;
				const toEmailField = document.querySelector('#weed_settings--test_smtp_recipient_email') as HTMLInputElement;
				data.to_email = toEmailField ? toEmailField.value : '';
				break;
		}

		if (noticeEl) {
			noticeEl.classList.add('is-hidden');
			noticeEl.classList.remove('is-error');
			noticeEl.classList.remove('is-success');
		}

		jQuery
			.ajax({
				url: ajaxurl,
				type: "post",
				dataType: "json",
				data: data,
			})
			.always(function (r) {
				isRequesting = false;
				stopLoading(button);

				console.log(r);

				if (noticeEl) {
					noticeEl.classList.remove('is-hidden');
					noticeEl.classList.add(r.success ? 'is-success' : 'is-error');
					noticeEl.innerHTML = r.data ? r.data : '';
				}
			});
	}
}