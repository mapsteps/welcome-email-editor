import {setupTabs} from "./tabs";
import {setupTemplateTags} from "./template-tags";
import {setupTestEmails} from "./test-emails";

init();

function init() {
	setupTabs(jQuery);
	setupTemplateTags();
	setupTestEmails();
}
