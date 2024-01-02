import {hideNotice, showNotice, startLoading, stopLoading} from "./utils";

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

		const allNoticeEls = document.querySelectorAll('.weed-submission-notice') as NodeListOf<HTMLElement>;

		allNoticeEls.forEach(function (el) {
			hideNotice(el);
		});

		const parentEl = button.parentElement as HTMLElement | null;
		const noticeEl = parentEl ? parentEl.querySelector('.weed-submission-notice') as HTMLElement | null : null;

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

		if (noticeEl) hideNotice(noticeEl);

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

				if (noticeEl) {
					showNotice({
						el: noticeEl,
						type: r.success ? 'success' : 'error',
						msg: r.data,
					});
				}
			});
	}
}