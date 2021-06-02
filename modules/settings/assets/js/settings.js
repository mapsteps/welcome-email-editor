/**
 * Settings.
 *
 * Used global objects:
 * - ajaxurl
 * - weedSettings
 */
(function ($) {
	var isRequesting = false;
	var loading = {};

	function init() {
		$(document).on('click', '.weed-reset-settings-button', resetSettings);
		$(document).on('click', '.weed-test-email-button', sendTestEmail);
	}

	/**
	 * Send test email via ajax request.
	 */
	function resetSettings(e) {
		if (!confirm(weedSettings.warningMessages.resetSettings)) e.preventDefault();
	}

	/**
	 * Send test email via ajax request.
	 */
	function sendTestEmail(e) {
		e.preventDefault();

		var button = this;

		if (isRequesting) return;
		isRequesting = true;
		loading.start(button);

		var data = {};

		data.email_type = this.dataset.emailType;
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

		$.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'json',
			data: data
		}).done(function (r) {
			//
		}).fail(function () {
			//
		}).always(function () {
			isRequesting = false;
			loading.stop(button);
		})
	}

	loading.start = function (button) {
		button.classList.add('is-loading');
	}

	loading.stop = function (button) {
		button.classList.remove('is-loading');
	}

	init();
})(jQuery);