import {startLoading, stopLoading} from "./utils";

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

export function setupTestEmails() {
	let isRequesting = false;

	init();

	function init() {
		// @ts-ignore
		jQuery(document).on("click", ".weed-reset-settings-button", resetSettings);

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
		}

		jQuery
			.ajax({
				url: ajaxurl,
				type: "post",
				dataType: "json",
				data: data,
			})
			.always(function () {
				isRequesting = false;
				stopLoading(button);
			});
	}

	function resetSettings(e: MouseEvent) {
		if (!confirm(weedSettings.warningMessages.resetSettings)) e.preventDefault();
	}
}