import {setupTabs} from "./tabs";
import {setupTemplateTags} from "./template-tags";
import {setupTestEmails} from "./test-emails";
import {setupConditionalFields} from "./conditional-fields";

setupTabs();
setupTemplateTags();
setupConditionalFields();
setupTestEmails();

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

// @ts-ignore
jQuery(document).on("click", ".weed-reset-settings-button", resetSettings);

function resetSettings(e: MouseEvent) {
	if (!confirm(weedSettings.warningMessages.resetSettings))
		e.preventDefault();
}
