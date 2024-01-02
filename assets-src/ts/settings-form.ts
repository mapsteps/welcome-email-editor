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

export default function setupSettingsForm() {
	// @ts-ignore
	jQuery(document).on("click", ".weed-reset-settings-button", resetSettings);
}

function resetSettings(e: MouseEvent) {
	if (!confirm(weedSettings.warningMessages.resetSettings))
		e.preventDefault();
}
